@extends('layouts.app')

@section('title', 'Onboarding - LinggaAir')

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
                Langkah 1 dari 3: Pengenalan
            </p>
            <h1 class="text-2xl font-semibold text-[#1b1b18]">
                Selamat Datang di LinggaAir
            </h1>
            <p class="text-sm sm:text-base text-[#52525b] leading-relaxed">
                Sebelum menggunakan dashboard operasional, tentukan peran akun dan lengkapi profil Anda agar sistem dapat menyesuaikan akses layanan distribusi air bersih Kabupaten Purbalingga.
            </p>
        </div>

        <div class="border-t border-[#e3e3e0] pt-5 space-y-3 text-sm">
            <h2 class="font-medium text-[#1b1b18]">Tahapan selanjutnya:</h2>
            <ol class="list-decimal list-inside space-y-2 text-[#52525b]">
                <li>Memilih peran akun sebagai <strong class="font-medium text-[#1b1b18]">Perwakilan Desa</strong> atau <strong class="font-medium text-[#1b1b18]">Mitra Penyalur</strong>.</li>
                <li>Mengisi profil wilayah desa dan titik drop air bersih, atau informasi kapasitas armada penyalur.</li>
            </ol>
        </div>

        <div class="pt-2 border-t border-[#e3e3e0] flex justify-end">
            <a
                href="{{ route('onboarding.role') }}"
                class="w-full sm:w-auto min-h-[44px] inline-flex items-center justify-center px-5 py-2.5 bg-[#1b1b18] hover:bg-black text-white text-sm font-medium rounded-md transition"
            >
                Lanjut Pilih Peran
            </a>
        </div>
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
