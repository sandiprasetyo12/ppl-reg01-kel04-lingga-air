@extends('layouts.dashboard')

@section('title', 'Detail Desa ' . $desa->nama_desa . ' - LinggaAir')
@section('page-title', 'Detail Data Desa')

@section('content')
@php
    $hasKoordinat = !is_null($desa->latitude) && !is_null($desa->longitude) && (float) $desa->latitude !== 0.0 && (float) $desa->longitude !== 0.0;
@endphp

<div class="w-full space-y-3">
    <!-- Tombol Aksi (Ramping) -->
    <div class="flex flex-wrap items-center justify-between gap-2">
        <a
            href="{{ route('admin.desa.index') }}"
            class="h-8 inline-flex items-center gap-1.5 px-3 bg-white border border-[#D0D5DD] rounded-lg text-[11px] font-semibold text-[#344054] hover:bg-[#F2F4F7] transition shadow-xs cursor-pointer"
        >
            <i class="fa-solid fa-arrow-left text-[9px]"></i>
            <span>Kembali ke Daftar</span>
        </a>
        <a
            href="{{ route('admin.desa.edit', $desa->kode_desa) }}"
            class="h-8 inline-flex items-center gap-1.5 px-3.5 bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white rounded-lg text-[11px] font-semibold transition shadow-xs cursor-pointer"
        >
            <i class="fa-solid fa-pen-to-square text-[9px]"></i>
            <span>Edit Data Desa</span>
        </a>
    </div>

    <!-- Detail Informasi Desa (IDDS Color Palette) -->
    <section class="bg-white border border-[#E4E7EC] rounded-xl p-4 shadow-xs space-y-3.5">
        <div class="border-b border-[#EAECF0] pb-3 flex flex-wrap items-start justify-between gap-2">
            <div>
                <h2 class="text-sm sm:text-base font-bold text-[#101828]">
                    Desa {{ $desa->nama_desa }}
                </h2>
                <p class="text-[10px] text-[#667085] mt-0.5">
                    Kecamatan {{ $desa->kecamatan }} &bull; Kode Wilayah: <span class="font-mono text-[#00629B] font-semibold">{{ $desa->kode_desa }}</span>
                </p>
            </div>

            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-semibold {{ $desa->user ? 'bg-[#ECFDF3] text-[#027A48] border border-[#A6F4C5]' : 'bg-[#F2F4F7] text-[#667085] border border-[#EAECF0]' }}">
                @if($desa->user)
                    <span class="w-1.5 h-1.5 rounded-full bg-[#027A48]"></span>
                    Akun Terhubung
                @else
                    Belum Ada Akun
                @endif
            </span>
        </div>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-[11px]">
            <div>
                <dt class="text-[9.5px] font-bold text-[#667085] uppercase tracking-wider">Status Akun Perwakilan</dt>
                <dd class="mt-0.5 text-[#101828] font-medium">
                    @if ($desa->user)
                        Terhubung ke username: <strong class="text-[#00629B]">{{ $desa->user->username }}</strong>
                    @else
                        <span class="text-[#667085] italic">Belum didaftarkan oleh perwakilan desa</span>
                    @endif
                </dd>
            </div>

            <div>
                <dt class="text-[9.5px] font-bold text-[#667085] uppercase tracking-wider">Titik Drop Air Bersih</dt>
                <dd class="mt-0.5 text-[#101828] font-medium flex items-center gap-1.5">
                    <i class="fa-solid fa-truck-droplet text-[#00629B] text-[10px]"></i>
                    <span>{{ $desa->titik_drop_air ?: 'Belum ditentukan' }}</span>
                </dd>
            </div>

            <div>
                <dt class="text-[9.5px] font-bold text-[#667085] uppercase tracking-wider">Total Kependudukan</dt>
                <dd class="mt-0.5 text-[#101828] font-medium">
                    <strong>{{ number_format((int) ($desa->jumlah_kk_total ?? 0)) }}</strong> KK &bull; <strong>{{ number_format((int) ($desa->jumlah_jiwa_total ?? 0)) }}</strong> Jiwa
                </dd>
            </div>

            <div>
                <dt class="text-[9.5px] font-bold text-[#667085] uppercase tracking-wider">Kelompok Rentan</dt>
                <dd class="mt-0.5 text-[#344054]">
                    Balita: <strong class="text-[#00629B]">{{ number_format((int) ($desa->jumlah_balita ?? 0)) }}</strong>,
                    Lansia: <strong class="text-purple-600">{{ number_format((int) ($desa->jumlah_lansia ?? 0)) }}</strong>,
                    Disabilitas: <strong class="text-amber-600">{{ number_format((int) ($desa->jumlah_disabilitas ?? 0)) }}</strong>
                </dd>
            </div>

            <div>
                <dt class="text-[9.5px] font-bold text-[#667085] uppercase tracking-wider">Penanggung Jawab Darurat</dt>
                <dd class="mt-0.5 text-[#101828] font-medium">
                    {{ $desa->nama_pj_darurat ?: '-' }}
                </dd>
            </div>

            <div>
                <dt class="text-[9.5px] font-bold text-[#667085] uppercase tracking-wider">No. WhatsApp Darurat</dt>
                <dd class="mt-0.5 text-[#101828] font-medium flex items-center gap-1.5">
                    @if($desa->no_wa_darurat)
                        <i class="fa-brands fa-whatsapp text-emerald-500"></i>
                        <span>{{ $desa->no_wa_darurat }}</span>
                    @else
                        <span>-</span>
                    @endif
                </dd>
            </div>

            <div class="sm:col-span-2 p-3 bg-[#F8FAFC] border border-[#E4E7EC] rounded-lg">
                <dt class="text-[9.5px] font-bold text-[#667085] uppercase tracking-wider">Koordinat Titik Tandon (GIS)</dt>
                <dd class="mt-0.5 font-mono text-[10.5px] text-[#101828]">
                    @if ($hasKoordinat)
                        <span class="text-[#00629B]">Lat: {{ $desa->latitude }}</span>, <span class="text-[#00629B]">Lng: {{ $desa->longitude }}</span>
                    @else
                        <span class="text-[#667085] italic">Belum dilengkapi (Lat: -, Lng: -)</span>
                    @endif
                </dd>
            </div>
        </dl>
    </section>
</div>
@endsection
