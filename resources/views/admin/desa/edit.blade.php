@extends('layouts.dashboard')

@section('title', 'Edit Data Desa ' . $desa->nama_desa . ' - LinggaAir')
@section('page-title', 'Edit Data Desa')

@section('content')
<div class="max-w-2xl space-y-6">
    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" class="text-sm text-[#52525b] flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-[#1b1b18] underline min-h-[44px] inline-flex items-center">
            Dashboard
        </a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('admin.desa.index') }}" class="hover:text-[#1b1b18] underline min-h-[44px] inline-flex items-center">
            Master Data Desa
        </a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('admin.desa.show', $desa->kode_desa) }}" class="hover:text-[#1b1b18] underline min-h-[44px] inline-flex items-center">
            {{ $desa->nama_desa }}
        </a>
        <span aria-hidden="true">/</span>
        <span class="text-[#1b1b18] font-medium">Edit</span>
    </nav>

    <div class="flex flex-wrap items-center justify-between gap-3">
        <a
            href="{{ route('admin.desa.index') }}"
            class="min-h-[44px] inline-flex items-center px-4 py-2 bg-white border border-[#d1d5db] rounded-md text-sm font-medium text-[#1b1b18] hover:bg-[#f5f5f4] transition"
        >
            Kembali ke Daftar
        </a>
        <a
            href="{{ route('admin.desa.show', $desa->kode_desa) }}"
            class="min-h-[44px] inline-flex items-center px-4 py-2 text-sm font-medium text-[#52525b] hover:text-[#1b1b18] underline"
        >
            Lihat Detail
        </a>
    </div>

    <section class="bg-white border border-[#e3e3e0] rounded-lg p-6 shadow-sm space-y-5">
        <div class="border-b border-[#e3e3e0] pb-4">
            <h2 class="text-lg font-semibold text-[#1b1b18]">
                Edit Data: Desa {{ $desa->nama_desa }}
            </h2>
            <p class="text-sm text-[#52525b] mt-1">
                Kecamatan {{ $desa->kecamatan }}, Kode Wilayah: {{ $desa->kode_desa }}
            </p>
        </div>

        @if ($errors->any())
            <div role="alert" class="p-3.5 rounded-md bg-red-50 border border-red-200 text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.desa.update', $desa->kode_desa) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="jumlah_kk_total" class="block text-sm font-medium text-[#1b1b18] mb-1">
                        Jumlah KK Total
                    </label>
                    <input
                        id="jumlah_kk_total"
                        type="number"
                        min="0"
                        name="jumlah_kk_total"
                        value="{{ old('jumlah_kk_total', $desa->jumlah_kk_total) }}"
                        class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                    >
                    @error('jumlah_kk_total')
                        <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jumlah_jiwa_total" class="block text-sm font-medium text-[#1b1b18] mb-1">
                        Jumlah Jiwa Total
                    </label>
                    <input
                        id="jumlah_jiwa_total"
                        type="number"
                        min="0"
                        name="jumlah_jiwa_total"
                        value="{{ old('jumlah_jiwa_total', $desa->jumlah_jiwa_total) }}"
                        class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                    >
                    @error('jumlah_jiwa_total')
                        <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="p-4 bg-[#FDFDFC] border border-[#e3e3e0] rounded-md space-y-3">
                <h3 class="text-sm font-medium text-[#1b1b18]">Data Kelompok Rentan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="jumlah_balita" class="block text-sm font-medium text-[#1b1b18] mb-1">
                            Balita
                        </label>
                        <input
                            id="jumlah_balita"
                            type="number"
                            min="0"
                            name="jumlah_balita"
                            value="{{ old('jumlah_balita', $desa->jumlah_balita) }}"
                            class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                        >
                        @error('jumlah_balita')
                            <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jumlah_lansia" class="block text-sm font-medium text-[#1b1b18] mb-1">
                            Lansia
                        </label>
                        <input
                            id="jumlah_lansia"
                            type="number"
                            min="0"
                            name="jumlah_lansia"
                            value="{{ old('jumlah_lansia', $desa->jumlah_lansia) }}"
                            class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                        >
                        @error('jumlah_lansia')
                            <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jumlah_disabilitas" class="block text-sm font-medium text-[#1b1b18] mb-1">
                            Disabilitas
                        </label>
                        <input
                            id="jumlah_disabilitas"
                            type="number"
                            min="0"
                            name="jumlah_disabilitas"
                            value="{{ old('jumlah_disabilitas', $desa->jumlah_disabilitas) }}"
                            class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                        >
                        @error('jumlah_disabilitas')
                            <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <label for="titik_drop_air" class="block text-sm font-medium text-[#1b1b18] mb-1">
                    Titik Drop Air Bersih
                </label>
                <input
                    id="titik_drop_air"
                    type="text"
                    name="titik_drop_air"
                    maxlength="50"
                    value="{{ old('titik_drop_air', $desa->titik_drop_air) }}"
                    placeholder="Contoh: Tandon Utama Balai Desa"
                    class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                >
                <p class="mt-1 text-xs text-[#52525b]">Maksimal 50 karakter.</p>
                @error('titik_drop_air')
                    <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="nama_pj_darurat" class="block text-sm font-medium text-[#1b1b18] mb-1">
                        Nama PJ Darurat
                    </label>
                    <input
                        id="nama_pj_darurat"
                        type="text"
                        name="nama_pj_darurat"
                        maxlength="100"
                        value="{{ old('nama_pj_darurat', $desa->nama_pj_darurat) }}"
                        placeholder="Nama penanggung jawab"
                        class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                    >
                    @error('nama_pj_darurat')
                        <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_wa_darurat" class="block text-sm font-medium text-[#1b1b18] mb-1">
                        No. WhatsApp Darurat
                    </label>
                    <input
                        id="no_wa_darurat"
                        type="tel"
                        name="no_wa_darurat"
                        maxlength="20"
                        value="{{ old('no_wa_darurat', $desa->no_wa_darurat) }}"
                        placeholder="Contoh: 0812xxxxxxxx"
                        class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                    >
                    @error('no_wa_darurat')
                        <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="p-4 bg-[#FDFDFC] border border-[#e3e3e0] rounded-md space-y-3">
                <div>
                    <h3 class="text-sm font-medium text-[#1b1b18]">Koordinat Titik Tandon (GIS)</h3>
                    <p class="text-xs text-[#52525b] mt-0.5">Rentang valid: Latitude -90 s.d. 90, Longitude -180 s.d. 180.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-[#1b1b18] mb-1">
                            Latitude
                        </label>
                        <input
                            id="latitude"
                            type="number"
                            step="any"
                            min="-90"
                            max="90"
                            name="latitude"
                            value="{{ old('latitude', $desa->latitude) }}"
                            placeholder="Contoh: -7.388611"
                            class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base font-mono text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                        >
                        @error('latitude')
                            <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="block text-sm font-medium text-[#1b1b18] mb-1">
                            Longitude
                        </label>
                        <input
                            id="longitude"
                            type="number"
                            step="any"
                            min="-180"
                            max="180"
                            name="longitude"
                            value="{{ old('longitude', $desa->longitude) }}"
                            placeholder="Contoh: 109.364167"
                            class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base font-mono text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                        >
                        @error('longitude')
                            <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-[#e3e3e0] flex flex-col-reverse sm:flex-row sm:items-center justify-end gap-3">
                <a
                    href="{{ route('admin.desa.index') }}"
                    class="min-h-[44px] inline-flex items-center justify-center px-4 py-2 bg-white border border-[#d1d5db] rounded-md text-sm font-medium text-[#1b1b18] hover:bg-[#f5f5f4] transition"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    class="min-h-[44px] inline-flex items-center justify-center px-5 py-2.5 bg-[#1b1b18] hover:bg-black text-white text-sm font-medium rounded-md transition"
                >
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </section>
</div>
@endsection