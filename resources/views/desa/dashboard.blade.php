@extends('layouts.desa')

@section('title', 'Beranda Desa - LinggaAir')
@section('page-title', 'Beranda')

@section('content')
<div class="space-y-5 pb-20 md:pb-6">
    
    <!-- 1. GREETING & INFO HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-[#EAECF0]">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-[#101828] tracking-tight">
                Portal Perwakilan Desa {{ $desa?->nama_desa ?? '' }}
            </h1>
            <p class="text-xs text-[#667085] mt-0.5">
                Kecamatan {{ $desa?->kecamatan ?? '-' }}, Kabupaten Purbalingga &middot; Kode Wilayah: <span class="font-mono text-[#344054] font-medium">{{ $desa?->kode_desa ?? '-' }}</span>
            </p>
        </div>

        <div class="inline-flex items-center gap-2 self-start sm:self-auto px-2.5 py-1 rounded-md bg-white text-[#344054] text-xs font-medium border border-[#D0D5DD] shadow-2xs">
            <i class="fa-regular fa-calendar text-[#667085]"></i>
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    <!-- 2. HERO STATUS CARD (F-02 Realtime Status & One Primary Action) -->
    @include('desa.components.hero-status')

    <!-- 3. PROGRESS STEPPER (5 Tahapan Penanganan Bantuan) -->
    @include('desa.components.stepper')

    <!-- GRID UTAMA 2 KOLOM (Pengajuan Terakhir & Ringkasan Desa) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 4. KARTU PENGAJUAN TERAKHIR (Mini Vertical Timeline) -->
        @include('desa.components.pengajuan-terakhir')

        <!-- 5. KARTU RINGKASAN DESA (Demografi, Peta Tandon GIS, Kontak WA, Kelengkapan Data) -->
        @include('desa.components.ringkasan-desa')
    </div>

    <!-- 6. KARTU AKTIVITAS TERBARU -->
    @include('desa.components.aktivitas-terbaru')

    <!-- 7. FOOTER BERANDA DESA -->
    <footer class="pt-6 border-t border-[#EAECF0] text-center text-xs text-[#667085]">
        <p>&copy; {{ date('Y') }} LinggaAir &ndash; BPBD Kabupaten Purbalingga</p>
    </footer>

</div>

<!-- BOTTOM NAVIGATION (Hanya Tampil di Layar Ponsel / Mobile) -->
@include('desa.components.mobile-nav')
@endsection
