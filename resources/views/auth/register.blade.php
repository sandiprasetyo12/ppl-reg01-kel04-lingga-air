<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun Baru - Lingga Air | Penyaluran Air Bersih BPBD Purbalingga</title>
    
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
                        'la-peach': '#F4BAA3',
                        'la-beige': '#F4F0E2',
                        'la-pastel': '#A6C8E2',
                        'la-blue': '#619DC6',
                        'la-blue-dark': '#4888B5',
                        'la-navy': '#1B3B5A',
                        'la-navy-dark': '#132A40',
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
            background-color: #F4F0E2;
            color: #1B3B5A;
        }
        .hero-register-bg {
            background: linear-gradient(145deg, #1B3B5A 0%, #132A40 60%, #1E466E 100%);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-[#F4F0E2] text-[#1B3B5A]">

    <!-- ==================== TOPBAR NAVIGASI (GAMBAR 3) ==================== -->
    <header class="bg-white/95 backdrop-blur-md border-b border-[#A6C8E2]/60 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-12 sm:h-14 flex items-center justify-between gap-4">
            
            <!-- Logo Lingga Air (Besar & Jelas) -->
            <a href="{{ url('/') }}" class="flex items-center gap-2 group py-1">
                <img 
                    src="{{ asset('images/logo-lingga.png') }}" 
                    alt="Lingga Air" 
                    class="h-9 sm:h-11 w-auto object-contain transition-transform group-hover:scale-105"
                    onerror="this.onerror=null; this.src='https://placehold.co/220x70/619DC6/FFFFFF?text=Lingga+Air';"
                >
            </a>

            <!-- Navigasi Cepat -->
            <div class="flex items-center gap-3 text-xs font-bold uppercase tracking-wider">
                <a href="{{ url('/') }}" class="text-[#1B3B5A] hover:text-[#619DC6] transition flex items-center gap-1.5 text-[11px]">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Kembali ke Beranda</span>
                </a>
                <a href="{{ route('login') }}" class="px-3.5 py-1.5 rounded-lg border border-[#619DC6] text-[#619DC6] hover:bg-[#619DC6] hover:text-white text-[11px] transition shadow-xs">
                    Masuk Akun
                </a>
            </div>

        </div>
    </header>

    <!-- ==================== KONTEN REGISTER UTAMA (GAMBAR 3, 4, 5) ==================== -->
    <main class="flex-1 flex items-center justify-center py-2 px-4 sm:px-6">
        <div class="max-w-4xl w-full bg-white rounded-2xl border border-[#A6C8E2] shadow-lg overflow-hidden grid grid-cols-1 lg:grid-cols-12 my-auto">
            
            <!-- KOLOM KIRI: KONSEP PENYALURAN AIR BERSIH (GAMBAR 3 & 4) -->
            <div class="hero-register-bg lg:col-span-5 p-4 sm:p-5 text-white flex flex-col justify-center relative">
                <div class="space-y-2.5 relative z-10">
                    
                    <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-[#F4BAA3] text-[#1B3B5A] text-[9px] font-extrabold uppercase tracking-wider shadow-xs">
                        <i class="fa-solid fa-id-card-clip text-[#619DC6]"></i>
                        <span>PENDAFTARAN AKUN RESMI</span>
                    </div>

                    <h2 class="text-base sm:text-lg font-extrabold leading-snug tracking-tight text-white drop-shadow-xs">
                        Bergabung Dalam Jaringan Tanggap Darurat Air Bersih
                    </h2>

                    <p class="text-[10.5px] text-slate-200 leading-relaxed font-normal">
                        Daftarkan akun perwakilan desa atau mitra penyalur untuk simulasi kelayakan krisis air, pengajuan armada tangki, dan distribusi bantuan resmi BPBD Purbalingga.
                    </p>

                    <!-- Langkah Pendaftaran -->
                    <div class="space-y-1.5 pt-0.5">
                        <div class="flex items-center gap-2 p-1.5 px-2.5 rounded-lg bg-white/10 backdrop-blur-xs border border-white/15">
                            <div class="w-6 h-6 rounded-full bg-[#F4BAA3] text-[#1B3B5A] flex items-center justify-center font-bold text-[9.5px] shrink-0">
                                1
                            </div>
                            <div class="text-[9.5px]">
                                <p class="font-bold text-white leading-tight">Buat Kredensial Akun</p>
                                <p class="text-slate-300 text-[8.5px] leading-tight">Daftarkan username dan kata sandi aparatur desa.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 p-1.5 px-2.5 rounded-lg bg-white/10 backdrop-blur-xs border border-white/15">
                            <div class="w-6 h-6 rounded-full bg-[#A6C8E2] text-[#1B3B5A] flex items-center justify-center font-bold text-[9.5px] shrink-0">
                                2
                            </div>
                            <div class="text-[9.5px]">
                                <p class="font-bold text-white leading-tight">Pilih Peran &amp; Tautkan Desa</p>
                                <p class="text-slate-300 text-[8.5px] leading-tight">Pilih nama desa dan konfirmasi data kependudukan.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 p-1.5 px-2.5 rounded-lg bg-white/10 backdrop-blur-xs border border-white/15">
                            <div class="w-6 h-6 rounded-full bg-[#619DC6] text-white flex items-center justify-center font-bold text-[9.5px] shrink-0">
                                3
                            </div>
                            <div class="text-[9.5px]">
                                <p class="font-bold text-white leading-tight">Siap Ajukan Bantuan Pasokan Air</p>
                                <p class="text-slate-300 text-[8.5px] leading-tight">Akses Cek Kelayakan Mandiri dan tiket distribusi.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- KOLOM KANAN: FORMULIR REGISTER -->
            <div class="lg:col-span-7 p-4 sm:p-5 bg-white flex flex-col justify-center space-y-2.5">
                
                <div>
                    <span class="text-[9.5px] font-bold uppercase tracking-wider text-[#619DC6]">REGISTRASI PENGGUNA BARU</span>
                    <h1 class="text-base sm:text-lg font-extrabold text-[#1B3B5A] tracking-tight mt-0.5">Daftar Akun Baru</h1>
                    <p class="text-[10.5px] text-slate-500">Akun perwakilan aparatur desa atau mitra penyalur air bersih.</p>
                </div>

                <!-- Pesan Error -->
                @if ($errors->any())
                    <div role="alert" class="p-2 rounded-lg bg-rose-50 border border-rose-200 text-[10.5px] text-rose-700 flex items-start gap-2">
                        <i class="fa-solid fa-circle-exclamation text-rose-500 text-xs mt-0.5"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- Form Register -->
                <form action="{{ route('register') }}" method="POST" class="space-y-2">
                    @csrf

                    <!-- Input Username -->
                    <div>
                        <label for="username" class="block text-[10px] font-bold text-[#1B3B5A] uppercase tracking-wide mb-0.5">
                            Username
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-user text-[10px]"></i>
                            </span>
                            <input
                                id="username"
                                name="username"
                                type="text"
                                value="{{ old('username') }}"
                                maxlength="50"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Contoh: desa_karangreja"
                                class="w-full pl-7 pr-3 py-1.5 bg-[#F4F0E2]/40 border border-[#A6C8E2] rounded-lg text-xs text-[#1B3B5A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#619DC6] focus:border-[#619DC6] transition"
                            >
                        </div>
                        <p class="mt-0.5 text-[9px] text-slate-400">Gunakan nama desa atau identitas aparatur resmi.</p>
                        @error('username')
                            <p role="alert" class="mt-0.5 text-[10px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Password -->
                    <div>
                        <div class="flex items-center justify-between mb-0.5">
                            <label for="password" class="block text-[10px] font-bold text-[#1B3B5A] uppercase tracking-wide">
                                Kata Sandi
                            </label>
                            <span class="text-[9px] text-slate-400">Min. 8 karakter</span>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-lock text-[10px]"></i>
                            </span>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                minlength="8"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••"
                                class="w-full pl-7 pr-8 py-1.5 bg-[#F4F0E2]/40 border border-[#A6C8E2] rounded-lg text-xs text-[#1B3B5A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#619DC6] focus:border-[#619DC6] transition"
                            >
                            <button
                                type="button"
                                onclick="togglePasswordVisibility('password', 'togglePasswordIcon')"
                                class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400 hover:text-[#619DC6] focus:outline-none cursor-pointer transition"
                                title="Lihat kata sandi"
                                aria-label="Lihat atau sembunyikan kata sandi"
                            >
                                <i id="togglePasswordIcon" class="fa-solid fa-eye text-xs"></i>
                            </button>
                        </div>
                        @error('password')
                            <p role="alert" class="mt-0.5 text-[10px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-[10px] font-bold text-[#1B3B5A] uppercase tracking-wide mb-0.5">
                            Ulangi Kata Sandi
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-check-double text-[10px]"></i>
                            </span>
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                minlength="8"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••"
                                class="w-full pl-7 pr-8 py-1.5 bg-[#F4F0E2]/40 border border-[#A6C8E2] rounded-lg text-xs text-[#1B3B5A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#619DC6] focus:border-[#619DC6] transition"
                            >
                            <button
                                type="button"
                                onclick="togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmIcon')"
                                class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400 hover:text-[#619DC6] focus:outline-none cursor-pointer transition"
                                title="Lihat konfirmasi kata sandi"
                                aria-label="Lihat atau sembunyikan konfirmasi kata sandi"
                            >
                                <i id="togglePasswordConfirmIcon" class="fa-solid fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Daftar -->
                    <div class="pt-0.5">
                        <button
                            type="submit"
                            class="w-full py-2 px-4 rounded-xl bg-[#619DC6] hover:bg-[#4888B5] text-white font-bold text-xs uppercase tracking-wider shadow-xs hover:shadow transition flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <i class="fa-solid fa-user-plus text-[11px]"></i>
                            <span>Daftar Akun Baru</span>
                        </button>
                    </div>

                </form>

                <!-- Link Masuk & Info Role Admin -->
                <div class="pt-2 border-t border-slate-100 text-[10.5px] text-slate-600 space-y-0.5">
                    <p class="leading-relaxed">
                        Sudah memiliki akun resmi? Silakan 
                        <a href="{{ route('login') }}" class="font-bold text-[#619DC6] hover:text-[#4888B5] hover:underline inline-flex items-center gap-0.5">
                            <span>Masuk di Sini</span>
                            <i class="fa-solid fa-arrow-right text-[8.5px]"></i>
                        </a>
                    </p>
                    <p class="text-[9.5px] text-slate-500 leading-tight">
                        Bagi <strong>Petugas BPBD (Admin)</strong>, akun dikelola langsung oleh administrator sistem.
                    </p>
                </div>

            </div>

        </div>
    </main>

    <!-- ==================== FOOTER KECIL (GAMBAR 5 BAWAH) ==================== -->
    <footer class="bg-[#1B3B5A] text-white py-1.5 sm:py-2 border-t-2 border-[#619DC6] text-center text-slate-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-1 text-[10px]">
            <p>
                BPBD Kabupaten Purbalingga &middot; Sistem Distribusi Bantuan Air Bersih Darurat
            </p>
            <p class="text-slate-400">
                &copy; {{ date('Y') }} Lingga Air &middot; Proyek Perangkat Lunak Kelompok 4
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
