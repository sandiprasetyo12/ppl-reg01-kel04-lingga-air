@hasSection('header')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="font-sans">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'LinggaAir'))</title>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = { 
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Poppins', 'ui-sans-serif', 'sans-serif'],
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

        html, body, input, button, select, textarea {
            font-family: 'Poppins', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* Prevent FontAwesome icons from being overridden by generic font families */
        i.fa-solid, i.fa-regular, i.fa-brands, i.fas, i.far, i.fab, [class*="fa-"], [class^="fa-"] {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "FontAwesome" !important;
            font-style: normal;
        }

        body {
            background-color: #F8FAFC;
            color: #101828;
        }
        :focus-visible {
            outline: 2px solid #00629B;
            outline-offset: 2px;
        }
        a[href], button, select, summary, label[for], input[type="checkbox"], input[type="radio"] {
            cursor: pointer;
        }
        button:disabled, input:disabled, select:disabled {
            cursor: not-allowed;
        }
    </style>
    @include('layouts.sidebar-styles')
    @stack('styles')
</head>
<body class="bg-[#F8FAFC] text-[#101828] antialiased min-h-screen flex flex-col font-sans">
    @yield('header')

    <main class="flex-1 flex flex-col">
        @if (session('success'))
            <div class="max-w-5xl w-full mx-auto px-4 sm:px-6 pt-4">
                <div role="status" class="p-3.5 rounded-lg bg-[#ECFDF3] border border-[#A6F4C5] text-[#027A48] text-sm">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-5xl w-full mx-auto px-4 sm:px-6 pt-4">
                <div role="alert" class="p-3.5 rounded-lg bg-[#FEF3F2] border border-[#FECDCA] text-[#B42318] text-sm">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    @yield('footer')
    @stack('scripts')
</body>
</html>
@else
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'LinggaAir - Sistem Penyaluran Bantuan Air Bersih')</title>

    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Tailwind CSS with Poppins & Brand Palette -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'ui-sans-serif', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#EBF5FB',
                            100: '#BAE3F5',
                            200: '#BAE3F5',
                            600: '#004F7D',
                            700: '#00629B',
                            800: '#003B5C',
                            900: '#101828',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            color-scheme: light;
            font-size: 15px;
            font-family: Poppins, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #101828;
            background: #F8FAFC;
        }
        * { box-sizing: border-box; }
        body { margin: 0; overflow-x: hidden; font-family: Poppins, sans-serif; background: #F8FAFC; color: #101828; }
        
        /* Topbar Style */
        .topbar { background: #00629B; color: #fff; border-bottom: 1px solid #004F7D; }
        .topbar-inner { position: relative; display: flex; align-items: center; justify-content: space-between; height: 76px; padding: 0 26px; }
        .brand { display: flex; max-width: 240px; min-width: 0; align-items: center; gap: 9px; font-size: 1.08rem; font-weight: 750; letter-spacing: -.03em; line-height: 1.15; text-decoration: none; color: inherit; }
        .brand-mark { display: grid; flex: 0 0 36px; place-items: center; width: 36px; height: 36px; border: 1px solid rgb(255 255 255 / 28%); border-radius: 10px; background: rgb(255 255 255 / 12%); font-size: 1rem; }
        .brand > span:last-child { min-width: 0; }
        .brand small { display: block; max-width: 170px; margin-top: 4px; font-size: .5rem; font-weight: 600; letter-spacing: .025em; line-height: 1.2; overflow-wrap: anywhere; opacity: .9; }
        .brand-wrap { display: flex; align-items: center; gap: 0; min-width: 0; }
        .topbar-label { position: static; margin-right: auto; display: flex; align-items: center; min-height: 24px; padding-left: 0; color: #BAE3F5; font-size: .9rem; line-height: 1.4; white-space: nowrap; }
        .account { display: flex; align-items: center; gap: 11px; margin-left: 20px; color: #fff; text-align: right; }
        .account-copy { font-size: .86rem; font-weight: 650; line-height: 1.3; white-space: nowrap; }
        .account-copy small { display: block; margin-top: 2px; color: #BAE3F5; font-size: .74rem; font-weight: 400; }
        .account-avatar { display: grid; flex: 0 0 40px; place-items: center; width: 40px; height: 40px; border: 1px solid rgb(255 255 255 / 48%); border-radius: 50%; background: rgb(255 255 255 / 12%); font-size: 1.1rem; }

        /* Frame & Sidebar Layout */
        .app-frame { display: grid; grid-template-columns: minmax(0, 240px) minmax(0, 1fr); min-width: 0; min-height: calc(100vh - 76px); }
        .sidebar { display: flex; min-width: 0; flex-direction: column; padding: 24px 14px 18px; border-right: 1px solid #EAECF0; background: #fff; }
        .sidebar-label { margin: 0 0 14px 12px; color: #667085; font-size: .76rem; font-weight: 750; letter-spacing: .07em; text-transform: uppercase; }
        .side-nav { display: grid; gap: 5px; }
        .side-link { display: flex; min-width: 0; align-items: center; gap: 10px; min-height: 46px; padding: 0 10px; border-radius: 9px; color: #475467; font-size: .88rem; font-weight: 500; text-decoration: none; overflow-wrap: anywhere; transition: background .15s ease, color .15s ease; }
        .side-link:hover:not(.active):not(.disabled) { background: #EBF5FB; color: #00629B; }
        .side-link.active { background: #00629B; color: #fff; font-weight: 700; }
        .side-link.disabled { color: #98A2B3; cursor: not-allowed; }
        .side-icon { display: grid; flex: 0 0 26px; place-items: center; width: 26px; height: 26px; color: #667085; font-size: 1.1rem; font-weight: 600; }
        .side-link.active .side-icon { color: #fff; }
        .sidebar-help { margin-top: auto; padding: 17px 16px; border: 1px solid #BAE3F5; border-radius: 12px; background: #EBF5FB; color: #101828; }
        .sidebar-help-icon { margin-bottom: 8px; color: #00629B; font-size: 1.25rem; }
        .sidebar-help strong { font-size: .88rem; color: #101828; }
        .sidebar-help p { margin: 6px 0 0; color: #475467; font-size: .8rem; line-height: 1.5; }
        .sidebar-footer { margin: 16px 4px 0; color: #667085; font-size: .73rem; line-height: 1.55; }
        .main-content { min-width: 0; }
        .shell { max-width: 1440px; margin: 0 auto; padding: 24px 38px 64px; }

        /* Typography & Headers */
        .eyebrow { color: #00629B; font-size: .76rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
        h1 { margin: 8px 0 8px; font-size: clamp(1.9rem, 4vw, 2.5rem); letter-spacing: -.045em; line-height: 1.15; color: #101828; font-weight: 800; }

        /* Isolasi z-index Leaflet Map agar tidak menembus/timpang tindih dengan modal dialog */
        .leaflet-container {
            z-index: 1 !important;
            isolation: isolate;
        }
        .leaflet-pane {
            z-index: 2 !important;
        }
        .leaflet-tile-pane {
            z-index: 2 !important;
        }
        .leaflet-overlay-pane {
            z-index: 3 !important;
        }
        .leaflet-marker-pane {
            z-index: 4 !important;
        }
        .leaflet-tooltip-pane, .leaflet-popup-pane {
            z-index: 5 !important;
        }
        .leaflet-top, .leaflet-bottom {
            z-index: 6 !important;
        }
        .intro { max-width: 800px; margin: 0; color: #475467; font-size: 1.05rem; line-height: 1.6; }

        /* Cards & Form Elements */
        .card { border: 1px solid #E4E7EC; border-radius: 16px; background: #fff; box-shadow: 0 1px 3px rgba(16, 24, 40, 0.05); }
        .section-title { margin: 20px 0 12px; padding-top: 17px; border-top: 1px solid #EAECF0; color: #101828; font-size: .9rem; font-weight: 800; }
        .section-copy { margin: 6px 0 18px; color: #475467; font-size: .86rem; line-height: 1.5; }
        label { display: block; margin-bottom: 7px; color: #344054; font-size: .88rem; font-weight: 700; }
        .input-wrap { position: relative; }
        .unit { position: absolute; top: 50%; right: 14px; color: #667085; font-size: .86rem; font-weight: 600; transform: translateY(-50%); pointer-events: none; }
        .hint { margin: 5px 0 0; color: #667085; font-size: .72rem; line-height: 1.4; }
        .error { margin: 6px 0 0; color: #B42318; font-size: .78rem; }
        
        select, input[type="text"], input[type="number"], textarea {
            width: 100%;
            min-height: 44px;
            padding: 9px 12px;
            border: 1px solid #D0D5DD;
            border-radius: 8px;
            background: #fff;
            color: #101828;
            font: inherit;
            font-size: .92rem;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        select:focus, input:focus, textarea:focus {
            outline: 3px solid rgba(0, 98, 155, 0.2);
            border-color: #00629B;
        }
        input:disabled, input[readonly] {
            border-color: #E4E7EC;
            background: #F2F4F7;
            color: #667085;
            cursor: not-allowed;
            opacity: 1;
        }
        input[type="number"] { appearance: textfield; -moz-appearance: textfield; }
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button { margin: 0; -webkit-appearance: none; }

        /* Buttons matching cek-kelayakan */
        .actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px; }
        .submit, .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 0 17px;
            border-radius: 8px;
            font: inherit;
            font-size: .9rem;
            font-weight: 700;
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
            min-height: 42px;
            padding: 0 17px;
            border-radius: 8px;
            font: inherit;
            font-size: .9rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid #D0D5DD;
            background: #fff;
            color: #344054;
            transition: background .15s ease-in-out;
        }
        .reset:hover, .btn-secondary:hover {
            background: #F2F4F7;
            color: #101828;
        }
        .btn-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 0 17px;
            border-radius: 8px;
            font: inherit;
            font-size: .9rem;
            font-weight: 700;
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
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            min-height: 32px;
            padding: 5px 14px;
            border: 1px solid transparent;
            border-radius: 999px;
            font-size: .84rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .status-icon { font-size: 1rem; line-height: 1; }
        .status-aman { border-color: #A6F4C5; background: #ECFDF3; color: #027A48; }
        .status-waspada { border-color: #FEDF89; background: #FFFAEB; color: #B54708; }
        .status-kritis { border-color: #FECDCA; background: #FEF3F2; color: #B42318; }
        .status-diajukan { border-color: #FEDF89; background: #FFFAEB; color: #B54708; }
        .status-selesai { border-color: #A6F4C5; background: #ECFDF3; color: #027A48; }
        .status-draf { border-color: #D0D5DD; background: #F8FAFC; color: #475467; }

        @media (max-width: 760px) {
            .topbar-inner { height: 68px; padding: 0 16px; }
            .topbar-label { display: none; }
            .account-copy { display: none; }
            .account { margin-left: 12px; }
            .app-frame { grid-template-columns: 1fr; }
            .sidebar { padding: 14px 14px 12px; border-right: 0; border-bottom: 1px solid #e0e8ef; }
            .sidebar-label { margin-bottom: 8px; }
            .side-nav { display: flex; overflow-x: auto; gap: 6px; padding-bottom: 2px; }
            .side-link { flex: 0 0 auto; min-height: 42px; padding: 0 12px; }
            .side-link.disabled { display: none; }
            .sidebar-help, .sidebar-footer { display: none; }
            .shell { padding: 30px 18px 44px; }
        }
        @media (max-width: 560px) {
            :root { font-size: 14px; }
            .shell { padding: 24px 14px 36px; }
        }
    </style>
    @include('layouts.sidebar-styles')
    @stack('styles')
</head>
<body>
    {{-- Layout Portal Desa: Topbar & Sidebar untuk Pengajuan Air (F-03) & Layanan Desa --}}
    <!-- APP FRAME: SIDEBAR + MAIN CONTENT -->
    <div class="app-frame role-sidebar-shell">
        @include('layouts.sidebar')

        <div class="main-content">
            <main class="shell">

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-5 p-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-900 flex items-start gap-3 shadow-xs">
                        <span class="text-emerald-600 font-bold text-lg leading-none mt-0.5">✓</span>
                        <div class="text-sm font-semibold">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if(session('info'))
                    <div class="mb-5 p-4 rounded-xl border border-sky-200 bg-sky-50 text-sky-900 flex items-start gap-3 shadow-xs">
                        <span class="text-sky-600 font-bold text-lg leading-none mt-0.5">ℹ</span>
                        <div class="text-sm font-medium">
                            {{ session('info') }}
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-5 p-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-900 shadow-xs">
                        <div class="flex items-center gap-2 text-rose-800 font-bold text-sm mb-1">
                            <span>⚠</span>
                            <span>Mohon periksa kembali isian formulir:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs space-y-1 text-rose-700 ml-4">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')

            </main>
        </div>
    </div>

    <!-- MODAL POP-UP KELAYAKAN STATUS AMAN -->
    @if(session('modal_kelayakan_aman'))
        @php
            $modalAman = session('modal_kelayakan_aman');
        @endphp
        <div id="modalKelayakanAman" class="fixed inset-0 z-[99999] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden animate-in fade-in duration-200 relative z-[100000]">
                <div class="p-6 text-center text-white" style="background: #619DC6;">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center mx-auto mb-3 text-2xl">
                        💧
                    </div>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-white/20 text-white mb-1">
                        Status Cek Kelayakan: AMAN
                    </span>
                    <h3 class="text-xl font-extrabold">Pengajuan Tidak Dapat Diproses</h3>
                    <p class="text-xs text-sky-100 mt-1">Desa {{ $modalAman['desa'] ?? (Auth::user()?->desa?->nama_desa ?? '') }}</p>
                </div>

                <div class="p-6 space-y-4">
                    <div class="border border-emerald-200 bg-emerald-50 rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider block">Skor Prioritas SPK (AHP-SAW)</span>
                            <span class="text-2xl font-extrabold text-emerald-700">{{ number_format($modalAman['skor'] ?? 48.1, 1) }}</span>
                            <span class="text-xs font-semibold text-emerald-600">/ 100 (Kategori Aman)</span>
                        </div>
                        <span class="status-badge status-aman">
                            Kondisi Terkendali
                        </span>
                    </div>

                    <div class="text-xs text-slate-600 space-y-2 leading-relaxed">
                        <p class="font-medium text-slate-800">
                            {{ $modalAman['pesan'] ?? 'Berdasarkan hasil Cek Kelayakan Mandiri (F-02), kondisi ketersediaan air di desa Anda dinyatakan dalam kondisi AMAN.' }}
                        </p>
                        <div class="border border-slate-200 rounded-xl p-3 bg-slate-50 text-[11px] text-slate-600">
                            <span class="font-bold text-slate-800 block mb-0.5">Ketentuan SOP Bantuan Air BPBD:</span>
                            Armada tangki distribusi air bersih hanya diprioritaskan bagi desa yang berada pada status <strong class="text-rose-700">KRITIS</strong> atau <strong class="text-amber-700">WASPADA</strong>. Desa dengan status <strong>AMAN</strong> tidak dapat mengakses formulir pengajuan bantuan.
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="button" onclick="document.getElementById('modalKelayakanAman').classList.add('hidden')" class="submit w-full text-center">
                            Mengerti & Kembali
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @stack('scripts')
</body>
</html>
@endif
