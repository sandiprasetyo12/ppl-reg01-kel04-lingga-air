@extends('layouts.app')

@section('title', 'Detail Pengajuan - Lingga Air')

@section('content')
<div class="space-y-6">

    <!-- BREADCRUMB -->
    <nav class="flex text-xs text-slate-500 space-x-2 items-center">
        <a href="{{ route('pengajuan.index') }}" class="hover:text-sky-600 transition">Beranda</a>
        <span>/</span>
        <a href="{{ route('pengajuan.index') }}" class="hover:text-sky-600 transition">Pengajuan</a>
        <span>/</span>
        <span class="text-slate-800 font-semibold">
            {{ $pengajuan->isDraft() ? 'Draf #' . str_pad($pengajuan->id, 4, '0', STR_PAD_LEFT) : $pengajuan->nomor_tiket }}
        </span>
    </nav>

    <!-- HEADER DETAIL & ACTIONS -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight font-mono">
                    {{ $pengajuan->isDraft() ? 'Draf #' . str_pad($pengajuan->id, 4, '0', STR_PAD_LEFT) : $pengajuan->nomor_tiket }}
                </h1>
                @php
                    $badge = match($pengajuan->status) {
                        'Draf' => 'bg-slate-100 text-slate-700 border-slate-300',
                        'Diajukan' => 'bg-amber-100 text-amber-800 border-amber-300',
                        'Dalam Telaah' => 'bg-blue-100 text-blue-800 border-blue-300',
                        'Disetujui' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                        'Dijadwalkan' => 'bg-purple-100 text-purple-800 border-purple-300',
                        'Selesai' => 'bg-emerald-600 text-white border-emerald-600',
                        'Ditolak' => 'bg-rose-100 text-rose-800 border-rose-300',
                        'Revisi' => 'bg-orange-100 text-orange-800 border-orange-300',
                        default => 'bg-slate-100 text-slate-800 border-slate-200',
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badge }}">
                    {{ $pengajuan->status === 'Diajukan' ? 'Menunggu verifikasi' : $pengajuan->status }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                {{ $desa->nama_desa }}, Kec. {{ $desa->kecamatan }} • 
                {{ $pengajuan->tanggal_pengajuan ? 'Diajukan pada ' . $pengajuan->tanggal_pengajuan->translatedFormat('d F Y, H:i') . ' WIB' : 'Dibuat pada ' . $pengajuan->created_at->translatedFormat('d F Y, H:i') . ' WIB' }}
            </p>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="flex items-center space-x-2">
            @if($pengajuan->isDraft())
                <!-- PBI-008: Ubah Draf -->
                <a href="{{ route('pengajuan.edit', $pengajuan->id) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-xs shadow-xs transition flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Ubah Draf</span>
                </a>

                <!-- PBI-008: Hapus Draf -->
                <button type="button" onclick="confirmDeleteDraf({{ $pengajuan->id }}, 'DRAF-{{ str_pad($pengajuan->id, 4, '0', STR_PAD_LEFT) }}')" class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-xl text-xs transition border border-rose-200 flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span>Hapus</span>
                </button>

                <!-- PBI-009: Kirim Draf -->
                <button type="button" onclick="openKirimModal()" class="px-5 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-xs shadow-md shadow-sky-600/30 transition flex items-center space-x-1.5">
                    <span>Kirim Pengajuan</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            @else
                <span class="inline-flex items-center space-x-1 bg-slate-100 text-slate-500 px-3 py-1.5 rounded-xl text-xs font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Draf Terkunci (Sudah Dikirim)</span>
                </span>
            @endif
        </div>
    </div>

    <!-- MAIN GRID DETAIL -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- KOLOM KIRI: DATA PENGAJUAN & BERKAS (8 Kolom) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Card Kebutuhan Kekeringan -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-sm text-slate-800 uppercase tracking-wider">Kondisi Kekeringan & Kebutuhan Air</h3>
                    <span class="text-xs text-sky-600 font-semibold">Estimasi: {{ number_format($pengajuan->estimasiLiter(), 0, ',', '.') }} Liter ({{ $pengajuan->estimasiTangki() }} Tangki)</span>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-center">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">KK Terdampak</span>
                        <span class="text-lg font-black text-slate-800">{{ $pengajuan->jumlah_kk }} KK</span>
                        <span class="text-[10px] text-slate-500 block">dari {{ $desa->jumlah_kk }} KK desa</span>
                    </div>

                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-center">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Lama Tanpa Air</span>
                        <span class="text-lg font-black text-slate-800">{{ $pengajuan->hari_tanpa_air }} Hari</span>
                        <span class="text-[10px] text-slate-500 block">krisis sumber</span>
                    </div>

                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-center">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Jarak Sumber</span>
                        <span class="text-lg font-black text-slate-800">{{ number_format($pengajuan->jarak_sumber_air, 0, ',', '.') }} m</span>
                        <span class="text-[10px] text-slate-500 block">alternatif terdekat</span>
                    </div>
                </div>

                <div>
                    <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alasan Pengajuan:</span>
                    <p class="text-xs text-slate-700 bg-slate-50 p-3.5 rounded-xl border border-slate-100 leading-relaxed">
                        {{ $pengajuan->alasan_pengajuan }}
                    </p>
                </div>

                @if($pengajuan->catatan_tambahan)
                    <div>
                        <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan Tambahan Akses Armada:</span>
                        <p class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100">
                            {{ $pengajuan->catatan_tambahan }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Card Foto Bukti Lapangan (PBI-007) -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-sm text-slate-800 uppercase tracking-wider">
                        Foto Bukti Kondisi Lapangan ({{ $pengajuan->fotoKondisi->count() }} Foto)
                    </h3>
                    <span class="text-[11px] text-emerald-600 font-bold bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                        Format JPG/PNG Tervalidasi
                    </span>
                </div>

                @if($pengajuan->fotoKondisi->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach($pengajuan->fotoKondisi as $foto)
                            <div class="group relative rounded-xl overflow-hidden border border-slate-200 shadow-xs bg-slate-50">
                                <img src="{{ $foto->url }}" alt="{{ $foto->nama_file }}" class="w-full h-36 object-cover group-hover:scale-105 transition duration-200">
                                <div class="p-2.5 bg-white">
                                    <p class="text-[11px] font-bold text-slate-800 truncate">{{ $foto->nama_file }}</p>
                                    <div class="flex items-center justify-between mt-1 text-[10px] text-slate-400">
                                        <span>{{ $foto->humanFileSize() }}</span>
                                        <a href="{{ $foto->url }}" target="_blank" class="text-sky-600 hover:text-sky-800 font-semibold">Buka Foto ↗</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-center text-slate-400 text-xs bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        Belum ada foto bukti lapangan yang diunggah pada draf ini.
                    </div>
                @endif
            </div>

            <!-- Card Surat Pengantar Kades -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-3">
                <h3 class="font-bold text-sm text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-3">
                    Surat Permohonan Resmi Kepala Desa
                </h3>

                @if($pengajuan->suratKepalaDesa)
                    <div class="flex items-center justify-between p-3.5 bg-sky-50/70 border border-sky-100 rounded-xl">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-sky-600 text-white flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">{{ $pengajuan->suratKepalaDesa->nama_file }}</p>
                                <p class="text-[10px] text-slate-500">{{ $pengajuan->suratKepalaDesa->humanFileSize() }} • {{ strtoupper($pengajuan->suratKepalaDesa->mime_type) }}</p>
                            </div>
                        </div>
                        <a href="{{ $pengajuan->suratKepalaDesa->url }}" target="_blank" class="px-3 py-1.5 bg-white hover:bg-sky-100 border border-sky-200 text-sky-700 font-bold rounded-lg text-xs transition">
                            Unduh Berkas
                        </a>
                    </div>
                @else
                    <p class="text-xs text-slate-500 italic">Tidak ada surat pengantar resmi yang dilampirkan.</p>
                @endif
            </div>

        </div>

        <!-- KOLOM KANAN: MAPS & HASIL EVALUASI (4 Kolom) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Hasil Cek Kelayakan SPK -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-5 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-amber-900 uppercase tracking-wider">Hasil Cek Kelayakan</span>
                    @php
                        $st = $pengajuan->simulasi ? $pengajuan->simulasi->status_kelayakan : 'Kritis';
                        $col = match($st) {
                            'Kritis' => 'bg-rose-600 text-white',
                            'Waspada' => 'bg-amber-500 text-white',
                            default => 'bg-emerald-600 text-white',
                        };
                    @endphp
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $col }}">
                        {{ $st }}
                    </span>
                </div>

                <div class="text-center py-2 bg-white/70 rounded-xl border border-amber-200">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Skor Prioritas AHP-SAW</span>
                    <span class="text-3xl font-extrabold text-amber-700 tracking-tight">
                        {{ number_format($pengajuan->simulasi->skor_ahp_saw ?? 92.4, 2) }}
                    </span>
                    <span class="text-xs text-slate-400 font-semibold">/ 100</span>
                </div>

                <p class="text-xs text-amber-900 font-medium">
                    {{ $pengajuan->simulasi->rekomendasi ?? 'Segera ajukan pasokan air bersih darurat untuk kebutuhan warga terdampak.' }}
                </p>
            </div>

            <!-- Titik Pengantaran Tandon & Mini Peta -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <h3 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Titik Drop Point Pengantaran</h3>
                <p class="text-xs font-semibold text-slate-700">{{ $pengajuan->lokasi_pengantaran ?: 'Halaman Balai Desa ' . $desa->nama_desa }}</p>
                <p class="text-[11px] text-slate-400 font-mono">{{ $pengajuan->latitude ?? $desa->latitude }}, {{ $pengajuan->longitude ?? $desa->longitude }}</p>

                <div id="detailMap" class="h-44 rounded-xl border border-slate-200 overflow-hidden"></div>
            </div>

            <!-- Kontak Penanggung Jawab -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-2 text-xs">
                <h3 class="font-bold text-slate-800 uppercase tracking-wider text-[11px]">Kontak Penanggung Jawab</h3>
                <div class="space-y-1 text-slate-600">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Nama PJ:</span>
                        <span class="font-bold text-slate-800">{{ $desa->nama_pj }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">WhatsApp:</span>
                        <span class="font-bold text-slate-800">{{ $desa->no_wa }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- MODAL KONFIRMASI HAPUS DRAF (PBI-008) -->
<div id="modalHapus" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden p-6 space-y-4">
        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>
        <div class="text-center">
            <h3 class="text-lg font-bold text-slate-900">Hapus Draf Pengajuan?</h3>
            <p class="text-xs text-slate-500 mt-1">Apakah Anda yakin ingin menghapus draf ini? Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <form id="formHapusDraf" method="POST" action="{{ route('pengajuan.destroy', $pengajuan->id) }}">
            @csrf
            @method('DELETE')
            <div class="flex items-center space-x-3 pt-2">
                <button type="button" onclick="closeHapusModal()" class="flex-1 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 font-semibold text-xs transition">Batal</button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-600/30 transition">Ya, Hapus Draf</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL KONFIRMASI KIRIM (PBI-009) -->
<div id="modalKirim" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden p-6 space-y-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-2xl bg-sky-100 text-sky-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900">Kirim Pengajuan ke Antrean Admin?</h3>
                <p class="text-xs text-slate-500">Nomor tiket resmi akan diterbitkan dan status berubah menjadi Diajukan.</p>
            </div>
        </div>

        <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100 text-xs space-y-1.5">
            <div class="flex justify-between">
                <span class="text-slate-400">Desa:</span>
                <span class="font-bold text-slate-800">{{ $desa->nama_desa }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">KK Terdampak:</span>
                <span class="font-bold text-slate-800">{{ $pengajuan->jumlah_kk }} KK ({{ $pengajuan->hari_tanpa_air }} hari)</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Foto Bukti:</span>
                <span class="font-bold text-slate-800">{{ $pengajuan->fotoKondisi->count() }} foto terlampir</span>
            </div>
        </div>

        <form method="POST" action="{{ route('pengajuan.submit', $pengajuan->id) }}">
            @csrf
            <div class="flex items-center justify-end space-x-3 pt-2">
                <button type="button" onclick="closeKirimModal()" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 font-semibold text-xs transition">
                    Periksa Kembali
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs shadow-md shadow-sky-600/30 transition">
                    Ya, Kirim Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const mapLat = {{ $pengajuan->latitude ?? $desa->latitude ?? -7.2845100 }};
    const mapLng = {{ $pengajuan->longitude ?? $desa->longitude ?? 109.4321000 }};
    const detailMap = L.map('detailMap').setView([mapLat, mapLng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(detailMap);
    L.marker([mapLat, mapLng]).addTo(detailMap).bindPopup("<b>Titik Tandon Air</b><br>{{ $desa->nama_desa }}").openPopup();

    function confirmDeleteDraf() {
        document.getElementById('modalHapus').classList.remove('hidden');
    }
    function closeHapusModal() {
        document.getElementById('modalHapus').classList.add('hidden');
    }
    function openKirimModal() {
        document.getElementById('modalKirim').classList.remove('hidden');
    }
    function closeKirimModal() {
        document.getElementById('modalKirim').classList.add('hidden');
    }
</script>
@endpush
@endsection

