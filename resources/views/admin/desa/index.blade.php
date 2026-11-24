@extends('layouts.dashboard')

@section('title', 'Kelola Data Desa - LinggaAir')
@section('page-title', 'Master Data Desa & Kelurahan')

@section('content')
@php
    $hasActiveFilter = request()->filled('search') || request()->filled('kecamatan');
    $kecamatanCount = $kecamatans->count();
@endphp

<div class="space-y-3">
    <!-- Pencarian & Filter Kecamatan (Ramping & IDDS Palette) -->
    <section aria-label="Pencarian dan filter desa" class="bg-white border border-[#E4E7EC] rounded-xl p-3 shadow-xs">
        <form action="{{ route('admin.desa.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-2 items-end">
            <div class="md:col-span-5">
                <label for="search" class="block text-[10px] font-semibold text-[#475467] mb-1">
                    Cari Desa atau Kode Wilayah
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 pointer-events-none text-[#667085]">
                        <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                    </span>
                    <input
                        id="search"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Nama desa atau kode wilayah..."
                        class="w-full h-8 pl-7 pr-3 bg-white border border-[#D0D5DD] rounded-lg text-[11px] text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]"
                    >
                </div>
            </div>

            <div class="md:col-span-4">
                <label for="kecamatan" class="block text-[10px] font-semibold text-[#475467] mb-1">
                    Filter Kecamatan
                </label>
                <select
                    id="kecamatan"
                    name="kecamatan"
                    class="w-full h-8 px-2.5 bg-white border border-[#D0D5DD] rounded-lg text-[11px] text-[#344054] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B] cursor-pointer"
                >
                    <option value="">Semua Kecamatan ({{ $kecamatanCount }} Kecamatan)</option>
                    @foreach ($kecamatans as $kec)
                        <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>
                            Kecamatan {{ $kec }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3 flex items-center gap-1.5">
                <button
                    type="submit"
                    class="flex-1 h-8 inline-flex items-center justify-center gap-1 px-3 bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white text-[11px] font-semibold rounded-lg transition shadow-xs cursor-pointer"
                >
                    <i class="fa-solid fa-filter text-[9px]"></i>
                    <span>Filter</span>
                </button>

                @if ($hasActiveFilter)
                    <a
                        href="{{ route('admin.desa.index') }}"
                        class="h-8 inline-flex items-center justify-center gap-1 px-2.5 bg-white border border-[#D0D5DD] rounded-lg text-[11px] font-medium text-[#667085] hover:text-[#101828] hover:bg-[#F2F4F7] transition"
                    >
                        <i class="fa-solid fa-rotate-left text-[9px]"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </section>

    <!-- Tabel Daftar Desa (Rapi & Selaras) -->
    <section aria-label="Daftar wilayah desa dan kelurahan" class="bg-white border border-[#E4E7EC] rounded-xl shadow-xs overflow-hidden">
        <div class="p-3 border-b border-[#EAECF0] flex flex-wrap items-center justify-between gap-2 bg-white">
            <div>
                <h2 class="text-xs font-bold text-[#101828]">
                    Daftar Wilayah Desa & Kelurahan
                </h2>
                <p class="text-[10px] text-[#667085] mt-0.5">Basis data demografi, titik drop air, dan akun perwakilan desa</p>
            </div>
            <span class="text-[10px] font-semibold text-[#00629B] bg-[#EBF5FB] border border-[#BAE3F5] px-2 py-0.5 rounded-md">
                Total: <strong class="text-[#00629B]">{{ number_format($desas->total()) }}</strong> wilayah
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-[10.5px]">
                <thead class="bg-[#F8FAFC] border-b border-[#EAECF0] text-[#475467] text-[9.5px] uppercase font-bold tracking-wider">
                    <tr>
                        <th scope="col" class="py-2 px-2.5 text-center w-10">No</th>
                        <th scope="col" class="py-2 px-2.5 whitespace-nowrap">Kode Desa</th>
                        <th scope="col" class="py-2 px-2.5 whitespace-nowrap">Nama Desa / Kelurahan</th>
                        <th scope="col" class="py-2 px-2.5 whitespace-nowrap">Kecamatan</th>
                        <th scope="col" class="py-2 px-2.5 whitespace-nowrap">Titik Drop Air</th>
                        <th scope="col" class="py-2 px-2.5 text-center whitespace-nowrap">Status Akun</th>
                        <th scope="col" class="py-2 px-2.5 text-center whitespace-nowrap w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EAECF0] text-[10px]">
                    @forelse ($desas as $index => $desa)
                        <tr class="hover:bg-[#F8FAFC] transition">
                            <!-- No: Plain text without circles -->
                            <td class="py-2 px-2.5 text-center text-[#475467] font-medium text-[10.5px]">
                                {{ $desas->firstItem() + $index }}
                            </td>
                            <td class="py-2 px-2.5 font-mono text-[10.5px] font-semibold text-[#00629B] whitespace-nowrap">
                                {{ $desa->kode_desa }}
                            </td>
                            <td class="py-2 px-2.5 font-bold text-[#101828] text-[10.5px] whitespace-nowrap">
                                Desa {{ $desa->nama_desa }}
                            </td>
                            <td class="py-2 px-2.5 text-[#475467] text-[10px] whitespace-nowrap">
                                Kec. {{ $desa->kecamatan }}
                            </td>
                            <td class="py-2 px-2.5 text-[#667085] text-[9.5px]">
                                @if($desa->titik_drop_air)
                                    <span class="flex items-center gap-1">
                                        <i class="fa-solid fa-location-dot text-[#00629B] text-[8.5px]"></i>
                                        <span class="truncate max-w-[200px]" title="{{ $desa->titik_drop_air }}">{{ $desa->titik_drop_air }}</span>
                                    </span>
                                @else
                                    <span class="text-[#98A2B3]">-</span>
                                @endif
                            </td>
                            <td class="py-2 px-2.5 text-center whitespace-nowrap">
                                @if ($desa->user_id)
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-semibold bg-[#ECFDF3] text-[#027A48] border border-[#A6F4C5]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#027A48]"></span>
                                        Terdaftar
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-semibold bg-[#F2F4F7] text-[#667085] border border-[#EAECF0]">
                                        Belum Ada Akun
                                    </span>
                                @endif
                            </td>
                            <td class="py-2 px-2.5 text-center whitespace-nowrap">
                                <div class="inline-flex items-center justify-center gap-1">
                                    <a
                                        href="{{ route('admin.desa.show', $desa->kode_desa) }}"
                                        class="h-7 px-2 bg-white border border-[#D0D5DD] rounded text-[9.5px] font-semibold text-[#344054] hover:bg-[#F2F4F7] transition shadow-xs inline-flex items-center gap-1"
                                        title="Detail {{ $desa->nama_desa }}"
                                    >
                                        <i class="fa-solid fa-eye text-[8px] text-[#00629B]"></i>
                                        <span>Detail</span>
                                    </a>
                                    <a
                                        href="{{ route('admin.desa.edit', $desa->kode_desa) }}"
                                        class="h-7 px-2 bg-[#00629B] hover:bg-[#004F7D] text-white rounded text-[9.5px] font-semibold transition shadow-xs inline-flex items-center gap-1"
                                        title="Edit {{ $desa->nama_desa }}"
                                    >
                                        <i class="fa-solid fa-pen text-[8px]"></i>
                                        <span>Edit</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-[#667085]">
                                <div class="w-8 h-8 rounded-lg bg-[#EBF5FB] text-[#00629B] flex items-center justify-center text-sm mx-auto mb-1.5">
                                    <i class="fa-solid fa-inbox"></i>
                                </div>
                                <p class="font-bold text-[11px] text-[#101828]">Tidak Ada Data Desa</p>
                                <p class="text-[10px] text-[#667085] mt-0.5">
                                    @if ($hasActiveFilter)
                                        Tidak ada data yang cocok dengan filter pencarian.
                                        <a href="{{ route('admin.desa.index') }}" class="underline text-[#00629B] font-semibold ml-1">Reset filter</a>
                                    @else
                                        Belum ada data desa yang tersedia di basis data master.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($desas->hasPages())
            <div class="p-3 border-t border-[#EAECF0] bg-[#F8FAFC]">
                {{ $desas->links() }}
            </div>
        @endif
    </section>
</div>
@endsection