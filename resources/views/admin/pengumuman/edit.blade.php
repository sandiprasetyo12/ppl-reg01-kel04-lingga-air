@extends('layouts.dashboard')

@section('title', 'Ubah Pengumuman - LinggaAir')
@section('page-title', 'Ubah Pengumuman Kedaruratan')

@section('content')
<div class="max-w-3xl space-y-5">

    {{-- Breadcrumb & Navigation Link --}}
    <div>
        <a href="{{ route('admin.pengumuman.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#00629B] hover:underline mb-2">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
            <span>Kembali ke Daftar Pengumuman</span>
        </a>
        <h1 class="text-xl font-bold text-[#101828] tracking-tight">
            Ubah Pengumuman Kedaruratan
        </h1>
        <p class="text-xs text-[#667085] mt-0.5">
            Perbarui informasi maklumat resmi atau status kedaruratan kekeringan.
        </p>
    </div>

    {{-- Card Form --}}
    <div class="bg-white border border-[#EAECF0] rounded-xl p-5 shadow-xs">
        <form action="{{ route('admin.pengumuman.update', $pengumuman) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Judul Pengumuman --}}
            <div>
                <label for="judul" class="block text-xs font-bold text-[#344054] mb-1">
                    Judul Pengumuman <span class="text-[#D92D20]">*</span>
                </label>
                <input
                    type="text"
                    id="judul"
                    name="judul"
                    value="{{ old('judul', $pengumuman->judul) }}"
                    class="w-full h-9 px-3 bg-white border @error('judul') border-[#FDA29B] @else border-[#D0D5DD] @enderror rounded-lg text-xs text-[#101828] placeholder-[#98A2B3] focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]"
                    required
                    maxlength="150"
                >
                @error('judul')
                    <p class="text-[11px] text-[#D92D20] mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tingkat Kedaruratan --}}
            <div>
                <label class="block text-xs font-bold text-[#344054] mb-1.5">
                    Tingkat Kedaruratan <span class="text-[#D92D20]">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                    @php
                        $selectedTingkat = old('tingkat_kelayakan', $pengumuman->tingkat_kelayakan);
                    @endphp

                    {{-- Kritis --}}
                    <label class="relative flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition-colors {{ $selectedTingkat === 'kritis' ? 'border-[#FECDCA] bg-[#FEF3F2]' : 'border-[#EAECF0] hover:bg-[#F8FAFC]' }}">
                        <input
                            type="radio"
                            name="tingkat_kelayakan"
                            value="kritis"
                            {{ $selectedTingkat === 'kritis' ? 'checked' : '' }}
                            class="text-[#D92D20] focus:ring-[#D92D20]"
                        >
                        <div>
                            <span class="block text-xs font-bold text-[#B42318]">Kritis (Darurat)</span>
                            <span class="block text-[10px] text-[#912018]">Prioritas penanganan darurat tangki segera</span>
                        </div>
                    </label>

                    {{-- Waspada --}}
                    <label class="relative flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition-colors {{ $selectedTingkat === 'waspada' ? 'border-[#FEDF89] bg-[#FEF0C7]' : 'border-[#EAECF0] hover:bg-[#F8FAFC]' }}">
                        <input
                            type="radio"
                            name="tingkat_kelayakan"
                            value="waspada"
                            {{ $selectedTingkat === 'waspada' ? 'checked' : '' }}
                            class="text-[#B54708] focus:ring-[#B54708]"
                        >
                        <div>
                            <span class="block text-xs font-bold text-[#B54708]">Waspada (Siaga)</span>
                            <span class="block text-[10px] text-[#93370D]">Penurunan debit air & persiapan armada</span>
                        </div>
                    </label>

                    {{-- Aman --}}
                    <label class="relative flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition-colors {{ $selectedTingkat === 'aman' ? 'border-[#A6F4C5] bg-[#ECFDF3]' : 'border-[#EAECF0] hover:bg-[#F8FAFC]' }}">
                        <input
                            type="radio"
                            name="tingkat_kelayakan"
                            value="aman"
                            {{ $selectedTingkat === 'aman' ? 'checked' : '' }}
                            class="text-[#027A48] focus:ring-[#027A48]"
                        >
                        <div>
                            <span class="block text-xs font-bold text-[#027A48]">Aman / Himbauan</span>
                            <span class="block text-[10px] text-[#05603A]">Kondisi terkendali & panduan penghematan</span>
                        </div>
                    </label>
                </div>
                @error('tingkat_kelayakan')
                    <p class="text-[11px] text-[#D92D20] mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Isi Maklumat / Pengumuman --}}
            <div>
                <label for="isi" class="block text-xs font-bold text-[#344054] mb-1">
                    Isi Pengumuman / Instruksi Kebijakan <span class="text-[#D92D20]">*</span>
                </label>
                <textarea
                    id="isi"
                    name="isi"
                    rows="6"
                    class="w-full p-3 bg-white border @error('isi') border-[#FDA29B] @else border-[#D0D5DD] @enderror rounded-lg text-xs text-[#101828] placeholder-[#98A2B3] focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]"
                    required
                >{{ old('isi', $pengumuman->isi) }}</textarea>
                @error('isi')
                    <p class="text-[11px] text-[#D92D20] mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status Aktif Langsung --}}
            <div class="pt-2">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        {{ old('is_active', $pengumuman->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-[#D0D5DD] text-[#00629B] focus:ring-[#00629B]"
                    >
                    <span class="text-xs font-semibold text-[#344054]">
                        Status aktif (Tampilkan di Beranda Desa & Publik)
                    </span>
                </label>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-[#F2F4F7]">
                <a href="{{ route('admin.pengumuman.index') }}" class="h-9 px-4 rounded-lg border border-[#D0D5DD] bg-white hover:bg-[#F2F4F7] text-[#344054] text-xs font-semibold inline-flex items-center transition-colors">
                    Batal
                </a>
                <button type="submit" class="h-9 px-4 rounded-lg bg-[#00629B] hover:bg-[#005180] text-white text-xs font-semibold shadow-xs inline-flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-floppy-disk text-[11px]"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
