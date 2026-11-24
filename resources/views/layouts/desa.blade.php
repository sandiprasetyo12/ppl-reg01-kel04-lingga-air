@php
    $currentUser = auth()->user();
    $desaData = $currentUser?->desa;
    $desaNama = $desaData?->nama_desa ? 'Desa ' . $desaData->nama_desa : ($currentUser?->username ?? 'Perwakilan Desa');
    $initials = Illuminate\Support\Str::upper(Illuminate\Support\Str::substr($desaNama, 0, 2));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda Desa - LinggaAir')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Poppins', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
                        },
                        colors: {
                            idds: {
                                blue: {
                                    DEFAULT: '#00629B',
                                    hover: '#004F7D',
                                    active: '#003B5C',
                                    tint: '#EBF5FB',
                                    stroke: '#BAE3F5',
                                }
                            }
                        }
                    }
                }
            };
        </script>
    @endif

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        *, html, body, input, button, select, textarea, .font-sans {
            font-family: 'Poppins', ui-sans-serif, system-ui, -apple-system, sans-serif !important;
        }
        i.fa-solid, i.fa-regular, i.fa-brands, i.fas, i.far, i.fab, [class*="fa-"], [class^="fa-"] {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "FontAwesome" !important;
            font-style: normal;
        }
        body { background: #F8FAFC; color: #101828; }
        :focus-visible { outline: 2px solid #0066A1; outline-offset: 2px; }
        button, a[href] { cursor: pointer; }

        /* Typography & Utility Styles for Portal Desa */
        h1 { margin: 2px 0 4px; font-size: 1.25rem; font-weight: 700; line-height: 1.3; color: #101828; letter-spacing: -0.02em; }
        .eyebrow { color: #00629B; font-size: .6875rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; }
        .intro { max-width: 800px; margin: 2px 0 0; color: #667085; font-size: .8125rem; line-height: 1.5; }
        .card { border: 1px solid #D0D5DD; border-radius: 12px; background: #fff; box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04); }
        .section-title { margin: 16px 0 10px; padding-top: 14px; border-top: 1px solid #EAECF0; color: #101828; font-size: .875rem; font-weight: 700; }
        .section-copy { margin: 4px 0 14px; color: #667085; font-size: .8125rem; line-height: 1.5; }
        label { display: block; margin-bottom: 5px; color: #344054; font-size: .8125rem; font-weight: 600; }
        .input-wrap { position: relative; }
        .unit { position: absolute; top: 50%; right: 12px; color: #667085; font-size: .75rem; font-weight: 600; transform: translateY(-50%); pointer-events: none; }
        .hint { margin: 4px 0 0; color: #667085; font-size: .72rem; line-height: 1.4; }
        .error { margin: 4px 0 0; color: #B42318; font-size: .75rem; }
        .actions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 16px; }

        select, input[type="text"], input[type="number"], input[type="date"], input[type="file"] {
            width: 100%;
            height: 42px;
            padding: 9px 14px;
            border: 1px solid #D0D5DD;
            border-radius: 8px;
            background: #fff;
            color: #101828;
            font-size: .875rem;
            line-height: 1.5;
            transition: border-color .15s ease, box-shadow .15s ease;
            box-sizing: border-box;
        }
        textarea {
            width: 100%;
            min-height: 90px;
            padding: 12px 14px;
            border: 1px solid #D0D5DD;
            border-radius: 8px;
            background: #fff;
            color: #101828;
            font-size: .875rem;
            line-height: 1.6;
            transition: border-color .15s ease, box-shadow .15s ease;
            box-sizing: border-box;
            resize: vertical;
        }
        select:focus, input:focus, textarea:focus {
            outline: none;
            border-color: #00629B;
            box-shadow: 0 0 0 3px rgba(0, 98, 155, 0.15);
        }
        textarea::placeholder, input::placeholder {
            color: #98A2B3;
            font-size: .875rem;
            line-height: 1.6;
        }
        input:disabled, input[readonly], select:disabled, textarea:disabled {
            border-color: #EAECF0;
            background: #F8FAFC;
            color: #667085;
            cursor: not-allowed;
        }

        .submit, .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 0 14px;
            border-radius: 6px;
            font: inherit;
            font-size: .75rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid #00629B;
            background: #00629B;
            color: #fff;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
            transition: background .15s ease-in-out, border-color .15s ease-in-out;
        }
        .submit:hover, .btn-primary:hover {
            background: #004F7D;
            border-color: #004F7D;
            color: #fff;
        }
        .reset, .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 0 14px;
            border-radius: 6px;
            font: inherit;
            font-size: .75rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid #D0D5DD;
            background: #fff;
            color: #344054;
            transition: background .15s ease-in-out, color .15s ease-in-out;
        }
        .reset:hover, .btn-secondary:hover {
            background: #F2F4F7;
            color: #101828;
        }
        .btn-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 0 14px;
            border-radius: 6px;
            font: inherit;
            font-size: .75rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid #FECDCA;
            background: #FEF3F2;
            color: #B42318;
            transition: background .15s ease-in-out;
        }
        .btn-danger:hover {
            background: #FEE4E2;
        }

        /* Status Badges */
        .status-badge { display: inline-flex; align-items: center; gap: 6px; min-height: 26px; padding: 2px 10px; border: 1px solid transparent; border-radius: 6px; font-size: .75rem; font-weight: 600; }
        .status-aman { border-color: #A6F4C5; background: #ECFDF3; color: #027A48; }
        .status-waspada { border-color: #FEDF89; background: #FFFAEB; color: #B54708; }
        .status-kritis { border-color: #FECDCA; background: #FEF3F2; color: #B42318; }
        .status-draf { border-color: #D0D5DD; background: #F8FAFC; color: #475467; }

        /* Leaflet Container Isolation */
        .leaflet-container { z-index: 1 !important; isolation: isolate; }
        .leaflet-pane { z-index: 2 !important; }
        .leaflet-tile-pane { z-index: 2 !important; }
        .leaflet-overlay-pane { z-index: 3 !important; }
        .leaflet-marker-pane { z-index: 4 !important; }
        .leaflet-tooltip-pane, .leaflet-popup-pane { z-index: 5 !important; }
        .leaflet-top, .leaflet-bottom { z-index: 6 !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-[#F8FAFC] text-[#101828] min-h-screen flex flex-col antialiased font-sans">

    {{-- TOP NAVBAR (NAVIGASI ATAS TANPA SIDEBAR) --}}
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-[#EAECF0] shadow-xs">
        <div class="w-full px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-2">
            
            <!-- Kiri: Brand Logo -->
            <div class="flex items-center shrink-0 min-w-[160px] lg:min-w-[200px]">
                <a href="{{ route('desa.dashboard') }}" class="inline-flex items-center shrink-0" aria-label="Lingga Air Beranda">
                    <img src="{{ asset('images/logo-lingga-oneline.png') }}" alt="Lingga Air" class="h-8 sm:h-9 w-auto object-contain">
                </a>
            </div>

            <!-- Tengah: 5 Menu Navigasi (Center & Font Ramping) -->
            <nav class="hidden md:flex items-center justify-center gap-1 mx-auto" aria-label="Menu navigasi utama">
                <!-- 1. Beranda -->
                <a 
                    href="{{ route('desa.dashboard') }}" 
                    class="whitespace-nowrap shrink-0 px-2.5 py-1.5 rounded-lg text-xs transition flex items-center gap-1.5 {{ request()->routeIs('desa.dashboard') ? 'bg-[#EBF5FB] text-[#00629B] font-semibold border border-[#BAE3F5] shadow-xs' : 'text-[#475467] font-medium hover:text-[#101828] hover:bg-[#F2F4F7]' }}"
                    @if(request()->routeIs('desa.dashboard')) aria-current="page" @endif
                >
                    <svg class="w-3.5 h-3.5 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                    </svg>
                    <span>Beranda</span>
                </a>

                <!-- 2. Cek Kelayakan -->
                <a 
                    href="{{ route('cek-kelayakan.index') }}" 
                    class="whitespace-nowrap shrink-0 px-2.5 py-1.5 rounded-lg text-xs transition flex items-center gap-1.5 {{ request()->routeIs('cek-kelayakan.*') ? 'bg-[#EBF5FB] text-[#00629B] font-semibold border border-[#BAE3F5] shadow-xs' : 'text-[#475467] font-medium hover:text-[#101828] hover:bg-[#F2F4F7]' }}"
                    @if(request()->routeIs('cek-kelayakan.*')) aria-current="page" @endif
                >
                    <svg class="w-3.5 h-3.5 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm3.75-6h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm3.75-6h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                    </svg>
                    <span>Cek Kelayakan</span>
                </a>

                <!-- 3. Pengajuan Bantuan -->
                <a 
                    href="{{ route('pengajuan.create') }}" 
                    class="whitespace-nowrap shrink-0 px-2.5 py-1.5 rounded-lg text-xs transition flex items-center gap-1.5 {{ request()->routeIs('pengajuan.create') ? 'bg-[#EBF5FB] text-[#00629B] font-semibold border border-[#BAE3F5] shadow-xs' : 'text-[#475467] font-medium hover:text-[#101828] hover:bg-[#F2F4F7]' }}"
                    @if(request()->routeIs('pengajuan.create')) aria-current="page" @endif
                >
                    <svg class="w-3.5 h-3.5 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Pengajuan Bantuan</span>
                </a>

                <!-- 4. Riwayat Pengajuan -->
                <a 
                    href="{{ route('pengajuan.index') }}" 
                    class="whitespace-nowrap shrink-0 px-2.5 py-1.5 rounded-lg text-xs transition flex items-center gap-1.5 {{ request()->routeIs('pengajuan.index') || request()->routeIs('pengajuan.show') ? 'bg-[#EBF5FB] text-[#00629B] font-semibold border border-[#BAE3F5] shadow-xs' : 'text-[#475467] font-medium hover:text-[#101828] hover:bg-[#F2F4F7]' }}"
                    @if(request()->routeIs('pengajuan.index') || request()->routeIs('pengajuan.show')) aria-current="page" @endif
                >
                    <svg class="w-3.5 h-3.5 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Riwayat Pengajuan</span>
                </a>

                <!-- 5. Lacak Pengajuan (Warna & font sama, tidak bisa dibuka) -->
                <a 
                    href="javascript:void(0)" 
                    class="whitespace-nowrap shrink-0 px-2.5 py-1.5 rounded-lg text-xs font-medium text-[#475467] hover:text-[#101828] hover:bg-[#F2F4F7] transition flex items-center gap-1.5 cursor-pointer"
                    role="button"
                    title="Lacak Pengajuan"
                >
                    <svg class="w-3.5 h-3.5 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                    </svg>
                    <span>Lacak Pengajuan</span>
                </a>
            </nav>

            <!-- Kanan: Profil & Logout -->
            <div class="flex items-center justify-end shrink-0 min-w-[160px] lg:min-w-[200px] gap-3">
                <div class="relative">
                    <button 
                        type="button" 
                        class="min-h-[44px] flex items-center gap-2.5 p-1 rounded-xl hover:bg-[#F8FAFC] transition text-left" 
                        data-desa-profile-toggle 
                        aria-expanded="false" 
                        aria-haspopup="true"
                    >
                        <span class="w-9 h-9 rounded-full bg-[#E6F2FA] text-[#0066A1] font-bold text-xs flex items-center justify-center shrink-0 border border-[#BAE3F5]">
                            {{ $initials }}
                        </span>
                        <div class="hidden sm:block leading-tight text-left pr-1">
                            <strong class="block text-xs font-bold text-[#101828] max-w-[130px] truncate">{{ $desaNama }}</strong>
                            <small class="text-[10px] text-[#667085]">Perwakilan Desa</small>
                        </div>
                        <svg class="w-3 h-3 text-[#667085] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="absolute top-[calc(100%+8px)] right-0 z-50 w-52 p-2 bg-white border border-[#EAECF0] rounded-2xl shadow-xl hidden" data-desa-profile-menu>
                        <div class="px-3 py-2 border-b border-[#EAECF0] sm:hidden">
                            <p class="text-xs font-bold text-[#101828] truncate">{{ $desaNama }}</p>
                            <p class="text-[10px] text-[#667085]">Perwakilan Desa</p>
                        </div>
                        <a 
                            href="{{ route('desa.profile.edit') }}" 
                            class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-[#344054] hover:bg-[#F2F4F7] hover:text-[#0066A1] rounded-xl transition"
                        >
                            <svg class="w-4 h-4 text-[#667085] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                            <span>Profil Desa</span>
                        </a>
                        <hr class="my-1.5 border-[#EAECF0]">
                        @if ($currentUser)
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button 
                                    type="submit" 
                                    class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-[#B42318] hover:bg-[#FEF3F2] rounded-xl transition text-left cursor-pointer"
                                >
                                    <svg class="w-4 h-4 text-[#B42318] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                                    </svg>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </header>

    {{-- KONTEN UTAMA --}}
    <main class="flex-1 w-full px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">
        @if (session('success'))
            <div role="status" class="p-4 rounded-2xl bg-[#ECFDF3] border border-[#A6F4C5] text-[#027A48] text-xs sm:text-sm font-semibold flex items-center gap-2.5 shadow-xs">
                <i class="fa-solid fa-circle-check text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div role="alert" class="p-4 rounded-2xl bg-[#FEF3F2] border border-[#FECDCA] text-[#B42318] text-xs sm:text-sm font-semibold flex items-center gap-2.5 shadow-xs">
                <i class="fa-solid fa-circle-exclamation text-base"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div role="status" class="p-4 rounded-2xl bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] text-xs sm:text-sm font-semibold flex items-center gap-2.5 shadow-xs">
                <svg class="w-5 h-5 text-[#00629B] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Script Dropdown Profil --}}
    <script>
        document.addEventListener('click', (event) => {
            const toggle = event.target.closest('[data-desa-profile-toggle]');
            const menu = document.querySelector('[data-desa-profile-menu]');
            if (!menu) return;

            if (toggle) {
                const isOpen = !menu.classList.contains('hidden');
                if (isOpen) {
                    menu.classList.add('hidden');
                    toggle.setAttribute('aria-expanded', 'false');
                } else {
                    menu.classList.remove('hidden');
                    toggle.setAttribute('aria-expanded', 'true');
                }
                return;
            }

            if (!event.target.closest('[data-desa-profile-menu]')) {
                menu.classList.add('hidden');
                const btn = document.querySelector('[data-desa-profile-toggle]');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
