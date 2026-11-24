@extends('layouts.app')

@section('title', 'Profil Mitra Penyalur - Lingga Air')

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
    <div class="w-full max-w-xl bg-white border border-[#E4E7EC] rounded-2xl p-6 sm:p-8 shadow-xs space-y-6">
        <div class="space-y-3">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#EBF5FB] text-[#00629B] border border-[#BAE3F5]">
                    Langkah 3 dari 3: Profil Mitra Penyalur
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#101828]">
                Lengkapi Profil Armada Penyalur
            </h1>
            <p class="text-sm text-[#475467]">
                Masukkan nama lembaga penyalur, kapasitas tangki armada, dan nomor telepon operasional untuk koordinasi distribusi air bersih.
            </p>
        </div>

        @if ($errors->any())
            <div role="alert" class="p-4 rounded-xl bg-[#FEF3F2] border border-[#FECDCA] text-xs font-semibold text-[#B42318] space-y-1">
                @foreach ($errors->all() as $error)
                    <p><i class="fa-solid fa-circle-exclamation mr-1.5"></i>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form id="profile-penyalur-form" action="{{ route('onboarding.profile.penyalur') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="nama_penyalur" class="block text-xs font-bold text-[#344054] mb-1.5">
                    Nama Lembaga / Mitra Penyalur <span class="text-[#B42318]" aria-hidden="true">*</span>
                </label>
                <input
                    id="nama_penyalur"
                    type="text"
                    name="nama_penyalur"
                    maxlength="100"
                    value="{{ old('nama_penyalur') }}"
                    required
                    autofocus
                    placeholder="Contoh: PMI Cabang Purbalingga / PDAM Tirta Perwira"
                    class="w-full min-h-[44px] px-3.5 py-2.5 bg-white border border-[#D0D5DD] rounded-xl text-sm text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:border-[#00629B] focus:ring-2 focus:ring-[#00629B]"
                >
                @error('nama_penyalur')
                    <p role="alert" class="mt-1 text-xs text-[#B42318]">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="kapasitas_tangki_liter" class="block text-xs font-bold text-[#344054] mb-1.5">
                    Kapasitas Tangki Armada (Liter) <span class="text-[#B42318]" aria-hidden="true">*</span>
                </label>
                <input
                    id="kapasitas_tangki_liter"
                    type="number"
                    min="100"
                    step="100"
                    name="kapasitas_tangki_liter"
                    value="{{ old('kapasitas_tangki_liter') }}"
                    placeholder="Contoh: 5000"
                    required
                    class="w-full min-h-[44px] px-3.5 py-2.5 bg-white border border-[#D0D5DD] rounded-xl text-sm text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:border-[#00629B] focus:ring-2 focus:ring-[#00629B]"
                >
                <p class="mt-1 text-[11px] text-[#667085]">Minimal 100 liter per armada pengiriman.</p>
                @error('kapasitas_tangki_liter')
                    <p role="alert" class="mt-1 text-xs text-[#B42318]">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="no_telepon" class="block text-xs font-bold text-[#344054] mb-1.5">
                    No. Telepon / WhatsApp Operasional <span class="text-[#B42318]" aria-hidden="true">*</span>
                </label>
                <input
                    id="no_telepon"
                    type="tel"
                    name="no_telepon"
                    maxlength="20"
                    value="{{ old('no_telepon') }}"
                    required
                    placeholder="Contoh: 081234567890"
                    class="w-full min-h-[44px] px-3.5 py-2.5 bg-white border border-[#D0D5DD] rounded-xl text-sm text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:border-[#00629B] focus:ring-2 focus:ring-[#00629B]"
                >
                @error('no_telepon')
                    <p role="alert" class="mt-1 text-xs text-[#B42318]">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-5 border-t border-[#EAECF0] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <p class="text-xs text-[#667085]">
                    Peran terpilih: <span class="inline-flex items-center gap-1 font-bold text-[#00629B] bg-[#EBF5FB] border border-[#BAE3F5] px-2 py-0.5 rounded-md"><i class="fa-solid fa-truck-droplet"></i> Mitra Penyalur</span>
                </p>
                <button
                    type="submit"
                    id="profile-penyalur-submit"
                    class="min-h-[44px] inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white text-xs sm:text-sm font-semibold rounded-xl transition shadow-xs"
                >
                    <span>Simpan & Masuk Dashboard</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
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
