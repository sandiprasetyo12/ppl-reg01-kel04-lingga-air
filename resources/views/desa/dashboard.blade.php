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
    <section class="bg-white border border-[#E4E7EC] rounded-2xl p-6 sm:p-7 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-6 relative overflow-hidden">
        <div class="space-y-2 max-w-2xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#EBF5FB] text-[#00629B] text-xs font-bold uppercase tracking-wider border border-[#BAE3F5]">
                <span class="w-2 h-2 rounded-full bg-[#00629B]"></span>
                <span>Alur Pengajuan Air Bersih</span>
            </div>
            <h2 class="text-xl font-extrabold text-[#101828]">Periksa kelayakan bantuan air bersih</h2>
            <p class="text-sm text-[#475467] leading-relaxed">
                Lakukan <strong>Cek Kelayakan Mandiri (F-02)</strong> terlebih dahulu untuk mengetahui tingkat kedaruratan desa Anda. Jika hasil bernilai Waspada atau Kritis, sistem akan langsung membuka akses formulir <strong>Pengajuan Bantuan Air Bersih (F-03)</strong>.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <a
                href="{{ route('cek-kelayakan.index') }}"
                class="min-h-[46px] inline-flex items-center justify-center px-6 py-2.5 bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-xs transition gap-2"
            >
                <i class="fa-solid fa-calculator"></i>
                <span>Mulai Cek Kelayakan</span>
            </a>
            <a
                href="{{ route('pengajuan.index') }}"
                class="min-h-[46px] inline-flex items-center justify-center px-5 py-2.5 bg-white border border-[#D0D5DD] hover:bg-[#F2F4F7] text-[#344054] text-xs font-bold uppercase tracking-wider rounded-xl transition gap-2"
            >
                <i class="fa-solid fa-file-lines"></i>
                <span>Draf & Status</span>
            </a>
        </div>
    </section>

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