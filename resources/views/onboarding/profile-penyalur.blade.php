@extends('layouts.app')

@section('title', 'Profil Mitra Penyalur - LinggaAir')

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
    <div class="w-full max-w-xl bg-white border border-[#e3e3e0] rounded-lg p-6 sm:p-8 shadow-sm space-y-6">
        <div class="space-y-2">
            <p class="text-xs font-medium uppercase tracking-wider text-[#52525b]">
                Langkah 3 dari 3: Profil Mitra Penyalur
            </p>
            <h1 class="text-2xl font-semibold text-[#1b1b18]">
                Lengkapi Profil Armada Penyalur
            </h1>
            <p class="text-sm sm:text-base text-[#52525b]">
                Masukkan nama lembaga penyalur, kapasitas tangki armada, dan nomor telepon operasional.
            </p>
        </div>

        @if ($errors->any())
            <div role="alert" class="p-3.5 rounded-md bg-red-50 border border-red-200 text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form id="profile-penyalur-form" action="{{ route('onboarding.profile.penyalur') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="nama_penyalur" class="block text-sm font-medium text-[#1b1b18] mb-1">
                    Nama Lembaga / Mitra Penyalur <span class="text-red-600" aria-hidden="true">*</span>
                </label>
                <input
                    id="nama_penyalur"
                    type="text"
                    name="nama_penyalur"
                    maxlength="100"
                    value="{{ old('nama_penyalur') }}"
                    required
                    autofocus
                    placeholder="Contoh: PMI Purbalingga"
                    class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                >
                @error('nama_penyalur')
                    <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="kapasitas_tangki_liter" class="block text-sm font-medium text-[#1b1b18] mb-1">
                    Kapasitas Tangki Armada (Liter) <span class="text-red-600" aria-hidden="true">*</span>
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
                    class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                >
                <p class="mt-1 text-xs text-[#52525b]">Minimal 100 liter.</p>
                @error('kapasitas_tangki_liter')
                    <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="no_telepon" class="block text-sm font-medium text-[#1b1b18] mb-1">
                    No. Telepon / WhatsApp Operasional <span class="text-red-600" aria-hidden="true">*</span>
                </label>
                <input
                    id="no_telepon"
                    type="tel"
                    name="no_telepon"
                    maxlength="20"
                    value="{{ old('no_telepon') }}"
                    required
                    placeholder="Contoh: 0812xxxxxxxx"
                    class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                >
                @error('no_telepon')
                    <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-[#e3e3e0] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <p class="text-sm text-[#52525b]">
                    Peran terpilih: <strong class="font-medium text-[#1b1b18]">Mitra Penyalur</strong>
                </p>
                <button
                    type="submit"
                    id="profile-penyalur-submit"
                    class="min-h-[44px] inline-flex items-center justify-center px-5 py-2.5 bg-[#1b1b18] hover:bg-black text-white text-sm font-medium rounded-md transition"
                >
                    Simpan dan Masuk Dashboard
                </button>
            </div>
        </form>
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
