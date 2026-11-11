@extends('layouts.app')

@section('title', 'Pilih Peran - LinggaAir')

@section('header')
<header class="bg-white border-b border-[#e3e3e0]">
    <nav aria-label="Menu onboarding" class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
        <a href="{{ url('/') }}" class="text-base font-semibold text-[#1b1b18] min-h-[44px] inline-flex items-center">
            LinggaAir
        </a>

        <div class="flex items-center gap-4">
            <span class="hidden sm:inline text-sm text-[#52525b]">
                Akun: <strong class="font-medium text-[#1b1b18]">{{ auth()->user()?->username ?? 'Pengguna' }}</strong>
            </span>
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
    </nav>
</header>
@endsection

@section('content')
<div class="flex-1 flex items-center justify-center px-4 py-10 bg-[#F3F4F6]">
    <div class="w-full max-w-2xl bg-white border border-[#e3e3e0] rounded-lg p-6 sm:p-8 shadow-sm space-y-6">
        <div class="space-y-2">
            <p class="text-xs font-medium uppercase tracking-wider text-[#52525b]">
                Langkah 2 dari 3: Pilih Peran
            </p>
            <h1 class="text-2xl font-semibold text-[#1b1b18]">
                Pilih Peran Akun Anda
            </h1>
            <p class="text-sm sm:text-base text-[#52525b]">
                Pilihan peran ini menentukan formulir profil dan akses dashboard akun Anda.
            </p>
        </div>

        @if ($errors->any())
            <div role="alert" class="p-3.5 rounded-md bg-red-50 border border-red-200 text-sm text-red-700">
                Silakan pilih salah satu peran yang tersedia sebelum melanjutkan.
            </div>
        @endif

        <form id="role-form" action="{{ route('onboarding.role') }}" method="POST" class="space-y-6">
            @csrf

            <fieldset class="space-y-3">
                <legend class="text-sm font-medium text-[#1b1b18]">
                    Daftar peran yang tersedia <span class="text-red-600" aria-hidden="true">*</span>
                </legend>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label
                        for="role_desa"
                        class="flex items-start gap-3 p-4 border border-[#d1d5db] rounded-md bg-white hover:bg-[#FDFDFC] transition min-h-[44px]"
                    >
                        <input
                            id="role_desa"
                            type="radio"
                            name="role"
                            value="desa"
                            {{ old('role') === 'desa' ? 'checked' : '' }}
                            required
                            class="mt-1 w-4 h-4 accent-[#1b1b18] shrink-0"
                        >
                        <span class="space-y-1 text-sm">
                            <span class="block font-semibold text-[#1b1b18]">Perwakilan Desa</span>
                            <span class="block text-[#52525b] leading-relaxed">
                                Perangkat desa yang menautkan data desa dan titik drop air bersih.
                            </span>
                        </span>
                    </label>

                    <label
                        for="role_penyalur"
                        class="flex items-start gap-3 p-4 border border-[#d1d5db] rounded-md bg-white hover:bg-[#FDFDFC] transition min-h-[44px]"
                    >
                        <input
                            id="role_penyalur"
                            type="radio"
                            name="role"
                            value="penyalur"
                            {{ old('role') === 'penyalur' ? 'checked' : '' }}
                            required
                            class="mt-1 w-4 h-4 accent-[#1b1b18] shrink-0"
                        >
                        <span class="space-y-1 text-sm">
                            <span class="block font-semibold text-[#1b1b18]">Mitra Penyalur</span>
                            <span class="block text-[#52525b] leading-relaxed">
                                Petugas atau pengelola armada tangki (BPBD, PMI, atau PDAM) penyalur air.
                            </span>
                        </span>
                    </label>
                </div>
            </fieldset>

            @error('role')
                <p role="alert" class="text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror

            <div class="pt-4 border-t border-[#e3e3e0] flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-3">
                <a
                    href="{{ route('onboarding.welcome') }}"
                    class="min-h-[44px] inline-flex items-center justify-center px-4 py-2 bg-white border border-[#d1d5db] text-sm font-medium text-[#1b1b18] rounded-md hover:bg-[#f5f5f4] transition"
                >
                    Kembali
                </a>
                <button
                    type="submit"
                    id="role-submit"
                    class="min-h-[44px] inline-flex items-center justify-center px-5 py-2.5 bg-[#1b1b18] hover:bg-black text-white text-sm font-medium rounded-md transition"
                >
                    Lanjut ke Profil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('footer')
<footer class="bg-white border-t border-[#e3e3e0] py-4">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 text-xs text-[#52525b]">
        &copy; {{ date('Y') }} LinggaAir - BPBD Kabupaten Purbalingga.
    </div>
</footer>
@endsection
