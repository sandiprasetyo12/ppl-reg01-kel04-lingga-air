@extends('layouts.app')

@section('title', 'Lengkapi Profil Mitra Penyalur - Onboarding LinggaAir')

@section('header')
<header class="w-full border-b border-[#F2F4F7] py-3.5 px-6 sm:px-10 flex items-center justify-between bg-white shrink-0">
    <div class="text-[14px] text-[#737373] flex items-center">
        <a href="{{ url('/') }}" class="hover:text-[#00629B] transition">Beranda</a>
        <span class="text-[#a1a8b1] mx-1">/</span>
        <a href="{{ route('onboarding.role') }}" class="hover:text-[#00629B] transition">Onboarding</a>
        <span class="text-[#a1a8b1] mx-1">/</span>
        <span class="text-[#2b2b2b] font-medium">Profil Mitra Penyalur</span>
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
<div class="flex-1 flex flex-col justify-center items-center px-4 py-4 sm:py-6 bg-white">
    <div class="w-full max-w-[540px] mx-auto space-y-4">
        
        {{-- Visual Stepper Ringkas --}}
        <div class="max-w-xs mx-auto">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-4 right-4 top-3.5 h-0.5 bg-[#00629B] -z-0"></div>
                
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
                    <div class="w-7 h-7 rounded-full bg-[#00629B] text-white text-[11px] font-semibold flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-[10.5px] font-medium text-[#101828] mt-1">Pilih Peran</span>
                </div>

                {{-- Step 3 --}}
                <div class="flex flex-col items-center relative z-10 bg-white px-1.5">
                    <div class="w-7 h-7 rounded-full bg-[#00629B] text-white text-[11px] font-semibold flex items-center justify-center ring-4 ring-[#EBF5FB]">
                        3
                    </div>
                    <span class="text-[10.5px] font-semibold text-[#00629B] mt-1">Profil Armada</span>
                </div>
            </div>
        </div>

        {{-- Header Teks Ringkas --}}
        <div class="text-center space-y-0.5">
            <h1 class="text-xl sm:text-2xl font-semibold text-[#101828] tracking-tight">
                Lengkapi Profil Armada Penyalur
            </h1>
            <p class="text-xs text-[#475467]">
                Masukkan lembaga penyalur, kapasitas tangki, dan kontak operasional pengiriman.
            </p>
        </div>

        @if ($errors->any())
            <div role="alert" class="p-2.5 rounded-lg bg-[#fff0f0] border border-[#ffd2d2] text-xs text-[#ce1f31] space-y-0.5 text-left">
                @foreach ($errors->all() as $error)
                    <p>&bull; {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form id="profile-penyalur-form" action="{{ route('onboarding.profile.penyalur') }}" method="POST" class="space-y-3.5">
            @csrf

            <div class="text-left space-y-1">
                <label for="nama_penyalur" class="block text-xs font-semibold text-[#344054]">
                    Nama Lembaga / Mitra Penyalur <span class="text-[#ce1f31]">*</span>
                </label>
                <input
                    id="nama_penyalur"
                    type="text"
                    name="nama_penyalur"
                    maxlength="100"
                    value="{{ old('nama_penyalur') }}"
                    required
                    autofocus
                    placeholder="Contoh: PMI Cabang Purbalingga / PDAM Tirta Perwira"
                    class="w-full h-10 px-3 bg-white border border-[#D0D5DD] rounded-lg text-xs sm:text-sm text-[#101828] placeholder-gray-400 focus:outline-none focus:border-[#00629B] transition"
                >
                @error('nama_penyalur')
                    <p role="alert" class="mt-0.5 text-xs text-[#ce1f31]">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-left space-y-1">
                <label for="kapasitas_tangki_liter" class="block text-xs font-semibold text-[#344054]">
                    Kapasitas Tangki Armada (Liter) <span class="text-[#ce1f31]">*</span>
                </label>
                <input
                    id="kapasitas_tangki_liter"
                    type="number"
                    min="100"
                    step="100"
                    name="kapasitas_tangki_liter"
                    value="{{ old('kapasitas_tangki_liter') }}"
                    placeholder="Contoh: 5000"
                    required
                    class="w-full h-10 px-3 bg-white border border-[#D0D5DD] rounded-lg text-xs sm:text-sm text-[#101828] placeholder-gray-400 focus:outline-none focus:border-[#00629B] transition"
                >
                <p class="text-[11px] text-[#667085]">Kapasitas armada pengiriman (minimal 100 liter).</p>
                @error('kapasitas_tangki_liter')
                    <p role="alert" class="mt-0.5 text-xs text-[#ce1f31]">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-left space-y-1">
                <label for="no_telepon" class="block text-xs font-semibold text-[#344054]">
                    No. Telepon / WhatsApp Operasional <span class="text-[#ce1f31]">*</span>
                </label>
                <input
                    id="no_telepon"
                    type="tel"
                    name="no_telepon"
                    maxlength="20"
                    value="{{ old('no_telepon') }}"
                    required
                    placeholder="Contoh: 081234567890"
                    class="w-full h-10 px-3 bg-white border border-[#D0D5DD] rounded-lg text-xs sm:text-sm text-[#101828] placeholder-gray-400 focus:outline-none focus:border-[#00629B] transition"
                >
                @error('no_telepon')
                    <p role="alert" class="mt-0.5 text-xs text-[#ce1f31]">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-3 border-t border-[#F2F4F7] flex items-center justify-between gap-3">
                <p class="text-xs text-[#667085]">
                    Peran: <strong class="text-[#00629B]">Penyalur</strong>
                </p>
                <button
                    type="submit"
                    id="profile-penyalur-submit"
                    class="min-h-[40px] px-6 rounded-lg bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white font-medium text-xs sm:text-sm inline-flex items-center justify-center transition cursor-pointer shadow-xs"
                >
                    Simpan & Masuk Dashboard &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
