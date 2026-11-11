@hasSection('header')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'LinggaAir'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = { darkMode: 'class' };
        </script>
    @endif

    <style>
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }
        :focus-visible {
            outline: 2px solid #1b1b18;
            outline-offset: 2px;
        }
        a[href], button, select, summary, label[for], input[type="checkbox"], input[type="radio"] {
            cursor: pointer;
        }
        button:disabled, input:disabled, select:disabled {
            cursor: not-allowed;
        }
    </style>
</head>
<body class="bg-[#FDFDFC] text-[#1b1b18] antialiased min-h-screen flex flex-col">
    @yield('header')

    <main class="flex-1 flex flex-col">
        @if (session('success'))
            <div class="max-w-5xl w-full mx-auto px-4 sm:px-6 pt-4">
                <div role="status" class="p-3.5 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-5xl w-full mx-auto px-4 sm:px-6 pt-4">
                <div role="alert" class="p-3.5 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm">
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
                            50: '#f0f8fb',
                            100: '#e0f0f7',
                            200: '#cae4ef',
                            600: '#0b6d8d',
                            700: '#086288',
                            800: '#064b68',
                            900: '#05394f',
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
            color: #202b3d;
            background: #f1f5f9;
        }
        * { box-sizing: border-box; }
        body { margin: 0; overflow-x: hidden; font-family: Poppins, sans-serif; background: #f1f5f9; color: #202b3d; }
        
        /* Topbar Style */
        .topbar { background: #086288; color: #fff; }
        .topbar-inner { position: relative; display: flex; align-items: center; justify-content: space-between; height: 76px; padding: 0 26px; }
        .brand { display: flex; max-width: 240px; min-width: 0; align-items: center; gap: 9px; font-size: 1.08rem; font-weight: 750; letter-spacing: -.03em; line-height: 1.15; text-decoration: none; color: inherit; }
        .brand-mark { display: grid; flex: 0 0 36px; place-items: center; width: 36px; height: 36px; border: 1px solid rgb(255 255 255 / 28%); border-radius: 10px; background: rgb(255 255 255 / 12%); font-size: 1rem; }
        .brand > span:last-child { min-width: 0; }
        .brand small { display: block; max-width: 170px; margin-top: 4px; font-size: .5rem; font-weight: 600; letter-spacing: .025em; line-height: 1.2; overflow-wrap: anywhere; opacity: .9; }
        .brand-wrap { display: flex; align-items: center; gap: 0; min-width: 0; }
        .topbar-label { position: absolute; left: 240px; display: flex; align-items: center; min-height: 24px; padding-left: 0; color: #e0f0f7; font-size: .9rem; line-height: 1.4; white-space: nowrap; }
        .account { display: flex; align-items: center; gap: 11px; margin-left: 20px; color: #fff; text-align: right; }
        .account-copy { font-size: .86rem; font-weight: 650; line-height: 1.3; white-space: nowrap; }
        .account-copy small { display: block; margin-top: 2px; color: #d7eaf1; font-size: .74rem; font-weight: 400; }
        .account-avatar { display: grid; flex: 0 0 40px; place-items: center; width: 40px; height: 40px; border: 1px solid rgb(255 255 255 / 48%); border-radius: 50%; background: rgb(255 255 255 / 12%); font-size: 1.1rem; }

        /* Frame & Sidebar Layout */
        .app-frame { display: grid; grid-template-columns: minmax(0, 240px) minmax(0, 1fr); min-width: 0; min-height: calc(100vh - 76px); }
        .sidebar { display: flex; min-width: 0; flex-direction: column; padding: 24px 14px 18px; border-right: 1px solid #e0e8ef; background: #fff; }
        .sidebar-label { margin: 0 0 14px 12px; color: #8294a8; font-size: .76rem; font-weight: 750; letter-spacing: .07em; text-transform: uppercase; }
        .side-nav { display: grid; gap: 5px; }
        .side-link { display: flex; min-width: 0; align-items: center; gap: 10px; min-height: 46px; padding: 0 10px; border-radius: 9px; color: #41566c; font-size: .88rem; font-weight: 500; text-decoration: none; overflow-wrap: anywhere; transition: background .15s ease, color .15s ease; }
        .side-link:hover:not(.active):not(.disabled) { background: #f1f5f9; color: #172c41; }
        .side-link.active { background: #086288; color: #fff; font-weight: 700; }
        .side-link.disabled { color: #8393a6; cursor: not-allowed; }
        .side-icon { display: grid; flex: 0 0 26px; place-items: center; width: 26px; height: 26px; color: #829bb1; font-size: 1.1rem; font-weight: 600; }
        .side-link.active .side-icon { color: #fff; }
        .sidebar-help { margin-top: auto; padding: 17px 16px; border: 1px solid #cae4ef; border-radius: 12px; background: #f0f8fb; color: #344b61; }
        .sidebar-help-icon { margin-bottom: 8px; color: #086288; font-size: 1.25rem; }
        .sidebar-help strong { font-size: .88rem; }
        .sidebar-help p { margin: 6px 0 0; color: #60768b; font-size: .8rem; line-height: 1.5; }
        .sidebar-footer { margin: 16px 4px 0; color: #8294a8; font-size: .73rem; line-height: 1.55; }
        .main-content { min-width: 0; }
        .shell { max-width: 1440px; margin: 0 auto; padding: 24px 38px 64px; }

        /* Typography & Headers */
        .eyebrow { color: #0b6d8d; font-size: .76rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
        h1 { margin: 8px 0 8px; font-size: clamp(1.9rem, 4vw, 2.5rem); letter-spacing: -.045em; line-height: 1.15; color: #172c41; font-weight: 800; }

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
        .intro { max-width: 800px; margin: 0; color: #667991; font-size: 1.05rem; line-height: 1.6; }

        /* Cards & Form Elements */
        .card { border: 1px solid #dce5ef; border-radius: 16px; background: #fff; box-shadow: 0 2px 5px rgb(31 48 70 / 5%); }
        .section-title { margin: 20px 0 12px; padding-top: 17px; border-top: 1px solid #e8edf3; color: #34445b; font-size: .9rem; font-weight: 800; }
        .section-copy { margin: 6px 0 18px; color: #73859c; font-size: .86rem; line-height: 1.5; }
        label { display: block; margin-bottom: 7px; color: #34445b; font-size: .88rem; font-weight: 700; }
        .input-wrap { position: relative; }
        .unit { position: absolute; top: 50%; right: 14px; color: #91a2ba; font-size: .86rem; font-weight: 600; transform: translateY(-50%); pointer-events: none; }
        .hint { margin: 5px 0 0; color: #71859a; font-size: .72rem; line-height: 1.4; }
        .error { margin: 6px 0 0; color: #b42318; font-size: .78rem; }
        
        select, input[type="text"], input[type="number"], textarea {
            width: 100%;
            min-height: 44px;
            padding: 9px 12px;
            border: 1px solid #d7e1ed;
            border-radius: 8px;
            background: #fff;
            color: #253348;
            font: inherit;
            font-size: .92rem;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        select:focus, input:focus, textarea:focus {
            outline: 3px solid rgb(11 100 132 / 13%);
            border-color: #0b6484;
        }
        input:disabled, input[readonly] {
            border-color: #e2e8f0;
            background: #f1f5f9;
            color: #64748b;
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
            border: 1px solid #086288;
            background: #086288;
            color: #fff;
            box-shadow: 0 2px 6px rgb(8 98 136 / 20%);
            transition: background .15s ease-in-out, border-color .15s ease-in-out;
        }
        .submit:hover, .btn-primary:hover {
            background: #064b68;
            border-color: #064b68;
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
            border: 1px solid #d7e1ed;
            background: #fff;
            color: #34445b;
            transition: background .15s ease-in-out;
        }
        .reset:hover, .btn-secondary:hover {
            background: #f7f9fc;
            color: #172c41;
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
            border: 1px solid #f2bfc1;
            background: #fff0ef;
            color: #9c292d;
            transition: background .15s ease-in-out;
        }
        .btn-danger:hover {
            background: #fde8e8;
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
        .status-aman { border-color: #b8dfc7; background: #e9f6ed; color: #21663b; }
        .status-waspada { border-color: #f0d39a; background: #fff5df; color: #815000; }
        .status-kritis { border-color: #f2bfc1; background: #fff0ef; color: #9c292d; }
        .status-diajukan { border-color: #f0d39a; background: #fff5df; color: #815000; }
        .status-selesai { border-color: #b8dfc7; background: #e9f6ed; color: #21663b; }
        .status-draf { border-color: #dce5ef; background: #f8fafc; color: #475569; }

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
    @stack('styles')
</head>
<body>
    {{-- Layout Portal Desa: Topbar & Sidebar untuk Pengajuan Air (F-03) & Layanan Desa --}}
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand-wrap">
                <a href="{{ url('/') }}" class="brand">
                    <span class="brand-mark" aria-hidden="true">💧</span>
                    <span>Lingga Air<small>LAYANAN BANTUAN AIR BERSIH</small></span>
                </a>
                <span class="topbar-label">Portal pelayanan desa</span>
            </div>
            @php
                $topDesa = isset($desa) ? $desa : (Auth::user()?->desa ?? \App\Models\Desa::where('nama_desa', 'like', '%Serang%')->first() ?? \App\Models\Desa::first());
            @endphp
            <div class="account">
                @if(Auth::check())
                    <span class="account-copy">
                        {{ Auth::user()->isBpbd() ? 'Admin BPBD' : (Auth::user()->isDesa() ? 'Akun Desa' : 'Mitra Penyalur') }}
                        <small>{{ Auth::user()->desa?->nama_desa ?? Auth::user()->username }}</small>
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" title="Keluar" class="account-avatar text-xs font-bold hover:bg-white/20 transition cursor-pointer">
                            ⇥
                        </button>
                    </form>
                @else
                    <span class="account-copy">Akun Desa<small>{{ $topDesa ? $topDesa->nama_desa : 'Kabupaten Purbalingga' }}</small></span>
                    <a href="{{ route('login') }}" class="account-avatar hover:bg-white/20 transition cursor-pointer" title="Masuk">
                        ♙
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- APP FRAME: SIDEBAR + MAIN CONTENT -->
    <div class="app-frame">
        <aside class="sidebar">
            <nav aria-label="Navigasi desa">
                <p class="sidebar-label">Layanan desa</p>
                <div class="side-nav">
                    @if(Auth::check() && Auth::user()->isDesa())
                        <a class="side-link {{ request()->routeIs('desa.dashboard') ? 'active' : '' }}" href="{{ route('desa.dashboard') }}">
                            <span class="side-icon" aria-hidden="true">🏠</span>Dashboard Desa
                        </a>
                    @endif
                    <a class="side-link {{ request()->routeIs('pengajuan.index') ? 'active' : '' }}" href="{{ route('pengajuan.index') }}">
                        <span class="side-icon" aria-hidden="true">▦</span>Beranda &amp; Draf
                    </a>
                    <a class="side-link {{ request()->routeIs('cek-kelayakan.*') ? 'active' : '' }}" href="{{ route('cek-kelayakan.index') }}">
                        <span class="side-icon" aria-hidden="true">✓</span>Cek Kelayakan
                    </a>
                    <a class="side-link {{ request()->routeIs('pengajuan.create') ? 'active' : '' }}" href="{{ route('pengajuan.create') }}">
                        <span class="side-icon" aria-hidden="true">＋</span>Pengajuan Bantuan
                    </a>
                    <a class="side-link" href="{{ route('pengajuan.index') }}">
                        <span class="side-icon" aria-hidden="true">▤</span>Status Pengajuan
                    </a>
                    <span class="side-link disabled" aria-disabled="true">
                        <span class="side-icon" aria-hidden="true">?</span>Bantuan &amp; Panduan
                    </span>
                </div>
            </nav>

            <section class="sidebar-help" aria-label="Bantuan teknis">
                <div class="sidebar-help-icon" aria-hidden="true">☎</div>
                <strong>Butuh bantuan?</strong>
                <p>Hubungi tim teknis BPBD untuk bantuan penggunaan layanan desa.</p>
            </section>

            <p class="sidebar-footer">© 2026 Lingga Air<br>Pemerintah Kabupaten Purbalingga</p>
        </aside>

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
                <div class="p-6 text-center text-white" style="background: #086288;">
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
