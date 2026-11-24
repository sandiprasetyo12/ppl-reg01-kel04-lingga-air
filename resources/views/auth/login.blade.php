<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Lingga Air | Penyaluran Air Bersih BPBD Purbalingga</title>
    
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
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F8FAFC;
            color: #101828;
        }
        .hero-login-bg {
            background: linear-gradient(145deg, #101828 0%, #003B5C 60%, #004F7D 100%);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-[#F8FAFC] text-[#101828]">

    <!-- ==================== TOPBAR NAVIGASI (GAMBAR 3) ==================== -->
    <header class="bg-white/95 backdrop-blur-md border-b border-[#EAECF0] sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-12 sm:h-14 flex items-center justify-between gap-4">
            
            <!-- Logo Lingga Air (Besar & Jelas) -->
            <a href="{{ url('/') }}" class="flex items-center gap-2 group py-1">
                <img 
                    src="{{ asset('images/logo-lingga.png') }}" 
                    alt="Lingga Air" 
                    class="h-9 sm:h-11 w-auto object-contain transition-transform group-hover:scale-105"
                    onerror="this.onerror=null; this.src='https://placehold.co/220x70/00629B/FFFFFF?text=Lingga+Air';"
                >
            </a>

            <!-- Navigasi Cepat -->
            <div class="flex items-center gap-3 text-xs font-bold uppercase tracking-wider">
                <a href="{{ url('/') }}" class="text-[#475467] hover:text-[#00629B] transition flex items-center gap-1.5 text-[11px]">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Kembali ke Beranda</span>
                </a>
                <a href="{{ route('register') }}" class="px-3.5 py-1.5 rounded-lg bg-[#00629B] hover:bg-[#004F7D] text-white text-[11px] transition shadow-xs">
                    Daftar Akun
                </a>
            </div>

        </div>
    </header>

    <!-- ==================== KONTEN LOGIN UTAMA (GAMBAR 3, 4, 5) ==================== -->
    <main class="flex-1 flex items-center justify-center py-2 px-4 sm:px-6">
        <div class="max-w-4xl w-full bg-white rounded-2xl border border-[#E4E7EC] shadow-sm overflow-hidden grid grid-cols-1 lg:grid-cols-12 my-auto">
            
            <!-- KOLOM KIRI: KONSEP PENYALURAN AIR BERSIH (GAMBAR 3 & 4) -->
            <div class="hero-login-bg lg:col-span-5 p-4 sm:p-5 text-white flex flex-col justify-center relative">
                <div class="space-y-2.5 relative z-10">
                    
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#EBF5FB] text-[#00629B] text-[9.5px] font-extrabold uppercase tracking-wider shadow-xs">
                        <i class="fa-solid fa-shield-halved text-[#00629B]"></i>
                        <span>PORTAL PENYALURAN AIR BERSIH</span>
                    </div>

                    <h2 class="text-base sm:text-lg font-extrabold leading-snug tracking-tight text-white drop-shadow-xs">
                        Penyaluran Air Bersih Tanggap Kekeringan Purbalingga
                    </h2>

                    <p class="text-[10.5px] text-slate-200 leading-relaxed font-normal">
                        Sistem terintegrasi pendataan aparatur desa, simulasi kelayakan krisis air mandiri, pengajuan bantuan pasokan armada tangki, dan verifikasi SPK AHP-SAW.
                    </p>

                    <!-- 3 Poin Fitur Utama (Sesuai Ikon Gambar 4) -->
                    <div class="space-y-1.5 pt-0.5">
                        <div class="flex items-center gap-2 p-1.5 px-2.5 rounded-lg bg-white/10 backdrop-blur-xs border border-white/15">
                            <div class="w-6 h-6 rounded-full bg-[#00629B] flex items-center justify-center text-white text-[9.5px] shrink-0">
                                <i class="fa-solid fa-truck-droplet"></i>
                            </div>
                            <div class="text-[9.5px]">
                                <p class="font-bold text-white leading-tight">Armada Tangki 5.000 Liter</p>
                                <p class="text-slate-300 text-[8.5px] leading-tight">Siap meluncur ke titik tandon penampungan desa.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 p-1.5 px-2.5 rounded-lg bg-white/10 backdrop-blur-xs border border-white/15">
                            <div class="w-6 h-6 rounded-full bg-[#00629B] flex items-center justify-center text-white text-[9.5px] shrink-0">
                                <i class="fa-solid fa-scale-balanced"></i>
                            </div>
                            <div class="text-[9.5px]">
                                <p class="font-bold text-white leading-tight">Prioritas Objektif SPK AHP-SAW</p>
                                <p class="text-slate-300 text-[8.5px] leading-tight">Transparansi antrean dan pemerataan hak air (SDG 10).</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 p-1.5 px-2.5 rounded-lg bg-white/10 backdrop-blur-xs border border-white/15">
                            <div class="w-6 h-6 rounded-full bg-[#00629B] flex items-center justify-center text-white text-[9.5px] shrink-0">
                                <i class="fa-solid fa-ticket"></i>
                            </div>
                            <div class="text-[9.5px]">
                                <p class="font-bold text-white leading-tight">Pelacakan Status Real-Time</p>
                                <p class="text-slate-300 text-[8.5px] leading-tight">Satu desa satu tiket aktif demi keadilan penyaluran.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- KOLOM KANAN: FORMULIR LOGIN -->
            <div class="lg:col-span-7 p-4 sm:p-5 bg-white flex flex-col justify-center space-y-2.5">
                
                <div>
                    <span class="text-[9.5px] font-bold uppercase tracking-wider text-[#00629B]">SELAMAT DATANG</span>
                    <h1 class="text-base sm:text-lg font-extrabold text-[#101828] tracking-tight mt-0.5">Masuk ke Akun Portal</h1>
                    <p class="text-[10.5px] text-[#475467]">Silakan masuk menggunakan kredensial akun terdaftar Anda.</p>
                </div>

                <!-- Pesan Error -->
                @if ($errors->any())
                    <div role="alert" class="p-2 rounded-lg bg-[#FEF3F2] border border-[#FECDCA] text-[10.5px] text-[#B42318] flex items-start gap-2">
                        <i class="fa-solid fa-circle-exclamation text-[#B42318] text-xs mt-0.5"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                @if (session('success'))
                    <div role="status" class="p-2 rounded-lg bg-[#ECFDF3] border border-[#A6F4C5] text-[10.5px] text-[#027A48] flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-[#027A48] text-xs mt-0.5"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Form Login -->
                <form action="{{ route('login') }}" method="POST" class="space-y-2">
                    @csrf

                    <!-- Input Username -->
                    <div>
                        <label for="username" class="block text-[10px] font-bold text-[#344054] uppercase tracking-wide mb-0.5">
                            Username
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 pointer-events-none text-[#667085]">
                                <i class="fa-solid fa-user text-[10px]"></i>
                            </span>
                            <input
                                id="username"
                                name="username"
                                type="text"
                                value="{{ old('username') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Masukkan username resmi Anda"
                                class="w-full pl-7 pr-3 py-1.5 bg-white border border-[#D0D5DD] rounded-lg text-xs text-[#101828] placeholder-[#667085] focus:outline-none focus:ring-2 focus:ring-[#00629B]/20 focus:border-[#00629B] transition shadow-xs"
                            >
                        </div>
                        @error('username')
                            <p role="alert" class="mt-0.5 text-[10px] text-[#B42318] font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Password -->
                    <div>
                        <div class="flex items-center justify-between mb-0.5">
                            <label for="password" class="block text-[10px] font-bold text-[#344054] uppercase tracking-wide">
                                Kata Sandi
                            </label>
                            <span class="text-[9px] text-[#667085]">Min. 8 karakter</span>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 pointer-events-none text-[#667085]">
                                <i class="fa-solid fa-lock text-[10px]"></i>
                            </span>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full pl-7 pr-8 py-1.5 bg-white border border-[#D0D5DD] rounded-lg text-xs text-[#101828] placeholder-[#667085] focus:outline-none focus:ring-2 focus:ring-[#00629B]/20 focus:border-[#00629B] transition shadow-xs"
                            >
                            <button
                                type="button"
                                onclick="togglePasswordVisibility('password', 'togglePasswordIcon')"
                                class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-[#667085] hover:text-[#00629B] focus:outline-none cursor-pointer transition"
                                title="Lihat kata sandi"
                                aria-label="Lihat atau sembunyikan kata sandi"
                            >
                                <i id="togglePasswordIcon" class="fa-solid fa-eye text-xs"></i>
                            </button>
                        </div>
                        @error('password')
                            <p role="alert" class="mt-0.5 text-[10px] text-[#B42318] font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember me -->
                    <div class="flex items-center justify-between pt-0">
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-3.5 h-3.5 text-[#00629B] rounded border-[#D0D5DD] focus:ring-[#00629B]">
                            <span class="text-[10.5px] text-[#475467]">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Tombol Masuk -->
                    <div class="pt-0.5">
                        <button
                            type="submit"
                            class="w-full py-2.5 px-4 rounded-lg bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white font-bold text-xs uppercase tracking-wider shadow-sm shadow-[#00629B]/20 transition flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <i class="fa-solid fa-arrow-right-to-bracket text-[11px]"></i>
                            <span>Masuk ke Portal Penyaluran</span>
                        </button>
                    </div>

                </form>

                <!-- Instruksi Akun & Pendaftaran -->
                <div class="pt-2 border-t border-[#EAECF0] text-[10.5px] text-[#475467] space-y-0.5">
                    <p class="leading-relaxed">
                        Belum memiliki akun? Silakan 
                        <a href="{{ route('register') }}" class="font-bold text-[#00629B] hover:text-[#004F7D] hover:underline inline-flex items-center gap-0.5">
                            <span>Daftar Akun Baru</span>
                            <i class="fa-solid fa-arrow-right text-[8.5px]"></i>
                        </a> 
                        sebagai <strong>Pemohon Desa</strong> atau <strong>Mitra Penyalur</strong>.
                    </p>
                    <p class="text-[9.5px] text-[#667085] leading-tight">
                        Bagi <strong>Petugas BPBD (Admin)</strong>, silakan masuk langsung menggunakan kredensial resmi yang telah didaftarkan oleh administrator sistem.
                    </p>
                </div>

            </div>

        </div>
    </main>

    <!-- ==================== FOOTER KECIL (GAMBAR 5 BAWAH) ==================== -->
    <footer class="bg-[#101828] text-white py-2 sm:py-2.5 border-t-2 border-[#00629B] text-center text-[#667085]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-1 text-[10px]">
            <p>
                BPBD Kabupaten Purbalingga &middot; Sistem Distribusi Bantuan Air Bersih Darurat
            </p>
            <p class="text-[#667085]">
                &copy; {{ date('Y') }} Lingga Air &middot; INA Digital Design System
            </p>
        </div>
    </footer>

    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                icon.classList.add('text-[#619DC6]');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.remove('text-[#619DC6]');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
