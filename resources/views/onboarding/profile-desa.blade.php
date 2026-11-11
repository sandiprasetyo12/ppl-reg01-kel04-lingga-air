@extends('layouts.app')

@section('title', 'Profil Desa - LinggaAir')

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
                Langkah 3 dari 3: Profil Desa
            </p>
            <h1 class="text-2xl font-semibold text-[#1b1b18]">
                Lengkapi Profil Desa Anda
            </h1>
            <p class="text-sm sm:text-base text-[#52525b]">
                Pilih wilayah desa yang belum terdaftar dan lengkapi data kependudukan serta titik drop air bersih.
            </p>
        </div>

        @if ($errors->any())
            <div role="alert" class="p-3.5 rounded-md bg-red-50 border border-red-200 text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if ($desaTersedia->isEmpty())
            <div class="p-5 border border-[#e3e3e0] rounded-md bg-[#FDFDFC] space-y-2">
                <h2 class="text-base font-semibold text-[#1b1b18]">
                    Seluruh Wilayah Desa Sudah Tertaut
                </h2>
                <p class="text-sm text-[#52525b] leading-relaxed">
                    Seluruh desa pada master data sudah memiliki akun terdaftar. Jika terjadi kekeliruan kepemilikan akun desa, hubungi Admin BPBD Kabupaten Purbalingga.
                </p>
            </div>
        @else
            <form id="profile-desa-form" action="{{ route('onboarding.profile.desa') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="kode_desa" class="block text-sm font-medium text-[#1b1b18] mb-1">
                        Wilayah Desa / Kelurahan <span class="text-red-600" aria-hidden="true">*</span>
                    </label>
                    <select
                        id="kode_desa"
                        name="kode_desa"
                        required
                        @disabled($desaTertaut !== null)
                        class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
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
                        <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="jumlah_kk_total" class="block text-sm font-medium text-[#1b1b18] mb-1">
                            Jumlah KK Total <span class="text-red-600" aria-hidden="true">*</span>
                        </label>
                        <input
                            id="jumlah_kk_total"
                            type="number"
                            min="1"
                            name="jumlah_kk_total"
                            value="{{ old('jumlah_kk_total', $desaTertaut?->jumlah_kk_total) }}"
                            placeholder="Contoh: 450"
                            required
                            class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                        >
                        @error('jumlah_kk_total')
                            <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jumlah_jiwa_total" class="block text-sm font-medium text-[#1b1b18] mb-1">
                            Jumlah Jiwa Total <span class="text-red-600" aria-hidden="true">*</span>
                        </label>
                        <input
                            id="jumlah_jiwa_total"
                            type="number"
                            min="1"
                            name="jumlah_jiwa_total"
                            value="{{ old('jumlah_jiwa_total', $desaTertaut?->jumlah_jiwa_total) }}"
                            placeholder="Contoh: 1800"
                            required
                            class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                        >
                        @error('jumlah_jiwa_total')
                            <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="p-4 bg-[#FDFDFC] border border-[#e3e3e0] rounded-md space-y-3">
                    <h2 class="text-sm font-medium text-[#1b1b18]">
                        Data Kelompok Rentan
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="jumlah_balita" class="block text-sm font-medium text-[#1b1b18] mb-1">
                                Balita <span class="text-red-600" aria-hidden="true">*</span>
                            </label>
                            <input
                                id="jumlah_balita"
                                type="number"
                                min="0"
                                name="jumlah_balita"
                                value="{{ old('jumlah_balita', $desaTertaut?->jumlah_balita) }}"
                                placeholder="0"
                                required
                                class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                            >
                            @error('jumlah_balita')
                                <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jumlah_lansia" class="block text-sm font-medium text-[#1b1b18] mb-1">
                                Lansia <span class="text-red-600" aria-hidden="true">*</span>
                            </label>
                            <input
                                id="jumlah_lansia"
                                type="number"
                                min="0"
                                name="jumlah_lansia"
                                value="{{ old('jumlah_lansia', $desaTertaut?->jumlah_lansia) }}"
                                placeholder="0"
                                required
                                class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                            >
                            @error('jumlah_lansia')
                                <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jumlah_disabilitas" class="block text-sm font-medium text-[#1b1b18] mb-1">
                                Disabilitas <span class="text-red-600" aria-hidden="true">*</span>
                            </label>
                            <input
                                id="jumlah_disabilitas"
                                type="number"
                                min="0"
                                name="jumlah_disabilitas"
                                value="{{ old('jumlah_disabilitas', $desaTertaut?->jumlah_disabilitas) }}"
                                placeholder="0"
                                required
                                class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                            >
                            @error('jumlah_disabilitas')
                                <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
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
                            value="{{ old('nama_pj_darurat') }}"
                            placeholder="Nama penanggung jawab desa"
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
                            value="{{ old('no_wa_darurat') }}"
                            placeholder="Contoh: 0812xxxxxxxx"
                            class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                        >
                        @error('no_wa_darurat')
                            <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="titik_drop_air" class="block text-sm font-medium text-[#1b1b18] mb-1">
                        Patokan Titik Drop Tandon Air
                    </label>
                    <input
                        id="titik_drop_air"
                        type="text"
                        name="titik_drop_air"
                        maxlength="50"
                        value="{{ old('titik_drop_air') }}"
                        placeholder="Contoh: Tandon Depan Balai Desa"
                        class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                    >
                    <p class="mt-1 text-xs text-[#52525b]">Maksimal 50 karakter.</p>
                    @error('titik_drop_air')
                        <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-[#e3e3e0] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <p class="text-sm text-[#52525b]">
                        Peran terpilih: <strong class="font-medium text-[#1b1b18]">Perwakilan Desa</strong>
                    </p>
                    <button
                        type="submit"
                        id="profile-desa-submit"
                        class="min-h-[44px] inline-flex items-center justify-center px-5 py-2.5 bg-[#1b1b18] hover:bg-black text-white text-sm font-medium rounded-md transition"
                    >
                        Simpan dan Masuk Dashboard
                    </button>
                </div>
            </form>
        @endif
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
