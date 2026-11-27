@extends('layouts.desa')

@section('title', 'Draf & Status Pengajuan | Lingga Air')

@section('content')
<div class="space-y-6">

    <!-- HEADER TITLE & TOP ACTIONS -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-[#EAECF0]">
        <div>
            <div class="eyebrow">Layanan Desa &middot; Arsip Permohonan</div>
            <h1 class="text-lg sm:text-xl font-bold text-[#101828] tracking-tight mt-0.5">Draf & Status Pengajuan</h1>
            <p class="intro">Kelola draf permohonan yang belum dikirim serta riwayat tiket bantuan air bersih Desa {{ $desa->nama_desa }}.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('cek-kelayakan.index') }}" class="h-9 px-3.5 bg-white border border-[#D0D5DD] hover:bg-[#F2F4F7] text-[#344054] text-xs font-semibold rounded-xl transition inline-flex items-center gap-1.5 shadow-xs">
                <svg class="w-3.5 h-3.5 text-[#00629B] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm3.75-6h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm3.75-6h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                </svg>
                <span>Cek Kelayakan Mandiri</span>
            </a>
            <a href="{{ route('pengajuan.create') }}" class="h-9 px-4 bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white text-xs font-bold rounded-xl transition inline-flex items-center gap-1.5 shadow-xs">
                <svg class="w-3.5 h-3.5 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span>Buat Pengajuan Baru</span>
            </a>
        </div>
    </div>

    <!-- SUMMARY METRICS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-stretch">
        
        <!-- Card 1: Draf Tersimpan -->
        <div class="card p-4 flex flex-col gap-2 h-full min-h-[124px]">
            <div class="w-9 h-9 rounded-lg bg-[#F8FAFC] border border-[#D0D5DD] flex items-center justify-center shrink-0 text-[#667085]">
                <i class="fa-solid fa-file-pen text-sm text-[#00629B]"></i>
            </div>
            <div class="min-w-0">
                <span class="text-[11px] font-semibold text-[#667085] uppercase tracking-wide block">Draf Tersimpan</span>
                <span class="text-xl font-bold text-[#101828] block" style="font-variant-numeric: tabular-nums;">
                    {{ $drafList->total() }}
                </span>
                <span class="text-[11px] text-[#667085]">Dapat diubah atau dihapus</span>
            </div>
        </div>

        <!-- Card 2: Tiket Aktif -->
        <div class="card p-4 flex flex-col gap-2 h-full min-h-[124px]">
            <div class="w-9 h-9 rounded-lg bg-[#F8FAFC] border border-[#D0D5DD] flex items-center justify-center shrink-0 text-[#667085]">
                <i class="fa-solid fa-ticket text-sm text-[#00629B]"></i>
            </div>
            <div class="min-w-0">
                <span class="text-[11px] font-semibold text-[#667085] uppercase tracking-wide block">Tiket Aktif</span>
                @if($hasActiveTicket)
                    <span class="text-xl font-bold text-[#B54708] block truncate font-mono">{{ $activeTicket->nomor_tiket }}</span>
                    <span class="text-[11px] text-[#667085]">{{ $activeTicket->status }}</span>
                @else
                    <span class="text-xl font-bold text-[#027A48] block">Tidak Ada Tiket Aktif</span>
                    <span class="text-[11px] text-[#667085]">Siap mengajukan baru</span>
                @endif
            </div>
        </div>

        <!-- Card 3: Riwayat Terkirim -->
        <div class="card p-4 flex flex-col gap-2 h-full min-h-[124px]">
            <div class="w-9 h-9 rounded-lg bg-[#F8FAFC] border border-[#D0D5DD] flex items-center justify-center shrink-0 text-[#667085]">
                <i class="fa-solid fa-hourglass-half text-sm text-[#00629B]"></i>
            </div>
            <div class="min-w-0">
                <span class="text-[11px] font-semibold text-[#667085] uppercase tracking-wide block">Dalam Antrean</span>
                <span class="text-xl font-bold text-[#101828] block" style="font-variant-numeric: tabular-nums;">
                    {{ $riwayatList->total() }}
                </span>
                <span class="text-[11px] text-[#667085]">Pengajuan yang telah dikirim</span>
            </div>
        </div>

    </div>

    <!-- SEKSI 1: TABEL DRAF PENGAJUAN (PBI-008) -->
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between" style="background: #fafcfe;">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                <h2 class="text-base font-bold text-slate-800 m-0">Draf Pengajuan Belum Dikirim ({{ $drafList->total() }})</h2>
            </div>
            <span class="text-xs text-slate-500">Draf milik desa Anda yang masih dapat diubah atau dihapus</span>
        </div>

        @if($drafList->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider font-bold border-b border-slate-200">
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
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900 font-mono">#DRAF-{{ str_pad($draf->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ $draf->created_at->translatedFormat('d M Y, H:i') }} WIB</div>
                                </td>

                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $draf->jumlah_kk }} KK Terdampak</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ $draf->hari_tanpa_air }} hari tanpa air • {{ number_format($draf->jarak_sumber_air, 0, ',', '.') }} m</div>
                                </td>

                                <td class="py-3.5 px-4">
                                    @if($draf->simulasi)
                                        <div class="flex items-center gap-1.5">
                                            @php
                                                $st = $draf->simulasi->status_kelayakan;
                                                $badgeClass = match(strtolower($st)) {
                                                    'kritis' => 'status-kritis',
                                                    'waspada' => 'status-waspada',
                                                    default => 'status-aman',
                                                };
                                            @endphp
                                            <span class="status-badge {{ $badgeClass }} text-[10px] py-1 px-2.5">
                                                {{ $st }}
                                            </span>
                                            <span class="font-bold text-slate-700 text-xs">{{ number_format($draf->simulasi->skor_ahp_saw, 1) }}</span>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic text-xs">-</span>
                                    @endif
                                </td>

                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="inline-flex items-center gap-1 border border-slate-200 bg-slate-50 px-2 py-0.5 rounded text-slate-700">
                                            📷 {{ $draf->fotoKondisi->count() }} Foto
                                        </span>
                                        @if($draf->suratKepalaDesa)
                                            <span class="inline-flex items-center gap-1 border border-sky-200 bg-sky-50 px-2 py-0.5 rounded text-sky-800" title="Surat Kades terlampir">
                                                📄 Surat
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-3.5 px-4 text-center">
                                    <span class="status-badge status-draf text-[10px]">
                                        Draf
                                    </span>
                                </td>

                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <!-- Lihat Detail -->
                                        <a href="{{ route('pengajuan.show', $draf->id) }}" class="reset py-1 px-2.5 min-h-0 text-xs" title="Lihat Detail Draf">
                                            Lihat
                                        </a>

                                        <!-- Ubah Draf -->
                                        <a href="{{ route('pengajuan.edit', $draf->id) }}" class="reset py-1 px-2.5 min-h-0 text-xs" style="border-color: #f0d39a; color: #815000;" title="Ubah Isian Draf">
                                            Ubah
                                        </a>

                                        <!-- Hapus Draf dengan Konfirmasi -->
                                        <button type="button" onclick="confirmDeleteDraf({{ $draf->id }}, 'DRAF-{{ str_pad($draf->id, 4, '0', STR_PAD_LEFT) }}')" class="btn-danger py-1 px-2.5 min-h-0 text-xs" title="Hapus Draf">
                                            Hapus
                                        </button>

                                        <!-- Kirim Draf (PBI-009) -->
                                        <a href="{{ route('pengajuan.show', $draf->id) }}" class="submit py-1 px-3 min-h-0 text-xs font-bold">
                                            Kirim →
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 pt-3 pb-2 border-t border-slate-100">
                {{ $drafList->links() }}
            </div>
        @else
            <div class="px-4 py-5 text-center text-slate-500">
                <p class="text-sm font-bold text-slate-700">Belum ada draf pengajuan tersimpan</p>
                <p class="text-xs text-slate-500 mt-1">Mulai menyusun draf baru untuk mencatat kebutuhan air desa.</p>
            </div>
        @endif
    </div>

    <!-- SEKSI 2: RIWAYAT PENGAJUAN TERKIRIM (NON-DRAF) -->
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between" style="background: #fafcfe;">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full" style="background: #00629B;"></span>
                <h2 class="text-base font-bold text-slate-800 m-0">Riwayat Pengajuan Terkirim &amp; Tiket Aktif ({{ $riwayatList->total() }})</h2>
            </div>
            <span class="text-xs text-slate-500">Pengajuan yang sudah dikirim terkunci dan tidak dapat diubah</span>
        </div>

        @if($riwayatList->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider font-bold border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Nomor Tiket</th>
                            <th class="py-3 px-4">Waktu Dikirim</th>
                            <th class="py-3 px-4">Kebutuhan Air</th>
                            <th class="py-3 px-4">Estimasi Tangki</th>
                            <th class="py-3 px-4 text-center">Status Tiket</th>
                            <th class="py-3 pl-4 pr-6 text-right">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($riwayatList as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-sm" style="color: #00629B;">
                                    {{ $item->nomor_tiket }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">
                                    {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->translatedFormat('d M Y, H:i') : $item->created_at->translatedFormat('d M Y, H:i') }} WIB
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-slate-800">{{ $item->jumlah_kk }} KK</span>
                                    <span class="text-slate-500 text-[11px] block">({{ $item->hari_tanpa_air }} hari tanpa air)</span>
                                </td>
                                <td class="py-3.5 px-4 font-mono font-semibold text-slate-700">
                                    {{ number_format($item->estimasiLiter(), 0, ',', '.') }} L ({{ $item->estimasiTangki() }} Tangki)
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @php
                                        $badge = match($item->status) {
                                            'Diajukan' => 'status-waspada',
                                            'Disetujui', 'Selesai' => 'status-aman',
                                            'Ditolak' => 'status-kritis',
                                            default => 'status-draf',
                                        };
                                    @endphp
                                    <span class="status-badge {{ $badge }} text-[10px]">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 pl-4 pr-6 text-right">
                                    <a href="{{ route('pengajuan.show', $item->id) }}" class="reset inline-flex items-center justify-end py-1 px-3 min-h-0 text-xs">
                                        Lihat →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 pt-3 pb-2 border-t border-slate-100">
                {{ $riwayatList->links() }}
            </div>
        @else
            <div class="p-6 text-center text-slate-400 text-xs">
                Belum ada permohonan yang telah dikirim ke antrean BPBD.
            </div>
        @endif
    </div>

</div>

<!-- Modal Konfirmasi Hapus Draf -->
<div id="modalHapus" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden p-6 space-y-4">
        <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center mx-auto text-xl font-bold">
            ⚠
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

            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeHapusModal()" class="reset flex-1">
                    Batal
                </button>
                <button type="submit" class="btn-danger flex-1">
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

