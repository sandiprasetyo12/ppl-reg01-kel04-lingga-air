@extends('layouts.dashboard')

@section('title', 'Dashboard Desa - LinggaAir')
@section('page-title', 'Dashboard Perwakilan Desa')

@section('content')
@php
    $user = auth()->user();
    $desa = $user?->desa;
    $hasKoordinat = $desa && !is_null($desa->latitude) && !is_null($desa->longitude) && (float) $desa->latitude !== 0.0 && (float) $desa->longitude !== 0.0;
@endphp

<div class="space-y-6">
    @if ($desa)
        <section class="bg-white border border-[#e3e3e0] rounded-lg p-6 shadow-sm space-y-6">
            <div class="border-b border-[#e3e3e0] pb-4 flex flex-wrap items-start justify-between gap-3">
                <div class="space-y-1">
                    <p class="text-xs font-medium text-[#52525b]">
                        Kecamatan {{ $desa->kecamatan }}
                    </p>
                    <h2 class="text-lg font-semibold text-[#1b1b18]">
                        Desa {{ $desa->nama_desa }}
                    </h2>
                    <p class="text-sm text-[#52525b]">
                        Kode Wilayah: {{ $desa->kode_desa }}
                    </p>
                </div>

                <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                    Profil Desa Aktif
                </span>
            </div>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                <div>
                    <dt class="text-[#52525b] font-medium">Total KK</dt>
                    <dd class="mt-1 text-[#1b1b18] font-medium">
                        {{ is_null($desa->jumlah_kk_total) ? '-' : number_format((int) $desa->jumlah_kk_total) }}
                    </dd>
                </div>

                <div>
                    <dt class="text-[#52525b] font-medium">Total Jiwa</dt>
                    <dd class="mt-1 text-[#1b1b18] font-medium">
                        {{ is_null($desa->jumlah_jiwa_total) ? '-' : number_format((int) $desa->jumlah_jiwa_total) }}
                    </dd>
                </div>

                <div>
                    <dt class="text-[#52525b] font-medium">Kelompok Rentan</dt>
                    <dd class="mt-1 text-[#1b1b18] font-medium">
                        Balita: {{ is_null($desa->jumlah_balita) ? '-' : number_format((int) $desa->jumlah_balita) }},
                        Lansia: {{ is_null($desa->jumlah_lansia) ? '-' : number_format((int) $desa->jumlah_lansia) }},
                        Disabilitas: {{ is_null($desa->jumlah_disabilitas) ? '-' : number_format((int) $desa->jumlah_disabilitas) }}
                    </dd>
                </div>

                <div>
                    <dt class="text-[#52525b] font-medium">Titik Drop Air</dt>
                    <dd class="mt-1 text-[#1b1b18] font-medium">
                        {{ $desa->titik_drop_air ?: '-' }}
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
                            Belum diisi.
                        @endif
                    </dd>
                </div>
            </dl>

            <!-- Aksi Layanan Darurat Air Bersih -->
            <div class="border-t border-[#e3e3e0] pt-6">
                <h3 class="text-sm font-bold text-[#1b1b18] uppercase tracking-wider mb-4">
                    Layanan Bantuan Air Bersih
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="border border-[#cae4ef] bg-[#f0f8fb] rounded-xl p-5 space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">💧</span>
                            <h4 class="font-bold text-[#086288] text-base">Cek Kelayakan Air Mandiri</h4>
                        </div>
                        <p class="text-xs text-[#52525b] leading-relaxed">
                            Simulasi perhitungan skor kedaruratan air desa berbasis metode AHP-SAW untuk menentukan kelayakan bantuan (Aman / Waspada / Kritis).
                        </p>
                        <a href="{{ route('cek-kelayakan.index') }}" class="inline-flex items-center px-4 py-2 bg-[#086288] hover:bg-[#064b68] text-white text-xs font-bold rounded-lg transition">
                            Mulai Cek Kelayakan &rarr;
                        </a>
                    </div>

                    <div class="border border-[#e3e3e0] bg-white rounded-xl p-5 space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">📋</span>
                            <h4 class="font-bold text-[#1b1b18] text-base">Pengajuan Bantuan Air</h4>
                        </div>
                        <p class="text-xs text-[#52525b] leading-relaxed">
                            Susun draf atau kirimkan formulir pengajuan bantuan pasokan air bersih darurat ke BPBD Kabupaten Purbalingga.
                        </p>
                        <a href="{{ route('pengajuan.index') }}" class="inline-flex items-center px-4 py-2 border border-[#d1d5db] hover:border-[#9ca3af] text-[#1b1b18] text-xs font-bold rounded-lg transition">
                            Kelola Pengajuan &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="bg-white border border-[#e3e3e0] rounded-lg p-6 shadow-sm space-y-2">
            <h2 class="text-lg font-semibold text-[#1b1b18]">
                Halo, {{ $user?->username }}
            </h2>
            <p class="text-sm text-[#52525b]">
                Akun ini belum tertaut ke data desa.
            </p>
        </section>
    @endif
</div>
@endsection
