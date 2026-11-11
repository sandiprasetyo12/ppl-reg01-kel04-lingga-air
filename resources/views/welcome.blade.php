@extends('layouts.app')

@section('title', 'LinggaAir - Sistem Distribusi Air Bersih Darurat BPBD Purbalingga')

@php
    $dashboardRouteName = 'onboarding.welcome';
    if (auth()->check()) {
        $authUser = auth()->user();
        if ($authUser->isBpbd()) {
            $dashboardRouteName = 'admin.dashboard';
        } elseif ($authUser->isDesa()) {
            $dashboardRouteName = 'desa.dashboard';
        } elseif ($authUser->isPenyalur()) {
            $dashboardRouteName = 'penyalur.dashboard';
        }
    }
@endphp

@section('header')
<header class="w-full max-w-4xl mx-auto px-6 pt-6">
    <nav aria-label="Navigasi utama" class="flex items-center justify-between gap-4">
        <a href="{{ url('/') }}" class="font-semibold text-base text-[#1b1b18] min-h-[44px] inline-flex items-center">
            LinggaAir
        </a>

        @if (Route::has('login'))
            <div class="flex items-center gap-3 text-sm">
                @auth
                    <a
                        href="{{ route($dashboardRouteName) }}"
                        class="min-h-[44px] inline-flex items-center px-5 py-2 border border-[#19140035] hover:border-[#1915014a] text-[#1b1b18] rounded-sm font-medium transition"
                    >
                        Dashboard
                    </a>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="min-h-[44px] inline-flex items-center px-4 py-2 text-[#1b1b18] border border-transparent hover:border-[#19140035] rounded-sm transition"
                    >
                        Masuk
                    </a>
                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="min-h-[44px] inline-flex items-center px-5 py-2 border border-[#19140035] hover:border-[#1915014a] text-[#1b1b18] rounded-sm font-medium transition"
                        >
                            Daftar
                        </a>
                    @endif
                @endauth
            </div>
        @endif
    </nav>
</header>
@endsection

@section('content')
<div class="flex-1 flex items-center justify-center p-6 lg:p-8">
    <div class="w-full max-w-4xl bg-white border border-[#e3e3e0] rounded-lg p-6 sm:p-10 shadow-sm space-y-6">
        <div class="space-y-2">
            <p class="text-xs font-medium uppercase tracking-wider text-[#52525b]">
                BPBD Kabupaten Purbalingga
            </p>
            <h1 class="text-2xl sm:text-3xl font-semibold text-[#1b1b18]">
                LinggaAir: Sistem Informasi Distribusi Air Bersih Darurat
            </h1>
            <p class="text-sm sm:text-base text-[#52525b] leading-relaxed max-w-2xl">
                Portal koordinasi master data wilayah desa Kabupaten Purbalingga, pendaftaran akun perwakilan desa, dan pendataan armada penyalur air bersih.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 pt-2">
            @auth
                <a
                    href="{{ route($dashboardRouteName) }}"
                    class="min-h-[44px] inline-flex items-center px-5 py-2.5 bg-[#1b1b18] hover:bg-black text-white text-sm font-medium rounded-sm transition"
                >
                    Buka Dashboard ({{ auth()->user()->username }})
                </a>
            @else
                <a
                    href="{{ route('login') }}"
                    class="min-h-[44px] inline-flex items-center px-5 py-2.5 bg-[#1b1b18] hover:bg-black text-white text-sm font-medium rounded-sm transition"
                >
                    Masuk ke Portal
                </a>
                <a
                    href="{{ route('register') }}"
                    class="min-h-[44px] inline-flex items-center px-5 py-2.5 border border-[#19140035] hover:border-[#1915014a] text-[#1b1b18] text-sm font-medium rounded-sm transition"
                >
                    Daftar Akun Baru
                </a>
            @endauth
        </div>

        <div class="border-t border-[#e3e3e0] pt-6 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div class="p-4 bg-[#FDFDFC] border border-[#e3e3e0] rounded-md">
                <h2 class="font-medium text-[#1b1b18]">Perwakilan Desa</h2>
                <p class="text-[#52525b] mt-1">Lengkapi data kependudukan desa dan titik drop air bersih.</p>
            </div>
            <div class="p-4 bg-[#FDFDFC] border border-[#e3e3e0] rounded-md">
                <h2 class="font-medium text-[#1b1b18]">Admin BPBD</h2>
                <p class="text-[#52525b] mt-1">Kelola master data wilayah desa dan koordinat lokasi tandon.</p>
            </div>
            <div class="p-4 bg-[#FDFDFC] border border-[#e3e3e0] rounded-md">
                <h2 class="font-medium text-[#1b1b18]">Mitra Penyalur</h2>
                <p class="text-[#52525b] mt-1">Daftarkan kapasitas tangki dan informasi operasional armada.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<footer class="w-full max-w-4xl mx-auto px-6 pb-8 text-xs text-[#52525b]">
    &copy; {{ date('Y') }} LinggaAir - BPBD Kabupaten Purbalingga.
</footer>
@endsection