@extends('layouts.app')

@section('title', 'Pilih Peran Akun - Onboarding LinggaAir')

@section('header')
<header class="w-full border-b border-[#EAECF0] py-3.5 px-6 sm:px-10 flex items-center justify-between bg-white shrink-0">
    <a href="{{ route('onboarding.welcome') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#475467] hover:text-[#00629B] transition py-1 px-2.5 rounded-lg hover:bg-[#F2F4F7]">
        <svg class="w-3.5 h-3.5 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        <span>Kembali ke Pengenalan</span>
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
<div class="flex-1 flex flex-col justify-center items-center px-4 py-8 bg-[#F8FAFC]">
    <div class="w-full max-w-[580px] mx-auto">

        {{-- Card --}}
        <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-sm p-8 space-y-5">
        
        {{-- Visual Stepper Ringkas --}}
        <div class="max-w-xs mx-auto">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-4 right-4 top-3.5 h-0.5 bg-[#EAECF0] -z-0"></div>
                <div class="absolute left-4 top-3.5 h-0.5 bg-[#00629B] transition-all -z-0" style="width: 50%;"></div>
                
                {{-- Step 1 --}}
                <div class="flex flex-col items-center relative z-10 bg-white px-1.5">
                    <div class="w-7 h-7 rounded-full bg-[#00629B] text-white text-[11px] font-semibold flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-[10.5px] font-medium text-[#101828] mt-1">Pengenalan</span>
                </div>

                {{-- Step 2 --}}
                <div class="flex flex-col items-center relative z-10 bg-white px-1.5">
                    <div class="w-7 h-7 rounded-full bg-[#00629B] text-white text-[11px] font-semibold flex items-center justify-center ring-4 ring-[#EBF5FB]">
                        2
                    </div>
                    <span class="text-[10.5px] font-semibold text-[#00629B] mt-1">Pilih Peran</span>
                </div>

                {{-- Step 3 --}}
                <div class="flex flex-col items-center relative z-10 bg-white px-1.5">
                    <div class="w-7 h-7 rounded-full bg-[#F2F4F7] text-[#667085] text-[11px] font-semibold flex items-center justify-center border border-[#EAECF0]">
                        3
                    </div>
                    <span class="text-[10.5px] font-normal text-[#667085] mt-1">Profil</span>
                </div>
            </div>
        </div>

        {{-- Header Teks --}}
        <div class="text-center space-y-1">
            <h1 class="text-xl sm:text-2xl font-semibold text-[#101828] tracking-tight">
                Pilih Peran Akun Anda
            </h1>
            <p class="text-xs text-[#475467] leading-relaxed">
                Tentukan peran akun Anda untuk menyesuaikan formulir profil dan akses layanan.
            </p>
        </div>

        @if ($errors->any())
            <div role="alert" class="p-2.5 rounded-md bg-[#fff0f0] border border-[#ffd2d2] text-xs text-[#ce1f31] text-center">
                Silakan pilih salah satu peran yang tersedia sebelum melanjutkan.
            </div>
        @endif

        <form id="role-form" action="{{ route('onboarding.role') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                {{-- Opsi 1: Perwakilan Desa --}}
                <label
                    for="role_desa"
                    class="relative flex flex-col justify-between p-4 border border-[#D0D5DD] rounded-xl bg-white hover:border-[#00629B] hover:bg-[#F8FAFC] transition cursor-pointer group has-[:checked]:border-[#00629B] has-[:checked]:ring-2 has-[:checked]:ring-[#00629B]/20 has-[:checked]:bg-[#EBF5FB]/30"
                >
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="w-8 h-8 rounded-lg bg-[#EBF5FB] text-[#00629B] flex items-center justify-center text-xs font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </span>
                            <input
                                id="role_desa"
                                type="radio"
                                name="role"
                                value="desa"
                                {{ old('role') === 'desa' ? 'checked' : '' }}
                                required
                                class="w-4 h-4 text-[#00629B] accent-[#00629B] cursor-pointer"
                            >
                        </div>

                        <div class="space-y-0.5 text-left">
                            <h3 class="text-sm font-semibold text-[#101828] group-hover:text-[#00629B] transition">
                                Perwakilan Desa
                            </h3>
                            <p class="text-[11.5px] text-[#667085] leading-relaxed">
                                Aparatur desa yang mengajukan bantuan air darurat, cek kelayakan SPK, dan titik tandon air.
                            </p>
                        </div>
                    </div>
                </label>

                {{-- Opsi 2: Mitra Penyalur --}}
                <label
                    for="role_penyalur"
                    class="relative flex flex-col justify-between p-4 border border-[#D0D5DD] rounded-xl bg-white hover:border-[#00629B] hover:bg-[#F8FAFC] transition cursor-pointer group has-[:checked]:border-[#00629B] has-[:checked]:ring-2 has-[:checked]:ring-[#00629B]/20 has-[:checked]:bg-[#EBF5FB]/30"
                >
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="w-8 h-8 rounded-lg bg-[#EBF5FB] text-[#00629B] flex items-center justify-center text-xs font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                </svg>
                            </span>
                            <input
                                id="role_penyalur"
                                type="radio"
                                name="role"
                                value="penyalur"
                                {{ old('role') === 'penyalur' ? 'checked' : '' }}
                                required
                                class="w-4 h-4 text-[#00629B] accent-[#00629B] cursor-pointer"
                            >
                        </div>

                        <div class="space-y-0.5 text-left">
                            <h3 class="text-sm font-semibold text-[#101828] group-hover:text-[#00629B] transition">
                                Mitra Penyalur
                            </h3>
                            <p class="text-[11.5px] text-[#667085] leading-relaxed">
                                Petugas/pengelola armada tangki air (PMI, PDAM, atau BPBD) yang mendistribusikan air bersih.
                            </p>
                        </div>
                    </div>
                </label>
            </div>

            @error('role')
                <p role="alert" class="text-xs text-[#ce1f31] font-medium text-center">{{ $message }}</p>
            @enderror

            {{-- Tombol Navigasi --}}
            <div class="pt-3 border-t border-[#F2F4F7] flex items-center justify-between gap-3">
                <a
                    href="{{ route('onboarding.welcome') }}"
                    class="min-h-[40px] px-4 rounded-lg bg-white border border-[#D0D5DD] text-[#344054] hover:bg-[#F8FAFC] font-medium text-xs sm:text-sm inline-flex items-center justify-center transition"
                >
                    &larr; Kembali
                </a>
                <button
                    type="submit"
                    id="role-submit"
                    class="min-h-[40px] px-6 rounded-lg bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white font-medium text-xs sm:text-sm inline-flex items-center justify-center transition cursor-pointer shadow-xs"
                >
                    Lanjut ke Profil &rarr;
                </button>
            </div>
        </form>

        </div>{{-- end card --}}
    </div>{{-- end max-w --}}
</div>
@endsection
