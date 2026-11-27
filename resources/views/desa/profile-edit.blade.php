@extends('layouts.desa')

@section('title', 'Lengkapi Profil Desa ' . $desa->nama_desa . ' - LinggaAir')
@section('page-title', 'Profil Desa')

@section('content')
<div class="-mt-2 w-full space-y-4 pb-20 sm:-mt-3 md:pb-6">
    <div class="flex items-center justify-between">
        <a 
            href="{{ route('desa.dashboard') }}" 
            class="h-9 inline-flex items-center gap-2 px-3.5 bg-white border border-[#EAECF0] hover:bg-[#F8FAFC] active:bg-[#EAECF0] text-[#344054] text-xs font-semibold rounded-xl transition shadow-xs"
        >
            <i class="fa-solid fa-arrow-left text-xs" aria-hidden="true"></i>
            <span>Kembali ke Beranda</span>
        </a>
    </div>

    <section class="bg-white border border-[#E4E7EC] rounded-2xl p-6 sm:px-8 sm:pt-6 sm:pb-8 shadow-xs space-y-6">
        <div class="border-b border-[#EAECF0] pb-4">
            <h2 class="text-lg sm:text-xl font-extrabold text-[#101828]">
                Lengkapi Data: Desa {{ $desa->nama_desa }}
            </h2>
            <p class="text-xs sm:text-sm text-[#667085] mt-1">
                Kecamatan {{ $desa->kecamatan }} &middot; Kode Wilayah: {{ $desa->kode_desa }}
            </p>
        </div>

        @if ($errors->any())
            <div role="alert" class="p-4 rounded-xl bg-[#FEF3F2] border border-[#FECDCA] text-xs sm:text-sm text-[#B42318] space-y-1">
                <div class="font-bold flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>Terdapat kesalahan pengisian data:</span>
                </div>
                <ul class="list-disc pl-5 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('desa.profile.update') }}" method="POST" class="w-full space-y-4">
            @csrf
            @method('PUT')

            <!-- Bagian 1: Data Kependudukan -->
            <div class="space-y-3">
                <h3 class="text-xs font-bold text-[#101828] uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-users text-[#0066A1]" aria-hidden="true"></i>
                    Data Kependudukan Total
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div>
                        <label for="jumlah_kk_total" class="block text-xs font-semibold text-[#344054] mb-1">
                            Jumlah KK Total
                        </label>
                        <input 
                            type="number" 
                            name="jumlah_kk_total" 
                            id="jumlah_kk_total" 
                            value="{{ old('jumlah_kk_total', $desa->jumlah_kk_total) }}"
                            min="0"
                            class="!h-10 !px-3 !py-2 !rounded-lg w-full border border-[#D0D5DD] text-sm text-[#101828] focus:!border-[#0066A1] focus:!ring-2 focus:!ring-[#E6F2FA] focus:!outline-none transition"
                            placeholder="Contoh: 450"
                        >
                    </div>

                    <div>
                        <label for="jumlah_jiwa_total" class="block text-xs font-semibold text-[#344054] mb-1">
                            Jumlah Jiwa Total
                        </label>
                        <input 
                            type="number" 
                            name="jumlah_jiwa_total" 
                            id="jumlah_jiwa_total" 
                            value="{{ old('jumlah_jiwa_total', $desa->jumlah_jiwa_total) }}"
                            min="0"
                            class="!h-10 !px-3 !py-2 !rounded-lg w-full border border-[#D0D5DD] text-sm text-[#101828] focus:!border-[#0066A1] focus:!ring-2 focus:!ring-[#E6F2FA] focus:!outline-none transition"
                            placeholder="Contoh: 1800"
                        >
                    </div>
                </div>
            </div>

            <!-- Bagian 2: Kelompok Rentan -->
            <div class="space-y-3 pt-2 border-t border-[#EAECF0]">
                <h3 class="text-xs font-bold text-[#101828] uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-people-group text-[#0066A1]" aria-hidden="true"></i>
                    Kelompok Rentan (Prioritas Darurat)
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <div>
                        <label for="jumlah_balita" class="block text-xs font-semibold text-[#344054] mb-1">
                            <i class="fa-solid fa-child text-[#0066A1] mr-1.5" aria-hidden="true"></i>Jumlah Balita
                        </label>
                        <input 
                            type="number" 
                            name="jumlah_balita" 
                            id="jumlah_balita" 
                            value="{{ old('jumlah_balita', $desa->jumlah_balita) }}"
                            min="0"
                            class="!h-10 !px-3 !py-2 !rounded-lg w-full border border-[#D0D5DD] text-sm text-[#101828] focus:!border-[#0066A1] focus:!ring-2 focus:!ring-[#E6F2FA] focus:!outline-none transition"
                            placeholder="0"
                        >
                    </div>

                    <div>
                        <label for="jumlah_lansia" class="block text-xs font-semibold text-[#344054] mb-1">
                            <i class="fa-solid fa-person-cane text-[#0066A1] mr-1.5" aria-hidden="true"></i>Jumlah Lansia
                        </label>
                        <input 
                            type="number" 
                            name="jumlah_lansia" 
                            id="jumlah_lansia" 
                            value="{{ old('jumlah_lansia', $desa->jumlah_lansia) }}"
                            min="0"
                            class="!h-10 !px-3 !py-2 !rounded-lg w-full border border-[#D0D5DD] text-sm text-[#101828] focus:!border-[#0066A1] focus:!ring-2 focus:!ring-[#E6F2FA] focus:!outline-none transition"
                            placeholder="0"
                        >
                    </div>

                    <div>
                        <label for="jumlah_disabilitas" class="block text-xs font-semibold text-[#344054] mb-1">
                            <i class="fa-solid fa-wheelchair text-[#0066A1] mr-1.5" aria-hidden="true"></i>Jumlah Disabilitas
                        </label>
                        <input 
                            type="number" 
                            name="jumlah_disabilitas" 
                            id="jumlah_disabilitas" 
                            value="{{ old('jumlah_disabilitas', $desa->jumlah_disabilitas) }}"
                            min="0"
                            class="!h-10 !px-3 !py-2 !rounded-lg w-full border border-[#D0D5DD] text-sm text-[#101828] focus:!border-[#0066A1] focus:!ring-2 focus:!ring-[#E6F2FA] focus:!outline-none transition"
                            placeholder="0"
                        >
                    </div>
                </div>
            </div>

            <!-- Bagian 3: Titik Drop Air & Titik Koordinat -->
            <div class="space-y-3 pt-2 border-t border-[#EAECF0]">
                <h3 class="text-xs font-bold text-[#101828] uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-location-dot text-[#0066A1]" aria-hidden="true"></i>
                    Titik Drop & Koordinat Tandon Air
                </h3>
                <div>
                    <label for="titik_drop_air" class="block text-xs font-semibold text-[#344054] mb-1">
                        Nama / Lokasi Titik Drop Air (Tandon Utama)
                    </label>
                    <input 
                        type="text" 
                        name="titik_drop_air" 
                        id="titik_drop_air" 
                        value="{{ old('titik_drop_air', $desa->titik_drop_air) }}"
                        class="!h-10 !px-3 !py-2 !rounded-lg w-full border border-[#D0D5DD] text-sm text-[#101828] focus:!border-[#0066A1] focus:!ring-2 focus:!ring-[#E6F2FA] focus:!outline-none transition"
                        placeholder="Contoh: Depan Balai Desa / Lapangan RT 02"
                    >
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="latitude" class="block text-xs font-semibold text-[#344054] mb-1">
                            Latitude (GPS)
                        </label>
                        <input 
                            type="number" 
                            name="latitude" 
                            id="latitude" 
                            value="{{ old('latitude', $desa->latitude) }}"
                            min="-90"
                            max="90"
                            step="any"
                            class="!h-10 !px-3 !py-2 !rounded-lg w-full border border-[#D0D5DD] text-sm text-[#101828] focus:!border-[#0066A1] focus:!ring-2 focus:!ring-[#E6F2FA] focus:!outline-none transition font-mono text-xs"
                            placeholder="-7.3920"
                        >
                    </div>

                    <div>
                        <label for="longitude" class="block text-xs font-semibold text-[#344054] mb-1">
                            Longitude (GPS)
                        </label>
                        <input 
                            type="number" 
                            name="longitude" 
                            id="longitude" 
                            value="{{ old('longitude', $desa->longitude) }}"
                            min="-180"
                            max="180"
                            step="any"
                            class="!h-10 !px-3 !py-2 !rounded-lg w-full border border-[#D0D5DD] text-sm text-[#101828] focus:!border-[#0066A1] focus:!ring-2 focus:!ring-[#E6F2FA] focus:!outline-none transition font-mono text-xs"
                            placeholder="109.3638"
                        >
                    </div>
                </div>
            </div>

            <!-- Bagian 4: Kontak Perwakilan Desa -->
            <div class="space-y-4 pt-2 border-t border-[#EAECF0]">
                <h3 class="text-xs font-bold text-[#101828] uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-address-card text-[#0066A1]" aria-hidden="true"></i>
                    Kontak Perwakilan Desa
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_pj_darurat" class="block text-xs font-semibold text-[#344054] mb-1">
                            Nama Perwakilan Desa
                        </label>
                        <input 
                            type="text" 
                            name="nama_pj_darurat" 
                            id="nama_pj_darurat" 
                            value="{{ old('nama_pj_darurat', $desa->nama_pj_darurat) }}"
                            class="min-h-[48px] w-full px-3.5 py-2.5 rounded-xl border border-[#D0D5DD] text-sm text-[#101828] focus:border-[#0066A1] focus:ring-2 focus:ring-[#E6F2FA] outline-none transition"
                            placeholder="Nama Kepala Desa / Kadus / Relawan"
                        >
                    </div>

                    <div>
                        <label for="no_wa_darurat" class="block text-xs font-semibold text-[#344054] mb-1">
                            Nomor WhatsApp Perwakilan Desa
                        </label>
                        <input 
                            type="text" 
                            name="no_wa_darurat" 
                            id="no_wa_darurat" 
                            value="{{ old('no_wa_darurat', $desa->no_wa_darurat) }}"
                            class="min-h-[48px] w-full px-3.5 py-2.5 rounded-xl border border-[#D0D5DD] text-sm text-[#101828] focus:border-[#0066A1] focus:ring-2 focus:ring-[#E6F2FA] outline-none transition"
                            placeholder="Contoh: 081234567890"
                        >
                    </div>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="pt-4 border-t border-[#EAECF0] flex items-center justify-end gap-3">
                <a 
                    href="{{ route('desa.dashboard') }}" 
                    class="min-h-[48px] inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-[#D0D5DD] text-[#344054] text-xs font-bold hover:bg-[#F2F4F7] transition"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="min-h-[48px] inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#0066A1] hover:bg-[#004F7D] active:bg-[#003B5C] text-white text-xs font-bold rounded-xl shadow-xs transition"
                >
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Simpan Perubahan Profil</span>
                </button>
            </div>
        </form>
    </section>
</div>

<!-- Mobile Bottom Navigation Bar -->
@include('desa.components.mobile-nav')
@endsection
