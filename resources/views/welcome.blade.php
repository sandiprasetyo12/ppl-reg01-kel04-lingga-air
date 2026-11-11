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
<header class="w-full max-w-5xl mx-auto px-6 pt-6">
    <nav aria-label="Navigasi utama" class="flex items-center justify-between gap-4">
        <a href="{{ url('/') }}" class="font-bold text-lg text-[#1b1b18] min-h-[44px] inline-flex items-center gap-2">
            <span>💧</span>
            <span>LinggaAir</span>
        </a>

        @if (Route::has('login'))
            <div class="flex items-center gap-3 text-sm">
                <a
                    href="{{ route('cek-kelayakan.index') }}"
                    class="min-h-[44px] inline-flex items-center px-4 py-2 text-[#086288] hover:text-[#064b68] font-semibold transition"
                >
                    Cek Kelayakan Air
                </a>

                @auth
                    <a
                        href="{{ route($dashboardRouteName) }}"
                        class="min-h-[44px] inline-flex items-center px-5 py-2 border border-[#19140035] hover:border-[#1915014a] text-[#1b1b18] rounded-md font-medium transition"
                    >
                        Dashboard
                    </a>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="min-h-[44px] inline-flex items-center px-4 py-2 text-[#1b1b18] border border-transparent hover:border-[#19140035] rounded-md transition"
                    >
                        Masuk
                    </a>
                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="min-h-[44px] inline-flex items-center px-5 py-2 border border-[#19140035] hover:border-[#1915014a] text-[#1b1b18] rounded-md font-medium transition"
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
    <div class="w-full max-w-5xl bg-white border border-[#e3e3e0] rounded-2xl p-6 sm:p-10 shadow-sm space-y-6">
        <div class="space-y-2">
            <p class="text-xs font-bold uppercase tracking-wider text-[#086288]">
                BPBD Kabupaten Purbalingga
            </p>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1b1b18] tracking-tight">
                LinggaAir: Sistem Distribusi Air Bersih Darurat
            </h1>
            <p class="text-sm sm:text-base text-[#52525b] leading-relaxed max-w-2xl">
                Portal resmi koordinasi tanggap darurat kekeringan Kabupaten Purbalingga: master data wilayah desa, pendaftaran perwakilan desa, cek kelayakan mandiri (AHP-SAW), penyusunan pengajuan bantuan air bersih, dan pendataan armada penyalur.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 pt-2">
            @auth
                <a
                    href="{{ route($dashboardRouteName) }}"
                    class="min-h-[44px] inline-flex items-center px-5 py-2.5 bg-[#086288] hover:bg-[#064b68] text-white text-sm font-bold rounded-lg transition"
                >
                    Buka Dashboard ({{ auth()->user()->username }})
                </a>
            @else
                <a
                    href="{{ route('login') }}"
                    class="min-h-[44px] inline-flex items-center px-5 py-2.5 bg-[#086288] hover:bg-[#064b68] text-white text-sm font-bold rounded-lg transition"
                >
                    Masuk ke Akun
                </a>
                <a
                    href="{{ route('register') }}"
                    class="min-h-[44px] inline-flex items-center px-5 py-2.5 border border-[#d1d5db] hover:border-[#9ca3af] text-[#1b1b18] text-sm font-bold rounded-lg transition"
                >
                    Daftar Akun Baru
                </a>
            @endauth

            <a
                href="{{ route('cek-kelayakan.index') }}"
                class="min-h-[44px] inline-flex items-center px-5 py-2.5 border border-[#cae4ef] bg-[#f0f8fb] text-[#086288] hover:bg-[#e0f0f7] text-sm font-bold rounded-lg transition"
            >
                ✓ Cek Kelayakan Mandiri (F-02)
            </a>

            <a
                href="{{ route('pengajuan.index') }}"
                class="min-h-[44px] inline-flex items-center px-5 py-2.5 border border-[#d1d5db] hover:bg-slate-50 text-slate-700 text-sm font-bold rounded-lg transition"
            >
                ▤ Pengajuan Bantuan Air (F-03)
            </a>
        </div>

        <div class="border-t border-[#e3e3e0] pt-6 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div class="p-4 bg-[#FDFDFC] border border-[#e3e3e0] rounded-xl">
                <h2 class="font-bold text-[#1b1b18]">Perwakilan Desa</h2>
                <p class="text-[#52525b] mt-1 text-xs">Cek kelayakan kekeringan mandiri, susun draf pengajuan bantuan air bersih, dan pantau status tiket armada.</p>
            </div>
            <div class="p-4 bg-[#FDFDFC] border border-[#e3e3e0] rounded-xl">
                <h2 class="font-bold text-[#1b1b18]">Admin BPBD</h2>
                <p class="text-[#52525b] mt-1 text-xs">Kelola master data 239 desa, verifikasi pengajuan darurat, dan koordinasi distribusi tangki air.</p>
            </div>
            <div class="p-4 bg-[#FDFDFC] border border-[#e3e3e0] rounded-xl">
                <h2 class="font-bold text-[#1b1b18]">Mitra Penyalur</h2>
                <p class="text-[#52525b] mt-1 text-xs">Daftarkan armada tangki dan verifikasi penyaluran air bersih ke tandon komunal desa.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<footer class="w-full max-w-5xl mx-auto px-6 pb-8 text-xs text-[#52525b]">
    &copy; {{ date('Y') }} LinggaAir - BPBD Pemerintah Kabupaten Purbalingga.
</footer>
@endsection
