@extends('layouts.app')

@section('title', 'Lengkapi Profil Desa - Onboarding LinggaAir')

@section('header')
<header class="w-full border-b border-[#F2F4F7] py-3.5 px-6 sm:px-10 flex items-center justify-between bg-white shrink-0">
    <div class="text-[14px] text-[#737373] flex items-center">
        <a href="{{ url('/') }}" class="hover:text-[#00629B] transition">Beranda</a>
        <span class="text-[#a1a8b1] mx-1">/</span>
        <a href="{{ route('onboarding.role') }}" class="hover:text-[#00629B] transition">Onboarding</a>
        <span class="text-[#a1a8b1] mx-1">/</span>
        <span class="text-[#2b2b2b] font-medium">Profil Desa</span>
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
    <div class="w-full max-w-[620px] mx-auto space-y-4">
        
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
                    <span class="text-[10.5px] font-semibold text-[#00629B] mt-1">Profil Desa</span>
                </div>
            </div>
        </div>

        {{-- Header Teks Ringkas --}}
        <div class="text-center space-y-0.5">
            <h1 class="text-xl sm:text-2xl font-semibold text-[#101828] tracking-tight">
                Lengkapi Profil Wilayah Desa
            </h1>
            <p class="text-xs text-[#475467]">
                Tautkan desa dan data demografi untuk aktivasi simulasi SPK air bersih darurat BPBD.
            </p>
        </div>

        @if ($errors->any())
            <div role="alert" class="p-2.5 rounded-lg bg-[#fff0f0] border border-[#ffd2d2] text-xs text-[#ce1f31] space-y-0.5 text-left">
                @foreach ($errors->all() as $error)
                    <p>&bull; {{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if ($desaTersedia->isEmpty())
            <div class="p-4 border border-[#BAE3F5] rounded-xl bg-[#EBF5FB] space-y-1 text-[#00629B] text-left">
                <h2 class="text-xs font-semibold">
                    Seluruh Wilayah Desa Sudah Tertaut
                </h2>
                <p class="text-xs text-[#344054] leading-relaxed">
                    Seluruh desa pada data master saat ini sudah memiliki akun perwakilan terdaftar.
                </p>
            </div>
        @else
            <form id="profile-desa-form" action="{{ route('onboarding.profile.desa') }}" method="POST" class="space-y-3.5">
                @csrf

                {{-- Pilihan Desa --}}
                <div class="text-left space-y-1">
                    <label for="kode_desa" class="block text-xs font-semibold text-[#344054]">
                        Wilayah Desa / Kelurahan <span class="text-[#ce1f31]">*</span>
                    </label>
                    <select
                        id="kode_desa"
                        name="kode_desa"
                        required
                        @disabled($desaTertaut !== null)
                        class="w-full h-10 px-3 bg-white border border-[#D0D5DD] rounded-lg text-xs sm:text-sm text-[#101828] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]/20 transition"
                    >
                        <option value="">Pilih Desa atau Kelurahan</option>
                        @php $currentKec = ''; @endphp
                        @foreach ($desaTersedia as $d)
                            @if ($currentKec !== $d->kecamatan)
                                @if ($currentKec !== '') </optgroup> @endif
                                <optgroup label="Kecamatan {{ $d->kecamatan }}">
                                @php $currentKec = $d->kecamatan; @endphp
                            @endif
                            <option value="{{ $d->kode_desa }}" {{ old('kode_desa', $desaTertaut?->kode_desa) == $d->kode_desa ? 'selected' : '' }}>
                                {{ $d->nama_desa }} ({{ $d->kode_desa }})
                            </option>
                        @endforeach
                        @if ($currentKec !== '') </optgroup> @endif
                    </select>
                    @if ($desaTertaut !== null)
                        <input type="hidden" name="kode_desa" value="{{ $desaTertaut->kode_desa }}">
                    @endif
                    @error('kode_desa')
                        <p role="alert" class="mt-0.5 text-xs text-[#ce1f31]">{{ $message }}</p>
                    @enderror
                </div>

                {{-- KK dan Jiwa Total --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-left">
                    <div class="space-y-1">
                        <label for="jumlah_kk_total" class="block text-xs font-semibold text-[#344054]">
                            Jumlah KK Total <span class="text-[#ce1f31]">*</span>
                        </label>
                        <input
                            id="jumlah_kk_total"
                            type="number"
                            min="1"
                            name="jumlah_kk_total"
                            value="{{ old('jumlah_kk_total', $desaTertaut?->jumlah_kk_total) }}"
                            placeholder="Contoh: 450"
                            required
                            class="w-full h-10 px-3 bg-white border border-[#D0D5DD] rounded-lg text-xs sm:text-sm text-[#101828] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]/20 transition"
                        >
                        @error('jumlah_kk_total')
                            <p role="alert" class="mt-0.5 text-xs text-[#ce1f31]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="jumlah_jiwa_total" class="block text-xs font-semibold text-[#344054]">
                            Jumlah Jiwa Total <span class="text-[#ce1f31]">*</span>
                        </label>
                        <input
                            id="jumlah_jiwa_total"
                            type="number"
                            min="1"
                            name="jumlah_jiwa_total"
                            value="{{ old('jumlah_jiwa_total', $desaTertaut?->jumlah_jiwa_total) }}"
                            placeholder="Contoh: 1800"
                            required
                            class="w-full h-10 px-3 bg-white border border-[#D0D5DD] rounded-lg text-xs sm:text-sm text-[#101828] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]/20 transition"
                        >
                        @error('jumlah_jiwa_total')
                            <p role="alert" class="mt-0.5 text-xs text-[#ce1f31]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Data Kelompok Rentan (Inline Ringkas) --}}
                <div class="p-3 bg-[#F8FAFC] border border-[#EAECF0] rounded-lg space-y-2 text-left">
                    <span class="block text-[11px] font-semibold text-[#344054] uppercase tracking-wide">
                        Kelompok Rentan untuk SPK AHP-SAW (Jiwa)
                    </span>
                    <div class="grid grid-cols-3 gap-2.5">
                        <div class="space-y-0.5">
                            <label for="jumlah_balita" class="block text-[11px] text-[#475467]">
                                Balita <span class="text-[#ce1f31]">*</span>
                            </label>
                            <input
                                id="jumlah_balita"
                                type="number"
                                min="0"
                                name="jumlah_balita"
                                value="{{ old('jumlah_balita', $desaTertaut?->jumlah_balita) }}"
                                placeholder="0"
                                required
                                class="w-full h-9 px-2.5 bg-white border border-[#D0D5DD] rounded-md text-xs text-[#101828] focus:outline-none focus:border-[#00629B] transition"
                            >
                        </div>
                        <div class="space-y-0.5">
                            <label for="jumlah_lansia" class="block text-[11px] text-[#475467]">
                                Lansia <span class="text-[#ce1f31]">*</span>
                            </label>
                            <input
                                id="jumlah_lansia"
                                type="number"
                                min="0"
                                name="jumlah_lansia"
                                value="{{ old('jumlah_lansia', $desaTertaut?->jumlah_lansia) }}"
                                placeholder="0"
                                required
                                class="w-full h-9 px-2.5 bg-white border border-[#D0D5DD] rounded-md text-xs text-[#101828] focus:outline-none focus:border-[#00629B] transition"
                            >
                        </div>
                        <div class="space-y-0.5">
                            <label for="jumlah_disabilitas" class="block text-[11px] text-[#475467]">
                                Disabilitas <span class="text-[#ce1f31]">*</span>
                            </label>
                            <input
                                id="jumlah_disabilitas"
                                type="number"
                                min="0"
                                name="jumlah_disabilitas"
                                value="{{ old('jumlah_disabilitas', $desaTertaut?->jumlah_disabilitas) }}"
                                placeholder="0"
                                required
                                class="w-full h-9 px-2.5 bg-white border border-[#D0D5DD] rounded-md text-xs text-[#101828] focus:outline-none focus:border-[#00629B] transition"
                            >
                        </div>
                    </div>
                </div>

                {{-- Kontak PJ Darurat --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-left">
                    <div class="space-y-1">
                        <label for="nama_pj_darurat" class="block text-xs font-semibold text-[#344054]">
                            Nama PJ Darurat
                        </label>
                        <input
                            id="nama_pj_darurat"
                            type="text"
                            name="nama_pj_darurat"
                            maxlength="100"
                            value="{{ old('nama_pj_darurat') }}"
                            placeholder="Contoh: Bambang (Kades)"
                            class="w-full h-10 px-3 bg-white border border-[#D0D5DD] rounded-lg text-xs sm:text-sm text-[#101828] placeholder-gray-400 focus:outline-none focus:border-[#00629B] transition"
                        >
                    </div>

                    <div class="space-y-1">
                        <label for="no_wa_darurat" class="block text-xs font-semibold text-[#344054]">
                            No. WhatsApp PJ
                        </label>
                        <input
                            id="no_wa_darurat"
                            type="tel"
                            name="no_wa_darurat"
                            maxlength="20"
                            value="{{ old('no_wa_darurat') }}"
                            placeholder="Contoh: 0812xxxxxxxx"
                            class="w-full h-10 px-3 bg-white border border-[#D0D5DD] rounded-lg text-xs sm:text-sm text-[#101828] placeholder-gray-400 focus:outline-none focus:border-[#00629B] transition"
                        >
                    </div>
                </div>

                {{-- Patokan Titik Drop Air --}}
                <div class="text-left space-y-1">
                    <label for="titik_drop_air" class="block text-xs font-semibold text-[#344054]">
                        Patokan Titik Drop Tandon Air Utama
                    </label>
                    <input
                        id="titik_drop_air"
                        type="text"
                        name="titik_drop_air"
                        maxlength="50"
                        value="{{ old('titik_drop_air') }}"
                        placeholder="Contoh: Bak Penampungan Sentral RT 04 RW 02"
                        class="w-full h-10 px-3 bg-white border border-[#D0D5DD] rounded-lg text-xs sm:text-sm text-[#101828] placeholder-gray-400 focus:outline-none focus:border-[#00629B] transition"
                    >
                </div>

                {{-- Navigasi Aksi Bawah --}}
                <div class="pt-3 border-t border-[#F2F4F7] flex items-center justify-between gap-3">
                    <p class="text-xs text-[#667085]">
                        Peran: <strong class="text-[#00629B]">Desa</strong>
                    </p>
                    <button
                        type="submit"
                        id="profile-desa-submit"
                        class="min-h-[40px] px-6 rounded-lg bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white font-medium text-xs sm:text-sm inline-flex items-center justify-center transition cursor-pointer shadow-xs"
                    >
                        Simpan & Masuk Dashboard &rarr;
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection
