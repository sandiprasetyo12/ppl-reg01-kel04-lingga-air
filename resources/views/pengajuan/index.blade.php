@extends('layouts.app')

@section('title', 'Draf & Pengajuan Bantuan Air - Lingga Air')

@section('content')
<div class="space-y-6">

    <!-- HEADER TITLE & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200/80 pb-4">
        <div>
            <div class="flex items-center space-x-2">
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Draf & Status Pengajuan</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-100 text-sky-800">
                    {{ $desa->nama_desa }}
                </span>
            </div>
            <p class="text-sm text-slate-500 mt-1">Kelola draf permohonan sebelum diajukan ke antrean verifikasi BPBD.</p>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('cek-kelayakan.index') }}" class="inline-flex items-center space-x-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold px-3.5 py-2.5 rounded-xl shadow-xs text-sm transition">
                <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>Cek Kelayakan Mandiri</span>
            </a>
            <a href="{{ route('pengajuan.create') }}" class="inline-flex items-center space-x-2 bg-sky-600 hover:bg-sky-700 text-white font-bold px-4 py-2.5 rounded-xl shadow-md shadow-sky-600/30 text-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Buat Pengajuan Baru</span>
            </a>
        </div>
    </div>

    <!-- SUMMARY METRICS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        
        <!-- Card 1: Draf Tersimpan -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Draf Tersimpan</span>
                <span class="text-2xl font-extrabold text-slate-800">{{ $drafList->total() }}</span>
                <span class="text-xs text-slate-500 block">Dapat diubah/dihapus</span>
            </div>
        </div>

        <!-- Card 2: Tiket Aktif Berjalan -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl {{ $hasActiveTicket ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600' }} flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Status Tiket Aktif</span>
                @if($hasActiveTicket)
                    <span class="text-sm font-extrabold text-amber-700 block truncate">{{ $activeTicket->nomor_tiket }}</span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 inline-block mt-0.5">{{ $activeTicket->status }}</span>
                @else
                    <span class="text-sm font-bold text-emerald-600 block">Tidak Ada Tiket Aktif</span>
                    <span class="text-xs text-slate-400">Siap mengajukan baru</span>
                @endif
            </div>
        </div>

        <!-- Card 3: Total Permohonan Terkirim -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Riwayat Terkirim</span>
                <span class="text-2xl font-extrabold text-sky-800">{{ $riwayatList->total() }}</span>
                <span class="text-xs text-slate-500 block">Terkunci / Dalam proses</span>
            </div>
        </div>

    </div>

    <!-- SEKSI 1: TABEL DRAF PENGAJUAN (PBI-008) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center space-x-2">
                <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                <h2 class="text-base font-bold text-slate-800">Draf Pengajuan Belum Dikirim ({{ $drafList->total() }})</h2>
            </div>
            <span class="text-xs text-slate-500">Draf milik desa Anda yang masih dapat diubah atau dihapus</span>
        </div>

        @if($drafList->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">Draf ID / Tanggal</th>
                            <th class="py-3 px-4">Kebutuhan Air</th>
                            <th class="py-3 px-4">Kelayakan (AHP-SAW)</th>
                            <th class="py-3 px-4">Lampiran</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($drafList as $draf)
                            <tr class="hover:bg-slate-50/80 transition">
                                <!-- ID & Tanggal -->
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">#DRAF-{{ str_pad($draf->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $draf->created_at->translatedFormat('d M Y, H:i') }} WIB</div>
                                </td>

                                <!-- Kebutuhan -->
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-slate-800">{{ $draf->jumlah_kk }} KK Terdampak</div>
                                    <div class="text-[11px] text-slate-500">{{ $draf->hari_tanpa_air }} hari tanpa air • {{ number_format($draf->jarak_sumber_air, 0, ',', '.') }} m</div>
                                </td>

                                <!-- Kelayakan -->
                                <td class="py-3.5 px-4">
                                    @if($draf->simulasi)
                                        <div class="flex items-center space-x-1.5">
                                            @php
                                                $st = $draf->simulasi->status_kelayakan;
                                                $badgeClass = match($st) {
                                                    'Kritis' => 'bg-rose-100 text-rose-800',
                                                    'Waspada' => 'bg-amber-100 text-amber-800',
                                                    default => 'bg-emerald-100 text-emerald-800',
                                                };
                                            @endphp
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $badgeClass }}">
                                                {{ $st }}
                                            </span>
                                            <span class="font-semibold text-slate-600 text-[11px]">{{ number_format($draf->simulasi->skor_ahp_saw, 1) }}</span>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">-</span>
                                    @endif
                                </td>

                                <!-- Lampiran -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center space-x-2 text-[11px]">
                                        <span class="inline-flex items-center space-x-1 bg-slate-100 px-2 py-0.5 rounded text-slate-700">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span>{{ $draf->fotoKondisi->count() }} Foto</span>
                                        </span>
                                        @if($draf->suratKepalaDesa)
                                            <span class="inline-flex items-center space-x-1 bg-sky-50 text-sky-700 px-2 py-0.5 rounded" title="Surat Kades terlampir">
                                                <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                <span>Surat</span>
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status Badge -->
                                <td class="py-3.5 px-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        Draf
                                    </span>
                                </td>

                                <!-- Aksi PBI-008 -->
                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center space-x-1.5">
                                        <!-- Lihat Detail -->
                                        <a href="{{ route('pengajuan.show', $draf->id) }}" class="p-1.5 rounded-lg text-slate-600 hover:text-sky-600 hover:bg-sky-50 transition" title="Lihat Detail Draf">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <!-- Ubah Draf -->
                                        <a href="{{ route('pengajuan.edit', $draf->id) }}" class="p-1.5 rounded-lg text-slate-600 hover:text-amber-600 hover:bg-amber-50 transition" title="Ubah Isian Draf">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <!-- Hapus Draf dengan Konfirmasi -->
                                        <button type="button" onclick="confirmDeleteDraf({{ $draf->id }}, 'DRAF-{{ str_pad($draf->id, 4, '0', STR_PAD_LEFT) }}')" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Hapus Draf">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>

                                        <!-- Kirim Draf (PBI-009) -->
                                        <a href="{{ route('pengajuan.show', $draf->id) }}" class="ml-1 inline-flex items-center space-x-1 px-2.5 py-1 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-lg text-[11px] shadow-xs transition">
                                            <span>Kirim</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $drafList->links() }}
            </div>
        @else
            <div class="p-8 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm font-semibold text-slate-600">Belum ada draf pengajuan tersimpan</p>
                <p class="text-xs text-slate-400 mt-0.5">Mulai menyusun draf baru untuk mencatat kebutuhan air desa.</p>
                <a href="{{ route('pengajuan.create') }}" class="inline-block mt-3 text-xs font-bold text-sky-600 hover:text-sky-700 underline">
                    + Buat Draf Pengajuan Baru
                </a>
            </div>
        @endif
    </div>

    <!-- SEKSI 2: RIWAYAT PENGAJUAN TERKIRIM (NON-DRAF) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center space-x-2">
                <span class="w-3 h-3 rounded-full bg-sky-500"></span>
                <h2 class="text-base font-bold text-slate-800">Riwayat Pengajuan Terkirim & Tiket Aktif ({{ $riwayatList->total() }})</h2>
            </div>
            <span class="text-xs text-slate-400">Pengajuan yang sudah dikirim terkunci dan tidak dapat diubah</span>
        </div>

        @if($riwayatList->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">Nomor Tiket</th>
                            <th class="py-3 px-4">Waktu Dikirim</th>
                            <th class="py-3 px-4">Kebutuhan Air</th>
                            <th class="py-3 px-4">Estimasi Tangki</th>
                            <th class="py-3 px-4 text-center">Status Tiket</th>
                            <th class="py-3 px-4 text-right">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($riwayatList as $item)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4">
                                    <span class="font-extrabold text-sky-700 font-mono text-sm">{{ $item->nomor_tiket }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-slate-600">{{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->translatedFormat('d M Y, H:i') : $item->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-medium text-slate-800">{{ $item->jumlah_kk }} KK ({{ $item->hari_tanpa_air }} hari)</span>
                                </td>
                                <td class="py-3.5 px-4 font-mono font-medium text-slate-600">
                                    {{ number_format($item->estimasiLiter(), 0, ',', '.') }} L ({{ $item->estimasiTangki() }} Tangki)
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @php
                                        $badge = match($item->status) {
                                            'Diajukan' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'Dalam Telaah' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'Disetujui' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'Dijadwalkan' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'Selesai' => 'bg-emerald-600 text-white border-emerald-600',
                                            'Ditolak' => 'bg-rose-100 text-rose-800 border-rose-200',
                                            'Revisi' => 'bg-orange-100 text-orange-800 border-orange-200',
                                            default => 'bg-slate-100 text-slate-800 border-slate-200',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $badge }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('pengajuan.show', $item->id) }}" class="inline-flex items-center space-x-1 px-3 py-1 bg-slate-100 hover:bg-sky-50 text-slate-700 hover:text-sky-700 rounded-lg text-xs font-semibold transition">
                                        <span>Lihat</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $riwayatList->links() }}
            </div>
        @else
            <div class="p-6 text-center text-slate-400 text-xs">
                Belum ada permohonan yang telah dikirim ke antrean BPBD.
            </div>
        @endif
    </div>

</div>

<!-- Modal konfirmasi hapus draf -->
<div id="modalHapus" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden p-6 space-y-4 animate-in fade-in zoom-in-95 duration-200">
        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>

        <div class="text-center">
            <h3 class="text-lg font-bold text-slate-900">Hapus Draf Pengajuan?</h3>
            <p class="text-xs text-slate-500 mt-1">
                Apakah Anda yakin ingin menghapus <strong id="hapusDrafTitle" class="text-slate-800"></strong>? Tindakan ini tidak dapat dibatalkan dan semua foto lampiran akan dihapus.
            </p>
        </div>

        <form id="formHapusDraf" method="POST" action="">
            @csrf
            @method('DELETE')

            <div class="flex items-center space-x-3 pt-2">
                <button type="button" onclick="closeHapusModal()" class="flex-1 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 font-semibold text-xs transition">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-600/30 transition">
                    Ya, Hapus Draf
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function confirmDeleteDraf(id, title) {
        document.getElementById('hapusDrafTitle').innerText = title;
        document.getElementById('formHapusDraf').action = '/pengajuan/' + id;
        document.getElementById('modalHapus').classList.remove('hidden');
    }

    function closeHapusModal() {
        document.getElementById('modalHapus').classList.add('hidden');
    }
</script>
@endpush
@endsection

