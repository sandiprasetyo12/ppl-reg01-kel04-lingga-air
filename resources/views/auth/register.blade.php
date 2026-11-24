<!DOCTYPE html>
<html lang="id" class="font-sans">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun Baru - LinggaAir</title>
    
    <!-- Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'ui-sans-serif', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        *, html, body, input, button, select, textarea {
            font-family: 'Poppins', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        }

        body {
            background-color: #FFFFFF;
            color: #101828;
            margin: 0;
            padding: 0;
        }

        /* Disable native Edge/IE password reveal button so it doesn't overlap or clip */
        input::-ms-reveal,
        input::-ms-clear {
            display: none !important;
        }

        /* Autofill style to keep background clean white */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0px 1000px #ffffff inset;
            -webkit-text-fill-color: #101828;
        }
    </style>
</head>
<body class="font-sans min-h-screen flex flex-col bg-[#F8FAFC] text-[#101828] antialiased">

    {{-- Topbar Header Konsisten (Pojok Kiri Atas) --}}
    <div class="w-full border-b border-[#EAECF0] bg-white py-3.5 px-6 sm:px-10 flex items-center justify-between shrink-0">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#475467] hover:text-[#00629B] transition py-1 px-2.5 rounded-lg hover:bg-[#F2F4F7]">
            <svg class="w-3.5 h-3.5 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            <span>Kembali ke Beranda</span>
        </a>
    </div>

    {{-- Main content centered --}}
    <div class="flex-1 flex flex-col justify-center items-center px-4 py-10">
        <div class="w-full max-w-[420px] mx-auto">

            {{-- Card putih --}}
            <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-sm p-8 space-y-6">
        
        {{-- Header Teks Selaras dengan Onboarding & Login --}}
        <div class="text-center space-y-1.5">
            <h1 class="text-2xl sm:text-3xl font-semibold text-[#101828] tracking-tight leading-tight">
                Daftar Akun Baru
            </h1>
            <p class="text-xs sm:text-sm text-[#475467] leading-relaxed font-normal">
                Isi form berikut untuk membuat akun perwakilan desa atau mitra.
            </p>
        </div>

        {{-- Pesan Status / Error Validasi Laravel --}}
        @if ($errors->any())
            <div role="alert" class="p-3 rounded-lg bg-[#fff0f0] border border-[#ffd2d2] text-xs text-[#ce1f31]">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Form Register --}}
        <form action="{{ route('register') }}" method="POST" id="registerForm" class="space-y-4">
            @csrf

            {{-- Input Username --}}
            <div class="space-y-1.5 text-left">
                <label for="username" class="block text-xs font-semibold text-[#344054]">
                    Username
                </label>
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
                    class="w-full h-11 px-3.5 bg-white border border-[#D0D5DD] rounded-lg text-sm text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:border-[#00629B] focus:ring-2 focus:ring-[#00629B]/20 transition shadow-xs"
                >
            </div>

            {{-- Input Sandi --}}
            <div class="space-y-1.5 text-left">
                <label for="password" class="block text-xs font-semibold text-[#344054]">
                    Sandi
                </label>
                <div class="relative">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        minlength="8"
                        required
                        autocomplete="new-password"
                        placeholder="Minimal 8 karakter"
                        class="w-full h-11 pl-3.5 pr-12 bg-white border border-[#D0D5DD] rounded-lg text-sm text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:border-[#00629B] focus:ring-2 focus:ring-[#00629B]/20 transition shadow-xs"
                    >
                    <button
                        type="button"
                        onclick="togglePasswordVisibility('password', 'togglePasswordIcon')"
                        class="absolute inset-y-0 right-0 w-11 h-11 flex items-center justify-center text-[#667085] hover:text-[#101828] focus:outline-none cursor-pointer transition"
                        title="Tampilkan atau sembunyikan kata sandi"
                        aria-label="Tampilkan atau sembunyikan kata sandi"
                    >
                        <svg id="togglePasswordIcon" class="w-5 h-5 text-[#667085] hover:text-[#101828] transition shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                            <line x1="2" y1="2" x2="22" y2="22" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Input Konfirmasi Sandi --}}
            <div class="space-y-1.5 text-left">
                <label for="password_confirmation" class="block text-xs font-semibold text-[#344054]">
                    Ulangi Sandi
                </label>
                <div class="relative">
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        minlength="8"
                        required
                        autocomplete="new-password"
                        placeholder="Ulangi kata sandi"
                        class="w-full h-11 pl-3.5 pr-12 bg-white border border-[#D0D5DD] rounded-lg text-sm text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:border-[#00629B] focus:ring-2 focus:ring-[#00629B]/20 transition shadow-xs"
                    >
                    <button
                        type="button"
                        onclick="togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmIcon')"
                        class="absolute inset-y-0 right-0 w-11 h-11 flex items-center justify-center text-[#667085] hover:text-[#101828] focus:outline-none cursor-pointer transition"
                        title="Tampilkan atau sembunyikan konfirmasi sandi"
                        aria-label="Tampilkan atau sembunyikan konfirmasi sandi"
                    >
                        <svg id="togglePasswordConfirmIcon" class="w-5 h-5 text-[#667085] hover:text-[#101828] transition shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                            <line x1="2" y1="2" x2="22" y2="22" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Tombol Register --}}
            <div class="pt-1">
                <button
                    type="submit"
                    class="w-full h-11 rounded-lg bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white font-medium text-sm flex items-center justify-center cursor-pointer transition shadow-xs"
                >
                    Register
                </button>
            </div>

            {{-- Teks Masuk Akun di Bawah Tombol --}}
            <div class="text-center text-xs sm:text-sm text-[#475467] pt-2">
                <span>Sudah memiliki akun?</span>
                <a href="{{ route('login') }}" class="font-semibold text-[#00629B] hover:text-[#004F7D] hover:underline ml-1">
                    Log In
                </a>
            </div>
        </form>

    </div>{{-- end card --}}
        </div>{{-- end max-w --}}
    </div>{{-- end flex-1 --}}

    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const iconSvg = document.getElementById(iconId);
            if (!input || !iconSvg) return;
            
            if (input.type === 'password') {
                input.type = 'text';
                iconSvg.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                    <circle cx="12" cy="12" r="3"/>
                `;
            } else {
                input.type = 'password';
                iconSvg.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                    <line x1="2" y1="2" x2="22" y2="22" stroke-linecap="round" stroke-linejoin="round"/>
                `;
            }
        }
    </script>
</body>
</html>
