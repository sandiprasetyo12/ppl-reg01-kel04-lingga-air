@php
    $currentUser = auth()->user();
    $roleLabel = 'Pengguna';
    $navLinks = [];

        if ($currentUser && $currentUser->isBpbd()) {
        $roleLabel = 'Admin BPBD';
        $navLinks = [
            [
                'label' => 'Dashboard',
                'route' => route('admin.dashboard'),
                'active' => request()->routeIs('admin.dashboard'),
            ],
            [
                'label' => 'Verifikasi Pengajuan',
                'route' => route('verifikasi.index'),
                'active' => request()->routeIs('verifikasi.*'),
            ],
            [
                'label' => 'Master Data Desa',
                'route' => route('admin.desa.index'),
                'active' => request()->routeIs('admin.desa.*'),
            ],
        ];

    } elseif ($currentUser && $currentUser->isDesa()) {
        $roleLabel = 'Perwakilan Desa';
        $navLinks = [
            [
                'label' => 'Dashboard Desa',
                'route' => route('desa.dashboard'),
                'active' => request()->routeIs('desa.dashboard'),
            ],
            [
                'label' => 'Cek Kelayakan',
                'route' => route('cek-kelayakan.index'),
                'active' => request()->routeIs('cek-kelayakan.*'),
            ],
        ];
    } elseif ($currentUser && $currentUser->isPenyalur()) {
        $roleLabel = 'Mitra Penyalur';
        $navLinks = [
            [
                'label' => 'Dashboard Penyalur',
                'route' => route('penyalur.dashboard'),
                'active' => request()->routeIs('penyalur.dashboard'),
            ],
        ];
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
<body class="bg-[#F3F4F6] text-[#1b1b18] antialiased min-h-screen flex flex-col">
    <!-- Top Navbar Standar Laravel -->
    <header class="bg-white border-b border-[#e3e3e0]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between gap-4 min-h-[64px] py-2">
                <div class="flex flex-wrap items-center gap-6">
                    <a href="{{ url('/') }}" class="font-semibold text-base text-[#1b1b18] min-h-[44px] inline-flex items-center">
                        LinggaAir
                    </a>

                    <nav aria-label="Navigasi dasbor" class="flex flex-wrap items-center gap-2">
                        @foreach ($navLinks as $link)
                            <a
                                href="{{ $link['route'] }}"
                                @if ($link['active']) aria-current="page" @endif
                                class="min-h-[44px] inline-flex items-center px-3 py-2 text-sm rounded-md border transition {{ $link['active'] ? 'border-[#1b1b18] bg-[#1b1b18] text-white font-medium' : 'border-transparent text-[#52525b] hover:text-[#1b1b18] hover:bg-[#f5f5f4]' }}"
                            >
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-sm text-[#52525b] hidden sm:block">
                        <span class="font-medium text-[#1b1b18]">{{ $usernameDisplay }}</span>
                        <span>({{ $roleLabel }})</span>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="min-h-[44px] inline-flex items-center px-4 py-2 text-sm font-medium text-[#1b1b18] bg-white border border-[#d1d5db] rounded-md hover:bg-[#f5f5f4] transition"
                        >
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Header Judul Halaman -->
    <div class="bg-white border-b border-[#e3e3e0]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <h1 class="text-lg sm:text-xl font-semibold text-[#1b1b18]">
                @yield('page-title', 'Dashboard')
            </h1>
        </div>
    </div>

    <!-- Konten Utama -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5">
        @if (session('success'))
            <div role="status" class="p-3.5 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div role="alert" class="p-3.5 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-white border-t border-[#e3e3e0] py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-[#52525b]">
            &copy; {{ date('Y') }} LinggaAir - BPBD Kabupaten Purbalingga.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
