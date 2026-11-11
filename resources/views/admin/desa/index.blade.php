@extends('layouts.dashboard')

@section('title', 'Kelola Data Desa - LinggaAir')
@section('page-title', 'Master Data Desa & Kelurahan')

@section('content')
@php
    $hasActiveFilter = request()->filled('search') || request()->filled('kecamatan');
    $kecamatanCount = $kecamatans->count();
@endphp

<div class="space-y-6">
    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" class="text-sm text-[#52525b] flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-[#1b1b18] underline min-h-[44px] inline-flex items-center">
            Dashboard
        </a>
        <span aria-hidden="true">/</span>
        <span class="text-[#1b1b18] font-medium">Master Data Desa</span>
    </nav>

    <!-- Pencarian & Filter Kecamatan -->
    <section aria-label="Pencarian dan filter desa" class="bg-white border border-[#e3e3e0] rounded-lg p-5 shadow-sm">
        <form action="{{ route('admin.desa.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-5">
                <label for="search" class="block text-sm font-medium text-[#1b1b18] mb-1">
                    Cari Desa atau Kode Wilayah
                </label>
                <input
                    id="search"
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Nama desa atau kode wilayah..."
                    class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                >
            </div>

            <div class="md:col-span-4">
                <label for="kecamatan" class="block text-sm font-medium text-[#1b1b18] mb-1">
                    Filter Kecamatan
                </label>
                <select
                    id="kecamatan"
                    name="kecamatan"
                    class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-base text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                >
                    <option value="">Semua Kecamatan ({{ $kecamatanCount }} Kecamatan)</option>
                    @foreach ($kecamatans as $kec)
                        <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>
                            Kecamatan {{ $kec }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3 flex items-center gap-2">
                <button
                    type="submit"
                    class="flex-1 min-h-[44px] inline-flex items-center justify-center px-4 py-2 bg-[#1b1b18] hover:bg-black text-white text-sm font-medium rounded-md transition"
                >
                    Filter
                </button>

                @if ($hasActiveFilter)
                    <a
                        href="{{ route('admin.desa.index') }}"
                        class="min-h-[44px] inline-flex items-center justify-center px-4 py-2 bg-white border border-[#d1d5db] rounded-md text-sm font-medium text-[#1b1b18] hover:bg-[#f5f5f4] transition"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </section>

    <!-- Tabel Daftar Desa -->
    <section aria-label="Daftar wilayah desa dan kelurahan" class="bg-white border border-[#e3e3e0] rounded-lg shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-[#e3e3e0] flex flex-wrap items-center justify-between gap-2">
            <h2 class="text-base font-semibold text-[#1b1b18]">
                Daftar Wilayah Desa
            </h2>
            <span class="text-sm text-[#52525b]">
                Total: <strong class="font-medium text-[#1b1b18]">{{ number_format($desas->total()) }}</strong> wilayah
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#e3e3e0] text-sm text-left">
                <thead class="bg-[#FDFDFC] text-[#52525b] text-xs uppercase font-medium">
                    <tr>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Kode Desa</th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Nama Desa / Kelurahan</th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Kecamatan</th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Titik Drop Air</th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Status Akun</th>
                        <th scope="col" class="px-4 py-3 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e3e3e0] bg-white">
                    @forelse ($desas as $desa)
                        <tr class="hover:bg-[#FDFDFC]">
                            <td class="px-4 py-3 font-mono text-xs text-[#52525b] whitespace-nowrap">
                                {{ $desa->kode_desa }}
                            </td>
                            <td class="px-4 py-3 font-medium text-[#1b1b18] whitespace-nowrap">
                                {{ $desa->nama_desa }}
                            </td>
                            <td class="px-4 py-3 text-[#52525b] whitespace-nowrap">
                                Kec. {{ $desa->kecamatan }}
                            </td>
                            <td class="px-4 py-3 text-[#52525b]">
                                {{ $desa->titik_drop_air ?: '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if ($desa->user_id)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Terdaftar
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-[#F3F4F6] text-[#52525b] border border-[#e3e3e0]">
                                        Belum Ada Akun
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <a
                                        href="{{ route('admin.desa.show', $desa->kode_desa) }}"
                                        class="min-h-[44px] inline-flex items-center px-3 py-1.5 border border-[#d1d5db] rounded-md text-xs font-medium text-[#1b1b18] bg-white hover:bg-[#f5f5f4] transition"
                                    >
                                        Detail<span class="sr-only"> {{ $desa->nama_desa }}</span>
                                    </a>
                                    <a
                                        href="{{ route('admin.desa.edit', $desa->kode_desa) }}"
                                        class="min-h-[44px] inline-flex items-center px-3 py-1.5 bg-[#1b1b18] hover:bg-black text-white rounded-md text-xs font-medium transition"
                                    >
                                        Edit<span class="sr-only"> {{ $desa->nama_desa }}</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-[#52525b]">
                                @if ($hasActiveFilter)
                                    Tidak ada data desa yang cocok dengan pencarian atau filter kecamatan tersebut.
                                    <a href="{{ route('admin.desa.index') }}" class="underline text-[#1b1b18] font-medium ml-1">Reset filter</a>
                                @else
                                    Belum ada data desa yang tersedia di basis data master.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($desas->hasPages())
            <div class="px-5 py-4 border-t border-[#e3e3e0] bg-[#FDFDFC]">
                {{ $desas->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
