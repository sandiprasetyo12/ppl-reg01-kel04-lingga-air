@extends('layouts.app')

@section('title', 'Pengajuan Berhasil Dikirim - Lingga Air')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 py-4">

    <!-- BREADCRUMB (Mockup Hal 60) -->
    <nav class="flex text-xs text-slate-500 space-x-2 items-center justify-center">
        <a href="{{ route('pengajuan.index') }}" class="hover:text-sky-600 transition">Beranda</a>
        <span>/</span>
        <a href="{{ route('pengajuan.index') }}" class="hover:text-sky-600 transition">Pengajuan</a>
        <span>/</span>
        <span class="text-slate-800 font-semibold">Berhasil Dikirim</span>
    </nav>

    <!-- HEADER CENTANG HIJAU (Mockup Hal 60) -->
    <div class="text-center space-y-2">
        <div class="w-16 h-16 rounded-full bg-emerald-100 border-4 border-emerald-50 text-emerald-600 flex items-center justify-center mx-auto shadow-inner">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <p class="text-xs uppercase font-extrabold tracking-wider text-sky-700">PENGAJUAN BANTUAN AIR BERSIH</p>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pengajuan Berhasil Dikirim</h1>
        <p class="text-xs sm:text-sm text-slate-500 max-w-lg mx-auto">
            Pengajuan Anda telah diterima dan akan diverifikasi oleh petugas BPBD. Pantau perkembangannya melalui halaman Status Pengajuan.
        </p>
    </div>

    <!-- DETAIL CARD PENGAJUAN (Mockup Hal 60) -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md overflow-hidden p-6 sm:p-8 space-y-6">
        
        <!-- Header Detail -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 gap-2">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Nomor Pengajuan / Tiket</span>
                <span class="text-xl sm:text-2xl font-black text-sky-800 font-mono tracking-tight">{{ $pengajuan->nomor_tiket }}</span>
            </div>
            <div>
                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    <span>Menunggu verifikasi</span>
                </span>
            </div>
        </div>

        <!-- Meta info desa & waktu -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div class="space-y-1">
                <span class="text-slate-400 block font-semibold">Desa Pemohon:</span>
                <span class="font-bold text-slate-800 text-sm">{{ $desa->nama_desa }} (Kecamatan {{ $desa->kecamatan }})</span>
                <p class="text-slate-500">Penanggung Jawab: {{ $desa->nama_pj }} • {{ $desa->no_wa }}</p>
            </div>
            <div class="space-y-1 sm:text-right">
                <span class="text-slate-400 block font-semibold">Waktu Pengajuan:</span>
                <span class="font-bold text-slate-800 text-sm">
                    {{ $pengajuan->tanggal_pengajuan ? $pengajuan->tanggal_pengajuan->translatedFormat('d F Y, H:i') : $pengajuan->created_at->translatedFormat('d F Y, H:i') }} WIB
                </span>
                <p class="text-slate-500">Lokasi Drop: {{ $pengajuan->lokasi_pengantaran }}</p>
            </div>
        </div>

        <!-- Foto Bukti Kondisi Lapangan Terlampir (Mockup Hal 60) -->
        <div class="border-t border-slate-100 pt-5 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-700">
                    Foto Bukti Kondisi Lapangan Terlampir ({{ $pengajuan->fotoKondisi->count() }} Foto)
                </span>
                <span class="text-[11px] text-emerald-600 font-semibold flex items-center space-x-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Terunggah / Siap diverifikasi</span>
                </span>
            </div>

            @if($pengajuan->fotoKondisi->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($pengajuan->fotoKondisi as $foto)
                        <div class="group relative rounded-xl overflow-hidden border border-slate-200 bg-slate-50">
                            <img src="{{ $foto->url }}" alt="{{ $foto->nama_file }}" class="w-full h-28 object-cover group-hover:scale-105 transition duration-200">
                            <div class="p-2 bg-white flex items-center justify-between text-[10px]">
                                <span class="font-bold text-slate-800 truncate">{{ $foto->nama_file }}</span>
                                <span class="text-slate-400">{{ $foto->humanFileSize() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 italic">Tidak ada foto bukti terlampir.</p>
            @endif
        </div>

        <!-- Quick Summary Bar (120 KK | 12.000 Liter | 3 Hari - Mockup Hal 60) -->
        <div class="grid grid-cols-3 gap-2 bg-slate-50 p-4 rounded-2xl border border-slate-100 text-center">
            <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase block">Warga Terdampak</span>
                <span class="text-base sm:text-lg font-black text-slate-800">{{ $pengajuan->jumlah_kk }} KK</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase block">Estimasi Pasokan</span>
                <span class="text-base sm:text-lg font-black text-sky-700">{{ number_format($pengajuan->estimasiLiter(), 0, ',', '.') }} Liter</span>
                <span class="text-[10px] text-slate-400 block font-medium">({{ $pengajuan->estimasiTangki() }} Tangki x 4.000 L)</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase block">Durasi Kekeringan</span>
                <span class="text-base sm:text-lg font-black text-slate-800">{{ $pengajuan->hari_tanpa_air }} Hari</span>
            </div>
        </div>

        <!-- Action Buttons (Mockup Hal 60) -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
            <a href="{{ route('pengajuan.index') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-100 font-semibold text-xs text-center transition">
                Kembali ke Beranda
            </a>
            <a href="{{ route('pengajuan.show', $pengajuan->id) }}" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs shadow-md shadow-sky-600/30 text-center transition flex items-center justify-center space-x-1.5">
                <span>Lihat Status Pengajuan</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

    </div>

    <!-- CARD "APA YANG TERJADI SELANJUTNYA?" (Mockup Hal 60) -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-4">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Apa yang terjadi selanjutnya?</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            
            <!-- Tahap 1 -->
            <div class="flex items-start space-x-3 p-3 bg-sky-50/50 rounded-2xl border border-sky-100">
                <div class="w-7 h-7 rounded-full bg-sky-600 text-white flex items-center justify-center shrink-0 font-bold text-xs shadow-xs">
                    1
                </div>
                <div>
                    <h4 class="font-bold text-slate-900">Pengajuan Diterima</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                        Data masuk ke basis data pusat dan tercatat dalam antrean BPBD.
                    </p>
                </div>
            </div>

            <!-- Tahap 2 -->
            <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-2xl border border-slate-100">
                <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center shrink-0 font-bold text-xs">
                    2
                </div>
                <div>
                    <h4 class="font-bold text-slate-900">Verifikasi Petugas</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                        Petugas meninjau hasil kelayakan, skor prioritas, dan foto bukti lapangan.
                    </p>
                </div>
            </div>

            <!-- Tahap 3 -->
            <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-2xl border border-slate-100">
                <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center shrink-0 font-bold text-xs">
                    3
                </div>
                <div>
                    <h4 class="font-bold text-slate-900">Penjadwalan Bantuan</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                        Armada tangki disiapkan dan dijadwalkan menuju titik drop point desa.
                    </p>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

