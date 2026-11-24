@extends('layouts.app')

@section('title', 'LinggaAir - Sistem Distribusi Bantuan Air Bersih BPBD Purbalingga')

@php
    $dashboardRouteName = 'onboarding.welcome';
    if (auth()->check()) {
        $authUser = auth()->user();
        if ($authUser->isBpbd()) {
            $dashboardRouteName = 'admin.dashboard';
        } elseif ($authUser->isDesa()) {
            $dashboardRouteName = 'desa.dashboard';
        } elseif ($authUser->isPenyalur()) {
            $dashboardRouteName = 'penyalur.dashboard';
        }
    }
@endphp

@section('header')
{{-- 1. NAVBAR (HEADER IDDS STANDARD) --}}
<header class="w-full bg-white/95 backdrop-blur-md border-b border-[#EAECF0] sticky top-0 z-40 transition-colors">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between gap-4">
        {{-- Logo IDDS LinggaAir --}}
        <a href="{{ url('/') }}" class="flex items-center gap-3 min-h-[44px]">
            <div class="w-9 h-9 rounded-lg bg-[#00629B] flex items-center justify-center text-white shadow-xs" aria-hidden="true">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v18M3 12h18"/>
                </svg>
            </div>
            <span class="font-bold text-lg tracking-tight">
                <span class="text-[#101828]">Lingga</span><span class="text-[#00629B]">Air</span>
            </span>
        </a>

        {{-- Nav Links --}}
        <nav aria-label="Navigasi Utama" class="hidden md:flex items-center gap-8 text-sm font-medium">
            <a href="#hero" class="text-[#00629B] font-semibold border-b-2 border-[#00629B] py-1 transition">Beranda</a>
            <a href="{{ route('cek-kelayakan.index') }}" class="text-[#475467] hover:text-[#00629B] transition">Cek Kelayakan</a>
            <a href="{{ route('admin.pemetaan.index') }}" class="text-[#475467] hover:text-[#00629B] transition">Peta GIS</a>
            <a href="#tentang" class="text-[#475467] hover:text-[#00629B] transition">Tentang Layanan</a>
            <a href="#program" class="text-[#475467] hover:text-[#00629B] transition">Program</a>
            <a href="#faq" class="text-[#475467] hover:text-[#00629B] transition">FAQ</a>
        </nav>

        {{-- Auth Action --}}
        <div class="flex items-center gap-3">
            @auth
                <a
                    href="{{ route($dashboardRouteName) }}"
                    class="min-h-[44px] inline-flex items-center justify-center px-5 py-2.5 bg-[#00629B] hover:bg-[#004F7D] text-white text-sm font-semibold rounded-lg shadow-sm shadow-[#00629B]/20 transition"
                >
                    Dashboard
                </a>
            @else
                <a
                    href="{{ route('login') }}"
                    class="min-h-[44px] inline-flex items-center justify-center px-5 py-2.5 bg-[#00629B] hover:bg-[#004F7D] text-white text-sm font-semibold rounded-lg shadow-sm shadow-[#00629B]/20 transition"
                >
                    Masuk Petugas
                </a>
            @endauth
        </div>
    </div>
</header>
@endsection

@section('content')
<main class="w-full bg-white text-[#101828] flex-1">

    {{-- 2. HERO SECTION (SKEMA FULL FOTO & TEKS DI TENGAH BERIDENTITAS IDDS) --}}
    <section id="hero" class="relative w-full min-h-[500px] lg:min-h-[620px] flex items-center justify-center bg-[#0B192C] text-white overflow-hidden">
        {{-- Background Tone Layer --}}
        <div class="absolute inset-0 w-full h-full bg-[#0F223D] opacity-75 pointer-events-none">
            <svg class="w-full h-full text-[#1E3A5F]/40" preserveAspectRatio="none" viewBox="0 0 100 100" fill="none">
                <line x1="0" y1="0" x2="100" y2="100" stroke="currentColor" stroke-width="0.75" vector-effect="non-scaling-stroke"/>
                <line x1="0" y1="100" x2="100" y2="0" stroke="currentColor" stroke-width="0.75" vector-effect="non-scaling-stroke"/>
            </svg>
        </div>

        {{-- Dark Civic Navy Gradient Overlay for Readability --}}
        <div class="absolute inset-0 bg-gradient-to-t from-[#0B192C] via-[#0B192C]/80 to-[#0B192C]/90 pointer-events-none"></div>

        {{-- Centered Content --}}
        <div class="relative z-10 max-w-4xl mx-auto px-6 py-20 lg:py-28 text-center space-y-6">
            {{-- Category Tag IDDS --}}
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full border border-[#BAE3F5]/30 bg-[#00629B]/30 text-xs font-semibold uppercase tracking-wider text-[#BAE3F5]">
                <span class="w-2 h-2 rounded-full bg-[#38BDF8] animate-pulse" aria-hidden="true"></span>
                BPBD Kabupaten Purbalingga
            </div>

            {{-- Centered H1 --}}
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-bold text-white tracking-tight leading-[1.2] max-w-3xl mx-auto">
                Penyaluran Bantuan Air Bersih Kabupaten Purbalingga
            </h1>

            {{-- Centered Subtitle --}}
            <p class="text-base sm:text-lg text-slate-200 leading-relaxed max-w-2xl mx-auto">
                Layanan resmi BPBD Kabupaten Purbalingga untuk memantau permohonan desa, jadwal distribusi truk tangki, dan pemetaan wilayah terdampak kekeringan secara transparan.
            </p>

            {{-- Centered CTA Buttons --}}
            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a
                    href="{{ route('cek-kelayakan.index') }}"
                    class="w-full sm:w-auto min-h-[44px] inline-flex items-center justify-center px-7 py-3.5 bg-[#00629B] hover:bg-[#004F7D] text-white text-sm font-semibold rounded-lg shadow-md shadow-black/20 hover:shadow-[#00629B]/30 transition"
                >
                    Cek Kelayakan Desa
                </a>
                <a
                    href="{{ route('admin.pemetaan.index') }}"
                    class="w-full sm:w-auto min-h-[44px] inline-flex items-center justify-center px-7 py-3.5 border border-white/50 hover:border-white bg-white/10 hover:bg-white/20 text-white text-sm font-semibold rounded-lg backdrop-blur-xs transition"
                >
                    Buka Peta GIS
                </a>
            </div>
        </div>
    </section>

    {{-- 3. SECTION: KENAPA SISTEM INI DIBUTUHKAN (COLLAGE + 4 POIN) --}}
    <section id="tentang" class="border-t border-[#EAECF0] py-20 bg-[#F8FAFC]">
        <div class="max-w-7xl mx-auto px-6">
            {{-- Centered Header --}}
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <div class="text-xs font-bold uppercase tracking-wider text-[#00629B]">Kesiapsiagaan Krisis Air</div>
                <h2 class="text-3xl font-bold text-[#101828] tracking-tight">
                    Kenapa Sistem Ini Dibutuhkan
                </h2>
                <p class="text-[#475467] text-sm sm:text-base leading-relaxed">
                    Menghadirkan kepastian alokasi pasokan air bersih yang adil, transparan, dan terukur bagi masyarakat desa terdampak kemarau.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Left: 3-Photo Collage Wireframe IDDS Styled --}}
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="aspect-[4/3] rounded-xl bg-[#EBF5FB]/80 border border-[#BAE3F5] relative overflow-hidden flex items-center justify-center">
                            <svg class="absolute inset-0 w-full h-full text-[#BAE3F5]/60 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100" fill="none">
                                <line x1="0" y1="0" x2="100" y2="100" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                                <line x1="0" y1="100" x2="100" y2="0" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                            </svg>
                            <span class="relative z-10 text-[11px] font-semibold text-[#00629B] bg-white px-2.5 py-1 rounded-md border border-[#BAE3F5] shadow-xs">
                                Dokumentasi 1
                            </span>
                        </div>
                        <div class="aspect-[4/3] rounded-xl bg-[#EBF5FB]/80 border border-[#BAE3F5] relative overflow-hidden flex items-center justify-center">
                            <svg class="absolute inset-0 w-full h-full text-[#BAE3F5]/60 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100" fill="none">
                                <line x1="0" y1="0" x2="100" y2="100" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                                <line x1="0" y1="100" x2="100" y2="0" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                            </svg>
                            <span class="relative z-10 text-[11px] font-semibold text-[#00629B] bg-white px-2.5 py-1 rounded-md border border-[#BAE3F5] shadow-xs">
                                Dokumentasi 2
                            </span>
                        </div>
                    </div>
                    <div class="aspect-[21/9] rounded-xl bg-[#EBF5FB]/80 border border-[#BAE3F5] relative overflow-hidden flex items-center justify-center">
                        <svg class="absolute inset-0 w-full h-full text-[#BAE3F5]/60 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100" fill="none">
                            <line x1="0" y1="0" x2="100" y2="100" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                            <line x1="0" y1="100" x2="100" y2="0" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                        </svg>
                        <span class="relative z-10 text-[11px] font-semibold text-[#00629B] bg-white px-2.5 py-1 rounded-md border border-[#BAE3F5] shadow-xs">
                            Dokumentasi Tandon Warga
                        </span>
                    </div>
                </div>

                {{-- Right: 4 Poin Fitur Layanan --}}
                <div class="space-y-6">
                    <ul class="space-y-5">
                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 rounded-lg bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center flex-shrink-0 mt-0.5 shadow-xs" aria-hidden="true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#101828] text-base">Penilaian Objektif Berbasis AHP-SAW</h3>
                                <p class="text-[#475467] text-sm mt-0.5">Penentuan urutan prioritas dihitung matematis berdasarkan kepadatan balita, lansia, dan ketiadaan air.</p>
                            </div>
                        </li>

                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 rounded-lg bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center flex-shrink-0 mt-0.5 shadow-xs" aria-hidden="true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#101828] text-base">Kapasitas Armada Standar 5.000 Liter</h3>
                                <p class="text-[#475467] text-sm mt-0.5">Setiap rit truk tangki membawa 5.000 liter air bersih higienis siap pakai untuk tandon komunal.</p>
                            </div>
                        </li>

                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 rounded-lg bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center flex-shrink-0 mt-0.5 shadow-xs" aria-hidden="true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#101828] text-base">Pemetaan Wilayah Terintegrasi GIS</h3>
                                <p class="text-[#475467] text-sm mt-0.5">Pantauan spasial titik lokasi kekeringan di 18 kecamatan menggunakan poligon batas GeoJSON resmi.</p>
                            </div>
                        </li>

                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 rounded-lg bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center flex-shrink-0 mt-0.5 shadow-xs" aria-hidden="true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#101828] text-base">Layanan Bebas Biaya Retribusi (Gratis)</h3>
                                <p class="text-[#475467] text-sm mt-0.5">Seluruh distribusi air bersih dari BPBD Kabupaten Purbalingga tidak dipungut biaya apa pun.</p>
                            </div>
                        </li>
                    </ul>

                    <div class="pt-4">
                        <a
                            href="#program"
                            class="min-h-[44px] inline-flex items-center justify-center px-6 py-2.5 bg-[#00629B] hover:bg-[#004F7D] text-white text-sm font-semibold rounded-lg shadow-sm shadow-[#00629B]/20 transition"
                        >
                            Pelajari Alur Layanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. SECTION: METRICS & LARGE SHOWCASE (STRATEGY & MAP) --}}
    <section id="gis" class="py-20 border-t border-[#EAECF0] bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
                <div class="text-xs font-bold uppercase tracking-wider text-[#00629B]">Pantauan Data Spasial</div>
                <h2 class="text-3xl font-bold text-[#101828] tracking-tight">
                    Cakupan Layanan & Pemetaan Spasial
                </h2>
                <p class="text-[#475467] text-sm sm:text-base leading-relaxed">
                    Basis data terpadu untuk memantau titik kerentanan air bersih di seluruh wilayah Kabupaten Purbalingga.
                </p>
            </div>

            {{-- 4-Column Metric Counters IDDS Styled --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center mb-12">
                <div class="p-6 bg-[#F8FAFC] rounded-xl border border-[#EAECF0] hover:border-[#BAE3F5] transition">
                    <div class="text-4xl font-bold text-[#00629B] tracking-tight">18</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-[#475467] mt-2">Kecamatan Terjangkau</div>
                </div>
                <div class="p-6 bg-[#F8FAFC] rounded-xl border border-[#EAECF0] hover:border-[#BAE3F5] transition">
                    <div class="text-4xl font-bold text-[#00629B] tracking-tight">239</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-[#475467] mt-2">Desa & Kelurahan</div>
                </div>
                <div class="p-6 bg-[#F8FAFC] rounded-xl border border-[#EAECF0] hover:border-[#BAE3F5] transition">
                    <div class="text-4xl font-bold text-[#00629B] tracking-tight">5.000 L</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-[#475467] mt-2">Standar per Tangki</div>
                </div>
                <div class="p-6 bg-[#F8FAFC] rounded-xl border border-[#EAECF0] hover:border-[#BAE3F5] transition">
                    <div class="text-4xl font-bold text-[#00629B] tracking-tight">100%</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-[#475467] mt-2">Penilaian Terbuka</div>
                </div>
            </div>

            {{-- Large Widescreen Wireframe Map Box with Water Tint --}}
            <div class="w-full aspect-[21/9] sm:h-[420px] rounded-xl bg-[#EBF5FB]/70 border border-[#BAE3F5] relative overflow-hidden flex items-center justify-center">
                <svg class="absolute inset-0 w-full h-full text-[#BAE3F5]/70 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100" fill="none">
                    <line x1="0" y1="0" x2="100" y2="100" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                    <line x1="0" y1="100" x2="100" y2="0" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                </svg>
                <div class="relative z-10 text-center space-y-2 bg-white/95 backdrop-blur-xs p-6 rounded-xl border border-[#BAE3F5] shadow-sm max-w-md mx-4">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#EBF5FB] text-[11px] font-bold text-[#00629B] uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#00629B]"></span>
                        Wadah Peta Interaktif GIS (Leaflet)
                    </div>
                    <p class="text-xs text-[#475467] leading-relaxed">Menampilkan layer GeoJSON 18 kecamatan dan sebaran titik penampungan air desa.</p>
                    <a href="{{ route('admin.pemetaan.index') }}" class="inline-flex items-center gap-1 mt-2 text-xs font-bold text-[#00629B] hover:text-[#004F7D] transition">
                        <span>Buka Peta GIS Lengkap</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- 5. SECTION: PARTNERS / MITRA TERKAIT --}}
    <section class="py-14 border-t border-[#EAECF0] bg-[#F8FAFC]">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h3 class="text-xs font-bold uppercase tracking-widest text-[#667085] mb-8">
                Mitra & Lembaga Terkait
            </h3>
            <div class="flex flex-wrap items-center justify-center gap-8 sm:gap-14 opacity-75">
                <span class="font-bold text-[#475467] hover:text-[#00629B] text-sm transition">PEMKAB PURBALINGGA</span>
                <span class="font-bold text-[#475467] hover:text-[#00629B] text-sm transition">BPBD JAWA TENGAH</span>
                <span class="font-bold text-[#475467] hover:text-[#00629B] text-sm transition">BMKG STASIUN METEOROLOGI</span>
                <span class="font-bold text-[#475467] hover:text-[#00629B] text-sm transition">PMI PURBALINGGA</span>
                <span class="font-bold text-[#475467] hover:text-[#00629B] text-sm transition">PDAM TIRTA PERWIRA</span>
            </div>
        </div>
    </section>

    {{-- 6. SECTION: LATEST BLOG / DOKUMENTASI PROGRAM --}}
    <section id="program" class="py-20 border-t border-[#EAECF0] bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
                <div class="text-xs font-bold uppercase tracking-wider text-[#00629B]">Laporan Lapangan</div>
                <h2 class="text-3xl font-bold text-[#101828] tracking-tight">
                    Dokumentasi Penyaluran Terkini
                </h2>
                <p class="text-[#475467] text-sm sm:text-base leading-relaxed">
                    Laporan kegiatan riil distribusi bantuan air bersih di berbagai desa Kabupaten Purbalingga.
                </p>
            </div>

            {{-- 3-Column Card Grid IDDS Styled --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Card 1 --}}
                <div class="border border-[#EAECF0] hover:border-[#BAE3F5] rounded-xl overflow-hidden bg-white hover:shadow-md hover:shadow-neutral-200/50 transition flex flex-col justify-between group">
                    <div>
                        <div class="w-full aspect-[16/10] bg-[#EBF5FB]/80 border-b border-[#BAE3F5]/60 relative overflow-hidden flex items-center justify-center">
                            <svg class="absolute inset-0 w-full h-full text-[#BAE3F5]/60 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100" fill="none">
                                <line x1="0" y1="0" x2="100" y2="100" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                                <line x1="0" y1="100" x2="100" y2="0" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                            </svg>
                            <span class="relative z-10 text-[11px] text-[#00629B] font-semibold bg-white px-2.5 py-1 rounded-md border border-[#BAE3F5] shadow-xs">
                                Foto Tangki Serang
                            </span>
                        </div>
                        <div class="p-6 space-y-2">
                            <h3 class="font-bold text-lg text-[#101828] group-hover:text-[#00629B] transition">Distribusi Tangki Air Desa Serang</h3>
                            <p class="text-xs text-[#475467] leading-relaxed">
                                Penyaluran 15.000 liter air bersih untuk 240 Kepala Keluarga di Dusun 3 yang terdampak penurunan debit mata air.
                            </p>
                        </div>
                    </div>
                    <div class="px-6 pb-6">
                        <a href="#kontak" class="text-xs font-bold text-[#00629B] hover:text-[#004F7D] inline-flex items-center gap-1 transition">
                            <span>Selengkapnya</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div class="border border-[#EAECF0] hover:border-[#BAE3F5] rounded-xl overflow-hidden bg-white hover:shadow-md hover:shadow-neutral-200/50 transition flex flex-col justify-between group">
                    <div>
                        <div class="w-full aspect-[16/10] bg-[#EBF5FB]/80 border-b border-[#BAE3F5]/60 relative overflow-hidden flex items-center justify-center">
                            <svg class="absolute inset-0 w-full h-full text-[#BAE3F5]/60 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100" fill="none">
                                <line x1="0" y1="0" x2="100" y2="100" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                                <line x1="0" y1="100" x2="100" y2="0" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                            </svg>
                            <span class="relative z-10 text-[11px] text-[#00629B] font-semibold bg-white px-2.5 py-1 rounded-md border border-[#BAE3F5] shadow-xs">
                                Foto Tandon Kertanegara
                            </span>
                        </div>
                        <div class="p-6 space-y-2">
                            <h3 class="font-bold text-lg text-[#101828] group-hover:text-[#00629B] transition">Pasokan Tandon Komunal Kertanegara</h3>
                            <p class="text-xs text-[#475467] leading-relaxed">
                                Pengisian 2 tangki armada ke tandon penampungan umum guna memenuhi kebutuhan sanitasi dan konsumsi warga.
                            </p>
                        </div>
                    </div>
                    <div class="px-6 pb-6">
                        <a href="#kontak" class="text-xs font-bold text-[#00629B] hover:text-[#004F7D] inline-flex items-center gap-1 transition">
                            <span>Selengkapnya</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                {{-- Card 3 --}}
                <div class="border border-[#EAECF0] hover:border-[#BAE3F5] rounded-xl overflow-hidden bg-white hover:shadow-md hover:shadow-neutral-200/50 transition flex flex-col justify-between group">
                    <div>
                        <div class="w-full aspect-[16/10] bg-[#EBF5FB]/80 border-b border-[#BAE3F5]/60 relative overflow-hidden flex items-center justify-center">
                            <svg class="absolute inset-0 w-full h-full text-[#BAE3F5]/60 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100" fill="none">
                                <line x1="0" y1="0" x2="100" y2="100" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                                <line x1="0" y1="100" x2="100" y2="0" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                            </svg>
                            <span class="relative z-10 text-[11px] text-[#00629B] font-semibold bg-white px-2.5 py-1 rounded-md border border-[#BAE3F5] shadow-xs">
                                Foto Ponpes Bobotsari
                            </span>
                        </div>
                        <div class="p-6 space-y-2">
                            <h3 class="font-bold text-lg text-[#101828] group-hover:text-[#00629B] transition">Bantuan Air Bersih Fasilitas Bobotsari</h3>
                            <p class="text-xs text-[#475467] leading-relaxed">
                                Respon tanggap darurat pasokan air bersih bagi fasilitas asrama pondok pesantren dan layanan masyarakat sekitar.
                            </p>
                        </div>
                    </div>
                    <div class="px-6 pb-6">
                        <a href="#kontak" class="text-xs font-bold text-[#00629B] hover:text-[#004F7D] inline-flex items-center gap-1 transition">
                            <span>Selengkapnya</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <a
                    href="#program"
                    class="min-h-[44px] inline-flex items-center justify-center px-6 py-2.5 border border-[#D0D5DD] hover:bg-[#F8FAFC] text-[#344054] text-sm font-semibold rounded-lg shadow-xs transition"
                >
                    Lihat Semua Dokumentasi
                </a>
            </div>
        </div>
    </section>

    {{-- 7. SECTION: OUR HAPPY CLIENTS (ASPIRASI & LAPORAN DESA) --}}
    <section class="py-20 border-t border-[#EAECF0] bg-[#F8FAFC]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
                <div class="text-xs font-bold uppercase tracking-wider text-[#00629B]">Suara Penerima Manfaat</div>
                <h2 class="text-3xl font-bold text-[#101828] tracking-tight">
                    Aspirasi & Laporan Penerima Manfaat
                </h2>
                <p class="text-[#475467] text-sm sm:text-base leading-relaxed">
                    Umpan balik langsung dari perwakilan pemerintah desa mengenai penyaluran bantuan air bersih.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                {{-- Testimonial Card 1 --}}
                <div class="bg-white border border-[#E4E7EC] hover:border-[#BAE3F5] rounded-xl p-8 shadow-xs space-y-6 transition">
                    <p class="text-[#344054] text-sm leading-relaxed italic">
                        "Dengan sistem penilaian objektif ini, desa kami yang posisinya di perbukitan tinggi bisa mendapatkan alokasi armada tangki secara pasti tanpa harus bolak-balik mengajukan proposal manual."
                    </p>
                    <div class="flex items-center gap-3 pt-2 border-t border-[#EAECF0]">
                        <div class="w-10 h-10 rounded-full bg-[#EBF5FB] border border-[#BAE3F5] flex items-center justify-center text-xs font-bold text-[#00629B]">
                            ST
                        </div>
                        <div>
                            <div class="text-sm font-bold text-[#101828]">Sutrisno</div>
                            <div class="text-xs text-[#667085]">Kepala Desa Serang, Karangreja</div>
                        </div>
                    </div>
                </div>

                {{-- Testimonial Card 2 --}}
                <div class="bg-white border border-[#E4E7EC] hover:border-[#BAE3F5] rounded-xl p-8 shadow-xs space-y-6 transition">
                    <p class="text-[#344054] text-sm leading-relaxed italic">
                        "Penyaluran air tepat waktu dan koordinasi pengemudi tangki ke tandon komunal RT sangat jelas. Warga dapat memantau kapan armada tiba melalui pengumuman perangkat desa."
                    </p>
                    <div class="flex items-center gap-3 pt-2 border-t border-[#EAECF0]">
                        <div class="w-10 h-10 rounded-full bg-[#EBF5FB] border border-[#BAE3F5] flex items-center justify-center text-xs font-bold text-[#00629B]">
                            WA
                        </div>
                        <div>
                            <div class="text-sm font-bold text-[#101828]">Wahyudi Ansori</div>
                            <div class="text-xs text-[#667085]">Perangkat Desa Kertanegara</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 8. SECTION: FREQUENTLY ASKED QUESTIONS (FAQ + WIREFRAME IMAGE) --}}
    <section id="faq" class="py-20 border-t border-[#EAECF0] bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
                {{-- Left: Image Box IDDS Styled --}}
                <div class="lg:col-span-5 aspect-square rounded-xl bg-[#EBF5FB]/70 border border-[#BAE3F5] relative overflow-hidden flex items-center justify-center">
                    <svg class="absolute inset-0 w-full h-full text-[#BAE3F5]/60 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100" fill="none">
                        <line x1="0" y1="0" x2="100" y2="100" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                        <line x1="0" y1="100" x2="100" y2="0" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                    </svg>
                    <span class="relative z-10 text-xs font-semibold text-[#00629B] bg-white px-3 py-1.5 rounded-lg border border-[#BAE3F5] shadow-xs">
                        Panduan Syarat & Prosedur
                    </span>
                </div>

                {{-- Right: Accordion FAQ Items --}}
                <div class="lg:col-span-7 space-y-6">
                    <div>
                        <div class="text-xs font-bold uppercase tracking-wider text-[#00629B]">Informasi Publik</div>
                        <h2 class="text-3xl font-bold text-[#101828] tracking-tight">
                            Pertanyaan yang Sering Diajukan
                        </h2>
                        <p class="text-[#475467] text-sm mt-2">
                            Informasi mengenai tata cara pengajuan dan mekanisme penyaluran air bersih BPBD Purbalingga.
                        </p>
                    </div>

                    <div class="space-y-3 pt-2">
                        <div class="border border-[#EAECF0] hover:border-[#BAE3F5] rounded-xl p-5 bg-[#F8FAFC] hover:bg-white transition">
                            <h3 class="font-bold text-[#101828] text-sm">Siapa yang berhak mengajukan bantuan air bersih?</h3>
                            <p class="text-xs text-[#475467] mt-2 leading-relaxed">
                                Permohonan diajukan resmi oleh Pemerintah Desa atas nama warga yang mengalami krisis air melalui surat permohonan Kepala Desa yang diketahui Camat setempat.
                            </p>
                        </div>

                        <div class="border border-[#EAECF0] hover:border-[#BAE3F5] rounded-xl p-5 bg-[#F8FAFC] hover:bg-white transition">
                            <h3 class="font-bold text-[#101828] text-sm">Bagaimana urutan prioritas ditentukan?</h3>
                            <p class="text-xs text-[#475467] mt-2 leading-relaxed">
                                Sistem menggunakan metode AHP-SAW untuk menilai tingkat keparahan krisis, kepadatan jiwa terdampak, serta proporsi kelompok rentan (balita, lansia, dan disabilitas).
                            </p>
                        </div>

                        <div class="border border-[#EAECF0] hover:border-[#BAE3F5] rounded-xl p-5 bg-[#F8FAFC] hover:bg-white transition">
                            <h3 class="font-bold text-[#101828] text-sm">Apakah ada biaya pengiriman atau uang bensin?</h3>
                            <p class="text-xs text-[#475467] mt-2 leading-relaxed">
                                Tidak ada. Seluruh bantuan air bersih beserta operasional armada truk tangki BPBD Kabupaten Purbalingga diberikan gratis 100% tanpa pungutan apa pun.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 9. SECTION: CALL TO ACTION BANNER (NEED MORE INFORMATION?) --}}
    <section id="kontak" class="py-16 bg-[#F8FAFC] border-t border-[#EAECF0]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="bg-gradient-to-r from-[#004F7D] via-[#00629B] to-[#004F7D] text-white rounded-2xl p-8 sm:p-12 flex flex-col md:flex-row items-center justify-between gap-6 shadow-lg shadow-[#00629B]/20">
                <div class="space-y-2 max-w-xl text-center md:text-left">
                    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight">
                        Desa Anda Membutuhkan Bantuan Air Segera?
                    </h2>
                    <p class="text-blue-100 text-sm leading-relaxed">
                        Hubungi posko siaga BPBD Kabupaten Purbalingga atau daftarkan permohonan melalui perwakilan perangkat desa Anda.
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <a
                        href="tel:0281891234"
                        class="min-h-[44px] inline-flex items-center justify-center px-6 py-3.5 bg-white hover:bg-[#EBF5FB] text-[#00629B] text-sm font-bold rounded-lg shadow-sm transition"
                    >
                        Hubungi Posko: (0281) 891-234
                    </a>
                </div>
            </div>
        </div>
    </section>

</main>
@endsection

@section('footer')
{{-- 10. FOOTER IDDS STANDARD --}}
<footer class="w-full bg-white border-t border-[#EAECF0] text-[#475467] text-xs py-14">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-12">
            {{-- Col 1: Brand Info --}}
            <div class="col-span-2 space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-[#00629B] flex items-center justify-center text-white shadow-xs" aria-hidden="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v18M3 12h18"/></svg>
                    </div>
                    <span class="font-bold text-base">
                        <span class="text-[#101828]">Lingga</span><span class="text-[#00629B]">Air</span>
                    </span>
                </div>
                <p class="text-[#475467] leading-relaxed max-w-sm">
                    Sistem Informasi Distribusi Air Bersih dan Pemetaan GIS Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Purbalingga.
                </p>
                <p class="text-[#667085]">
                    Jl. Mayjen Sungkono No. 12, Selabaya, Kalimanah, Purbalingga 53371.
                </p>
            </div>

            {{-- Col 2: Layanan --}}
            <div class="space-y-3">
                <h4 class="font-bold text-[#101828] uppercase tracking-wider text-[11px]">Layanan</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('cek-kelayakan.index') }}" class="hover:text-[#00629B] transition">Cek Kelayakan</a></li>
                    <li><a href="{{ route('admin.pemetaan.index') }}" class="hover:text-[#00629B] transition">Peta Sebaran GIS</a></li>
                    <li><a href="#program" class="hover:text-[#00629B] transition">Dokumentasi</a></li>
                    <li><a href="#faq" class="hover:text-[#00629B] transition">Panduan SOP</a></li>
                </ul>
            </div>

            {{-- Col 3: Portal --}}
            <div class="space-y-3">
                <h4 class="font-bold text-[#101828] uppercase tracking-wider text-[11px]">Akses</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('login') }}" class="hover:text-[#00629B] transition">Login Verifikator</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-[#00629B] transition">Login Pengemudi</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-[#00629B] transition">Login Desa</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-[#00629B] transition">Daftar Akun Baru</a></li>
                </ul>
            </div>

            {{-- Col 4: Posko Siaga --}}
            <div class="space-y-3">
                <h4 class="font-bold text-[#101828] uppercase tracking-wider text-[11px]">Posko Siaga</h4>
                <p class="text-[#667085]">Hotline: (0281) 891-234</p>
                <p class="text-[#667085]">WA: 0812-3456-7890</p>
                <div class="flex items-center gap-2 pt-2">
                    <span class="w-6 h-6 rounded-full bg-[#EBF5FB] border border-[#BAE3F5] flex items-center justify-center text-[10px] text-[#00629B] font-bold">X</span>
                    <span class="w-6 h-6 rounded-full bg-[#EBF5FB] border border-[#BAE3F5] flex items-center justify-center text-[10px] text-[#00629B] font-bold">FB</span>
                    <span class="w-6 h-6 rounded-full bg-[#EBF5FB] border border-[#BAE3F5] flex items-center justify-center text-[10px] text-[#00629B] font-bold">IG</span>
                    <span class="w-6 h-6 rounded-full bg-[#EBF5FB] border border-[#BAE3F5] flex items-center justify-center text-[10px] text-[#00629B] font-bold">YT</span>
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-[#EAECF0] flex flex-col sm:flex-row items-center justify-between gap-4 text-[#667085]">
            <div>
                &copy; 2026 BPBD Kabupaten Purbalingga. Seluruh hak cipta dilindungi.
            </div>
            <div class="flex items-center gap-4 text-[11px]">
                <span>Sistem Penyaluran Terpadu</span>
                <span>&bull;</span>
                <span>Bebas Biaya</span>
                <span>&bull;</span>
                <span>SPK AHP-SAW Engine</span>
            </div>
        </div>
    </div>
</footer>
@endsection
