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
        <section class="bg-white border border-[#e3e3e0] rounded-lg p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <h2 class="text-lg font-semibold text-[#1b1b18]">
                    {{ $penyalur->nama_penyalur }}
                </h2>
                <p class="text-sm text-[#52525b]">
                    Kontak Operasional: {{ $penyalur->no_telepon }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <div class="sm:text-right">
                    <p class="text-xs text-[#52525b]">Kapasitas Tangki Armada</p>
                    <p class="text-lg font-semibold text-[#1b1b18]">
                        {{ number_format((int) $penyalur->kapasitas_tangki_liter) }} Liter
                    </p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium {{ $isAktif ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                    Status: {{ ucfirst((string) $penyalur->status) }}
                </span>
            </div>
        </section>
    @else
        <section class="bg-white border border-[#e3e3e0] rounded-lg p-6 shadow-sm space-y-2">
            <h2 class="text-lg font-semibold text-[#1b1b18]">
                Halo, {{ $user?->username }}
            </h2>
            <p class="text-sm text-[#52525b]">
                Profil mitra penyalur belum dilengkapi.
            </p>
        </section>
    @endif
</div>
@endsection
