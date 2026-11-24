@extends('layouts.app')

@section('title', 'Selamat Datang di LinggaAir - Onboarding')

@section('header')
<header class="w-full border-b border-[#EAECF0] py-3.5 px-6 sm:px-10 flex items-center justify-between bg-white shrink-0">
    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#475467] hover:text-[#00629B] transition py-1 px-2.5 rounded-lg hover:bg-[#F2F4F7]">
        <svg class="w-3.5 h-3.5 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        <span>Kembali ke Beranda</span>
    </a>
    <div class="flex items-center gap-2.5">
        {{-- Badge Profil Akun --}}
        <div class="hidden sm:flex items-center gap-2 pl-1 pr-2.5 py-1 bg-[#F8FAFC] border border-[#EAECF0] rounded-full text-xs">
            <span class="w-5 h-5 rounded-full bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] font-bold text-[10px] flex items-center justify-center">
                {{ strtoupper(substr(auth()->user()?->username ?? 'P', 0, 1)) }}
            </span>
            <span class="font-medium text-[#344054] max-w-[130px] truncate">
                {{ auth()->user()?->username ?? 'Pengguna' }}
            </span>
        </div>

        {{-- Tombol Keluar Ringkas --}}
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 h-7 px-2.5 bg-white border border-[#FECDCA] hover:bg-[#FEF3F2] active:bg-[#FEE4E2] text-[#B42318] text-[11px] font-semibold rounded-lg transition shadow-xs cursor-pointer" title="Keluar dari akun">
                <svg class="w-3 h-3 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                </svg>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</header>
@endsection

@section('content')
<div class="flex-1 flex flex-col justify-center items-center px-4 py-10 bg-[#F8FAFC] text-center">
    <div class="w-full max-w-[460px] mx-auto space-y-4 sm:space-y-5">

        {{-- Card --}}
        <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-sm p-8 space-y-5 text-center">
        
        {{-- Ilustrasi Sambutan Onboarding --}}
        <div class="flex justify-center">
            <img 
                src="{{ asset('images/onboarding-welcome.svg') }}" 
                alt="Ilustrasi Onboarding LinggaAir" 
                class="w-52 sm:w-60 h-auto max-h-[160px] object-contain select-none"
            >
        </div>

        {{-- Judul & Deskripsi --}}
        <div class="space-y-1.5">
            <h1 class="text-2xl sm:text-3xl font-semibold text-[#101828] tracking-tight leading-tight">
                Selamat Datang di Platform LinggaAir!
            </h1>
            <p class="text-xs sm:text-sm text-[#475467] leading-relaxed max-w-md mx-auto font-normal">
                Sebelum memulai, kami akan menanyakan beberapa informasi singkat untuk menyesuaikan akses layanan distribusi bantuan air bersih bagi wilayah Anda.
            </p>
        </div>

        {{-- Tombol Lanjutkan Terpusat (Bebas Scroll) --}}
        <div class="pt-2">
            <a
                href="{{ route('onboarding.role') }}"
                class="min-h-[42px] px-9 py-2.5 rounded-lg bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white font-medium text-sm inline-flex items-center justify-center transition shadow-xs"
            >
                Lanjutkan
            </a>
        </div>

    </div>{{-- end card --}}
    </div>{{-- end max-w --}}
</div>
@endsection
