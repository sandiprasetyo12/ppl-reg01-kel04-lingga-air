@extends('layouts.app')

@section('title', 'Selamat Datang di LinggaAir - Onboarding')

@section('header')
<header class="w-full border-b border-[#F2F4F7] py-3.5 px-6 sm:px-10 flex items-center justify-between bg-white shrink-0">
    <div class="text-[14px] text-[#737373] flex items-center">
        <a href="{{ url('/') }}" class="hover:text-[#00629B] transition">Beranda</a>
        <span class="text-[#a1a8b1] mx-1">/</span>
        <span class="text-[#2b2b2b] font-medium">Onboarding</span>
    </div>

    <div class="flex items-center gap-3 text-[13px] text-[#737373]">
        <span class="hidden sm:inline">Akun: <strong class="text-[#2b2b2b] font-medium">{{ auth()->user()?->username ?? 'Pengguna' }}</strong></span>
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="text-[#ce1f31] hover:underline font-medium text-[13px]">
                Keluar
            </button>
        </form>
    </div>
</header>
@endsection

@section('content')
<div class="flex-1 flex flex-col justify-center items-center px-4 py-4 sm:py-6 bg-white text-center">
    <div class="w-full max-w-[500px] mx-auto space-y-4 sm:space-y-5">
        
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

    </div>
</div>
@endsection
