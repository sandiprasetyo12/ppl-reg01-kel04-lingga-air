@extends('layouts.app')

@section('title', 'LinggaAir - Wireframe Sistem Distribusi Air Bersih BPBD Purbalingga')

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
{{-- 1. NAVBAR (HEADER WIREFRAME) --}}
<header class="w-full bg-white border-b border-neutral-300 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between gap-4">
        {{-- Logo Wireframe --}}
        <a href="{{ url('/') }}" class="flex items-center gap-3 min-h-[44px]">
            <div class="w-9 h-9 rounded-full bg-black flex items-center justify-center text-white" aria-hidden="true">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v18M3 12h18"/>
                </svg>
            </div>
            <span class="font-bold text-lg text-neutral-900 tracking-tight">LinggaAir</span>
        </a>

        {{-- Nav Links --}}
        <nav aria-label="Navigasi Utama" class="hidden md:flex items-center gap-8 text-sm font-medium text-neutral-700">
            <a href="#hero" class="text-black font-semibold hover:text-neutral-900 transition">Beranda</a>
            <a href="{{ route('cek-kelayakan.index') }}" class="hover:text-black transition">Cek Kelayakan</a>
            <a href="{{ route('admin.pemetaan.index') }}" class="hover:text-black transition">Peta GIS</a>
            <a href="#tentang" class="hover:text-black transition">Tentang Layanan</a>
            <a href="#program" class="hover:text-black transition">Program</a>
            <a href="#faq" class="hover:text-black transition">FAQ</a>
        </nav>

        {{-- Auth Action --}}
        <div class="flex items-center gap-3">
            @auth
                <a
                    href="{{ route($dashboardRouteName) }}"
                    class="min-h-[44px] inline-flex items-center justify-center px-5 py-2.5 bg-black hover:bg-neutral-800 text-white text-sm font-medium rounded-md transition"
                >
                    Dashboard
                </a>
            @else
                <a
                    href="{{ route('login') }}"
                    class="min-h-[44px] inline-flex items-center justify-center px-5 py-2.5 bg-black hover:bg-neutral-800 text-white text-sm font-medium rounded-md transition"
                >
                    Masuk Petugas
                </a>
            @endauth
        </div>
    </div>
</header>
@endsection

@section('content')
<main class="w-full bg-white text-neutral-900 flex-1">

    {{-- 2. HERO SECTION (SKEMA FULL FOTO & TEKS DI TENGAH) --}}
    <section id="hero" class="relative w-full min-h-[500px] lg:min-h-[600px] flex items-center justify-center bg-neutral-900 text-white overflow-hidden">
        {{-- Background Wireframe Image Layer with Diagonal X --}}
        <div class="absolute inset-0 w-full h-full bg-neutral-800 opacity-60 pointer-events-none">
            <svg class="w-full h-full text-neutral-700" preserveAspectRatio="none" viewBox="0 0 100 100" fill="none">
                <line x1="0" y1="0" x2="100" y2="100" stroke="currentColor" stroke-width="0.75" vector-effect="non-scaling-stroke"/>
                <line x1="0" y1="100" x2="100" y2="0" stroke="currentColor" stroke-width="0.75" vector-effect="non-scaling-stroke"/>
            </svg>
        </div>

        {{-- Dark Vignette/Overlay for Readability --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/60 to-black/80 pointer-events-none"></div>

        {{-- Centered Content --}}
        <div class="relative z-10 max-w-4xl mx-auto px-6 py-20 lg:py-28 text-center space-y-6">
            {{-- Category Tag --}}
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full border border-white/20 bg-white/10 text-xs font-semibold uppercase tracking-wider text-neutral-200">
                BPBD Kabupaten Purbalingga
            </div>

            {{-- Centered H1 --}}
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-bold text-white tracking-tight leading-[1.2] max-w-3xl mx-auto">
                Penyaluran Bantuan Air Bersih Kabupaten Purbalingga
            </h1>

            {{-- Centered Subtitle --}}
            <p class="text-base sm:text-lg text-neutral-300 leading-relaxed max-w-2xl mx-auto">
                Layanan resmi BPBD Kabupaten Purbalingga untuk memantau permohonan desa, jadwal distribusi truk tangki, dan pemetaan wilayah terdampak kekeringan secara transparan.
            </p>

            {{-- Centered CTA Buttons --}}
            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a
                    href="{{ route('cek-kelayakan.index') }}"
                    class="w-full sm:w-auto min-h-[44px] inline-flex items-center justify-center px-7 py-3.5 bg-white hover:bg-neutral-100 text-black text-sm font-bold rounded-md shadow-sm transition"
                >
                    Cek Kelayakan Desa
                </a>
                <a
                    href="{{ route('admin.pemetaan.index') }}"
                    class="w-full sm:w-auto min-h-[44px] inline-flex items-center justify-center px-7 py-3.5 border border-white/40 hover:border-white text-white text-sm font-semibold rounded-md transition"
                >
                    Buka Peta GIS
                </a>
            </div>
        </div>
    </section>

    {{-- 3. SECTION: WHY OUR CLIENTS TRUST US (COLLAGE + 4 POIN) --}}
    <section id="tentang" class="border-t border-neutral-200 py-20 bg-neutral-50">
        <div class="max-w-7xl mx-auto px-6">
            {{-- Centered Header --}}
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <h2 class="text-3xl font-bold text-neutral-900 tracking-tight">
                    Kenapa Sistem Ini Dibutuhkan
                </h2>
                <p class="text-neutral-600 text-sm sm:text-base leading-relaxed">
                    Menghadirkan kepastian alokasi pasokan air bersih yang adil, transparan, dan terukur bagi masyarakat desa terdampak kemarau.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Left: 3-Photo Collage Wireframe --}}
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="aspect-[4/3] rounded-lg bg-neutral-200 border border-neutral-300 relative overflow-hidden flex items-center justify-center">
                            <svg class="absolute inset-0 w-full h-full text-neutral-300 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100" fill="none">
                                <line x1="0" y1="0" x2="100" y2="100" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                                <line x1="0" y1="100" x2="100" y2="0" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                            </svg>
                            <span class="relative z-10 text-[11px] font-medium text-neutral-500 bg-white/80 px-2 py-0.5 rounded border border-neutral-300">
                                Dokumentasi 1
                            </span>
                        </div>
                        <div class="aspect-[4/3] rounded-lg bg-neutral-200 border border-neutral-300 relative overflow-hidden flex items-center justify-center">
                            <svg class="absolute inset-0 w-full h-full text-neutral-300 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100" fill="none">
                                <line x1="0" y1="0" x2="100" y2="100" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                                <line x1="0" y1="100" x2="100" y2="0" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                            </svg>
                            <span class="relative z-10 text-[11px] font-medium text-neutral-500 bg-white/80 px-2 py-0.5 rounded border border-neutral-300">
                                Dokumentasi 2
                            </span>
                        </div>
                    </div>
                    <div class="aspect-[21/9] rounded-lg bg-neutral-200 border border-neutral-300 relative overflow-hidden flex items-center justify-center">
                        <svg class="absolute inset-0 w-full h-full text-neutral-300 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100" fill="none">
                            <line x1="0" y1="0" x2="100" y2="100" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                            <line x1="0" y1="100" x2="100" y2="0" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                        </svg>
                        <span class="relative z-10 text-[11px] font-medium text-neutral-500 bg-white/80 px-2 py-0.5 rounded border border-neutral-300">
                            Dokumentasi Tandon Warga
                        </span>
                    </div>
                </div>

                {{-- Right: 4 Poin Fitur Layanan --}}
                <div class="space-y-6">
                    <ul class="space-y-5">
                        <li class="flex items-start gap-4">
                            <div class="w-6 h-6 rounded-full bg-neutral-900 text-white flex items-center justify-center flex-shrink-0 mt-0.5" aria-hidden="true">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-neutral-900 text-base">Penilaian Objektif Berbasis AHP-SAW</h3>
                                <p class="text-neutral-600 text-sm mt-0.5">Penentuan urutan prioritas dihitung matematis berdasarkan kepadatan balita, lansia, dan ketiadaan air.</p>
                            </div>
                        </li>

                        <li class="flex items-start gap-4">
                            <div class="w-6 h-6 rounded-full bg-neutral-900 text-white flex items-center justify-center flex-shrink-0 mt-0.5" aria-hidden="true">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-neutral-900 text-base">Kapasitas Armada Standar 5.000 Liter</h3>
                                <p class="text-neutral-600 text-sm mt-0.5">Setiap rit truk tangki membawa 5.000 liter air bersih higienis siap pakai untuk tandon komunal.</p>
                            </div>
                        </li>

                        <li class="flex items-start gap-4">
                            <div class="w-6 h-6 rounded-full bg-neutral-900 text-white flex items-center justify-center flex-shrink-0 mt-0.5" aria-hidden="true">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-neutral-900 text-base">Pemetaan Wilayah Terintegrasi GIS</h3>
                                <p class="text-neutral-600 text-sm mt-0.5">Pantauan spasial titik lokasi kekeringan di 18 kecamatan menggunakan poligon batas GeoJSON resmi.</p>
                            </div>
                        </li>

                        <li class="flex items-start gap-4">
                            <div class="w-6 h-6 rounded-full bg-neutral-900 text-white flex items-center justify-center flex-shrink-0 mt-0.5" aria-hidden="true">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-neutral-900 text-base">Layanan Bebas Biaya Retribusi (Gratis)</h3>
                                <p class="text-neutral-600 text-sm mt-0.5">Seluruh distribusi air bersih dari BPBD Kabupaten Purbalingga tidak dipungut biaya apa pun.</p>
                            </div>
                        </li>
                    </ul>

                    <div class="pt-4">
                        <a
                            href="#program"
                            class="min-h-[44px] inline-flex items-center justify-center px-6 py-2.5 bg-black hover:bg-neutral-800 text-white text-sm font-semibold rounded-md transition"
                        >
                            Pelajari Alur Layanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. SECTION: METRICS & LARGE SHOWCASE (STRATEGY & MAP) --}}
    <section id="gis" class="py-20 border-t border-neutral-200">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
                <h2 class="text-3xl font-bold text-neutral-900 tracking-tight">
                    Cakupan Layanan & Pemetaan Spasial
                </h2>
                <p class="text-neutral-600 text-sm sm:text-base leading-relaxed">
                    Basis data terpadu untuk memantau titik kerentanan air bersih di seluruh wilayah Kabupaten Purbalingga.
                </p>
            </div>

            {{-- 4-Column Metric Counters --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center mb-12">
                <div class="p-6 bg-neutral-50 rounded-lg border border-neutral-200">
                    <div class="text-4xl font-bold text-neutral-900 tracking-tight">18</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-neutral-600 mt-2">Kecamatan Terjangkau</div>
                </div>
                <div class="p-6 bg-neutral-50 rounded-lg border border-neutral-200">
                    <div class="text-4xl font-bold text-neutral-900 tracking-tight">239</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-neutral-600 mt-2">Desa & Kelurahan</div>
                </div>
                <div class="p-6 bg-neutral-50 rounded-lg border border-neutral-200">
                    <div class="text-4xl font-bold text-neutral-900 tracking-tight">5.000 L</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-neutral-600 mt-2">Standar per Tangki</div>
                </div>
                <div class="p-6 bg-neutral-50 rounded-lg border border-neutral-200">
                    <div class="text-4xl font-bold text-neutral-900 tracking-tight">100%</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-neutral-600 mt-2">Penilaian Terbuka</div>
                </div>
            </div>

            {{-- Large Widescreen Wireframe Map Box --}}
            <div class="w-full aspect-[21/9] sm:h-[420px] rounded-lg bg-neutral-200 border border-neutral-300 relative overflow-hidden flex items-center justify-center">
                <svg class="absolute inset-0 w-full h-full text-neutral-300 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100" fill="none">
                    <line x1="0" y1="0" x2="100" y2="100" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                    <line x1="0" y1="100" x2="100" y2="0" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                </svg>
                <div class="relative z-10 text-center space-y-2 bg-white/90 p-4 rounded-md border border-neutral-300 max-w-md mx-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-neutral-700">Wadah Peta Interaktif GIS (Leaflet)</p>
                    <p class="text-xs text-neutral-500">Menampilkan layer GeoJSON 18 kecamatan dan sebaran titik penampungan air desa.</p>
                    <a href="{{ route('admin.pemetaan.index') }}" class="inline-block mt-2 text-xs font-semibold text-black underline">
                        Buka Peta GIS Lengkap &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- 5. SECTION: PARTNERS / MITRA TERKAIT --}}
    <section class="py-14 border-t border-neutral-200 bg-neutral-50">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-500 mb-8">
                Mitra & Lembaga Terkait
            </h3>
            <div class="flex flex-wrap items-center justify-center gap-8 sm:gap-14 opacity-70 grayscale">
                <span class="font-bold text-neutral-600 text-sm">PEMKAB PURBALINGGA</span>
                <span class="font-bold text-neutral-600 text-sm">BPBD JAWA TENGAH</span>
                <span class="font-bold text-neutral-600 text-sm">BMKG STASIUN METEOROLOGI</span>
                <span class="font-bold text-neutral-600 text-sm">PMI PURBALINGGA</span>
                <span class="font-bold text-neutral-600 text-sm">PDAM TIRTA PERWIRA</span>
            </div>
        </div>
    </section>

    {{-- 6. SECTION: LATEST BLOG / DOKUMENTASI PROGRAM --}}
    <section id="program" class="py-20 border-t border-neutral-200">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
                <h2 class="text-3xl font-bold text-neutral-900 tracking-tight">
                    Dokumentasi Penyaluran Terkini
                </h2>
                <p class="text-neutral-600 text-sm sm:text-base leading-relaxed">
                    Laporan kegiatan riil distribusi bantuan air bersih di berbagai desa Kabupaten Purbalingga.
                </p>
            </div>

            {{-- 3-Column Card Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Card 1 --}}
                <div class="border border-neutral-300 rounded-lg overflow-hidden bg-white flex flex-col justify-between">
                    <div>
                        <div class="w-full aspect-[16/10] bg-neutral-200 border-b border-neutral-300 relative overflow-hidden flex items-center justify-center">
                            <svg class="absolute inset-0 w-full h-full text-neutral-300 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100" fill="none">
                                <line x1="0" y1="0" x2="100" y2="100" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                                <line x1="0" y1="100" x2="100" y2="0" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                            </svg>
                            <span class="relative z-10 text-[11px] text-neutral-500 font-medium bg-white/80 px-2 py-0.5 rounded">Foto Tangki Serang</span>
                        </div>
                        <div class="p-6 space-y-2">
                            <h3 class="font-bold text-lg text-neutral-900">Distribusi Tangki Air Desa Serang</h3>
                            <p class="text-xs text-neutral-600 leading-relaxed">
                                Penyaluran 15.000 liter air bersih untuk 240 Kepala Keluarga di Dusun 3 yang terdampak penurunan debit mata air.
                            </p>
                        </div>
                    </div>
                    <div class="px-6 pb-6">
                        <a href="#kontak" class="text-xs font-semibold text-black underline">Selengkapnya &rarr;</a>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div class="border border-neutral-300 rounded-lg overflow-hidden bg-white flex flex-col justify-between">
                    <div>
                        <div class="w-full aspect-[16/10] bg-neutral-200 border-b border-neutral-300 relative overflow-hidden flex items-center justify-center">
                            <svg class="absolute inset-0 w-full h-full text-neutral-300 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100" fill="none">
                                <line x1="0" y1="0" x2="100" y2="100" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                                <line x1="0" y1="100" x2="100" y2="0" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                            </svg>
                            <span class="relative z-10 text-[11px] text-neutral-500 font-medium bg-white/80 px-2 py-0.5 rounded">Foto Tandon Kertanegara</span>
                        </div>
                        <div class="p-6 space-y-2">
                            <h3 class="font-bold text-lg text-neutral-900">Pasokan Tandon Komunal Kertanegara</h3>
                            <p class="text-xs text-neutral-600 leading-relaxed">
                                Pengisian 2 tangki armada ke tandon penampungan umum guna memenuhi kebutuhan sanitasi dan konsumsi warga.
                            </p>
                        </div>
                    </div>
                    <div class="px-6 pb-6">
                        <a href="#kontak" class="text-xs font-semibold text-black underline">Selengkapnya &rarr;</a>
                    </div>
                </div>

                {{-- Card 3 --}}
                <div class="border border-neutral-300 rounded-lg overflow-hidden bg-white flex flex-col justify-between">
                    <div>
                        <div class="w-full aspect-[16/10] bg-neutral-200 border-b border-neutral-300 relative overflow-hidden flex items-center justify-center">
                            <svg class="absolute inset-0 w-full h-full text-neutral-300 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100" fill="none">
                                <line x1="0" y1="0" x2="100" y2="100" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                                <line x1="0" y1="100" x2="100" y2="0" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                            </svg>
                            <span class="relative z-10 text-[11px] text-neutral-500 font-medium bg-white/80 px-2 py-0.5 rounded">Foto Ponpes Bobotsari</span>
                        </div>
                        <div class="p-6 space-y-2">
                            <h3 class="font-bold text-lg text-neutral-900">Bantuan Air Bersih Fasilitas Bobotsari</h3>
                            <p class="text-xs text-neutral-600 leading-relaxed">
                                Respon tanggap darurat pasokan air bersih bagi fasilitas asrama pondok pesantren dan layanan masyarakat sekitar.
                            </p>
                        </div>
                    </div>
                    <div class="px-6 pb-6">
                        <a href="#kontak" class="text-xs font-semibold text-black underline">Selengkapnya &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <a
                    href="#program"
                    class="min-h-[44px] inline-flex items-center justify-center px-6 py-2.5 bg-black hover:bg-neutral-800 text-white text-sm font-semibold rounded-md transition"
                >
                    Lihat Semua Dokumentasi
                </a>
            </div>
        </div>
    </section>

    {{-- 7. SECTION: OUR HAPPY CLIENTS (ASPIRASI & LAPORAN DESA) --}}
    <section class="py-20 border-t border-neutral-200 bg-neutral-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
                <h2 class="text-3xl font-bold text-neutral-900 tracking-tight">
                    Aspirasi & Laporan Penerima Manfaat
                </h2>
                <p class="text-neutral-600 text-sm sm:text-base leading-relaxed">
                    Umpan balik langsung dari perwakilan pemerintah desa mengenai penyaluran bantuan air bersih.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                {{-- Testimonial Card 1 --}}
                <div class="bg-white border border-neutral-300 rounded-lg p-8 shadow-xs space-y-6">
                    <p class="text-neutral-700 text-sm leading-relaxed italic">
                        "Dengan sistem penilaian objektif ini, desa kami yang posisinya di perbukitan tinggi bisa mendapatkan alokasi armada tangki secara pasti tanpa harus bolak-balik mengajukan proposal manual."
                    </p>
                    <div class="flex items-center gap-3 pt-2 border-t border-neutral-100">
                        <div class="w-10 h-10 rounded-full bg-neutral-300 flex items-center justify-center text-xs font-bold text-neutral-700">
                            ST
                        </div>
                        <div>
                            <div class="text-sm font-bold text-neutral-900">Sutrisno</div>
                            <div class="text-xs text-neutral-500">Kepala Desa Serang, Karangreja</div>
                        </div>
                    </div>
                </div>

                {{-- Testimonial Card 2 --}}
                <div class="bg-white border border-neutral-300 rounded-lg p-8 shadow-xs space-y-6">
                    <p class="text-neutral-700 text-sm leading-relaxed italic">
                        "Penyaluran air tepat waktu dan koordinasi pengemudi tangki ke tandon komunal RT sangat jelas. Warga dapat memantau kapan armada tiba melalui pengumuman perangkat desa."
                    </p>
                    <div class="flex items-center gap-3 pt-2 border-t border-neutral-100">
                        <div class="w-10 h-10 rounded-full bg-neutral-300 flex items-center justify-center text-xs font-bold text-neutral-700">
                            WA
                        </div>
                        <div>
                            <div class="text-sm font-bold text-neutral-900">Wahyudi Ansori</div>
                            <div class="text-xs text-neutral-500">Perangkat Desa Kertanegara</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 8. SECTION: FREQUENTLY ASKED QUESTIONS (FAQ + WIREFRAME IMAGE) --}}
    <section id="faq" class="py-20 border-t border-neutral-200">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
                {{-- Left: Wireframe Image Box --}}
                <div class="lg:col-span-5 aspect-square rounded-lg bg-neutral-200 border border-neutral-300 relative overflow-hidden flex items-center justify-center">
                    <svg class="absolute inset-0 w-full h-full text-neutral-300 pointer-events-none" preserveAspectRatio="none" viewBox="0 0 100 100" fill="none">
                        <line x1="0" y1="0" x2="100" y2="100" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                        <line x1="0" y1="100" x2="100" y2="0" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"/>
                    </svg>
                    <span class="relative z-10 text-xs font-medium text-neutral-500 bg-white/80 px-3 py-1 rounded border border-neutral-300">
                        Panduan Syarat & Prosedur
                    </span>
                </div>

                {{-- Right: Accordion FAQ Items --}}
                <div class="lg:col-span-7 space-y-6">
                    <div>
                        <h2 class="text-3xl font-bold text-neutral-900 tracking-tight">
                            Pertanyaan yang Sering Diajukan
                        </h2>
                        <p class="text-neutral-600 text-sm mt-2">
                            Informasi mengenai tata cara pengajuan dan mekanisme penyaluran air bersih BPBD Purbalingga.
                        </p>
                    </div>

                    <div class="space-y-3 pt-2">
                        <div class="border border-neutral-300 rounded-lg p-5 bg-white">
                            <h3 class="font-bold text-neutral-900 text-sm">Siapa yang berhak mengajukan bantuan air bersih?</h3>
                            <p class="text-xs text-neutral-600 mt-2 leading-relaxed">
                                Permohonan diajukan resmi oleh Pemerintah Desa atas nama warga yang mengalami krisis air melalui surat permohonan Kepala Desa yang diketahui Camat setempat.
                            </p>
                        </div>

                        <div class="border border-neutral-300 rounded-lg p-5 bg-white">
                            <h3 class="font-bold text-neutral-900 text-sm">Bagaimana urutan prioritas ditentukan?</h3>
                            <p class="text-xs text-neutral-600 mt-2 leading-relaxed">
                                Sistem menggunakan metode AHP-SAW untuk menilai tingkat keparahan krisis, kepadatan jiwa terdampak, serta proporsi kelompok rentan (balita, lansia, dan disabilitas).
                            </p>
                        </div>

                        <div class="border border-neutral-300 rounded-lg p-5 bg-white">
                            <h3 class="font-bold text-neutral-900 text-sm">Apakah ada biaya pengiriman atau uang bensin?</h3>
                            <p class="text-xs text-neutral-600 mt-2 leading-relaxed">
                                Tidak ada. Seluruh bantuan air bersih beserta operasional armada truk tangki BPBD Kabupaten Purbalingga diberikan gratis 100% tanpa pungutan apa pun.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 9. SECTION: CALL TO ACTION BANNER (NEED MORE INFORMATION?) --}}
    <section id="kontak" class="py-16 bg-neutral-50 border-t border-neutral-200">
        <div class="max-w-7xl mx-auto px-6">
            <div class="bg-neutral-900 text-white rounded-xl p-8 sm:p-12 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-2 max-w-xl text-center md:text-left">
                    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight">
                        Desa Anda Membutuhkan Bantuan Air Segera?
                    </h2>
                    <p class="text-neutral-300 text-sm leading-relaxed">
                        Hubungi posko siaga BPBD Kabupaten Purbalingga atau daftarkan permohonan melalui perwakilan perangkat desa Anda.
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <a
                        href="tel:0281891234"
                        class="min-h-[44px] inline-flex items-center justify-center px-6 py-3.5 bg-white hover:bg-neutral-100 text-black text-sm font-bold rounded-md transition"
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
{{-- 10. FOOTER WIREFRAME --}}
<footer class="w-full bg-white border-t border-neutral-300 text-neutral-600 text-xs py-14">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-12">
            {{-- Col 1: Brand Info --}}
            <div class="col-span-2 space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-black flex items-center justify-center text-white" aria-hidden="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v18M3 12h18"/></svg>
                    </div>
                    <span class="font-bold text-base text-neutral-900">LinggaAir</span>
                </div>
                <p class="text-neutral-600 leading-relaxed max-w-sm">
                    Sistem Informasi Distribusi Air Bersih dan Pemetaan GIS Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Purbalingga.
                </p>
                <p class="text-neutral-500">
                    Jl. Mayjen Sungkono No. 12, Selabaya, Kalimanah, Purbalingga 53371.
                </p>
            </div>

            {{-- Col 2: Layanan --}}
            <div class="space-y-3">
                <h4 class="font-bold text-neutral-900 uppercase tracking-wider text-[11px]">Layanan</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('cek-kelayakan.index') }}" class="hover:text-black transition">Cek Kelayakan</a></li>
                    <li><a href="{{ route('admin.pemetaan.index') }}" class="hover:text-black transition">Peta Sebaran GIS</a></li>
                    <li><a href="#program" class="hover:text-black transition">Dokumentasi</a></li>
                    <li><a href="#faq" class="hover:text-black transition">Panduan SOP</a></li>
                </ul>
            </div>

            {{-- Col 3: Portal --}}
            <div class="space-y-3">
                <h4 class="font-bold text-neutral-900 uppercase tracking-wider text-[11px]">Akses</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('login') }}" class="hover:text-black transition">Login Verifikator</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-black transition">Login Pengemudi</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-black transition">Login Desa</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-black transition">Daftar Akun Baru</a></li>
                </ul>
            </div>

            {{-- Col 4: Sosial & Posko --}}
            <div class="space-y-3">
                <h4 class="font-bold text-neutral-900 uppercase tracking-wider text-[11px]">Posko Siaga</h4>
                <p class="text-neutral-500">Hotline: (0281) 891-234</p>
                <p class="text-neutral-500">WA: 0812-3456-7890</p>
                <div class="flex items-center gap-2 pt-2">
                    <span class="w-6 h-6 rounded-full bg-neutral-200 border border-neutral-300 flex items-center justify-center text-[10px] text-neutral-600 font-bold">X</span>
                    <span class="w-6 h-6 rounded-full bg-neutral-200 border border-neutral-300 flex items-center justify-center text-[10px] text-neutral-600 font-bold">FB</span>
                    <span class="w-6 h-6 rounded-full bg-neutral-200 border border-neutral-300 flex items-center justify-center text-[10px] text-neutral-600 font-bold">IG</span>
                    <span class="w-6 h-6 rounded-full bg-neutral-200 border border-neutral-300 flex items-center justify-center text-[10px] text-neutral-600 font-bold">YT</span>
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-neutral-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-neutral-500">
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
