@extends('layouts.app')

@section('title', 'Pengumuman BPBD Purbalingga - LinggaAir')

@section('header')
<header class="bg-white border-b border-[#EAECF0]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#101828]">
            <img src="{{ asset('images/logo-lingga-oneline.png') }}" alt="LinggaAir" class="h-8 w-auto">
        </a>
        <a href="{{ route('home') }}" class="text-xs font-semibold text-[#00629B] hover:underline">Kembali ke beranda</a>
    </div>
</header>
@endsection

@section('content')
<div class="max-w-5xl w-full mx-auto px-4 sm:px-6 py-10">
    <div class="mb-6">
        <p class="text-xs font-bold uppercase tracking-wider text-[#00629B]">Informasi Resmi</p>
        <h1 class="mt-1 text-2xl font-bold text-[#101828]">Pengumuman BPBD Purbalingga</h1>
        <p class="mt-2 text-sm text-[#667085]">Informasi dan imbauan terbaru terkait kondisi kekeringan serta penanganan bantuan air bersih.</p>
    </div>

    <div class="space-y-4">
        @forelse ($pengumumans as $item)
            <article class="rounded-xl border border-[#EAECF0] bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $item->badge_class }}">
                        Status {{ $item->tingkat_label }}
                    </span>
                    <time class="text-xs text-[#667085]" datetime="{{ $item->published_at?->toIso8601String() }}">
                        {{ $item->published_at?->translatedFormat('d F Y, H:i') }} WIB
                    </time>
                </div>
                <h2 class="mt-3 text-base font-bold text-[#101828]">{{ $item->judul }}</h2>
                <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-[#344054]">{{ $item->isi }}</p>
            </article>
        @empty
            <div class="rounded-xl border border-dashed border-[#D0D5DD] bg-white px-5 py-12 text-center">
                <p class="text-sm font-semibold text-[#344054]">Belum ada pengumuman aktif.</p>
                <p class="mt-1 text-xs text-[#667085]">Informasi terbaru akan ditampilkan di halaman ini.</p>
            </div>
        @endforelse
    </div>

    @if ($pengumumans->hasPages())
        <div class="mt-6">{{ $pengumumans->links() }}</div>
    @endif
</div>
@endsection

@section('footer')
<footer class="border-t border-[#EAECF0] bg-white py-5 text-center text-xs text-[#667085]">
    &copy; {{ date('Y') }} LinggaAir - BPBD Kabupaten Purbalingga.
</footer>
@endsection
