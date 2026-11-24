@extends('layouts.app')

@section('title', 'Profil Desa - Lingga Air')

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
                    Langkah 3 dari 3: Profil Desa
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#101828]">
                Lengkapi Profil Wilayah Desa
            </h1>
            <p class="text-sm text-[#475467]">
                Pilih wilayah desa yang Anda wakili dan lengkapi data kependudukan serta titik drop tandon air bersih darurat.
            </p>
        </div>

        @if ($errors->any())
            <div role="alert" class="p-4 rounded-xl bg-[#FEF3F2] border border-[#FECDCA] text-xs font-semibold text-[#B42318] space-y-1">
                @foreach ($errors->all() as $error)
                    <p><i class="fa-solid fa-circle-exclamation mr-1.5"></i>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if ($desaTersedia->isEmpty())
            <div class="p-5 border border-[#BAE3F5] rounded-2xl bg-[#EBF5FB] space-y-2 text-[#00629B]">
                <h2 class="text-sm font-bold flex items-center gap-2">
                    <i class="fa-solid fa-circle-info"></i>
                    Seluruh Wilayah Desa Sudah Tertaut
                </h2>
                <p class="text-xs text-[#344054] leading-relaxed">
                    Seluruh desa pada master data saat ini sudah memiliki akun perwakilan terdaftar. Jika terjadi kekeliruan kepemilikan akun desa, silakan hubungi Call Center Admin BPBD Kabupaten Purbalingga.
                </p>
            </div>
        @else
            <form id="profile-desa-form" action="{{ route('onboarding.profile.desa') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="kode_desa" class="block text-xs font-bold text-[#344054] mb-1.5">
                        Wilayah Desa / Kelurahan <span class="text-[#B42318]" aria-hidden="true">*</span>
                    </label>
                    <select
                        id="kode_desa"
                        name="kode_desa"
                        required
                        @disabled($desaTertaut !== null)
                        class="w-full min-h-[44px] px-3.5 py-2.5 bg-white border border-[#D0D5DD] rounded-xl text-sm text-[#101828] focus:outline-none focus:border-[#00629B] focus:ring-2 focus:ring-[#00629B]"
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
                        <p role="alert" class="mt-1 text-xs text-[#B42318]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="jumlah_kk_total" class="block text-xs font-bold text-[#344054] mb-1.5">
                            Jumlah KK Total <span class="text-[#B42318]" aria-hidden="true">*</span>
                        </label>
                        <input
                            id="jumlah_kk_total"
                            type="number"
                            min="1"
                            name="jumlah_kk_total"
                            value="{{ old('jumlah_kk_total', $desaTertaut?->jumlah_kk_total) }}"
                            placeholder="Contoh: 450"
                            required
                            class="w-full min-h-[44px] px-3.5 py-2.5 bg-white border border-[#D0D5DD] rounded-xl text-sm text-[#101828] focus:outline-none focus:border-[#00629B] focus:ring-2 focus:ring-[#00629B]"
                        >
                        @error('jumlah_kk_total')
                            <p role="alert" class="mt-1 text-xs text-[#B42318]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jumlah_jiwa_total" class="block text-xs font-bold text-[#344054] mb-1.5">
                            Jumlah Jiwa Total <span class="text-[#B42318]" aria-hidden="true">*</span>
                        </label>
                        <input
                            id="jumlah_jiwa_total"
                            type="number"
                            min="1"
                            name="jumlah_jiwa_total"
                            value="{{ old('jumlah_jiwa_total', $desaTertaut?->jumlah_jiwa_total) }}"
                            placeholder="Contoh: 1800"
                            required
                            class="w-full min-h-[44px] px-3.5 py-2.5 bg-white border border-[#D0D5DD] rounded-xl text-sm text-[#101828] focus:outline-none focus:border-[#00629B] focus:ring-2 focus:ring-[#00629B]"
                        >
                        @error('jumlah_jiwa_total')
                            <p role="alert" class="mt-1 text-xs text-[#B42318]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bagian Kelompok Rentan -->
                <div class="p-4 sm:p-5 bg-[#F8FAFC] border border-[#EAECF0] rounded-2xl space-y-3">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-users text-[#00629B] text-xs"></i>
                        <h2 class="text-xs font-bold text-[#101828] uppercase tracking-wider">
                            Data Kelompok Rentan Desa
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div>
                            <label for="jumlah_balita" class="block text-[11px] font-semibold text-[#475467] mb-1">
                                Balita (Jiwa) <span class="text-[#B42318]" aria-hidden="true">*</span>
                            </label>
                            <input
                                id="jumlah_balita"
                                type="number"
                                min="0"
                                name="jumlah_balita"
                                value="{{ old('jumlah_balita', $desaTertaut?->jumlah_balita) }}"
                                placeholder="0"
                                required
                                class="w-full min-h-[40px] px-3 py-2 bg-white border border-[#D0D5DD] rounded-xl text-xs text-[#101828] focus:outline-none focus:border-[#00629B] focus:ring-2 focus:ring-[#00629B]"
                            >
                            @error('jumlah_balita')
                                <p role="alert" class="mt-1 text-xs text-[#B42318]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jumlah_lansia" class="block text-[11px] font-semibold text-[#475467] mb-1">
                                Lansia (Jiwa) <span class="text-[#B42318]" aria-hidden="true">*</span>
                            </label>
                            <input
                                id="jumlah_lansia"
                                type="number"
                                min="0"
                                name="jumlah_lansia"
                                value="{{ old('jumlah_lansia', $desaTertaut?->jumlah_lansia) }}"
                                placeholder="0"
                                required
                                class="w-full min-h-[40px] px-3 py-2 bg-white border border-[#D0D5DD] rounded-xl text-xs text-[#101828] focus:outline-none focus:border-[#00629B] focus:ring-2 focus:ring-[#00629B]"
                            >
                            @error('jumlah_lansia')
                                <p role="alert" class="mt-1 text-xs text-[#B42318]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jumlah_disabilitas" class="block text-[11px] font-semibold text-[#475467] mb-1">
                                Disabilitas (Jiwa) <span class="text-[#B42318]" aria-hidden="true">*</span>
                            </label>
                            <input
                                id="jumlah_disabilitas"
                                type="number"
                                min="0"
                                name="jumlah_disabilitas"
                                value="{{ old('jumlah_disabilitas', $desaTertaut?->jumlah_disabilitas) }}"
                                placeholder="0"
                                required
                                class="w-full min-h-[40px] px-3 py-2 bg-white border border-[#D0D5DD] rounded-xl text-xs text-[#101828] focus:outline-none focus:border-[#00629B] focus:ring-2 focus:ring-[#00629B]"
                            >
                            @error('jumlah_disabilitas')
                                <p role="alert" class="mt-1 text-xs text-[#B42318]">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_pj_darurat" class="block text-xs font-bold text-[#344054] mb-1.5">
                            Nama PJ Darurat Air Bersih
                        </label>
                        <input
                            id="nama_pj_darurat"
                            type="text"
                            name="nama_pj_darurat"
                            maxlength="100"
                            value="{{ old('nama_pj_darurat') }}"
                            placeholder="Contoh: Bambang (Kades / Kadus)"
                            class="w-full min-h-[44px] px-3.5 py-2.5 bg-white border border-[#D0D5DD] rounded-xl text-sm text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:border-[#00629B] focus:ring-2 focus:ring-[#00629B]"
                        >
                        @error('nama_pj_darurat')
                            <p role="alert" class="mt-1 text-xs text-[#B42318]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="no_wa_darurat" class="block text-xs font-bold text-[#344054] mb-1.5">
                            No. WhatsApp PJ Darurat
                        </label>
                        <input
                            id="no_wa_darurat"
                            type="tel"
                            name="no_wa_darurat"
                            maxlength="20"
                            value="{{ old('no_wa_darurat') }}"
                            placeholder="Contoh: 0812xxxxxxxx"
                            class="w-full min-h-[44px] px-3.5 py-2.5 bg-white border border-[#D0D5DD] rounded-xl text-sm text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:border-[#00629B] focus:ring-2 focus:ring-[#00629B]"
                        >
                        @error('no_wa_darurat')
                            <p role="alert" class="mt-1 text-xs text-[#B42318]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="titik_drop_air" class="block text-xs font-bold text-[#344054] mb-1.5">
                        Patokan Titik Drop Tandon Air Utama
                    </label>
                    <input
                        id="titik_drop_air"
                        type="text"
                        name="titik_drop_air"
                        maxlength="50"
                        value="{{ old('titik_drop_air') }}"
                        placeholder="Contoh: Bak Penampungan Sentral RT 04 RW 02"
                        class="w-full min-h-[44px] px-3.5 py-2.5 bg-white border border-[#D0D5DD] rounded-xl text-sm text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:border-[#00629B] focus:ring-2 focus:ring-[#00629B]"
                    >
                    <p class="mt-1 text-[11px] text-[#667085]">Lokasi drop utama yang dapat diakses armada tangki 5.000 liter.</p>
                    @error('titik_drop_air')
                        <p role="alert" class="mt-1 text-xs text-[#B42318]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-5 border-t border-[#EAECF0] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <p class="text-xs text-[#667085]">
                        Peran terpilih: <span class="inline-flex items-center gap-1 font-bold text-[#00629B] bg-[#EBF5FB] border border-[#BAE3F5] px-2 py-0.5 rounded-md"><i class="fa-solid fa-tree-city"></i> Perwakilan Desa</span>
                    </p>
                    <button
                        type="submit"
                        id="profile-desa-submit"
                        class="min-h-[44px] inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white text-xs sm:text-sm font-semibold rounded-xl transition shadow-xs"
                    >
                        <span>Simpan & Masuk Dashboard</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </form>
        @endif
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
