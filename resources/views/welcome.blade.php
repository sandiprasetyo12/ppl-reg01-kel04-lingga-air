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
{{-- 1. NAVBAR (HEADER IDDS STANDARD - TRANSPARENT AT TOP, WHITE ON SCROLL) --}}
<header id="main-navbar" class="w-full fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-transparent border-b border-transparent">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 h-20 flex items-center justify-between gap-4">
        {{-- Logo IDDS LinggaAir (One-line: Blue Droplet + lingga + AIR) --}}
        <a href="{{ url('/') }}" class="flex items-center min-h-[44px] group">
            <img 
                id="nav-logo" 
                src="{{ asset('images/logo-lingga-oneline.png') }}" 
                alt="LinggaAir" 
                class="h-9 sm:h-10 w-auto object-contain transition-transform duration-200 group-hover:scale-102"
            >
        </a>

        {{-- Nav Links --}}
        <nav aria-label="Navigasi Utama" class="hidden md:flex items-center gap-8 text-sm font-medium">
            <a href="#hero" class="nav-link text-white font-semibold border-b-2 border-white py-1 transition-colors">Beranda</a>
            <a href="{{ route('cek-kelayakan.index') }}" class="nav-link text-white/90 hover:text-white transition-colors">Cek Kelayakan</a>
            <a href="#gis" class="nav-link text-white/90 hover:text-white transition-colors">Peta GIS</a>
            <a href="#tentang" class="nav-link text-white/90 hover:text-white transition-colors">Tentang Layanan</a>
            <a href="#prosedur" class="nav-link text-white/90 hover:text-white transition-colors">Alur SOP</a>
            <a href="#program" class="nav-link text-white/90 hover:text-white transition-colors">Program</a>
            <a href="#faq" class="nav-link text-white/90 hover:text-white transition-colors">FAQ</a>
        </nav>

        {{-- Auth Action (Transparent Text when top, Blue button card when scrolled) --}}
        <div class="flex items-center gap-3">
            @auth
                <a
                    id="nav-auth-btn"
                    href="{{ route($dashboardRouteName) }}"
                    class="min-h-[44px] inline-flex items-center justify-center px-4 py-2 text-white/90 hover:text-white text-sm font-semibold transition bg-transparent border-0"
                >
                    Dashboard
                </a>
            @else
                <a
                    id="nav-auth-btn"
                    href="{{ route('login') }}"
                    class="min-h-[44px] inline-flex items-center justify-center px-4 py-2 text-white/90 hover:text-white text-sm font-semibold transition bg-transparent border-0"
                >
                    Masuk
                </a>
            @endauth
        </div>
    </div>
</header>
@endsection

@section('content')
<main class="w-full bg-white text-[#101828] flex-1">

    {{-- 2. HERO SECTION (FULL WIDTH EDGE-TO-EDGE WITH SIDE BREATHING PADDING) --}}
    <section id="hero" class="relative w-full min-h-[580px] lg:min-h-[660px] flex items-center justify-center bg-neutral-900 text-white overflow-hidden pt-20">
        {{-- Background Real Photo Layer (Natural Image) --}}
        <img 
            src="{{ asset('images/hero.jpg') }}" 
            alt="Masyarakat Desa Mengambil Bantuan Air Bersih di Purbalingga" 
            class="absolute inset-0 w-full h-full object-cover object-center pointer-events-none"
        >

        {{-- Natural Dark Neutral Overlay (Zero Blue Tint) --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/55 to-black/75 pointer-events-none"></div>

        {{-- Centered Content (Raised Slightly as Requested, Standard Padding) --}}
        <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 w-full flex flex-col items-center justify-center text-center pt-10 pb-20 lg:pt-14 lg:pb-28">
            <div class="max-w-4xl mx-auto space-y-6 -mt-4 sm:-mt-6">
                {{-- Centered H1 (Font Medium - Lighter, Sleek & Elegant) --}}
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-medium text-white tracking-tight leading-[1.25] max-w-3xl mx-auto drop-shadow-xs">
                    Penyaluran Bantuan Air Bersih Kabupaten Purbalingga
                </h1>

                {{-- Centered Subtitle --}}
                <p class="text-base sm:text-lg text-slate-100 leading-relaxed max-w-2xl mx-auto drop-shadow-xs">
                    Layanan resmi BPBD Kabupaten Purbalingga untuk memantau permohonan desa, jadwal distribusi truk tangki, dan pemetaan wilayah terdampak kekeringan secara transparan.
                </p>

                {{-- Centered CTA Buttons --}}
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-6 sm:gap-8">
                    <a
                        href="{{ route('cek-kelayakan.index') }}"
                        class="w-full sm:w-auto min-h-[44px] inline-flex items-center justify-center px-7 py-3.5 bg-[#00629B] hover:bg-[#004F7D] text-white text-sm font-semibold rounded-lg shadow-md shadow-black/20 hover:shadow-[#00629B]/30 transition"
                    >
                        Cek Kelayakan Desa
                    </a>
                    <button
                        type="button"
                        onclick="openVideoModal()"
                        class="inline-flex items-center gap-3.5 bg-transparent border-0 min-h-[44px] group focus:outline-none cursor-pointer"
                    >
                        <span class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-white text-neutral-900 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform flex-shrink-0" aria-hidden="true">
                            <svg class="w-5 h-5 fill-current ml-0.5" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </span>
                        <span class="text-white font-bold tracking-wider text-sm sm:text-base uppercase drop-shadow-xs group-hover:text-slate-200 transition-colors">
                            WATCH VIDEO
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- Video Modal Dialog --}}
    <div id="video-modal" class="fixed inset-0 z-50 bg-black/85 hidden items-center justify-center p-4 backdrop-blur-xs" role="dialog" aria-modal="true" aria-label="Video Dokumentasi Penyaluran Air">
        <div class="relative w-full max-w-3xl bg-neutral-900 rounded-2xl overflow-hidden shadow-2xl border border-white/20">
            <div class="flex items-center justify-between px-5 py-3.5 bg-neutral-800 border-b border-neutral-700 text-white text-xs font-semibold">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#38BDF8]"></span>
                    <span>Dokumentasi Distribusi Bantuan Air Bersih BPBD Purbalingga</span>
                </div>
                <button type="button" onclick="closeVideoModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white text-base leading-none transition" aria-label="Tutup Video">
                    &times;
                </button>
            </div>
            <div class="aspect-video w-full bg-black relative flex items-center justify-center">
                <div class="relative w-full h-full flex flex-col items-center justify-center p-6 text-center text-white bg-gradient-to-t from-black/90 via-black/50 to-black/80">
                    <img src="{{ asset('images/hero.jpg') }}" alt="Preview Video Dokumentasi" class="absolute inset-0 w-full h-full object-cover opacity-35 pointer-events-none">
                    <div class="relative z-10 space-y-3 max-w-md">
                        <div class="w-14 h-14 rounded-full bg-[#00629B] text-white flex items-center justify-center mx-auto shadow-lg shadow-[#00629B]/40">
                            <svg class="w-6 h-6 fill-current ml-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                        <h3 class="font-semibold text-base sm:text-lg">Dokumentasi Operasional Armada Tangki BPBD</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Merekam proses distribusi air bersih bagi warga terdampak kekeringan di 18 kecamatan Kabupaten Purbalingga.
                        </p>
                        <a href="#program" onclick="closeVideoModal()" class="inline-flex items-center gap-1.5 text-xs text-[#38BDF8] hover:underline font-semibold mt-2">
                            <span>Lihat Dokumentasi Kegiatan Selengkapnya</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. SECTION: KENAPA SISTEM INI DIBUTUHKAN (COLLAGE + 4 POIN) --}}
    <section id="tentang" class="border-t border-[#EAECF0] py-20 bg-[#F8FAFC]">
        <div class="max-w-7xl mx-auto px-6">
            {{-- Centered Header --}}
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <div class="text-xs font-semibold uppercase tracking-wider text-[#00629B]">Kesiapsiagaan Krisis Air</div>
                <h2 class="text-3xl font-semibold text-[#101828] tracking-tight">
                    Kenapa Sistem Ini Dibutuhkan
                </h2>
                <p class="text-[#475467] text-sm sm:text-base leading-relaxed">
                    Menghadirkan kepastian alokasi pasokan air bersih yang adil, transparan, dan terukur bagi masyarakat desa terdampak kemarau.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Left: 3-Photo Collage Armada BPBD --}}
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="aspect-[4/3] rounded-xl bg-[#EBF5FB] border border-[#BAE3F5] relative overflow-hidden group shadow-xs">
                            <img 
                                src="{{ asset('images/armada1.png') }}" 
                                alt="Armada Tangki Air BPBD 1" 
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                            >
                            <span class="absolute bottom-2.5 left-2.5 text-[11px] font-semibold text-[#00629B] bg-white/95 backdrop-blur-xs px-2.5 py-1 rounded-md border border-[#BAE3F5] shadow-xs">
                                Armada 1
                            </span>
                        </div>
                        <div class="aspect-[4/3] rounded-xl bg-[#EBF5FB] border border-[#BAE3F5] relative overflow-hidden group shadow-xs">
                            <img 
                                src="{{ asset('images/armada2.png') }}" 
                                alt="Armada Tangki Air BPBD 2" 
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                            >
                            <span class="absolute bottom-2.5 left-2.5 text-[11px] font-semibold text-[#00629B] bg-white/95 backdrop-blur-xs px-2.5 py-1 rounded-md border border-[#BAE3F5] shadow-xs">
                                Armada 2
                            </span>
                        </div>
                    </div>
                    <div class="aspect-[21/9] rounded-xl bg-[#EBF5FB] border border-[#BAE3F5] relative overflow-hidden group shadow-xs">
                        <img 
                            src="{{ asset('images/armada3.png') }}" 
                            alt="Armada Tangki Air BPBD 3 - Penyaluran Tandon Warga" 
                            class="w-full h-full object-cover object-center group-hover:scale-105 transition duration-300"
                        >
                        <span class="absolute bottom-2.5 left-2.5 text-[11px] font-semibold text-[#00629B] bg-white/95 backdrop-blur-xs px-2.5 py-1 rounded-md border border-[#BAE3F5] shadow-xs">
                            Armada 3 - Penyaluran Tandon Warga
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
                                <h3 class="font-semibold text-[#101828] text-base">Penilaian Objektif Berbasis AHP-SAW</h3>
                                <p class="text-[#475467] text-sm mt-0.5">Penentuan urutan prioritas dihitung matematis berdasarkan kepadatan balita, lansia, dan ketiadaan air.</p>
                            </div>
                        </li>

                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 rounded-lg bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center flex-shrink-0 mt-0.5 shadow-xs" aria-hidden="true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-[#101828] text-base">Kapasitas Armada Standar 5.000 Liter</h3>
                                <p class="text-[#475467] text-sm mt-0.5">Setiap rit truk tangki membawa 5.000 liter air bersih higienis siap pakai untuk tandon komunal.</p>
                            </div>
                        </li>

                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 rounded-lg bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center flex-shrink-0 mt-0.5 shadow-xs" aria-hidden="true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-[#101828] text-base">Pemetaan Wilayah Terintegrasi GIS</h3>
                                <p class="text-[#475467] text-sm mt-0.5">Pantauan spasial titik lokasi kekeringan di 18 kecamatan menggunakan poligon batas GeoJSON resmi.</p>
                            </div>
                        </li>

                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 rounded-lg bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center flex-shrink-0 mt-0.5 shadow-xs" aria-hidden="true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-[#101828] text-base">Layanan Bebas Biaya Retribusi (Gratis)</h3>
                                <p class="text-[#475467] text-sm mt-0.5">Seluruh distribusi air bersih dari BPBD Kabupaten Purbalingga tidak dipungut biaya apa pun.</p>
                            </div>
                        </li>
                    </ul>

                    <div class="pt-4">
                        <a
                            href="#prosedur"
                            class="min-h-[44px] inline-flex items-center justify-center px-6 py-2.5 bg-[#00629B] hover:bg-[#004F7D] text-white text-sm font-semibold rounded-lg shadow-sm shadow-[#00629B]/20 transition"
                        >
                            Pelajari Alur Layanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. SECTION: METRICS & LIVE GIS MAP SHOWCASE --}}
    <section id="gis" class="py-20 border-t border-[#EAECF0] bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
                <div class="text-xs font-semibold uppercase tracking-wider text-[#00629B]">Pantauan Data Spasial</div>
                <h2 class="text-3xl font-semibold text-[#101828] tracking-tight">
                    Cakupan Layanan &amp; Pemetaan Spasial
                </h2>
                <p class="text-[#475467] text-sm sm:text-base leading-relaxed">
                    Basis data terpadu untuk memantau titik kerentanan air bersih dan progres penyaluran di seluruh wilayah Kabupaten Purbalingga.
                </p>
            </div>

            {{-- 4-Column Metric Counters IDDS Styled (Clean & Subdued) --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 text-center mb-10">
                <div class="p-6 bg-white rounded-xl border border-[#EAECF0] shadow-xs hover:border-[#D0D5DD] transition">
                    <div class="text-3xl sm:text-4xl font-semibold text-[#00629B] tracking-tight">18</div>
                    <div class="text-xs font-medium uppercase tracking-wider text-[#667085] mt-1.5">Kecamatan Terjangkau</div>
                </div>
                <div class="p-6 bg-white rounded-xl border border-[#EAECF0] shadow-xs hover:border-[#D0D5DD] transition">
                    <div class="text-3xl sm:text-4xl font-semibold text-[#00629B] tracking-tight">239</div>
                    <div class="text-xs font-medium uppercase tracking-wider text-[#667085] mt-1.5">Desa &amp; Kelurahan</div>
                </div>
                <div class="p-6 bg-white rounded-xl border border-[#EAECF0] shadow-xs hover:border-[#D0D5DD] transition">
                    <div class="text-3xl sm:text-4xl font-semibold text-[#00629B] tracking-tight">5.000 L</div>
                    <div class="text-xs font-medium uppercase tracking-wider text-[#667085] mt-1.5">Standar per Tangki</div>
                </div>
                <div class="p-6 bg-white rounded-xl border border-[#EAECF0] shadow-xs hover:border-[#D0D5DD] transition">
                    <div class="text-3xl sm:text-4xl font-semibold text-[#00629B] tracking-tight">100%</div>
                    <div class="text-xs font-medium uppercase tracking-wider text-[#667085] mt-1.5">Penilaian Terbuka</div>
                </div>
            </div>

            {{-- Interactive Leaflet GIS Map Container --}}
            <div class="rounded-2xl border border-[#EAECF0] shadow-sm overflow-hidden bg-white">
                {{-- Status Bar & Legend --}}
                <div class="px-5 py-3.5 bg-[#F8FAFC] border-b border-[#EAECF0] flex flex-wrap items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#00629B]"></span>
                        <span class="font-semibold text-[#101828]">Status Sebaran Desa</span>
                        <span class="text-[#667085] hidden sm:inline">(Kabupaten Purbalingga)</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-[#475467] font-medium">
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#DC2626]"></span>
                            <span>Perlu Penanganan (<strong id="stat-merah" class="text-[#101828]">0</strong>)</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#D97706]"></span>
                            <span>Proses Penyaluran (<strong id="stat-kuning" class="text-[#101828]">0</strong>)</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#059669]"></span>
                            <span>Tersalurkan (<strong id="stat-hijau" class="text-[#101828]">0</strong>)</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#6B7280]"></span>
                            <span>Tanpa Tiket (<strong id="stat-abu" class="text-[#101828]">0</strong>)</span>
                        </span>
                    </div>
                </div>

                {{-- Map Frame --}}
                <div class="relative w-full h-[460px] sm:h-[540px] bg-[#F1F5F9]">
                    <div id="landing-map" class="w-full h-full" style="z-index: 1;"></div>
                    <div id="landing-map-loading" class="absolute inset-0 bg-white/80 backdrop-blur-xs z-10 flex flex-col items-center justify-center p-6 text-center">
                        <div class="w-8 h-8 rounded-full border-2 border-[#00629B] border-t-transparent animate-spin mb-3" aria-hidden="true"></div>
                        <p class="text-xs font-medium text-[#475467]">Memuat data spasial 239 desa...</p>
                    </div>
                </div>

                {{-- Map Footer Action --}}
                <div class="px-5 py-3.5 bg-[#F8FAFC] border-t border-[#EAECF0] flex flex-wrap items-center justify-between gap-3 text-xs text-[#475467]">
                    <span>Menampilkan layer poligon batas administrasi 239 desa serta titik sebaran pengajuan air bersih.</span>
                    <a href="{{ route('admin.pemetaan.index') }}" class="font-semibold text-[#00629B] hover:text-[#004F7D] inline-flex items-center gap-1.5 transition">
                        <span>Buka Peta GIS Lengkap</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- 5. SECTION: PARTNERS / MITRA TERKAIT (CONTINUOUS LOGO SCROLLER WITHOUT CARDS) --}}
    <section class="py-14 sm:py-18 border-y border-[#EAECF0] bg-[#F8FAFC] overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 text-center space-y-2 mb-10">
            <span class="text-xs font-semibold uppercase tracking-widest text-[#00629B]">
                Kolaborasi Lintas Lembaga
            </span>
            <h2 class="text-2xl sm:text-3xl font-semibold text-[#101828] tracking-tight">
                Mitra &amp; Lembaga Penyalur Terkait
            </h2>
            <p class="text-sm text-[#475467] max-w-xl mx-auto leading-relaxed">
                Didukung oleh sinergi instansi pemerintah dan organisasi kemanusiaan dalam penyediaan armada serta distribusi air bersih Kabupaten Purbalingga.
            </p>
        </div>

        {{-- Bare Logos Continuous Marquee (No Cards, Bold Size, Seamless Infinite Loop) --}}
        <div class="relative w-full overflow-hidden [mask-image:linear-gradient(to_right,transparent,black_10%,black_90%,transparent)]">
            <div class="flex items-center gap-14 sm:gap-20 lg:gap-24 animate-marquee py-3">
                {{-- Track 1 --}}
                <div class="flex items-center gap-14 sm:gap-20 lg:gap-24 flex-shrink-0">
                    <img src="{{ asset('images/mitra/pemkab.svg') }}" alt="Pemkab Purbalingga" class="h-14 sm:h-18 lg:h-20 w-auto object-contain flex-shrink-0 hover:scale-105 transition-transform duration-200" title="Pemerintah Kabupaten Purbalingga">
                    <img src="{{ asset('images/mitra/bpbd.png') }}" alt="BPBD Jawa Tengah" class="h-14 sm:h-18 lg:h-20 w-auto object-contain flex-shrink-0 hover:scale-105 transition-transform duration-200" title="BPBD Jawa Tengah">
                    <img src="{{ asset('images/mitra/bmkg.png') }}" alt="BMKG" class="h-14 sm:h-18 lg:h-20 w-auto object-contain flex-shrink-0 hover:scale-105 transition-transform duration-200" title="BMKG Stasiun Meteorologi">
                    <img src="{{ asset('images/mitra/pmi.svg') }}" alt="PMI Purbalingga" class="h-14 sm:h-18 lg:h-20 w-auto object-contain flex-shrink-0 hover:scale-105 transition-transform duration-200" title="PMI Cabang Purbalingga">
                    <img src="{{ asset('images/mitra/pdam.png') }}" alt="PDAM Tirta Perwira" class="h-14 sm:h-18 lg:h-20 w-auto object-contain flex-shrink-0 hover:scale-105 transition-transform duration-200" title="Perumda Air Minum Tirta Perwira">
                    <img src="{{ asset('images/mitra/dinsos.svg') }}" alt="Dinas Sosial" class="h-14 sm:h-18 lg:h-20 w-auto object-contain flex-shrink-0 hover:scale-105 transition-transform duration-200" title="Dinas Sosial Purbalingga">
                    <img src="{{ asset('images/mitra/baznas.svg') }}" alt="BAZNAS Purbalingga" class="h-14 sm:h-18 lg:h-20 w-auto object-contain flex-shrink-0 hover:scale-105 transition-transform duration-200" title="BAZNAS Kabupaten Purbalingga">
                </div>
                {{-- Track 2 (Seamless loop copy) --}}
                <div class="flex items-center gap-14 sm:gap-20 lg:gap-24 flex-shrink-0" aria-hidden="true">
                    <img src="{{ asset('images/mitra/pemkab.svg') }}" alt="Pemkab Purbalingga" class="h-14 sm:h-18 lg:h-20 w-auto object-contain flex-shrink-0 hover:scale-105 transition-transform duration-200">
                    <img src="{{ asset('images/mitra/bpbd.png') }}" alt="BPBD Jawa Tengah" class="h-14 sm:h-18 lg:h-20 w-auto object-contain flex-shrink-0 hover:scale-105 transition-transform duration-200">
                    <img src="{{ asset('images/mitra/bmkg.png') }}" alt="BMKG" class="h-14 sm:h-18 lg:h-20 w-auto object-contain flex-shrink-0 hover:scale-105 transition-transform duration-200">
                    <img src="{{ asset('images/mitra/pmi.svg') }}" alt="PMI Purbalingga" class="h-14 sm:h-18 lg:h-20 w-auto object-contain flex-shrink-0 hover:scale-105 transition-transform duration-200">
                    <img src="{{ asset('images/mitra/pdam.png') }}" alt="PDAM Tirta Perwira" class="h-14 sm:h-18 lg:h-20 w-auto object-contain flex-shrink-0 hover:scale-105 transition-transform duration-200">
                    <img src="{{ asset('images/mitra/dinsos.svg') }}" alt="Dinas Sosial" class="h-14 sm:h-18 lg:h-20 w-auto object-contain flex-shrink-0 hover:scale-105 transition-transform duration-200">
                    <img src="{{ asset('images/mitra/baznas.svg') }}" alt="BAZNAS Purbalingga" class="h-14 sm:h-18 lg:h-20 w-auto object-contain flex-shrink-0 hover:scale-105 transition-transform duration-200">
                </div>
            </div>
        </div>
    </section>

    {{-- 6. SECTION: LATEST BLOG / DOKUMENTASI PROGRAM --}}
    <section id="program" class="py-20 border-t border-[#EAECF0] bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
                <div class="text-xs font-semibold uppercase tracking-wider text-[#00629B]">Laporan Lapangan</div>
                <h2 class="text-3xl font-semibold text-[#101828] tracking-tight">
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
                        <div class="w-full aspect-[16/10] bg-[#EBF5FB] border-b border-[#BAE3F5]/60 relative overflow-hidden">
                            <img 
                                src="{{ asset('images/armada1.png') }}" 
                                alt="Distribusi Tangki Air Desa Serang" 
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                            >
                            <span class="absolute bottom-2.5 left-2.5 text-[11px] text-[#00629B] font-semibold bg-white/95 backdrop-blur-xs px-2.5 py-1 rounded-md border border-[#BAE3F5] shadow-xs">
                                Tangki Serang
                            </span>
                        </div>
                        <div class="p-6 space-y-2">
                            <h3 class="font-semibold text-lg text-[#101828] group-hover:text-[#00629B] transition">Distribusi Tangki Air Desa Serang</h3>
                            <p class="text-xs text-[#475467] leading-relaxed">
                                Penyaluran 15.000 liter air bersih untuk 240 Kepala Keluarga di Dusun 3 yang terdampak penurunan debit mata air.
                            </p>
                        </div>
                    </div>
                    <div class="px-6 pb-6">
                        <a href="#prosedur" class="text-xs font-semibold text-[#00629B] hover:text-[#004F7D] inline-flex items-center gap-1 transition">
                            <span>Selengkapnya</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div class="border border-[#EAECF0] hover:border-[#BAE3F5] rounded-xl overflow-hidden bg-white hover:shadow-md hover:shadow-neutral-200/50 transition flex flex-col justify-between group">
                    <div>
                        <div class="w-full aspect-[16/10] bg-[#EBF5FB] border-b border-[#BAE3F5]/60 relative overflow-hidden">
                            <img 
                                src="{{ asset('images/armada2.png') }}" 
                                alt="Pasokan Tandon Komunal Kertanegara" 
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                            >
                            <span class="absolute bottom-2.5 left-2.5 text-[11px] text-[#00629B] font-semibold bg-white/95 backdrop-blur-xs px-2.5 py-1 rounded-md border border-[#BAE3F5] shadow-xs">
                                Tandon Kertanegara
                            </span>
                        </div>
                        <div class="p-6 space-y-2">
                            <h3 class="font-semibold text-lg text-[#101828] group-hover:text-[#00629B] transition">Pasokan Tandon Komunal Kertanegara</h3>
                            <p class="text-xs text-[#475467] leading-relaxed">
                                Pengisian 2 tangki armada ke tandon penampungan umum guna memenuhi kebutuhan sanitasi dan konsumsi warga.
                            </p>
                        </div>
                    </div>
                    <div class="px-6 pb-6">
                        <a href="#prosedur" class="text-xs font-semibold text-[#00629B] hover:text-[#004F7D] inline-flex items-center gap-1 transition">
                            <span>Selengkapnya</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                {{-- Card 3 --}}
                <div class="border border-[#EAECF0] hover:border-[#BAE3F5] rounded-xl overflow-hidden bg-white hover:shadow-md hover:shadow-neutral-200/50 transition flex flex-col justify-between group">
                    <div>
                        <div class="w-full aspect-[16/10] bg-[#EBF5FB] border-b border-[#BAE3F5]/60 relative overflow-hidden">
                            <img 
                                src="{{ asset('images/armada3.png') }}" 
                                alt="Bantuan Air Bersih Fasilitas Bobotsari" 
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                            >
                            <span class="absolute bottom-2.5 left-2.5 text-[11px] text-[#00629B] font-semibold bg-white/95 backdrop-blur-xs px-2.5 py-1 rounded-md border border-[#BAE3F5] shadow-xs">
                                Fasilitas Bobotsari
                            </span>
                        </div>
                        <div class="p-6 space-y-2">
                            <h3 class="font-semibold text-lg text-[#101828] group-hover:text-[#00629B] transition">Bantuan Air Bersih Fasilitas Bobotsari</h3>
                            <p class="text-xs text-[#475467] leading-relaxed">
                                Respon tanggap darurat pasokan air bersih bagi fasilitas asrama pondok pesantren dan layanan masyarakat sekitar.
                            </p>
                        </div>
                    </div>
                    <div class="px-6 pb-6">
                        <a href="#prosedur" class="text-xs font-semibold text-[#00629B] hover:text-[#004F7D] inline-flex items-center gap-1 transition">
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
                <div class="text-xs font-semibold uppercase tracking-wider text-[#00629B]">Suara Penerima Manfaat</div>
                <h2 class="text-3xl font-semibold text-[#101828] tracking-tight">
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
                        <div class="w-10 h-10 rounded-full bg-[#EBF5FB] border border-[#BAE3F5] flex items-center justify-center text-xs font-semibold text-[#00629B]">
                            ST
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-[#101828]">Sutrisno</div>
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
                        <div class="w-10 h-10 rounded-full bg-[#EBF5FB] border border-[#BAE3F5] flex items-center justify-center text-xs font-semibold text-[#00629B]">
                            WA
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-[#101828]">Wahyudi Ansori</div>
                            <div class="text-xs text-[#667085]">Perangkat Desa Kertanegara</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 8. SECTION: ALUR & PROSEDUR PENGAJUAN (ALTERNATING TIMELINE PERSIS GAMBAR REFERENSI) --}}
    <section id="prosedur" class="py-20 border-t border-[#EAECF0] bg-white">
        <div class="max-w-7xl mx-auto px-6 sm:px-8">
            {{-- Section Header --}}
            <div class="text-center max-w-2xl mx-auto mb-16 sm:mb-20 space-y-3">
                <div class="text-xs font-semibold uppercase tracking-wider text-[#00629B]">SOP Pelayanan Resmi</div>
                <h2 class="text-3xl font-semibold text-[#101828] tracking-tight">
                    Alur &amp; Prosedur Pengajuan Bantuan Air
                </h2>
                <p class="text-[#475467] text-sm sm:text-base leading-relaxed">
                    Tahapan resmi bagi pemerintah desa mulai dari pengisian permohonan hingga armada tangki air bersih BPBD tiba di lokasi warga.
                </p>
            </div>

            {{-- Alternating Timeline Container --}}
            <div class="relative max-w-4xl mx-auto">
                {{-- Central Vertical Spine Line --}}
                <div class="absolute left-6 md:left-1/2 top-6 bottom-6 w-0.5 bg-[#BAE3F5] -translate-x-1/2 pointer-events-none" aria-hidden="true"></div>

                <div class="space-y-12 sm:space-y-16">
                    {{-- STEP 01: Right Side --}}
                    <div class="relative flex flex-col md:flex-row items-start md:items-center">
                        {{-- Left Column (Empty on Desktop for Step 01) --}}
                        <div class="hidden md:block md:w-1/2 md:pr-12 text-right"></div>

                        {{-- Center Connector + Badge --}}
                        <div class="absolute left-6 md:left-1/2 -translate-x-1/2 flex items-center z-10">
                            {{-- Horizontal Tick to Right on Desktop --}}
                            <div class="hidden md:block w-7 h-0.5 bg-[#BAE3F5]"></div>
                            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#00629B] text-white font-bold text-sm sm:text-base flex items-center justify-center shadow-md shadow-[#00629B]/25 flex-shrink-0">
                                01
                            </div>
                        </div>

                        {{-- Right Column (Content for Step 01) --}}
                        <div class="pl-16 md:pl-12 md:w-1/2 text-left space-y-1">
                            <h3 class="text-base sm:text-lg font-bold text-[#101828]">Isi Formulir Pendaftaran</h3>
                            <p class="text-xs sm:text-sm text-[#475467] leading-relaxed">
                                Lengkapi formulir permohonan online LinggaAir dengan akun desa resmi yang telah terdaftar dan terverifikasi di sistem BPBD Purbalingga.
                            </p>
                        </div>
                    </div>

                    {{-- STEP 02: Left Side --}}
                    <div class="relative flex flex-col md:flex-row items-start md:items-center">
                        {{-- Left Column (Content for Step 02 on Desktop) --}}
                        <div class="hidden md:block md:w-1/2 md:pr-12 text-right space-y-1">
                            <h3 class="text-base sm:text-lg font-bold text-[#101828]">Unggah Dokumen Persyaratan</h3>
                            <p class="text-xs sm:text-sm text-[#475467] leading-relaxed">
                                Unggah dokumen yang diminta dalam format digital. Pastikan surat pengantar Kepala Desa (diketahui Camat) dan foto sumber air warga terbaca jelas.
                            </p>
                        </div>

                        {{-- Center Connector + Badge --}}
                        <div class="absolute left-6 md:left-1/2 -translate-x-1/2 flex items-center z-10">
                            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#00629B] text-white font-bold text-sm sm:text-base flex items-center justify-center shadow-md shadow-[#00629B]/25 flex-shrink-0">
                                02
                            </div>
                            {{-- Horizontal Tick to Left on Desktop --}}
                            <div class="hidden md:block w-7 h-0.5 bg-[#BAE3F5]"></div>
                        </div>

                        {{-- Mobile Fallback Content (Step 02) --}}
                        <div class="md:hidden pl-16 text-left space-y-1">
                            <h3 class="text-base sm:text-lg font-bold text-[#101828]">Unggah Dokumen Persyaratan</h3>
                            <p class="text-xs sm:text-sm text-[#475467] leading-relaxed">
                                Unggah dokumen yang diminta dalam format digital. Pastikan surat pengantar Kepala Desa (diketahui Camat) dan foto sumber air warga terbaca jelas.
                            </p>
                        </div>
                        <div class="hidden md:block md:w-1/2 md:pl-12"></div>
                    </div>

                    {{-- STEP 03: Right Side --}}
                    <div class="relative flex flex-col md:flex-row items-start md:items-center">
                        <div class="hidden md:block md:w-1/2 md:pr-12 text-right"></div>
                        <div class="absolute left-6 md:left-1/2 -translate-x-1/2 flex items-center z-10">
                            <div class="hidden md:block w-7 h-0.5 bg-[#BAE3F5]"></div>
                            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#00629B] text-white font-bold text-sm sm:text-base flex items-center justify-center shadow-md shadow-[#00629B]/25 flex-shrink-0">
                                03
                            </div>
                        </div>
                        <div class="pl-16 md:pl-12 md:w-1/2 text-left space-y-1">
                            <h3 class="text-base sm:text-lg font-bold text-[#101828]">Periksa dan Kirim Formulir</h3>
                            <p class="text-xs sm:text-sm text-[#475467] leading-relaxed">
                                Periksa kembali data demografi jiwa rentan dan titik tandon komunal yang telah diisi. Jika sudah benar dan lengkap, kirim formulir pendaftaran.
                            </p>
                        </div>
                    </div>

                    {{-- STEP 04: Left Side --}}
                    <div class="relative flex flex-col md:flex-row items-start md:items-center">
                        <div class="hidden md:block md:w-1/2 md:pr-12 text-right space-y-1">
                            <h3 class="text-base sm:text-lg font-bold text-[#101828]">Terima Bukti Pendaftaran</h3>
                            <p class="text-xs sm:text-sm text-[#475467] leading-relaxed">
                                Setelah formulir berhasil dikirim, simpan nomor tiket atau bukti pendaftaran digital sebagai referensi resmi untuk pemantauan proses selanjutnya.
                            </p>
                        </div>
                        <div class="absolute left-6 md:left-1/2 -translate-x-1/2 flex items-center z-10">
                            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#00629B] text-white font-bold text-sm sm:text-base flex items-center justify-center shadow-md shadow-[#00629B]/25 flex-shrink-0">
                                04
                            </div>
                            <div class="hidden md:block w-7 h-0.5 bg-[#BAE3F5]"></div>
                        </div>
                        <div class="md:hidden pl-16 text-left space-y-1">
                            <h3 class="text-base sm:text-lg font-bold text-[#101828]">Terima Bukti Pendaftaran</h3>
                            <p class="text-xs sm:text-sm text-[#475467] leading-relaxed">
                                Setelah formulir berhasil dikirim, simpan nomor tiket atau bukti pendaftaran digital sebagai referensi resmi untuk pemantauan proses selanjutnya.
                            </p>
                        </div>
                        <div class="hidden md:block md:w-1/2 md:pl-12"></div>
                    </div>

                    {{-- STEP 05: Right Side --}}
                    <div class="relative flex flex-col md:flex-row items-start md:items-center">
                        <div class="hidden md:block md:w-1/2 md:pr-12 text-right"></div>
                        <div class="absolute left-6 md:left-1/2 -translate-x-1/2 flex items-center z-10">
                            <div class="hidden md:block w-7 h-0.5 bg-[#BAE3F5]"></div>
                            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#00629B] text-white font-bold text-sm sm:text-base flex items-center justify-center shadow-md shadow-[#00629B]/25 flex-shrink-0">
                                05
                            </div>
                        </div>
                        <div class="pl-16 md:pl-12 md:w-1/2 text-left space-y-1">
                            <h3 class="text-base sm:text-lg font-bold text-[#101828]">Verifikasi oleh BPBD Purbalingga</h3>
                            <p class="text-xs sm:text-sm text-[#475467] leading-relaxed">
                                Petugas Pusdalops memeriksa kelengkapan dokumen dan kesesuaian data lapangan, lalu sistem AHP-SAW menghitung skor prioritas kelayakan desa.
                            </p>
                        </div>
                    </div>

                    {{-- STEP 06: Left Side --}}
                    <div class="relative flex flex-col md:flex-row items-start md:items-center">
                        <div class="hidden md:block md:w-1/2 md:pr-12 text-right space-y-1">
                            <h3 class="text-base sm:text-lg font-bold text-[#101828]">Informasi Jadwal &amp; Penyaluran</h3>
                            <p class="text-xs sm:text-sm text-[#475467] leading-relaxed">
                                Pantau jadwal pengiriman armada melalui portal resmi LinggaAir. Truk tangki 5.000 liter diberangkatkan menuju lokasi drop point tandon komunal desa.
                            </p>
                        </div>
                        <div class="absolute left-6 md:left-1/2 -translate-x-1/2 flex items-center z-10">
                            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#00629B] text-white font-bold text-sm sm:text-base flex items-center justify-center shadow-md shadow-[#00629B]/25 flex-shrink-0">
                                06
                            </div>
                            <div class="hidden md:block w-7 h-0.5 bg-[#BAE3F5]"></div>
                        </div>
                        <div class="md:hidden pl-16 text-left space-y-1">
                            <h3 class="text-base sm:text-lg font-bold text-[#101828]">Informasi Jadwal &amp; Penyaluran</h3>
                            <p class="text-xs sm:text-sm text-[#475467] leading-relaxed">
                                Pantau jadwal pengiriman armada melalui portal resmi LinggaAir. Truk tangki 5.000 liter diberangkatkan menuju lokasi drop point tandon komunal desa.
                            </p>
                        </div>
                        <div class="hidden md:block md:w-1/2 md:pl-12"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 9. SECTION: FREQUENTLY ASKED QUESTIONS (MINIMALIST BORDER-DIVIDED ACCORDION PERSIS GAMBAR) --}}
    <section id="faq" class="py-20 border-t border-[#EAECF0] bg-white">
        <div class="max-w-4xl mx-auto px-6 sm:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <div class="text-xs font-semibold uppercase tracking-wider text-[#00629B]">Informasi Publik</div>
                <h2 class="text-3xl font-semibold text-[#101828] tracking-tight">
                    Pertanyaan yang Sering Diajukan
                </h2>
                <p class="text-[#475467] text-sm sm:text-base leading-relaxed">
                    Jawaban resmi seputar mekanisme permohonan, penentuan prioritas, dan operasional bantuan air bersih BPBD Purbalingga.
                </p>
            </div>

            {{-- Minimalist Line-Divided Accordion List (Matching Reference Image) --}}
            <div class="border-t border-[#EAECF0] divide-y divide-[#EAECF0]">
                {{-- FAQ Item 1 --}}
                <div class="faq-item">
                    <button
                        type="button"
                        onclick="toggleFaq(this)"
                        class="w-full min-h-[56px] py-5 sm:py-6 text-left flex items-center justify-between gap-6 group cursor-pointer focus:outline-none"
                        aria-expanded="false"
                    >
                        <span class="text-base sm:text-lg font-semibold text-[#101828] group-hover:text-[#00629B] transition-colors">
                            Siapa yang berhak mengajukan permohonan bantuan air bersih?
                        </span>
                        <svg class="faq-icon w-5 h-5 text-[#667085] group-hover:text-[#00629B] transition-transform duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                    <div class="faq-answer hidden pb-6 text-sm sm:text-base text-[#475467] leading-relaxed">
                        <p>
                            Permohonan diajukan secara resmi oleh Pemerintah Desa atas nama warga yang mengalami krisis air melalui surat pengantar Kepala Desa yang diketahui oleh Camat setempat.
                        </p>
                    </div>
                </div>

                {{-- FAQ Item 2 --}}
                <div class="faq-item">
                    <button
                        type="button"
                        onclick="toggleFaq(this)"
                        class="w-full min-h-[56px] py-5 sm:py-6 text-left flex items-center justify-between gap-6 group cursor-pointer focus:outline-none"
                        aria-expanded="false"
                    >
                        <span class="text-base sm:text-lg font-semibold text-[#101828] group-hover:text-[#00629B] transition-colors">
                            Bagaimana urutan prioritas penyaluran ditentukan?
                        </span>
                        <svg class="faq-icon w-5 h-5 text-[#667085] group-hover:text-[#00629B] transition-transform duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                    <div class="faq-answer hidden pb-6 text-sm sm:text-base text-[#475467] leading-relaxed">
                        <p>
                            Sistem menggunakan metode AHP-SAW untuk menilai tingkat keparahan krisis, kepadatan jiwa terdampak, serta proporsi kelompok rentan (balita, lansia, dan disabilitas) secara objektif.
                        </p>
                    </div>
                </div>

                {{-- FAQ Item 3 --}}
                <div class="faq-item">
                    <button
                        type="button"
                        onclick="toggleFaq(this)"
                        class="w-full min-h-[56px] py-5 sm:py-6 text-left flex items-center justify-between gap-6 group cursor-pointer focus:outline-none"
                        aria-expanded="false"
                    >
                        <span class="text-base sm:text-lg font-semibold text-[#101828] group-hover:text-[#00629B] transition-colors">
                            Apakah ada pungutan biaya pengiriman atau uang bensin armada?
                        </span>
                        <svg class="faq-icon w-5 h-5 text-[#667085] group-hover:text-[#00629B] transition-transform duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                    <div class="faq-answer hidden pb-6 text-sm sm:text-base text-[#475467] leading-relaxed">
                        <p>
                            Tidak ada biaya apa pun. Seluruh pasokan air bersih beserta operasional armada truk tangki BPBD Kabupaten Purbalingga diberikan gratis 100% tanpa pungutan retribusi.
                        </p>
                    </div>
                </div>

                {{-- FAQ Item 4 --}}
                <div class="faq-item">
                    <button
                        type="button"
                        onclick="toggleFaq(this)"
                        class="w-full min-h-[56px] py-5 sm:py-6 text-left flex items-center justify-between gap-6 group cursor-pointer focus:outline-none"
                        aria-expanded="false"
                    >
                        <span class="text-base sm:text-lg font-semibold text-[#101828] group-hover:text-[#00629B] transition-colors">
                            Berapa kapasitas pasokan air yang dibawa setiap armada?
                        </span>
                        <svg class="faq-icon w-5 h-5 text-[#667085] group-hover:text-[#00629B] transition-transform duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                    <div class="faq-answer hidden pb-6 text-sm sm:text-base text-[#475467] leading-relaxed">
                        <p>
                            Setiap armada truk tangki BPBD Purbalingga berkapasitas standar 5.000 liter air bersih siap pakai yang disalurkan langsung ke tandon komunal penampungan warga desa.
                        </p>
                    </div>
                </div>

                {{-- FAQ Item 5 --}}
                <div class="faq-item">
                    <button
                        type="button"
                        onclick="toggleFaq(this)"
                        class="w-full min-h-[56px] py-5 sm:py-6 text-left flex items-center justify-between gap-6 group cursor-pointer focus:outline-none"
                        aria-expanded="false"
                    >
                        <span class="text-base sm:text-lg font-semibold text-[#101828] group-hover:text-[#00629B] transition-colors">
                            Bagaimana cara desa memantau posisi antrean dan jadwal armada?
                        </span>
                        <svg class="faq-icon w-5 h-5 text-[#667085] group-hover:text-[#00629B] transition-transform duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                    <div class="faq-answer hidden pb-6 text-sm sm:text-base text-[#475467] leading-relaxed">
                        <p>
                            Perangkat desa dapat memantau progres status tiket permohonan secara real-time melalui portal LinggaAir, mulai dari tahap verifikasi hingga armada diberangkatkan menuju lokasi.
                        </p>
                    </div>
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
                    <img src="{{ asset('images/logo-lingga-oneline.png') }}" alt="LinggaAir" class="h-8 w-auto object-contain">
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
                <h4 class="font-semibold text-[#101828] uppercase tracking-wider text-[11px]">Layanan</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('cek-kelayakan.index') }}" class="hover:text-[#00629B] transition">Cek Kelayakan</a></li>
                    <li><a href="{{ route('admin.pemetaan.index') }}" class="hover:text-[#00629B] transition">Peta Sebaran GIS</a></li>
                    <li><a href="#program" class="hover:text-[#00629B] transition">Dokumentasi</a></li>
                    <li><a href="#faq" class="hover:text-[#00629B] transition">Panduan SOP</a></li>
                </ul>
            </div>

            {{-- Col 3: Portal --}}
            <div class="space-y-3">
                <h4 class="font-semibold text-[#101828] uppercase tracking-wider text-[11px]">Akses</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('login') }}" class="hover:text-[#00629B] transition">Login Verifikator</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-[#00629B] transition">Login Pengemudi</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-[#00629B] transition">Login Desa</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-[#00629B] transition">Daftar Akun Baru</a></li>
                </ul>
            </div>

            {{-- Col 4: Posko Siaga --}}
            <div class="space-y-3">
                <h4 class="font-semibold text-[#101828] uppercase tracking-wider text-[11px]">Posko Siaga</h4>
                <p class="text-[#667085]">Hotline: (0281) 891-234</p>
                <p class="text-[#667085]">WA: 0812-3456-7890</p>
                <div class="flex items-center gap-2 pt-2">
                    <span class="w-6 h-6 rounded-full bg-[#EBF5FB] border border-[#BAE3F5] flex items-center justify-center text-[10px] text-[#00629B] font-semibold">X</span>
                    <span class="w-6 h-6 rounded-full bg-[#EBF5FB] border border-[#BAE3F5] flex items-center justify-center text-[10px] text-[#00629B] font-semibold">FB</span>
                    <span class="w-6 h-6 rounded-full bg-[#EBF5FB] border border-[#BAE3F5] flex items-center justify-center text-[10px] text-[#00629B] font-semibold">IG</span>
                    <span class="w-6 h-6 rounded-full bg-[#EBF5FB] border border-[#BAE3F5] flex items-center justify-center text-[10px] text-[#00629B] font-semibold">YT</span>
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

@push('scripts')
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""
/>
<style>
    .leaflet-container {
        font-family: inherit;
        z-index: 10;
    }
    @keyframes marquee {
        0% { transform: translateX(0%); }
        100% { transform: translateX(-50%); }
    }
    .animate-marquee {
        display: flex;
        width: max-content;
        animation: marquee 26s linear infinite;
    }
    .animate-marquee:hover {
        animation-play-state: paused;
    }
    .landing-popup .leaflet-popup-content-wrapper {
        border-radius: 12px;
        padding: 0;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        border: 1px solid #EAECF0;
    }
    .landing-popup .leaflet-popup-content {
        margin: 0;
        line-height: 1.4;
    }
</style>
<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""
></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mapEl = document.getElementById('landing-map');
        if (!mapEl || typeof window.L === 'undefined') return;

        const map = L.map('landing-map', {
            center: [-7.355, 109.38],
            zoom: 11,
            zoomControl: true,
            scrollWheelZoom: false,
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map);

        const loadingEl = document.getElementById('landing-map-loading');

        // 1. Memuat Poligon Batas Wilayah Administrasi 239 Desa
        fetch(@json(asset('geojson/purbalingga_desa.geojson')))
            .then(res => res.json())
            .then(geojson => {
                L.geoJSON(geojson, {
                    style: function () {
                        return {
                            color: '#00629B',
                            weight: 1,
                            opacity: 0.35,
                            fillColor: '#00629B',
                            fillOpacity: 0.03,
                        };
                    },
                    onEachFeature: function (feature, layer) {
                        const props = feature.properties || {};
                        const name = props.NAMOBJ || props.nama_desa || 'Desa';
                        const kec = props.WADMKC || props.kecamatan || '';
                        layer.bindTooltip(`${name}${kec ? ', ' + kec : ''}`, {
                            sticky: true,
                            className: 'text-xs font-medium',
                        });
                    }
                }).addTo(map);
            })
            .catch(err => console.warn('GeoJSON boundary load error:', err));

        // 2. Mengambil Data Marker Sebaran Desa dan Status Terkini
        fetch(@json(route('peta.data')))
            .then(res => res.json())
            .then(data => {
                if (loadingEl) loadingEl.classList.add('hidden');

                if (data.summary) {
                    const s = data.summary;
                    const elMerah = document.getElementById('stat-merah');
                    const elKuning = document.getElementById('stat-kuning');
                    const elHijau = document.getElementById('stat-hijau');
                    const elAbu = document.getElementById('stat-abu');
                    if (elMerah) elMerah.textContent = s.merah || 0;
                    if (elKuning) elKuning.textContent = s.kuning || 0;
                    if (elHijau) elHijau.textContent = s.hijau || 0;
                    if (elAbu) elAbu.textContent = s.abu_abu || 0;
                }

                if (Array.isArray(data.markers)) {
                    const markerLayer = L.layerGroup().addTo(map);
                    data.markers.forEach(m => {
                        if (!m.latitude || !m.longitude) return;

                        const warna = m.status_gis ? m.status_gis.kode_warna : '#6B7280';
                        const isUrgent = m.status_gis && (m.status_gis.warna_key === 'merah' || m.status_gis.warna_key === 'kuning');

                        const circle = L.circleMarker([m.latitude, m.longitude], {
                            radius: isUrgent ? 7 : 5,
                            fillColor: warna,
                            color: '#FFFFFF',
                            weight: 1.5,
                            opacity: 1,
                            fillOpacity: 0.9,
                        });

                        const popupHtml = `
                            <div class="p-3.5 space-y-2 text-xs">
                                <div class="border-b border-[#EAECF0] pb-2">
                                    <h4 class="font-semibold text-sm text-[#101828]">${m.nama_desa}</h4>
                                    <p class="text-[11px] text-[#667085]">Kecamatan ${m.kecamatan}</p>
                                    <div class="mt-1.5">
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold" style="background:${warna}18; color:${warna}; border: 1px solid ${warna}40;">
                                            ${m.status_gis ? m.status_gis.status : 'Tanpa Tiket'}
                                        </span>
                                    </div>
                                </div>
                                <div class="space-y-1 text-[#475467]">
                                    <div>Titik Drop: <strong class="text-[#101828]">${m.titik_drop_air || 'Balai Desa'}</strong></div>
                                    <div>Jiwa Rentan: <strong class="text-[#101828]">${(m.demografi && m.demografi.total_jiwa_rentan) ? m.demografi.total_jiwa_rentan : 0} jiwa</strong></div>
                                    <div>Prioritas SPK: <strong class="text-[#101828]">${(m.kelayakan && m.kelayakan.status_label) ? m.kelayakan.status_label : 'Belum Diverifikasi'}</strong></div>
                                </div>
                            </div>
                        `;

                        circle.bindPopup(popupHtml, { className: 'landing-popup' });
                        circle.addTo(markerLayer);
                    });
                }
            })
            .catch(err => {
                console.error('Peta data fetch error:', err);
                if (loadingEl) {
                    loadingEl.innerHTML = '<p class="text-xs text-rose-600 font-medium">Gagal memuat sebaran desa.</p>';
                }
            });

        // 3. Efek Transisi Navbar (Transparan di Awal -> Putih Solid saat Scroll)
        const navbar = document.getElementById('main-navbar');
        const authBtn = document.getElementById('nav-auth-btn');
        const navLinks = document.querySelectorAll('.nav-link');

        function handleNavbarScroll() {
            if (!navbar) return;
            const scrolled = window.scrollY > 40;
            if (scrolled) {
                navbar.classList.remove('bg-transparent', 'border-transparent');
                navbar.classList.add('bg-white/95', 'backdrop-blur-md', 'border-[#EAECF0]', 'shadow-xs');
                if (authBtn) {
                    authBtn.className = 'min-h-[44px] inline-flex items-center justify-center px-5 py-2.5 bg-[#00629B] hover:bg-[#004F7D] text-white text-sm font-semibold rounded-lg shadow-sm transition';
                }
                navLinks.forEach((link, idx) => {
                    if (idx === 0) {
                        link.className = 'nav-link text-[#00629B] font-semibold border-b-2 border-[#00629B] py-1 transition-colors';
                    } else {
                        link.className = 'nav-link text-[#475467] hover:text-[#00629B] transition-colors';
                    }
                });
            } else {
                navbar.classList.remove('bg-white/95', 'backdrop-blur-md', 'border-[#EAECF0]', 'shadow-xs');
                navbar.classList.add('bg-transparent', 'border-transparent');
                if (authBtn) {
                    authBtn.className = 'min-h-[44px] inline-flex items-center justify-center px-4 py-2 text-white/90 hover:text-white text-sm font-semibold transition bg-transparent border-0';
                }
                navLinks.forEach((link, idx) => {
                    if (idx === 0) {
                        link.className = 'nav-link text-white font-semibold border-b-2 border-white py-1 transition-colors';
                    } else {
                        link.className = 'nav-link text-white/90 hover:text-white transition-colors';
                    }
                });
            }
        }

        window.addEventListener('scroll', handleNavbarScroll, { passive: true });
        handleNavbarScroll();
    });

    // Helper Modal Video Dokumentasi
    function openVideoModal() {
        const modal = document.getElementById('video-modal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeVideoModal() {
        const modal = document.getElementById('video-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }

    window.openVideoModal = openVideoModal;
    window.closeVideoModal = closeVideoModal;

    // Helper Toggle FAQ Minimalist Dropdown (+ to x)
    function toggleFaq(btn) {
        const item = btn.closest('.faq-item');
        if (!item) return;
        const answer = item.querySelector('.faq-answer');
        const iconSvg = item.querySelector('.faq-icon');
        const isExpanded = btn.getAttribute('aria-expanded') === 'true';

        if (isExpanded) {
            btn.setAttribute('aria-expanded', 'false');
            if (answer) answer.classList.add('hidden');
            if (iconSvg) {
                iconSvg.classList.remove('rotate-45', 'text-[#00629B]');
                iconSvg.classList.add('text-[#667085]');
            }
        } else {
            btn.setAttribute('aria-expanded', 'true');
            if (answer) answer.classList.remove('hidden');
            if (iconSvg) {
                iconSvg.classList.add('rotate-45', 'text-[#00629B]');
                iconSvg.classList.remove('text-[#667085]');
            }
        }
    }

    window.toggleFaq = toggleFaq;
</script>
@endpush
