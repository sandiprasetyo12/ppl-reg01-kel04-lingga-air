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
                'label' => 'Laporan Penyaluran',
                'route' => route('laporan.index'),
                'active' => request()->routeIs('laporan.*'),
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
            [
                'label' => 'Status Pengajuan',
                'route' => route('pengajuan.index'),
                'active' => request()->routeIs('pengajuan.*'),
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
            [
                'label' => 'Laporan Penyaluran',
                'route' => route('penyalur.laporan.index'),
                'active' => request()->routeIs('penyalur.laporan.*'),
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
<body class="bg-[#F8FAFC] text-[#101828] antialiased min-h-screen flex flex-col font-sans">
    <!-- Top Navbar Standar Lingga Air -->
    <header class="bg-white border-b border-[#EAECF0] shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between gap-4 min-h-[64px] py-2">
                <div class="flex flex-wrap items-center gap-6">
                    <a href="{{ url('/') }}" class="min-h-[44px] inline-flex items-center">
                        <img src="{{ asset('images/logo-lingga.png') }}" alt="Lingga Air" class="h-14 sm:h-16 w-auto object-contain" onerror="this.onerror=null; this.src='https://placehold.co/180x60/00629B/FFFFFF?text=Lingga+Air';">
                    </a>

                    <nav aria-label="Navigasi dasbor" class="flex flex-wrap items-center gap-2">
                        @foreach ($navLinks as $link)
                            <a
                                href="{{ $link['route'] }}"
                                @if ($link['active']) aria-current="page" @endif
                                class="min-h-[44px] inline-flex items-center px-3.5 py-2 text-xs font-bold uppercase tracking-wider rounded-xl border transition {{ $link['active'] ? 'border-[#00629B] bg-[#00629B] text-white shadow-xs' : 'border-transparent text-[#475467] hover:text-[#00629B] hover:bg-[#EBF5FB]' }}"
                            >
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-xs text-[#475467] hidden sm:block">
                        <span class="font-bold text-[#101828]">{{ $usernameDisplay }}</span>
                        <span class="text-[11px] text-[#00629B] font-semibold">({{ $roleLabel }})</span>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="min-h-[38px] inline-flex items-center justify-center px-3.5 py-1.5 border border-[#D0D5DD] hover:bg-[#F2F4F7] text-[#344054] text-xs font-bold rounded-xl transition cursor-pointer"
                        >
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Header Judul Halaman -->
    <div class="bg-white border-b border-[#EAECF0]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <h1 class="text-lg sm:text-xl font-bold text-[#101828]">
                @yield('page-title', 'Dashboard')
            </h1>
        </div>
    </div>

    <!-- Konten Utama -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5">
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

    <footer class="bg-white border-t border-[#EAECF0] py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-[#667085]">
            &copy; {{ date('Y') }} LinggaAir - BPBD Kabupaten Purbalingga.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>