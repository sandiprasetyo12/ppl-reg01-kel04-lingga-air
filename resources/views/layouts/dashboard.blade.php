@php
    $currentUser = auth()->user();
    $roleLabel = 'Pengguna';

    if ($currentUser && $currentUser->isBpbd()) {
        $roleLabel = 'Admin BPBD';
    } elseif ($currentUser && $currentUser->isDesa()) {
        $roleLabel = 'Perwakilan Desa';
    } elseif ($currentUser && $currentUser->isPenyalur()) {
        $roleLabel = 'Mitra Penyalur';
    }

    $usernameDisplay = $currentUser->username ?? 'Pengguna';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - ' . config('app.name', 'LinggaAir'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = { darkMode: 'class' };
        </script>
    @endif

    <style>
        *, html, body, input, button, select, textarea { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; }
        body { background: #F8FAFC; color: #101828; font-size: 0.75rem; /* 12px compact base */ }
        :focus-visible { outline: 2px solid #00629B; outline-offset: 2px; }
        button, a[href] { cursor: pointer; }
        button:disabled { cursor: not-allowed; }

        .dashboard-app { display: grid; grid-template-columns: 205px minmax(0, 1fr); min-height: 100vh; transition: grid-template-columns .18s ease; }
        .dashboard-sidebar { display: flex; min-width: 0; flex-direction: column; padding: 10px 12px 14px; border-right: 1px solid #EAECF0; background: #fff; }
        .sidebar-brand-row { position: relative; display: flex; align-items: center; justify-content: flex-start; gap: 8px; min-height: 54px; padding: 0 4px 10px; border-bottom: 1px solid #EAECF0; }
        .sidebar-collapse { position: absolute; right: -22px; bottom: -15px; z-index: 2; display: grid; flex: 0 0 30px; place-items: center; width: 30px; height: 30px; border: 0; border-radius: 0; background: transparent; color: #667085; }
        .sidebar-collapse:hover { color: #00629B; background: transparent; }
        .sidebar-profile { display: flex; align-items: center; gap: 10px; min-width: 0; margin: 12px 0 14px; padding: 10px; border: 1px solid #F2F4F7; border-radius: 12px; background: #F8FAFC; }
        .sidebar-profile-icon { display: grid; flex: 0 0 30px; place-items: center; width: 30px; height: 30px; border-radius: 9px; background: #EBF5FB; color: #00629B; }
        .sidebar-profile-copy { min-width: 0; }
        .sidebar-profile-copy strong { display: block; overflow: hidden; color: #101828; font-size: .73rem; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
        .sidebar-profile-copy small { display: block; margin-top: 2px; color: #667085; font-size: .65rem; }
        .sidebar-section { margin: 0 0 7px 10px; color: #667085; font-size: .62rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
        .sidebar-nav { display: grid; gap: 4px; }
        .sidebar-link { display: flex; align-items: center; gap: 10px; min-height: 36px; min-width: 0; padding: 0 10px; border-radius: 9px; color: #344054; font-size: .69rem; font-weight: 500; text-decoration: none; transition: background .14s ease, color .14s ease; }
        .sidebar-link i { display: grid; flex: 0 0 16px; place-items: center; color: #667085; font-size: .78rem; }
        .sidebar-link:hover:not(.active):not(.disabled) { background: #F2F4F7; color: #00629B; }
        .sidebar-link.active { background: #00629B; color: #fff; font-weight: 700; }
        .sidebar-link.active i { color: #fff; }
        .sidebar-link.disabled { color: #475467; }
        .sidebar-link.disabled i { color: #667085; }
        .sidebar-divider { height: 1px; margin: 13px 4px; border: 0; background: #EAECF0; }
        .sidebar-utility { display: grid; gap: 4px; }
        .sidebar-badge { display: inline-grid; min-width: 17px; height: 17px; place-items: center; margin-left: auto; padding: 0 5px; border-radius: 999px; background: #00629B; color: #fff; font-size: .58rem; font-weight: 700; }
        .sidebar-footer { margin-top: auto; padding: 18px 7px 0; color: #98A2B3; font-size: .62rem; line-height: 1.5; }

        .dashboard-main { display: flex; min-width: 0; flex-direction: column; }
        .dashboard-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; min-height: 54px; padding: 8px 20px; border-bottom: 1px solid #EAECF0; background: #fff; }
        .dashboard-header-title { color: #344054; font-size: .78rem; font-weight: 700; }
        .dashboard-account { display: flex; align-items: center; gap: 10px; color: #344054; font-size: .7rem; }
        .dashboard-account-role { color: #667085; font-size: .64rem; }
        .dashboard-logout { display: inline-flex; align-items: center; justify-content: center; min-height: 32px; padding: 0 10px; border: 1px solid #D0D5DD; border-radius: 8px; background: #fff; color: #344054; font-size: .67rem; font-weight: 600; }
        .dashboard-logout:hover { background: #F2F4F7; }
        .dashboard-page-heading { padding: 12px 20px 0; }
        .dashboard-page-heading h1 { margin: 0; color: #101828; font-size: 0.875rem; /* 14px */ font-weight: 700; }
        .dashboard-content { width: 100%; max-width: 1440px; margin: 0 auto; padding: 12px 20px 24px; }
        .dashboard-footer { margin-top: auto; padding: 10px 20px; border-top: 1px solid #EAECF0; background: #fff; color: #667085; font-size: .625rem; }
        .dashboard-app.is-collapsed { grid-template-columns: 76px minmax(0, 1fr); }
        .dashboard-app.is-collapsed .sidebar-profile-copy,
        .dashboard-app.is-collapsed .sidebar-section,
        .dashboard-app.is-collapsed .sidebar-link-label,
        .dashboard-app.is-collapsed .sidebar-badge,
        .dashboard-app.is-collapsed .sidebar-footer { display: none; }
        .dashboard-app.is-collapsed .sidebar-brand-row,
        .dashboard-app.is-collapsed .sidebar-profile,
        .dashboard-app.is-collapsed .sidebar-link { justify-content: center; }
        .dashboard-app.is-collapsed .sidebar-profile { padding: 8px 0; }
        .dashboard-app.is-collapsed .sidebar-link { padding: 0; }

        @media (max-width: 760px) {
            .dashboard-app, .dashboard-app.is-collapsed { grid-template-columns: minmax(0, 1fr); }
            .dashboard-sidebar { padding: 8px 12px 12px; border-right: 0; border-bottom: 1px solid #EAECF0; }
            .sidebar-brand-row { min-height: 46px; }
            .sidebar-collapse { position: static; margin-left: auto; }
            .sidebar-profile { margin: 9px 0; }
            .sidebar-nav, .sidebar-utility { display: flex; gap: 5px; overflow-x: auto; padding-bottom: 3px; }
            .sidebar-link { flex: 0 0 auto; min-height: 36px; white-space: nowrap; }
            .sidebar-divider, .sidebar-section, .sidebar-footer { display: none; }
            .dashboard-app.is-collapsed .sidebar-profile-copy,
            .dashboard-app.is-collapsed .sidebar-link-label { display: initial; }
            .dashboard-app.is-collapsed .sidebar-profile,
            .dashboard-app.is-collapsed .sidebar-link { justify-content: flex-start; }
            .dashboard-header { min-height: 50px; padding: 8px 14px; }
            .dashboard-page-heading { padding: 16px 16px 0; }
            .dashboard-content { padding: 16px 16px 28px; }
            .dashboard-footer { padding: 12px 16px; }
        }
    </style>
    @include('layouts.sidebar-styles')
</head>
<body>
    <div class="dashboard-app role-sidebar-shell" id="dashboard-app">
        @include('layouts.sidebar')

        <div class="dashboard-main">
            <div class="dashboard-page-heading">
                <h1>@yield('page-title', 'Dashboard')</h1>
            </div>

            <main class="dashboard-content space-y-5">
                @if (session('success'))
                    <div role="status" class="p-3.5 rounded-lg bg-[#ECFDF3] border border-[#A6F4C5] text-[#027A48] text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div role="alert" class="p-3.5 rounded-lg bg-[#FEF3F2] border border-[#FECDCA] text-[#B42318] text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="dashboard-footer">
                &copy; {{ date('Y') }} LinggaAir - BPBD Kabupaten Purbalingga.
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
