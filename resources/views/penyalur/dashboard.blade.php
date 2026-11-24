@extends('layouts.dashboard')

@section('title', 'Dashboard Penyalur - LinggaAir')
@section('page-title', 'Dashboard Mitra Penyalur')

@section('content')
@php
    $user = auth()->user();
    $penyalur = $user?->penyalur;
    $isAktif = ($penyalur->status ?? '') === 'aktif';
@endphp

<div class="space-y-6">
    @if ($penyalur)
        <section class="bg-white border border-[#E4E7EC] rounded-2xl p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <h2 class="text-xl font-bold text-[#101828]">
                        {{ $penyalur->nama_penyalur }}
                    </h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $isAktif ? 'bg-[#ECFDF3] text-[#027A48] border border-[#A6F4C5]' : 'bg-[#FFFAEB] text-[#B54708] border border-[#FEDF89]' }}">
                        Status: {{ ucfirst((string) $penyalur->status) }}
                    </span>
                </div>
                <p class="text-sm text-[#475467]">
                    Kontak Operasional: <span class="font-medium text-[#101828]">{{ $penyalur->no_telepon }}</span>
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <div class="sm:text-right">
                    <p class="text-xs text-[#667085]">Kapasitas Tangki Armada</p>
                    <p class="text-xl font-extrabold text-[#00629B]">
                        {{ number_format((int) $penyalur->kapasitas_tangki_liter, 0, ',', '.') }} Liter
                    </p>
                </div>
            </div>
        </section>

        <!-- Quick Access Feature 8: Rekap Penyaluran -->
        <section class="bg-gradient-to-r from-[#00629B] to-[#004F7D] rounded-2xl p-6 text-white shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <h3 class="text-lg font-bold">Laporan & Rekap Penyaluran Armada</h3>
                <p class="text-xs text-blue-100 max-w-xl">
                    Pantau seluruh riwayat pengiriman air bersih darurat ke desa, periksa status serah terima, dan unduh laporan resmi dalam format Excel atau PDF.
                </p>
            </div>
            <a href="{{ route('penyalur.laporan.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-[#00629B] hover:bg-blue-50 text-xs font-bold rounded-xl transition shadow-xs shrink-0">
                <i class="fa-solid fa-file-invoice"></i>
                <span>Buka Rekap Penyaluran</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </section>
    @else
        <section class="bg-white border border-[#E4E7EC] rounded-2xl p-6 shadow-xs space-y-2">
            <h2 class="text-lg font-bold text-[#101828]">
                Halo, {{ $user?->username }}
            </h2>
            <p class="text-sm text-[#667085]">
                Profil mitra penyalur belum dilengkapi.
            </p>
        </section>
    @endif
</div>
@endsection
