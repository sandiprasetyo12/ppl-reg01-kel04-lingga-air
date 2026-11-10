@extends('layouts.app')

@section('title', 'Detail Pengajuan | Lingga Air')

@section('content')
<div class="space-y-6">

    <!-- HEADER DETAIL & ACTIONS -->
    <div class="card p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="eyebrow">Layanan Desa</div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight font-mono m-0">
                    {{ $pengajuan->isDraft() ? 'Draf #' . str_pad($pengajuan->id, 4, '0', STR_PAD_LEFT) : $pengajuan->nomor_tiket }}
                </h1>
                @php
                    $badge = match($pengajuan->status) {
                        'Diajukan' => 'status-waspada',
                        'Disetujui', 'Selesai' => 'status-aman',
                        'Ditolak' => 'status-kritis',
                        default => 'status-draf',
                    };
                @endphp
                <span class="status-badge {{ $badge }} text-xs">
                    {{ $pengajuan->status === 'Diajukan' ? 'Menunggu verifikasi' : $pengajuan->status }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                {{ $desa->nama_desa }}, Kec. {{ $desa->kecamatan }} • 
                {{ $pengajuan->tanggal_pengajuan ? 'Diajukan pada ' . $pengajuan->tanggal_pengajuan->translatedFormat('d F Y, H:i') . ' WIB' : 'Dibuat pada ' . $pengajuan->created_at->translatedFormat('d F Y, H:i') . ' WIB' }}
            </p>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="flex items-center gap-2">
            @if($pengajuan->isDraft())
                <!-- PBI-008: Ubah Draf -->
                <a href="{{ route('pengajuan.edit', $pengajuan->id) }}" class="reset py-2 px-3 text-xs font-bold" style="border-color: #f0d39a; color: #815000;">
                    ✎ Ubah Draf
                </a>

                <!-- PBI-008: Hapus Draf -->
                <button type="button" onclick="confirmDeleteDraf()" class="btn-danger py-2 px-3 text-xs">
                    ✕ Hapus
                </button>

                <!-- PBI-009: Kirim Draf -->
                <button type="button" onclick="openKirimModal()" class="submit py-2 px-4 text-xs font-bold">
                    Kirim Pengajuan →
                </button>
            @else
                <span class="inline-flex items-center gap-1.5 border border-slate-200 bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg text-xs font-semibold">
                    <span>🔒</span>
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
            <div class="card p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-sm text-slate-800 uppercase tracking-wider m-0">Kondisi Kekeringan &amp; Kebutuhan Air</h3>
                    <span class="text-xs font-bold" style="color: #086288;">Estimasi: {{ number_format($pengajuan->estimasiLiter(), 0, ',', '.') }} L ({{ $pengajuan->estimasiTangki() }} Tangki)</span>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-center">
                        <span class="text-[10px] uppercase font-bold text-slate-500 block">KK Terdampak</span>
                        <span class="text-xl font-extrabold text-slate-900">{{ $pengajuan->jumlah_kk }} KK</span>
                        <span class="text-[10px] text-slate-500 block">dari {{ $desa->jumlah_kk }} KK desa</span>
                    </div>

                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-center">
                        <span class="text-[10px] uppercase font-bold text-slate-500 block">Lama Tanpa Air</span>
                        <span class="text-xl font-extrabold text-slate-900">{{ $pengajuan->hari_tanpa_air }} Hari</span>
                        <span class="text-[10px] text-slate-500 block">krisis sumber</span>
                    </div>

                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-center">
                        <span class="text-[10px] uppercase font-bold text-slate-500 block">Jarak Sumber</span>
                        <span class="text-xl font-extrabold text-slate-900">{{ number_format($pengajuan->jarak_sumber_air, 0, ',', '.') }} m</span>
                        <span class="text-[10px] text-slate-500 block">alternatif terdekat</span>
                    </div>
                </div>

                <div>
                    <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alasan Pengajuan:</span>
                    <p class="text-xs text-slate-700 bg-slate-50 p-3.5 rounded-xl border border-slate-200 leading-relaxed m-0">
                        {{ $pengajuan->alasan_pengajuan }}
                    </p>
                </div>

                @if($pengajuan->catatan_tambahan)
                    <div>
                        <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan Tambahan Akses Armada:</span>
                        <p class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-200 m-0">
                            {{ $pengajuan->catatan_tambahan }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Card Foto Bukti Lapangan (PBI-007) -->
            <div class="card p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-sm text-slate-800 uppercase tracking-wider m-0">
                        Foto Bukti Kondisi Lapangan ({{ $pengajuan->fotoKondisi->count() }} Foto)
                    </h3>
                    <span class="text-[11px] text-emerald-800 font-bold bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                        Format JPG/PNG Tervalidasi
                    </span>
                </div>

                @if($pengajuan->fotoKondisi->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach($pengajuan->fotoKondisi as $foto)
                            <div class="card overflow-hidden">
                                <img src="{{ $foto->url }}" alt="{{ $foto->nama_file }}" class="w-full h-36 object-cover">
                                <div class="p-2.5 bg-white">
                                    <p class="text-[11px] font-bold text-slate-800 truncate m-0">{{ $foto->nama_file }}</p>
                                    <div class="flex items-center justify-between mt-1 text-[10px] text-slate-500">
                                        <span>{{ $foto->humanFileSize() }}</span>
                                        <a href="{{ $foto->url }}" target="_blank" class="font-bold hover:underline" style="color: #086288;">Buka Foto ↗</a>
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
            <div class="card p-6 space-y-3">
                <h3 class="font-bold text-sm text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-3 m-0">
                    Surat Permohonan Resmi Kepala Desa
                </h3>

                @if($pengajuan->suratKepalaDesa)
                    <div class="flex items-center justify-between p-3.5 border border-sky-200 bg-sky-50 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl shrink-0" style="background: #086288; color: #fff;">
                                📄
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800 m-0">{{ $pengajuan->suratKepalaDesa->nama_file }}</p>
                                <p class="text-[10px] text-slate-500 m-0">{{ $pengajuan->suratKepalaDesa->humanFileSize() }} • {{ strtoupper($pengajuan->suratKepalaDesa->mime_type) }}</p>
                            </div>
                        </div>
                        <a href="{{ $pengajuan->suratKepalaDesa->url }}" target="_blank" class="reset py-1 px-3 text-xs">
                            Unduh Berkas
                        </a>
                    </div>
                @else
                    <p class="text-xs text-slate-500 italic m-0">Tidak ada surat pengantar resmi yang dilampirkan.</p>
                @endif
            </div>

        </div>

        <!-- KOLOM KANAN: MAPS & HASIL EVALUASI (4 Kolom) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Hasil Cek Kelayakan SPK -->
            <div class="card p-5 space-y-3" style="background: #f0f8fb; border-color: #cae4ef;">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Hasil Cek Kelayakan</span>
                    @php
                        $st = $pengajuan->simulasi ? $pengajuan->simulasi->status_kelayakan : 'Kritis';
                        $col = match(strtolower($st)) {
                            'kritis' => 'status-kritis',
                            'waspada' => 'status-waspada',
                            default => 'status-aman',
                        };
                    @endphp
                    <span class="status-badge {{ $col }} text-[10px]">
                        {{ $st }}
                    </span>
                </div>

                <div class="text-center py-2.5 bg-white rounded-xl border border-slate-200">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Skor Prioritas AHP-SAW</span>
                    <span class="text-3xl font-extrabold tracking-tight" style="color: #086288; font-variant-numeric: tabular-nums;">
                        {{ number_format($pengajuan->simulasi->skor_ahp_saw ?? 92.4, 2) }}
                    </span>
                    <span class="text-xs text-slate-400 font-semibold">/ 100</span>
                </div>

                <p class="text-xs text-slate-700 font-medium m-0">
                    {{ $pengajuan->simulasi->rekomendasi ?? 'Segera ajukan pasokan air bersih darurat untuk kebutuhan warga terdampak.' }}
                </p>
            </div>

            <!-- Titik Pengantaran Tandon & Mini Peta -->
            <div class="card p-5 space-y-3">
                <h3 class="font-bold text-xs text-slate-800 uppercase tracking-wider m-0">Titik Drop Point Pengantaran</h3>
                <p class="text-xs font-bold text-slate-800 m-0">{{ $pengajuan->lokasi_pengantaran ?: 'Halaman Balai Desa ' . $desa->nama_desa }}</p>
                <p class="text-[11px] text-slate-500 font-mono m-0">{{ $pengajuan->latitude ?? $desa->latitude }}, {{ $pengajuan->longitude ?? $desa->longitude }}</p>

                <div id="detailMap" class="h-44 rounded-xl border border-slate-300 overflow-hidden"></div>
            </div>

            <!-- Kontak Penanggung Jawab -->
            <div class="card p-5 space-y-2 text-xs">
                <h3 class="font-bold text-slate-800 uppercase tracking-wider text-[11px] m-0">Kontak Penanggung Jawab</h3>
                <div class="space-y-1.5 text-slate-600">
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
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden p-6 space-y-4">
        <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center mx-auto text-xl font-bold">
            ⚠
        </div>
        <div class="text-center">
            <h3 class="text-lg font-bold text-slate-900 m-0">Hapus Draf Pengajuan?</h3>
            <p class="text-xs text-slate-500 mt-1">Apakah Anda yakin ingin menghapus draf ini? Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <form id="formHapusDraf" method="POST" action="{{ route('pengajuan.destroy', $pengajuan->id) }}">
            @csrf
            @method('DELETE')
            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeHapusModal()" class="reset flex-1">Batal</button>
                <button type="submit" class="btn-danger flex-1">Ya, Hapus Draf</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL KONFIRMASI KIRIM (PBI-009) -->
<div id="modalKirim" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden p-6 space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl shrink-0" style="background: #eef4f8; color: #086288;">
                ℹ
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900 m-0">Kirim Pengajuan ke Antrean Admin?</h3>
                <p class="text-xs text-slate-500 mt-0.5">Nomor tiket resmi akan diterbitkan dan status berubah menjadi Diajukan.</p>
            </div>
        </div>

        <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 text-xs space-y-1.5">
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
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeKirimModal()" class="reset">
                    Periksa Kembali
                </button>
                <button type="submit" class="submit font-bold">
                    Ya, Kirim Sekarang →
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
