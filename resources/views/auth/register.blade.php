@extends('layouts.app')

@section('title', 'Daftar - LinggaAir')

@section('header')
<header class="bg-white border-b border-[#e3e3e0]">
    <nav aria-label="Navigasi pendaftaran" class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
        <a href="{{ url('/') }}" class="text-base font-semibold text-[#1b1b18] min-h-[44px] inline-flex items-center">
            LinggaAir
        </a>
        <a href="{{ url('/') }}" class="text-sm text-[#52525b] hover:text-[#1b1b18] underline min-h-[44px] inline-flex items-center">
            Kembali ke Beranda
        </a>
    </nav>
</header>
@endsection

@section('content')
<div class="flex-1 flex flex-col justify-center items-center px-4 py-10 bg-[#F3F4F6]">
    <div class="w-full max-w-md bg-white border border-[#e3e3e0] rounded-lg p-6 sm:p-8 shadow-sm space-y-5">
        <div>
            <h1 class="text-xl font-semibold text-[#1b1b18]">Daftar Akun Baru</h1>
            <p class="text-sm text-[#52525b] mt-1">Buat akun untuk memilih peran desa atau penyalur.</p>
        </div>

        @if ($errors->any())
            <div role="alert" class="p-3.5 rounded-md bg-red-50 border border-red-200 text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="username" class="block text-sm font-medium text-[#1b1b18] mb-1">
                    Username
                </label>
                <input
                    id="username"
                    name="username"
                    type="text"
                    value="{{ old('username') }}"
                    maxlength="50"
                    required
                    autofocus
                    autocomplete="username"
                    class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                >
                @error('username')
                    <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-[#1b1b18] mb-1">
                    Password
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    minlength="8"
                    required
                    autocomplete="new-password"
                    class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                >
                <p class="mt-1 text-xs text-[#52525b]">Minimal 8 karakter.</p>
                @error('password')
                    <p role="alert" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-[#1b1b18] mb-1">
                    Konfirmasi Password
                </label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    minlength="8"
                    required
                    autocomplete="new-password"
                    class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                >
            </div>

            <div class="pt-1 flex items-center justify-between gap-4">
                <a href="{{ route('login') }}" class="text-sm text-[#52525b] hover:text-[#1b1b18] underline min-h-[44px] inline-flex items-center">
                    Sudah punya akun?
                </a>

                <button
                    type="submit"
                    class="min-h-[44px] inline-flex items-center justify-center px-5 py-2 bg-[#1b1b18] hover:bg-black text-white text-sm font-medium rounded-md transition"
                >
                    Daftar
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
