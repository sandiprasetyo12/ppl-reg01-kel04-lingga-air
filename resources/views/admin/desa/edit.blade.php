@extends('layouts.dashboard')

@section('title', 'Edit Data Desa ' . $desa->nama_desa . ' - LinggaAir')
@section('page-title', 'Edit Data Desa')

@section('content')
<div class="w-full space-y-3">
    <div class="flex flex-wrap items-center justify-between gap-2">
        <a
            href="{{ route('admin.desa.index') }}"
            class="h-8 inline-flex items-center gap-1.5 px-3 bg-white border border-[#D0D5DD] rounded-lg text-[11px] font-semibold text-[#344054] hover:bg-[#F2F4F7] transition shadow-xs cursor-pointer"
        >
            <i class="fa-solid fa-arrow-left text-[9px]"></i>
            <span>Kembali ke Daftar</span>
        </a>
        <a
            href="{{ route('admin.desa.show', $desa->kode_desa) }}"
            class="h-8 inline-flex items-center gap-1 px-3 text-[11px] font-semibold text-[#00629B] hover:underline"
        >
            <i class="fa-solid fa-eye text-[9px]"></i>
            <span>Lihat Detail</span>
        </a>
    </div>

    <section class="bg-white border border-[#E4E7EC] rounded-xl p-4 shadow-xs space-y-3.5">
        <div class="border-b border-[#EAECF0] pb-2.5">
            <h2 class="text-sm font-bold text-[#101828]">
                Edit Data: Desa {{ $desa->nama_desa }}
            </h2>
            <p class="text-[10px] text-[#667085] mt-0.5">
                Kecamatan {{ $desa->kecamatan }} &bull; Kode Wilayah: <span class="font-mono text-[#00629B] font-semibold">{{ $desa->kode_desa }}</span>
            </p>
        </div>

        @if ($errors->any())
            <div role="alert" class="p-2.5 rounded-lg bg-[#FEF3F2] border border-[#FECDCA] text-[10.5px] text-[#B42318] space-y-0.5">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center gap-1"><i class="fa-solid fa-circle-exclamation text-[9px]"></i> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.desa.update', $desa->kode_desa) }}" method="POST" class="space-y-3">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <div>
                    <label for="jumlah_kk_total" class="block text-[10px] font-semibold text-[#475467] mb-1">
                        Jumlah KK Total
                    </label>
                    <input
                        id="jumlah_kk_total"
                        type="number"
                        min="0"
                        name="jumlah_kk_total"
                        value="{{ old('jumlah_kk_total', $desa->jumlah_kk_total) }}"
                        class="w-full h-8 px-2.5 bg-white border border-[#D0D5DD] rounded-lg text-[11px] text-[#101828] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]"
                    >
                    @error('jumlah_kk_total')
                        <p role="alert" class="mt-0.5 text-[9.5px] text-[#B42318]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jumlah_jiwa_total" class="block text-[10px] font-semibold text-[#475467] mb-1">
                        Jumlah Jiwa Total
                    </label>
                    <input
                        id="jumlah_jiwa_total"
                        type="number"
                        min="0"
                        name="jumlah_jiwa_total"
                        value="{{ old('jumlah_jiwa_total', $desa->jumlah_jiwa_total) }}"
                        class="w-full h-8 px-2.5 bg-white border border-[#D0D5DD] rounded-lg text-[11px] text-[#101828] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]"
                    >
                    @error('jumlah_jiwa_total')
                        <p role="alert" class="mt-0.5 text-[9.5px] text-[#B42318]">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="p-3 bg-[#F8FAFC] border border-[#E4E7EC] rounded-lg space-y-2">
                <h3 class="text-[10px] font-bold text-[#101828] uppercase tracking-wider">Data Kelompok Rentan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <div>
                        <label for="jumlah_balita" class="block text-[10px] font-semibold text-[#475467] mb-1">
                            Balita
                        </label>
                        <input
                            id="jumlah_balita"
                            type="number"
                            min="0"
                            name="jumlah_balita"
                            value="{{ old('jumlah_balita', $desa->jumlah_balita) }}"
                            class="w-full h-8 px-2.5 bg-white border border-[#D0D5DD] rounded-lg text-[11px] text-[#101828] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]"
                        >
                        @error('jumlah_balita')
                            <p role="alert" class="mt-0.5 text-[9.5px] text-[#B42318]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jumlah_lansia" class="block text-[10px] font-semibold text-[#475467] mb-1">
                            Lansia
                        </label>
                        <input
                            id="jumlah_lansia"
                            type="number"
                            min="0"
                            name="jumlah_lansia"
                            value="{{ old('jumlah_lansia', $desa->jumlah_lansia) }}"
                            class="w-full h-8 px-2.5 bg-white border border-[#D0D5DD] rounded-lg text-[11px] text-[#101828] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]"
                        >
                        @error('jumlah_lansia')
                            <p role="alert" class="mt-0.5 text-[9.5px] text-[#B42318]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jumlah_disabilitas" class="block text-[10px] font-semibold text-[#475467] mb-1">
                            Disabilitas
                        </label>
                        <input
                            id="jumlah_disabilitas"
                            type="number"
                            min="0"
                            name="jumlah_disabilitas"
                            value="{{ old('jumlah_disabilitas', $desa->jumlah_disabilitas) }}"
                            class="w-full h-8 px-2.5 bg-white border border-[#D0D5DD] rounded-lg text-[11px] text-[#101828] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]"
                        >
                        @error('jumlah_disabilitas')
                            <p role="alert" class="mt-0.5 text-[9.5px] text-[#B42318]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <label for="titik_drop_air" class="block text-[10px] font-semibold text-[#475467] mb-1">
                    Titik Drop Air Bersih
                </label>
                <input
                    id="titik_drop_air"
                    type="text"
                    name="titik_drop_air"
                    maxlength="50"
                    value="{{ old('titik_drop_air', $desa->titik_drop_air) }}"
                    placeholder="Contoh: Tandon Utama Balai Desa"
                    class="w-full h-8 px-2.5 bg-white border border-[#D0D5DD] rounded-lg text-[11px] text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]"
                >
                <p class="mt-0.5 text-[9.5px] text-[#667085]">Maksimal 50 karakter.</p>
                @error('titik_drop_air')
                    <p role="alert" class="mt-0.5 text-[9.5px] text-[#B42318]">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <div>
                    <label for="nama_pj_darurat" class="block text-[10px] font-semibold text-[#475467] mb-1">
                        Nama PJ Darurat
                    </label>
                    <input
                        id="nama_pj_darurat"
                        type="text"
                        name="nama_pj_darurat"
                        maxlength="100"
                        value="{{ old('nama_pj_darurat', $desa->nama_pj_darurat) }}"
                        placeholder="Nama penanggung jawab"
                        class="w-full h-8 px-2.5 bg-white border border-[#D0D5DD] rounded-lg text-[11px] text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]"
                    >
                    @error('nama_pj_darurat')
                        <p role="alert" class="mt-0.5 text-[9.5px] text-[#B42318]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_wa_darurat" class="block text-[10px] font-semibold text-[#475467] mb-1">
                        No. WhatsApp Darurat
                    </label>
                    <input
                        id="no_wa_darurat"
                        type="tel"
                        name="no_wa_darurat"
                        maxlength="20"
                        value="{{ old('no_wa_darurat', $desa->no_wa_darurat) }}"
                        placeholder="Contoh: 0812xxxxxxxx"
                        class="w-full h-8 px-2.5 bg-white border border-[#D0D5DD] rounded-lg text-[11px] text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]"
                    >
                    @error('no_wa_darurat')
                        <p role="alert" class="mt-0.5 text-[9.5px] text-[#B42318]">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="p-3 bg-[#F8FAFC] border border-[#E4E7EC] rounded-lg space-y-2">
                <div>
                    <h3 class="text-[10px] font-bold text-[#101828] uppercase tracking-wider">Koordinat Titik Tandon (GIS)</h3>
                    <p class="text-[9.5px] text-[#667085]">Rentang valid: Latitude -90 s.d. 90, Longitude -180 s.d. 180.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <div>
                        <label for="latitude" class="block text-[10px] font-semibold text-[#475467] mb-1">
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
                            class="w-full h-8 px-2.5 bg-white border border-[#D0D5DD] rounded-lg text-[11px] font-mono text-[#101828] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]"
                        >
                        @error('latitude')
                            <p role="alert" class="mt-0.5 text-[9.5px] text-[#B42318]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="block text-[10px] font-semibold text-[#475467] mb-1">
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
                            class="w-full h-8 px-2.5 bg-white border border-[#D0D5DD] rounded-lg text-[11px] font-mono text-[#101828] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]"
                        >
                        @error('longitude')
                            <p role="alert" class="mt-0.5 text-[9.5px] text-[#B42318]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-[#EAECF0] flex flex-col-reverse sm:flex-row sm:items-center justify-end gap-2">
                <a
                    href="{{ route('admin.desa.index') }}"
                    class="h-8 inline-flex items-center justify-center px-3 bg-white border border-[#D0D5DD] rounded-lg text-[11px] font-semibold text-[#344054] hover:bg-[#F2F4F7] transition shadow-xs cursor-pointer"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    class="h-8 inline-flex items-center justify-center gap-1.5 px-4 bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white text-[11px] font-semibold rounded-lg shadow-xs transition cursor-pointer"
                >
                    <i class="fa-solid fa-floppy-disk text-[9px]"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </section>
</div>
@endsection