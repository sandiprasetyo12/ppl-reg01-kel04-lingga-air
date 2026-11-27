@extends('layouts.desa')

@section('title', 'Pengajuan Berhasil Dikirim | Lingga Air')

@section('content')
<div class="w-full max-w-4xl mx-auto space-y-4">

    <!-- HEADER CENTANG HIJAU -->
    <div class="text-center space-y-1.5">
        <div class="w-16 h-16 rounded-full bg-emerald-100 border-4 border-emerald-50 text-emerald-700 flex items-center justify-center mx-auto text-2xl shadow-inner">
            <i class="fa-solid fa-check" aria-hidden="true"></i>
        </div>
        <div class="eyebrow">Pengajuan Bantuan Air Bersih</div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight m-0">Pengajuan Berhasil Dikirim</h1>
        <p class="intro text-center mx-auto max-w-xl">
            Pengajuan Anda telah diterima dan akan diverifikasi oleh petugas BPBD. Pantau perkembangannya melalui halaman Beranda &amp; Draf.
        </p>
    </div>

    <!-- DETAIL CARD PENGAJUAN -->
    <div class="card w-full max-w-4xl mx-auto px-4 py-5 sm:p-8 space-y-6">
        
        <!-- Header Detail -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 gap-2">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Nomor Pengajuan / Tiket</span>
                <span class="text-2xl font-extrabold font-mono tracking-tight" style="color: #086288;">{{ $pengajuan->nomor_tiket }}</span>
            </div>
            <div>
                <span class="status-badge status-waspada text-xs">
                    Menunggu verifikasi
                </span>
            </div>
        </div>

        <!-- Meta info desa & waktu -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div class="space-y-1">
                <span class="text-slate-400 block font-bold uppercase tracking-wider text-[10px]">Desa Pemohon:</span>
                <span class="font-bold text-slate-800 text-sm block">{{ $desa->nama_desa }} (Kecamatan {{ $desa->kecamatan }})</span>
                <p class="text-slate-500 m-0">Perwakilan Desa: {{ $desa->nama_pj }} • {{ $desa->no_wa }}</p>
            </div>
            <div class="space-y-1 sm:text-right">
                <span class="text-slate-400 block font-bold uppercase tracking-wider text-[10px]">Waktu Pengajuan:</span>
                <span class="font-bold text-slate-800 text-sm block">
                    {{ $pengajuan->tanggal_pengajuan ? $pengajuan->tanggal_pengajuan->translatedFormat('d F Y, H:i') : $pengajuan->created_at->translatedFormat('d F Y, H:i') }} WIB
                </span>
                <p class="text-slate-500 m-0">Lokasi Drop: {{ $pengajuan->lokasi_pengantaran }}</p>
            </div>
        </div>

        <!-- Foto Bukti Kondisi Lapangan Terlampir -->
        <div class="border-t border-slate-100 pt-5 space-y-3">
            <div class="w-full flex items-center justify-between gap-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-700">
                    Foto Bukti Kondisi Lapangan Terlampir ({{ $pengajuan->fotoKondisi->count() }} Foto)
                </span>
                <span class="text-[11px] text-emerald-800 font-bold bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                    ✓ Terunggah
                </span>
            </div>

            @if($pengajuan->fotoKondisi->count() > 0)
                @php($jumlahFoto = $pengajuan->fotoKondisi->count())
                <div class="grid w-full gap-3 {{ $jumlahFoto === 1 ? 'grid-cols-1' : ($jumlahFoto === 2 ? 'grid-cols-1 sm:grid-cols-2' : 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3') }}">
                    @foreach($pengajuan->fotoKondisi as $foto)
                        <div class="card min-w-0 overflow-hidden rounded-xl">
                            <button type="button" class="block w-full cursor-zoom-in" data-lightbox-src="{{ $foto->url }}" data-lightbox-alt="{{ $foto->nama_file }}" aria-label="Pratinjau {{ $foto->nama_file }}">
                                <img src="{{ $foto->url }}" alt="{{ $foto->nama_file }}" class="w-full aspect-video object-cover rounded-xl">
                            </button>
                            <div class="p-2 bg-white flex items-center justify-between gap-2 text-[10px] min-w-0">
                                <span class="font-bold text-slate-800 truncate min-w-0" title="{{ $foto->nama_file }}">{{ $foto->nama_file }}</span>
                                <span class="text-slate-400 whitespace-nowrap shrink-0">{{ $foto->humanFileSize() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="hint italic m-0">Tidak ada foto bukti terlampir.</p>
            @endif
        </div>

        <!-- Quick Summary Bar -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 bg-slate-50 p-4 rounded-xl border border-slate-200 text-center">
            <div>
                <span class="text-[10px] text-slate-500 font-bold uppercase block">Warga Terdampak</span>
                <span class="text-base sm:text-lg font-extrabold text-slate-900">{{ $pengajuan->jumlah_kk }} KK</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-500 font-bold uppercase block">Estimasi Pasokan</span>
                <span class="text-base sm:text-lg font-extrabold" style="color: #086288;">{{ number_format($pengajuan->estimasiLiter(), 0, ',', '.') }} L</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-500 font-bold uppercase block">Estimasi Tangki</span>
                <span class="text-base sm:text-lg font-extrabold text-slate-900">{{ $pengajuan->estimasiTangki() }} Tangki</span>
            </div>
        </div>

        <!-- Tombol Tindak Lanjut -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('pengajuan.index') }}" class="reset w-full sm:w-auto text-center font-semibold">
                Riwayat Pengajuan
            </a>
            <a href="{{ route('pengajuan.show', $pengajuan->id) }}" class="submit w-full sm:w-auto text-center font-bold">
                Lihat Detail Pengajuan →
            </a>
        </div>

    </div>

</div>

<dialog id="foto-lightbox" class="m-auto max-h-[92vh] max-w-[94vw] overflow-visible border-0 bg-transparent p-0 backdrop:bg-slate-950/80">
    <button type="button" id="foto-lightbox-close" class="absolute -right-3 -top-3 z-10 flex h-9 w-9 items-center justify-center rounded-full bg-white text-slate-700 shadow-lg" aria-label="Tutup pratinjau">
        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
    </button>
    <img id="foto-lightbox-image" src="" alt="" class="max-h-[92vh] max-w-[94vw] rounded-xl object-contain shadow-2xl">
</dialog>

@push('scripts')
<script>
    (() => {
        const lightbox = document.getElementById('foto-lightbox');
        const preview = document.getElementById('foto-lightbox-image');
        const closeButton = document.getElementById('foto-lightbox-close');

        if (!lightbox || !preview || !closeButton) return;

        document.querySelectorAll('[data-lightbox-src]').forEach((trigger) => {
            trigger.addEventListener('click', () => {
                preview.src = trigger.dataset.lightboxSrc;
                preview.alt = trigger.dataset.lightboxAlt || '';
                lightbox.showModal();
            });
        });

        closeButton.addEventListener('click', () => lightbox.close());
        lightbox.addEventListener('click', (event) => {
            if (event.target === lightbox) lightbox.close();
        });
    })();
</script>
@endpush
@endsection
