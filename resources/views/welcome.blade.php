<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lingga Air - Sistem Distribusi Air Bersih Darurat BPBD Purbalingga</title>
    
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        idds: {
                            blue: {
                                DEFAULT: '#00629B',
                                hover: '#004F7D',
                                dark: '#003B5C',
                                tint: '#EBF5FB',
                                stroke: '#BAE3F5',
                            },
                            neutral: {
                                25: '#FCFDFD',
                                50: '#F8FAFC',
                                100: '#F2F4F7',
                                200: '#E4E7EC',
                                300: '#D0D5DD',
                                500: '#667085',
                                600: '#475467',
                                700: '#344054',
                                900: '#101828',
                            },
                            status: {
                                error: {
                                    text: '#B42318',
                                    bg: '#FEF3F2',
                                    border: '#FECDCA',
                                },
                                success: {
                                    text: '#027A48',
                                    bg: '#ECFDF3',
                                    border: '#A6F4C5',
                                },
                                warning: {
                                    text: '#B54708',
                                    bg: '#FFFAEB',
                                    border: '#FEDF89',
                                },
                            },
                        },
                    },
                    boxShadow: {
                        'xs': '0 1px 2px 0 rgba(16, 24, 40, 0.05)',
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        html {
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F8FAFC;
            color: #101828;
        }
        section, footer {
            scroll-margin-top: 90px;
        }
        .hero-banner {
            background: linear-gradient(135deg, #FFFFFF 0%, #F8FAFC 60%, #EBF5FB 100%);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-[#F8FAFC] text-[#101828]">

    <!-- ==================== TOPBAR NAVIGASI (GAMBAR 3) ==================== -->
    <header class="bg-white/95 backdrop-blur-md sticky top-0 z-50 border-b border-[#EAECF0] shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between gap-4">
            
            <!-- Logo Lingga Air (Besar & Jelas) -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 group py-1">
                <img 
                    src="{{ asset('images/logo-lingga.png') }}" 
                    alt="Lingga Air" 
                    class="h-12 sm:h-16 w-auto object-contain transition-transform group-hover:scale-105"
                    onerror="this.onerror=null; this.src='https://placehold.co/220x70/00629B/FFFFFF?text=Lingga+Air';"
                >
            </a>

            <!-- Menu Navigasi Tengah (Interaktif dengan Penanda Aktif) -->
            <nav id="navbar-menu" class="hidden md:flex items-center gap-8 text-xs font-bold tracking-wider uppercase">
                <a href="#beranda" class="nav-item text-[#00629B] border-b-2 border-[#00629B] pb-1 transition-all duration-200">
                    Home
                </a>
                <a href="#tentang" class="nav-item text-[#475467] hover:text-[#00629B] border-b-2 border-transparent pb-1 transition-all duration-200">
                    Tentang Kami
                </a>
                <a href="#layanan" class="nav-item text-[#475467] hover:text-[#00629B] border-b-2 border-transparent pb-1 transition-all duration-200">
                    Layanan Air
                </a>
                <a href="#galeri" class="nav-item text-[#475467] hover:text-[#00629B] border-b-2 border-transparent pb-1 transition-all duration-200">
                    Armada Tangki
                </a>
                <a href="#kontak" class="nav-item text-[#475467] hover:text-[#00629B] border-b-2 border-transparent pb-1 transition-all duration-200">
                    Kontak BPBD
                </a>
            </nav>

            <!-- Aksi Masuk / Daftar -->
            <div class="flex items-center gap-3">
                @auth
                    @php
                        $dashUrl = match(auth()->user()->role) {
                            'bpbd' => route('verifikasi.index'),
                            'penyalur' => route('penyalur.dashboard'),
                            default => route('desa.dashboard'),
                        };
                    @endphp
                    <a href="{{ $dashUrl }}" class="px-5 py-2.5 rounded-lg bg-[#00629B] hover:bg-[#004F7D] text-white font-bold text-xs uppercase tracking-wider transition shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-gauge-high"></i>
                        <span>Buka Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-lg border border-[#D0D5DD] bg-white text-[#344054] hover:bg-[#F2F4F7] font-bold text-xs uppercase tracking-wider transition shadow-xs">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="hidden sm:inline-flex px-5 py-2.5 rounded-lg bg-[#00629B] hover:bg-[#004F7D] text-white font-bold text-xs uppercase tracking-wider transition shadow-sm">
                        Daftar Akun
                    </a>
                @endauth
            </div>

        </div>
    </header>

    <!-- ==================== HERO BANNER 2 KOLOM (GAMBAR 3) ==================== -->
    <section id="beranda" class="hero-banner relative pt-6 sm:pt-8 lg:pt-10 pb-8 sm:pb-10 lg:pb-12 px-4 sm:px-6 lg:px-8 border-b border-[#EAECF0] overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row items-center gap-6 lg:gap-10">
                
                <!-- Kolom Kiri: Teks & Aksi (Proporsional & Pas) -->
                <div class="lg:w-7/12 space-y-4">
                    
                    <!-- Tag Kategori -->
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/90 backdrop-blur-sm border border-[#BAE3F5] text-[#101828] text-xs font-bold uppercase tracking-wider shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-[#00629B] animate-pulse"></span>
                        <span class="text-[#00629B] font-extrabold">SISTEM INFORMASI</span>
                        <span class="text-slate-300">|</span>
                        <span class="font-semibold text-[#475467]">PENYALURAN AIR BERSIH</span>
                    </div>

                    <!-- Judul Headline -->
                    <h1 class="text-2xl sm:text-3xl lg:text-[40px] font-extrabold leading-[1.18] tracking-tight text-[#101828]">
                        Penyaluran Air Bersih Darurat, <br class="hidden sm:inline">
                        <span class="text-[#00629B]">Cepat &amp; Tepat Sasaran</span>
                    </h1>

                    <!-- Paragraf Konsep Penyaluran Air Bersih -->
                    <p class="text-xs sm:text-sm text-[#344054] leading-relaxed font-normal">
                        Layanan resmi BPBD Kabupaten Purbalingga untuk penanggulangan darurat bencana kekeringan. Menyediakan pasokan air bersih bagi tandon warga desa terdampak, fasilitas kesehatan, sekolah, dan tempat ibadah secara adil serta terverifikasi metode SPK (AHP-SAW).
                    </p>

                    <!-- Tombol Aksi Utama (Tunggal & Fokus - Standar IDDS Primary CTA) -->
                    <div class="pt-0.5">
                        @auth
                            @php
                                $mainDash = match(auth()->user()->role) {
                                    'bpbd' => route('verifikasi.index'),
                                    'penyalur' => route('penyalur.dashboard'),
                                    default => route('desa.dashboard'),
                                };
                            @endphp
                            <a href="{{ $mainDash }}" class="px-7 py-3 rounded-lg bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white font-bold text-xs uppercase tracking-wider shadow-sm shadow-[#00629B]/20 transition-all duration-150 inline-flex items-center gap-2 transform hover:-translate-y-0.5">
                                <i class="fa-solid fa-gauge-high"></i>
                                <span>Buka Dashboard Layanan</span>
                                <i class="fa-solid fa-arrow-right text-[10px] text-white/80"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-7 py-3 rounded-lg bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white font-bold text-xs uppercase tracking-wider shadow-sm shadow-[#00629B]/20 transition-all duration-150 inline-flex items-center gap-2 transform hover:-translate-y-0.5">
                                <i class="fa-solid fa-truck-droplet"></i>
                                <span>Ajukan Bantuan Air Bersih</span>
                                <i class="fa-solid fa-arrow-right text-[10px] text-white/80"></i>
                            </a>
                        @endauth
                    </div>

                    <!-- Alur Proses Bisnis Lingga Air (Sesuai Konsep SPK AHP-SAW di Excel) -->
                    <div class="pt-2.5 pb-0.5 border-t border-[#EAECF0]">
                        <p class="text-[11px] font-bold text-[#101828] uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-diagram-project text-[#00629B]"></i>
                            <span>Alur Distribusi Air Bersih (Sistem SPK Lingga Air):</span>
                        </p>
                        <div class="grid grid-cols-3 gap-2 sm:gap-3 text-[11px]">
                            <!-- Tahap 1: Cek Kelayakan Mandiri (F-02) -->
                            <div class="p-2 sm:p-2.5 rounded-xl bg-white border border-[#E4E7EC] shadow-xs">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span class="w-4 h-4 rounded bg-[#00629B] text-white flex items-center justify-center font-extrabold text-[9px] shrink-0">1</span>
                                    <span class="font-extrabold text-[#101828] text-[11px] sm:text-xs truncate">Cek Kelayakan</span>
                                </div>
                                <span class="text-[9.5px] text-[#475467] block leading-tight">
                                    Simulasi mandiri kedaruratan kekeringan berbasis SPK (AHP-SAW).
                                </span>
                            </div>

                            <!-- Tahap 2: Pengajuan Bantuan Air (F-03) -->
                            <div class="p-2 sm:p-2.5 rounded-xl bg-white border border-[#E4E7EC] shadow-xs">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span class="w-4 h-4 rounded bg-[#00629B] text-white flex items-center justify-center font-extrabold text-[9px] shrink-0">2</span>
                                    <span class="font-extrabold text-[#101828] text-[11px] sm:text-xs truncate">Pengajuan Tiket</span>
                                </div>
                                <span class="text-[9.5px] text-[#475467] block leading-tight">
                                    Permohonan digital satu desa satu tiket aktif terisi otomatis dari hasil uji.
                                </span>
                            </div>

                            <!-- Tahap 3: Verifikasi & Penyaluran Armada (F-04 & F-05) -->
                            <div class="p-2 sm:p-2.5 rounded-xl bg-white border border-[#E4E7EC] shadow-xs">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span class="w-4 h-4 rounded bg-[#00629B] text-white flex items-center justify-center font-extrabold text-[9px] shrink-0">3</span>
                                    <span class="font-extrabold text-[#101828] text-[11px] sm:text-xs truncate">Verifikasi &amp; Kirim</span>
                                </div>
                                <span class="text-[9.5px] text-[#475467] block leading-tight">
                                    Keputusan prioritas BPBD &amp; drop armada tangki 5.000 L (BAST digital).
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Poin Keunggulan Cepat (Jejer 3 Rapi) -->
                    <div class="grid grid-cols-3 gap-2 sm:gap-4 pt-1.5 text-[11px] sm:text-xs font-semibold text-[#344054]">
                        <div class="flex items-center gap-1.5 whitespace-nowrap">
                            <i class="fa-solid fa-circle-check text-[#027A48] text-sm shrink-0"></i>
                            <span>Tanggap Darurat</span>
                        </div>
                        <div class="flex items-center gap-1.5 whitespace-nowrap">
                            <i class="fa-solid fa-circle-check text-[#027A48] text-sm shrink-0"></i>
                            <span>Kapasitas 5.000 L</span>
                        </div>
                        <div class="flex items-center gap-1.5 whitespace-nowrap">
                            <i class="fa-solid fa-circle-check text-[#027A48] text-sm shrink-0"></i>
                            <span>Terverifikasi SPK</span>
                        </div>
                    </div>

                </div>

                <!-- Kolom Kanan: Foto Menyatu Mulus Tanpa Kotak & Proporsional -->
                <div class="lg:w-5/12 w-full relative flex items-center justify-center lg:justify-end">
                    <div class="relative w-full max-w-lg">
                        
                        <!-- Foto Truk Tangki: Fokus ke Atas & Tinggi Pas -->
                        <div class="relative overflow-hidden rounded-2xl shadow-xs">
                            <img 
                                src="{{ asset('images/truck.png') }}" 
                                alt="Armada Tangki Air Bersih BPBD" 
                                class="w-full h-64 sm:h-[310px] lg:h-[345px] object-cover object-top"
                            >

                            <!-- Gradasi Lembut di Sisi Kiri (Melebur Halus ke Arah Teks) -->
                            <div class="absolute inset-y-0 left-0 w-1/3 bg-gradient-to-r from-[#F8FAFC] via-[#F8FAFC]/60 to-transparent pointer-events-none"></div>
                            
                            <!-- Gradasi Lembut di Sisi Bawah -->
                            <div class="absolute inset-x-0 bottom-0 h-10 bg-gradient-to-t from-[#F8FAFC] to-transparent pointer-events-none"></div>
                        </div>

                        <!-- Floating Badge Pojok Kanan Atas -->
                        <div class="absolute top-2.5 right-3 flex items-center gap-1.5">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/95 backdrop-blur-md text-[#101828] text-[10px] font-extrabold shadow-xs border border-[#E4E7EC]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#027A48] animate-pulse"></span>
                                BPBD Purbalingga
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#00629B] text-white text-[10px] font-extrabold shadow-xs">
                                <i class="fa-solid fa-droplet text-[9px]"></i>
                                5.000 L
                            </span>
                        </div>

                        <!-- Label Keterangan Mengambang Halus di Bawah -->
                        <div class="absolute bottom-2.5 right-3 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-xl shadow-xs border border-[#E4E7EC] text-right">
                            <p class="text-[11px] font-bold text-[#101828] leading-tight">Kabupaten Purbalingga</p>
                            <p class="text-[9px] text-[#475467] font-medium leading-tight">Distribusi Air Bersih Darurat</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ==================== SUB-BANNER PITA BIRU (GAMBAR 3 BOTTOM) ==================== -->
    <div class="bg-[#101828] text-white py-4 px-4 sm:px-6 border-y border-[#1D2939]">
        <div class="max-w-7xl mx-auto flex items-center justify-center gap-3 text-center">
            <span class="w-8 h-8 rounded-full bg-[#00629B] flex items-center justify-center text-white text-sm shrink-0">
                <i class="fa-solid fa-hand-holding-droplet"></i>
            </span>
            <span class="text-base sm:text-lg font-bold tracking-wide">
                Air Bersih Untuk Kebutuhan Masyarakat Kabupaten Purbalingga
            </span>
        </div>
    </div>

    <!-- ==================== 4 LAYANAN KEBUTUHAN AIR BERSIH (GAMBAR 4) ==================== -->
    <section id="layanan" class="py-16 sm:py-24 bg-[#101828] text-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-[#BAE3F5]">
                    LAYANAN TERPADU BPBD PURBALINGGA
                </span>
                <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight">
                    Air Bersih Untuk Kebutuhan Anda
                </h2>
                <p class="text-xs sm:text-sm text-slate-300">
                    Tanggap darurat pasokan air bersih siap didistribusikan ke seluruh titik krisis di 18 kecamatan Kabupaten Purbalingga.
                </p>
            </div>

            <!-- Grid 4 Ikon Bulat (Sesuai Gambar 4) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Layanan 1: Armada Tangki -->
                <div class="flex flex-col items-center text-center p-6 rounded-2xl bg-[#003B5C]/60 border border-[#BAE3F5]/20 hover:border-[#00629B] transition duration-200">
                    <div class="w-20 h-20 rounded-full bg-[#00629B] text-white flex items-center justify-center text-3xl shadow-lg mb-5 border-4 border-[#BAE3F5]/40">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <h3 class="font-bold text-base mb-2">Armada Tangki Darurat</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Menyediakan pasokan air bersih kapasitas 5.000 liter untuk kebutuhan darurat desa dan warga terdampak kekeringan.
                    </p>
                </div>

                <!-- Layanan 2: Tandon Komunal -->
                <div class="flex flex-col items-center text-center p-6 rounded-2xl bg-[#003B5C]/60 border border-[#BAE3F5]/20 hover:border-[#00629B] transition duration-200">
                    <div class="w-20 h-20 rounded-full bg-[#00629B] text-white flex items-center justify-center text-3xl shadow-lg mb-5 border-4 border-[#BAE3F5]/40">
                        <i class="fa-solid fa-box-archive"></i>
                    </div>
                    <h3 class="font-bold text-base mb-2">Tandon Air Komunal</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Pengisian berkala tandon komunal di titik strategis RT/RW agar pembagian air ke warga tertib dan merata.
                    </p>
                </div>

                <!-- Layanan 3: Kebutuhan Minum & Sanitasi -->
                <div class="flex flex-col items-center text-center p-6 rounded-2xl bg-[#003B5C]/60 border border-[#BAE3F5]/20 hover:border-[#00629B] transition duration-200">
                    <div class="w-20 h-20 rounded-full bg-[#00629B] text-white flex items-center justify-center text-3xl shadow-lg mb-5 border-4 border-[#BAE3F5]/40">
                        <i class="fa-solid fa-glass-water"></i>
                    </div>
                    <h3 class="font-bold text-base mb-2">Air Bersih Konsumsi</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Pasokan air higienis dan teruji bersumber dari mata air pegunungan untuk kebutuhan minum dan memasak keluarga.
                    </p>
                </div>

                <!-- Layanan 4: Fasilitas Umum & Sosial -->
                <div class="flex flex-col items-center text-center p-6 rounded-2xl bg-[#003B5C]/60 border border-[#BAE3F5]/20 hover:border-[#00629B] transition duration-200">
                    <div class="w-20 h-20 rounded-full bg-[#00629B] text-white flex items-center justify-center text-3xl shadow-lg mb-5 border-4 border-[#BAE3F5]/40">
                        <i class="fa-solid fa-hospital-user"></i>
                    </div>
                    <h3 class="font-bold text-base mb-2">Fasilitas Layanan Publik</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Prioritas suplai air bersih bagi fasilitas kesehatan desa, puskesmas pembantu, sekolah, dan tempat ibadah.
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- ==================== TENTANG KAMI & PROFIL PENYALURAN (GAMBAR 4 BOTTOM) ==================== -->
    <section id="tentang" class="py-16 sm:py-20 bg-[#F8FAFC]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Kolom Kiri: Kartu Promo Air Bersih (Sesuai Gambar 4 Kiri) -->
                <div class="lg:col-span-5">
                    <div class="bg-gradient-to-br from-[#00629B] to-[#101828] rounded-3xl p-8 text-white shadow-xl relative overflow-hidden border border-[#BAE3F5]">
                        <div class="absolute -right-8 -bottom-8 w-44 h-44 rounded-full bg-[#BAE3F5]/20 blur-2xl"></div>
                        
                        <div class="relative z-10 space-y-4">
                            <span class="px-3 py-1 rounded-full bg-[#EBF5FB] text-[#00629B] text-[11px] font-extrabold uppercase tracking-wider">
                                DISTRIBUSI AIR BERSIH
                            </span>
                            <h3 class="text-2xl sm:text-3xl font-extrabold leading-snug">
                                Air Bersih Kebutuhan Warga, Hak Dasar Manusia
                            </h3>
                            <p class="text-xs text-slate-200 leading-relaxed">
                                Komitmen pemenuhan target SDG 10: Mengurangi ketimpangan akses terhadap sumber daya air bersih pada masa darurat kekeringan.
                            </p>
                            
                            <div class="pt-4 border-t border-white/20 flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-slate-300">Standar Pasokan BPBD</p>
                                    <p class="text-lg font-bold text-[#BAE3F5]">5.000 Liter / Tangki</p>
                                </div>
                                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-2xl text-white">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Narasi Penyaluran (Sesuai Gambar 4 Kanan) -->
                <div class="lg:col-span-7 space-y-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-[#00629B] block mb-1">
                            SISTEM TANGGAP DARURAT
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-[#101828] tracking-tight">
                            Penyaluran Air Bersih Tanggap Kekeringan Purbalingga
                        </h2>
                    </div>

                    <p class="text-sm text-[#344054] leading-relaxed">
                        <strong>Lingga Air</strong> adalah sistem informasi terpadu yang dikembangkan untuk mendukung Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Purbalingga dalam mengoordinasikan distribusi bantuan pasokan air bersih darurat ke desa-desa yang dilanda kekeringan.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-4 rounded-xl bg-white border border-[#E4E7EC] shadow-xs space-y-1">
                            <p class="font-bold text-[#101828] flex items-center gap-1.5">
                                <span class="text-[#027A48]">✓</span> Cek Kelayakan Mandiri
                            </p>
                            <p class="text-[#475467]">Aparatur desa dapat menghitung status darurat desanya (Kritis, Waspada, Aman) sebelum pengajuan.</p>
                        </div>

                        <div class="p-4 rounded-xl bg-white border border-[#E4E7EC] shadow-xs space-y-1">
                            <p class="font-bold text-[#101828] flex items-center gap-1.5">
                                <span class="text-[#027A48]">✓</span> Objektif Berbasis SPK
                            </p>
                            <p class="text-[#475467]">Penentuan antrean verifikasi menggunakan algoritma AHP-SAW bebas intervensi.</p>
                        </div>

                        <div class="p-4 rounded-xl bg-white border border-[#E4E7EC] shadow-xs space-y-1">
                            <p class="font-bold text-[#101828] flex items-center gap-1.5">
                                <span class="text-[#027A48]">✓</span> Pengawalan Logistik Armada
                            </p>
                            <p class="text-[#475467]">Tracking alokasi tangki dari verifikasi persetujuan BPBD sampai pengiriman air tiba di desa.</p>
                        </div>

                        <div class="p-4 rounded-xl bg-white border border-[#E4E7EC] shadow-xs space-y-1">
                            <p class="font-bold text-[#101828] flex items-center gap-1.5">
                                <span class="text-[#027A48]">✓</span> Satu Desa Satu Tiket Aktif
                            </p>
                            <p class="text-[#475467]">Menjamin integritas antrean agar seluruh desa di Purbalingga mendapat giliran secara adil.</p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- ==================== GALERI ARMADA PENYALURAN (GAMBAR 5 ATAS) ==================== -->
    <section id="galeri" class="py-16 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                <span class="text-xs font-bold uppercase tracking-widest text-[#00629B]">
                    DOKUMENTASI LAPANGAN
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#101828]">
                    Armada Tangki Penyaluran Air Bersih
                </h2>
                <p class="text-xs text-[#475467]">
                    Aktivitas armada tangki air BPBD saat menyuplai air bersih ke tandon penampungan warga di berbagai desa di Kabupaten Purbalingga.
                </p>
            </div>

            <!-- Kartu Galeri 3 Foto (Armada 1, 2, 3) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                
                <div class="rounded-2xl overflow-hidden border border-[#E4E7EC] bg-[#F8FAFC] shadow-xs hover:shadow-sm transition group">
                    <div class="h-52 sm:h-56 bg-slate-200 overflow-hidden relative">
                        <img src="{{ asset('images/armada1.png') }}" alt="Armada Tangki 1" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <div class="p-4 text-center">
                        <p class="font-bold text-sm text-[#101828]">Armada Tangki Air BPBD</p>
                        <p class="text-xs text-[#475467] mt-0.5">Kapasitas 5.000 Liter / Armada</p>
                    </div>
                </div>

                <div class="rounded-2xl overflow-hidden border border-[#E4E7EC] bg-[#F8FAFC] shadow-xs hover:shadow-sm transition group">
                    <div class="h-52 sm:h-56 bg-slate-200 overflow-hidden relative">
                        <img src="{{ asset('images/armada2.png') }}" alt="Armada Tangki 2" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <div class="p-4 text-center">
                        <p class="font-bold text-sm text-[#101828]">Pengisian Tandon Komunal</p>
                        <p class="text-xs text-[#475467] mt-0.5">Titik Penampungan Bersama Desa</p>
                    </div>
                </div>

                <div class="rounded-2xl overflow-hidden border border-[#E4E7EC] bg-[#F8FAFC] shadow-xs hover:shadow-sm transition group">
                    <div class="h-52 sm:h-56 bg-slate-200 overflow-hidden relative">
                        <img src="{{ asset('images/armada3.png') }}" alt="Armada Tangki 3" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <div class="p-4 text-center">
                        <p class="font-bold text-sm text-[#101828]">Distribusi Langsung ke Warga</p>
                        <p class="text-xs text-[#475467] mt-0.5">Bantuan Wilayah Terdampak Kekeringan</p>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ==================== FOOTER UTAMA (GAMBAR 5 BAWAH) ==================== -->
    <footer id="kontak" class="bg-[#101828] text-white pt-16 pb-8 border-t-4 border-[#00629B]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 max-w-2xl mx-auto">
                
                <h3 class="text-xl sm:text-2xl font-extrabold tracking-wide uppercase text-white">
                    BPBD KABUPATEN PURBALINGGA
                </h3>
                
                <p class="text-xs text-slate-300 leading-relaxed">
                    Jl. Soekarno Hatta, Karangmanyar, Kalimanah, Kabupaten Purbalingga, Jawa Tengah 53371, Indonesia.
                </p>

                <div class="pt-2 text-xs text-slate-300 space-y-1">
                    <p>
                        <strong>Telepon:</strong> (0281) 896455 &nbsp;|&nbsp; 
                        <strong>Layanan Darurat:</strong> 112
                    </p>
                    <p>
                        <strong>Email Resmi:</strong> info@bpbdpurbalingga.com &nbsp;|&nbsp; 
                        <strong>Website:</strong> lingga-air.purbalinggakab.go.id
                    </p>
                </div>

                <!-- Ikon Sosial Media (Facebook & Instagram) -->
                <div class="flex items-center justify-center gap-4 pt-4">
                    <a href="https://www.facebook.com/share/1Lx1Ti3ws2/" target="_blank" rel="noopener noreferrer" aria-label="Facebook BPBD Purbalingga" class="w-9 h-9 rounded-full bg-[#00629B] hover:bg-white hover:text-[#00629B] text-white flex items-center justify-center text-sm transition shadow-xs">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="https://www.instagram.com/bpbdpurbalingga?bqrl=ZnpvNnh2dWlzazVt" target="_blank" rel="noopener noreferrer" aria-label="Instagram BPBD Purbalingga" class="w-9 h-9 rounded-full bg-[#00629B] hover:bg-white hover:text-[#00629B] text-white flex items-center justify-center text-sm transition shadow-xs">
                        <i class="fa-brands fa-instagram text-base"></i>
                    </a>
                </div>

                <!-- Hak Cipta -->
                <div class="pt-8 mt-8 border-t border-slate-800 text-[11px] text-slate-400">
                    <p>Copyright &copy; {{ date('Y') }}. Hak Cipta Dilindungi Undang-Undang.</p>
                    <p class="mt-1 text-slate-500">Lingga Air · Proyek Perangkat Lunak Kelompok 4 (PS1SI-08-REG-01) · INA Digital Design System</p>
                </div>

            </div>
        </div>
    </footer>

    <!-- Script Indikator Navigasi Aktif (ScrollSpy & Click Listener) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const navLinks = document.querySelectorAll('#navbar-menu .nav-item');
            const targetIds = Array.from(navLinks).map(a => a.getAttribute('href')).filter(href => href && href.startsWith('#'));
            const sections = targetIds
                .map(id => document.querySelector(id))
                .filter(Boolean)
                .sort((a, b) => a.offsetTop - b.offsetTop);

            function setActiveLink(activeHref) {
                navLinks.forEach(link => {
                    const isTarget = link.getAttribute('href') === activeHref;
                    if (isTarget) {
                        link.classList.remove('text-[#475467]', 'border-transparent');
                        link.classList.add('text-[#00629B]', 'border-[#00629B]');
                    } else {
                        link.classList.remove('text-[#00629B]', 'border-[#00629B]');
                        link.classList.add('text-[#475467]', 'border-transparent');
                    }
                });
            }

            // Click listener: seketika update garis penanda aktif saat diklik
            navLinks.forEach(link => {
                link.addEventListener('click', function () {
                    const href = this.getAttribute('href');
                    setActiveLink(href);
                });
            });

            // Scroll listener: otomatis deteksi seksi aktif saat scroll
            let isTicking = false;
            window.addEventListener('scroll', function () {
                if (!isTicking) {
                    window.requestAnimationFrame(function () {
                        const scrollPosition = window.scrollY + 160; // offset toleransi topbar
                        let currentHref = '#beranda';

                        for (let i = sections.length - 1; i >= 0; i--) {
                            const section = sections[i];
                            if (section && section.offsetTop <= scrollPosition) {
                                currentHref = '#' + section.id;
                                break;
                            }
                        }

                        // Jika mendekati batas paling bawah halaman, aktifkan kontak
                        if ((window.innerHeight + window.scrollY) >= (document.documentElement.scrollHeight - 60)) {
                            currentHref = '#kontak';
                        }

                        setActiveLink(currentHref);
                        isTicking = false;
                    });
                    isTicking = true;
                }
            }, { passive: true });
        });
    </script>
</body>
</html>
