@extends('layouts.dashboard')

@section('title', 'Detail Desa ' . $desa->nama_desa . ' - LinggaAir')
@section('page-title', 'Detail Data Desa')

@section('content')
@php
    $hasKoordinat = !is_null($desa->latitude) && !is_null($desa->longitude) && (float) $desa->latitude !== 0.0 && (float) $desa->longitude !== 0.0;
@endphp

<div class="max-w-3xl space-y-6">
    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" class="text-sm text-[#52525b] flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-[#1b1b18] underline min-h-[44px] inline-flex items-center">
            Dashboard
        </a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('admin.desa.index') }}" class="hover:text-[#1b1b18] underline min-h-[44px] inline-flex items-center">
            Master Data Desa
        </a>
        <span aria-hidden="true">/</span>
        <span class="text-[#1b1b18] font-medium">{{ $desa->nama_desa }}</span>
    </nav>

    <!-- Tombol Aksi -->
    <div class="flex flex-wrap items-center justify-between gap-3">
        <a
            href="{{ route('admin.desa.index') }}"
            class="min-h-[44px] inline-flex items-center px-4 py-2 bg-white border border-[#d1d5db] rounded-md text-sm font-medium text-[#1b1b18] hover:bg-[#f5f5f4] transition"
        >
            Kembali ke Daftar
        </a>
        <a
            href="{{ route('admin.desa.edit', $desa->kode_desa) }}"
            class="min-h-[44px] inline-flex items-center px-5 py-2 bg-[#1b1b18] hover:bg-black text-white rounded-md text-sm font-medium transition"
        >
            Edit Data Desa
        </a>
    </div>

    <!-- Detail Informasi Desa -->
    <section class="bg-white border border-[#e3e3e0] rounded-lg p-6 shadow-sm space-y-6">
        <div class="border-b border-[#e3e3e0] pb-4 flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-[#1b1b18]">
                    Desa {{ $desa->nama_desa }}
                </h2>
                <p class="text-sm text-[#52525b] mt-1">
                    Kecamatan {{ $desa->kecamatan }}, Kode Wilayah: {{ $desa->kode_desa }}
                </p>
            </div>

            <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium {{ $desa->user ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-[#F3F4F6] text-[#52525b] border border-[#e3e3e0]' }}">
                {{ $desa->user ? 'Akun Terhubung' : 'Belum Ada Akun' }}
            </span>
        </div>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
            <div>
                <dt class="text-[#52525b] font-medium">Status Kepemilikan Akun</dt>
                <dd class="mt-1 text-[#1b1b18] font-medium">
                    @if ($desa->user)
                        Terhubung ke pengguna: {{ $desa->user->username }}
                    @else
                        Belum didaftarkan oleh perwakilan desa
                    @endif
                </dd>
            </div>

            <div>
                <dt class="text-[#52525b] font-medium">Titik Drop Air Bersih</dt>
                <dd class="mt-1 text-[#1b1b18] font-medium">
                    {{ $desa->titik_drop_air ?: 'Belum ditentukan' }}
                </dd>
            </div>

            <div>
                <dt class="text-[#52525b] font-medium">Total KK &amp; Jiwa</dt>
                <dd class="mt-1 text-[#1b1b18] font-medium">
                    {{ number_format((int) ($desa->jumlah_kk_total ?? 0)) }} KK, {{ number_format((int) ($desa->jumlah_jiwa_total ?? 0)) }} Jiwa
                </dd>
            </div>

            <div>
                <dt class="text-[#52525b] font-medium">Kelompok Rentan</dt>
                <dd class="mt-1 text-[#1b1b18] font-medium">
                    Balita: {{ number_format((int) ($desa->jumlah_balita ?? 0)) }},
                    Lansia: {{ number_format((int) ($desa->jumlah_lansia ?? 0)) }},
                    Disabilitas: {{ number_format((int) ($desa->jumlah_disabilitas ?? 0)) }}
                </dd>
            </div>

            <div>
                <dt class="text-[#52525b] font-medium">Penanggung Jawab Darurat</dt>
                <dd class="mt-1 text-[#1b1b18] font-medium">
                    {{ $desa->nama_pj_darurat ?: '-' }}
                </dd>
            </div>

            <div>
                <dt class="text-[#52525b] font-medium">No. WhatsApp Darurat</dt>
                <dd class="mt-1 text-[#1b1b18] font-medium">
                    {{ $desa->no_wa_darurat ?: '-' }}
                </dd>
            </div>

            <div class="sm:col-span-2 p-4 bg-[#FDFDFC] border border-[#e3e3e0] rounded-md">
                <dt class="text-[#52525b] font-medium">Koordinat Titik Tandon (GIS)</dt>
                <dd class="mt-1 font-mono text-xs sm:text-sm text-[#1b1b18]">
                    @if ($hasKoordinat)
                        Lat: {{ $desa->latitude }}, Lng: {{ $desa->longitude }}
                    @else
                        Belum dilengkapi (Lat: -, Lng: -)
                    @endif
                </dd>
            </div>
        </dl>
    </section>
</div>
@endsection
