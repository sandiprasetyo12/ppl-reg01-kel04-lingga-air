@extends('layouts.app')

@section('title', 'Pilih Peran - Lingga Air')

@section('header')
<header class="bg-white border-b border-[#EAECF0] sticky top-0 z-30 shadow-xs">
    <nav aria-label="Menu onboarding" class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
        <a href="{{ url('/') }}" class="min-h-[44px] inline-flex items-center group">
            <img 
                src="{{ asset('images/logo-lingga.png') }}" 
                alt="Lingga Air" 
                class="h-9 sm:h-11 w-auto object-contain transition-transform group-hover:scale-105"
                onerror="this.onerror=null; this.src='https://placehold.co/180x60/00629B/FFFFFF?text=Lingga+Air';"
            >
        </a>

        <div class="flex items-center gap-4">
            <span class="hidden sm:inline text-xs text-[#667085]">
                Akun: <strong class="font-bold text-[#101828]">{{ auth()->user()?->username ?? 'Pengguna' }}</strong>
            </span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button
                    type="submit"
                    class="min-h-[38px] inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold text-[#B42318] bg-[#FEF3F2] border border-[#FECDCA] rounded-xl hover:bg-red-100 transition shadow-xs"
                >
                    <i class="fa-solid fa-arrow-right-from-bracket text-[11px]"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </nav>
</header>
@endsection

@section('content')
<div class="flex-1 flex items-center justify-center px-4 py-12 bg-[#F8FAFC]">
    <div class="w-full max-w-2xl bg-white border border-[#E4E7EC] rounded-2xl p-6 sm:p-8 shadow-xs space-y-6">
        <div class="space-y-3">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#EBF5FB] text-[#00629B] border border-[#BAE3F5]">
                    Langkah 2 dari 3: Pilih Peran
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#101828]">
                Pilih Peran Akun Anda
            </h1>
            <p class="text-sm text-[#475467]">
                Pilihan peran ini menentukan formulir profil dan akses modul operasional dashboard Anda.
            </p>
        </div>

        @if ($errors->any())
            <div role="alert" class="p-3.5 rounded-xl bg-[#FEF3F2] border border-[#FECDCA] text-xs font-semibold text-[#B42318] flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-sm"></i>
                <span>Silakan pilih salah satu peran yang tersedia sebelum melanjutkan.</span>
            </div>
        @endif

        <form id="role-form" action="{{ route('onboarding.role') }}" method="POST" class="space-y-6">
            @csrf

            <fieldset class="space-y-3">
                <legend class="text-xs font-bold uppercase tracking-wider text-[#667085] mb-2">
                    Daftar peran yang tersedia <span class="text-[#B42318]" aria-hidden="true">*</span>
                </legend>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Opsi 1: Perwakilan Desa -->
                    <label
                        for="role_desa"
                        class="relative flex items-start gap-3.5 p-4 sm:p-5 border border-[#D0D5DD] rounded-2xl bg-white hover:border-[#00629B] hover:bg-[#EBF5FB]/30 transition cursor-pointer group shadow-xs has-[:checked]:border-[#00629B] has-[:checked]:bg-[#EBF5FB]/40 has-[:checked]:ring-2 has-[:checked]:ring-[#00629B]"
                    >
                        <input
                            id="role_desa"
                            type="radio"
                            name="role"
                            value="desa"
                            {{ old('role') === 'desa' ? 'checked' : '' }}
                            required
                            class="mt-1 w-4 h-4 accent-[#00629B] shrink-0"
                        >
                        <span class="space-y-1.5 text-xs">
                            <span class="flex items-center gap-2 font-bold text-sm text-[#101828] group-hover:text-[#00629B] transition">
                                <i class="fa-solid fa-tree-city text-[#00629B]"></i>
                                Perwakilan Desa
                            </span>
                            <span class="block text-[#475467] leading-relaxed">
                                Perangkat atau perwakilan desa yang mengajukan bantuan air bersih, mengecek kelayakan, dan menentukan titik tandon desa.
                            </span>
                        </span>
                    </label>

                    <!-- Opsi 2: Mitra Penyalur -->
                    <label
                        for="role_penyalur"
                        class="relative flex items-start gap-3.5 p-4 sm:p-5 border border-[#D0D5DD] rounded-2xl bg-white hover:border-[#00629B] hover:bg-[#EBF5FB]/30 transition cursor-pointer group shadow-xs has-[:checked]:border-[#00629B] has-[:checked]:bg-[#EBF5FB]/40 has-[:checked]:ring-2 has-[:checked]:ring-[#00629B]"
                    >
                        <input
                            id="role_penyalur"
                            type="radio"
                            name="role"
                            value="penyalur"
                            {{ old('role') === 'penyalur' ? 'checked' : '' }}
                            required
                            class="mt-1 w-4 h-4 accent-[#00629B] shrink-0"
                        >
                        <span class="space-y-1.5 text-xs">
                            <span class="flex items-center gap-2 font-bold text-sm text-[#101828] group-hover:text-[#00629B] transition">
                                <i class="fa-solid fa-truck-droplet text-[#00629B]"></i>
                                Mitra Penyalur
                            </span>
                            <span class="block text-[#475467] leading-relaxed">
                                Petugas atau pengelola armada tangki bantuan (PMI, PDAM, atau BPBD) yang mendistribusikan pasokan air bersih.
                            </span>
                        </span>
                    </label>
                </div>
            </fieldset>

            @error('role')
                <p role="alert" class="text-xs text-[#B42318] font-medium">{{ $message }}</p>
            @enderror

            <div class="pt-4 border-t border-[#EAECF0] flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-3">
                <a
                    href="{{ route('onboarding.welcome') }}"
                    class="min-h-[42px] inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-[#D0D5DD] text-xs font-semibold text-[#344054] rounded-xl hover:bg-[#F8FAFC] transition shadow-xs"
                >
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>Kembali</span>
                </a>
                <button
                    type="submit"
                    id="role-submit"
                    class="min-h-[42px] inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white text-xs font-semibold rounded-xl transition shadow-xs"
                >
                    <span>Lanjut ke Formulir Profil</span>
                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('footer')
<footer class="bg-white border-t border-[#EAECF0] py-4">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 text-xs text-[#667085] flex flex-wrap items-center justify-between gap-2">
        <span>&copy; {{ date('Y') }} Lingga Air - BPBD Kabupaten Purbalingga.</span>
        <span>Sistem Distribusi Bantuan Air Bersih Darurat</span>
    </div>
</footer>
@endsection
