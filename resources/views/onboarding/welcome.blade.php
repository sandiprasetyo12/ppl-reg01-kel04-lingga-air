@extends('layouts.app')

@section('title', 'Selamat Datang di Lingga Air - Onboarding')

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
                    Langkah 1 dari 3: Pengenalan
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#101828]">
                Selamat Datang di Lingga Air
            </h1>
            <p class="text-sm text-[#475467] leading-relaxed">
                Sebelum menggunakan dashboard operasional, tentukan peran akun dan lengkapi profil Anda agar sistem dapat menyesuaikan akses layanan distribusi air bersih Kabupaten Purbalingga.
            </p>
        </div>

        <div class="border-t border-[#EAECF0] pt-5 space-y-3 text-sm">
            <h2 class="font-bold text-[#101828]">Tahapan selanjutnya:</h2>
            <ol class="list-decimal list-inside space-y-2 text-[#475467]">
                <li>Memilih peran akun sebagai <strong class="font-semibold text-[#101828]">Perwakilan Desa</strong> atau <strong class="font-semibold text-[#101828]">Mitra Penyalur</strong>.</li>
                <li>Mengisi profil wilayah desa dan titik drop air bersih, atau informasi kapasitas armada penyalur.</li>
            </ol>
        </div>

        <div class="pt-4 border-t border-[#EAECF0] flex justify-end">
            <a
                href="{{ route('onboarding.role') }}"
                class="w-full sm:w-auto min-h-[44px] inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white text-sm font-semibold rounded-xl transition shadow-xs"
            >
                <span>Lanjut Pilih Peran</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
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
