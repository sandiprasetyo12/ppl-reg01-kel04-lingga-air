@extends('layouts.app')

@section('title', 'Lengkapi Profil Desa - Onboarding LinggaAir')

@section('header')
<header class="w-full border-b border-[#EAECF0] py-3.5 px-6 sm:px-10 flex items-center justify-between bg-white shrink-0">
    <a href="{{ route('onboarding.role') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#475467] hover:text-[#00629B] transition py-1 px-2.5 rounded-lg hover:bg-[#F2F4F7]">
        <svg class="w-3.5 h-3.5 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        <span>Kembali ke Pilih Peran</span>
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
    <div class="w-full max-w-[620px] mx-auto">
        {{-- Card putih --}}
        <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-sm p-6 sm:p-8 space-y-5">
        
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

                @php
                    $selectedKodeDesa = (string) old('kode_desa', $desaTertaut?->kode_desa ?? '');
                    $selectedDesaObj = $desaTersedia->firstWhere('kode_desa', $selectedKodeDesa);
                    $selectedKecamatan = (string) old('kecamatan', $selectedDesaObj?->kecamatan ?? ($desaTertaut?->kecamatan ?? ''));
                    $kecamatanList = $kecamatanList ?? $desaTersedia->pluck('kecamatan')->unique()->filter()->sort()->values();
                    $desaListJson = $desaTersedia->map(fn($d) => [
                        'kode' => (string) $d->kode_desa,
                        'nama' => (string) $d->nama_desa,
                        'kecamatan' => (string) $d->kecamatan,
                    ])->values();
                @endphp

                {{-- Pilihan Wilayah: Kecamatan & Desa (Filter Bertingkat) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-left">
                    {{-- Dropdown Kecamatan --}}
                    <div class="space-y-1">
                        <label for="select_kecamatan" class="block text-xs font-semibold text-[#344054]">
                            Kecamatan <span class="text-[#ce1f31]">*</span>
                        </label>
                        <select
                            id="select_kecamatan"
                            name="kecamatan"
                            required
                            @disabled($desaTertaut !== null)
                            class="w-full h-10 px-3 bg-white border border-[#D0D5DD] rounded-lg text-xs sm:text-sm text-[#101828] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]/20 transition disabled:bg-[#F2F4F7] disabled:text-[#667085] disabled:cursor-not-allowed disabled:border-[#E4E7EC]"
                        >
                            <option value="">Pilih Kecamatan</option>
                            @foreach ($kecamatanList as $kec)
                                <option value="{{ $kec }}" {{ $selectedKecamatan === (string) $kec ? 'selected' : '' }}>
                                    {{ $kec }}
                                </option>
                            @endforeach
                        </select>
                        @error('kecamatan')
                            <p role="alert" class="mt-0.5 text-xs text-[#ce1f31]">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Dropdown Desa --}}
                    <div class="space-y-1">
                        <label for="kode_desa" class="block text-xs font-semibold text-[#344054]">
                            Wilayah Desa / Kelurahan <span class="text-[#ce1f31]">*</span>
                        </label>
                        <select
                            id="kode_desa"
                            name="kode_desa"
                            required
                            @disabled($desaTertaut !== null || empty($selectedKecamatan))
                            class="w-full h-10 px-3 bg-white border border-[#D0D5DD] rounded-lg text-xs sm:text-sm text-[#101828] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]/20 transition disabled:bg-[#F2F4F7] disabled:text-[#667085] disabled:cursor-not-allowed disabled:border-[#E4E7EC]"
                        >
                            @if (empty($selectedKecamatan))
                                <option value="">Pilih Kecamatan terlebih dahulu</option>
                            @else
                                <option value="">Pilih Desa atau Kelurahan</option>
                                @foreach ($desaTersedia->where('kecamatan', $selectedKecamatan) as $d)
                                    <option value="{{ $d->kode_desa }}" {{ $selectedKodeDesa === (string) $d->kode_desa ? 'selected' : '' }}>
                                        {{ $d->nama_desa }} ({{ $d->kode_desa }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @if ($desaTertaut !== null)
                            <input type="hidden" name="kode_desa" value="{{ $desaTertaut->kode_desa }}">
                        @endif
                        @error('kode_desa')
                            <p role="alert" class="mt-0.5 text-xs text-[#ce1f31]">{{ $message }}</p>
                        @enderror
                    </div>
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

                {{-- Kontak Perwakilan Desa --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-left">
                    <div class="space-y-1">
                        <label for="nama_pj_darurat" class="block text-xs font-semibold text-[#344054]">
                            Nama Perwakilan Desa
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
                            No. WhatsApp Perwakilan Desa
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
        </div>{{-- end card --}}
    </div>{{-- end max-w --}}
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const kecamatanSelect = document.getElementById('select_kecamatan');
    const desaSelect = document.getElementById('kode_desa');
    if (!kecamatanSelect || !desaSelect) return;

    // Master data desa yang tersedia untuk filter client-side
    const desaList = @json($desaListJson);

    const isLocked = {{ $desaTertaut !== null ? 'true' : 'false' }};
    if (isLocked) return;

    function renderDesaOptions(selectedKec, targetKode = '') {
        desaSelect.innerHTML = '';

        if (!selectedKec) {
            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = 'Pilih Kecamatan terlebih dahulu';
            desaSelect.appendChild(defaultOpt);
            desaSelect.disabled = true;
            return;
        }

        desaSelect.disabled = false;
        const defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.textContent = 'Pilih Desa atau Kelurahan';
        desaSelect.appendChild(defaultOpt);

        const filtered = desaList.filter(function (item) {
            return item.kecamatan === selectedKec;
        });

        filtered.forEach(function (item) {
            const opt = document.createElement('option');
            opt.value = item.kode;
            opt.textContent = item.nama + ' (' + item.kode + ')';
            if (targetKode && item.kode === targetKode) {
                opt.selected = true;
            }
            desaSelect.appendChild(opt);
        });
    }

    kecamatanSelect.addEventListener('change', function () {
        renderDesaOptions(this.value, '');
    });
});
</script>
@endpush
