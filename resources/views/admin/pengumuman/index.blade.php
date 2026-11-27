@extends('layouts.dashboard')

@section('title', 'Kelola Pengumuman Kedaruratan - LinggaAir')
@section('page-title', 'Pengumuman Kedaruratan')

@section('content')
<div class="space-y-6">

    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#EAECF0]">
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-[#101828] tracking-tight">
                Pengumuman Status Kedaruratan
            </h1>
            <p class="text-xs text-[#667085] mt-1">
                Kelola maklumat resmi, status darurat kekeringan, dan kebijakan distribusi air ke seluruh desa.
            </p>
        </div>

        <div class="flex items-center gap-2.5 self-start sm:self-center">
            <a href="{{ route('admin.pengumuman.create') }}" class="h-9 px-3.5 inline-flex items-center gap-2 rounded-lg bg-[#00629B] hover:bg-[#005180] text-white text-xs font-semibold shadow-xs transition-colors">
                <i class="fa-solid fa-plus text-[11px]"></i>
                <span>Buat Pengumuman Baru</span>
            </a>
        </div>
    </div>

    {{-- Filter & Search Toolbar --}}
    <div class="bg-white border border-[#EAECF0] rounded-xl p-4 shadow-xs">
        <form action="{{ route('admin.pengumuman.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex min-w-0 items-center gap-2">
                <span class="shrink-0 text-xs font-medium text-[#475467]">Filter Status:</span>
                <div class="min-w-0 max-w-full flex-1 overflow-x-auto">
                    <div class="flex w-max items-center gap-2 whitespace-nowrap">
                        <a href="{{ route('admin.pengumuman.index', array_filter(['q' => $search])) }}"
                           class="shrink-0 px-2.5 py-1 rounded-md text-xs font-medium transition-colors {{ empty($status) ? 'bg-[#00629B] text-white' : 'bg-[#F2F4F7] text-[#344054] hover:bg-[#E4E7EC]' }}">
                            Semua ({{ $totalCount }})
                        </a>
                        <a href="{{ route('admin.pengumuman.index', array_filter(['status' => 'kritis', 'q' => $search])) }}"
                           class="shrink-0 px-2.5 py-1 rounded-md text-xs font-medium transition-colors {{ $status === 'kritis' ? 'bg-[#D92D20] text-white' : 'bg-[#FEF3F2] text-[#B42318] hover:bg-[#FEE4E2]' }}">
                            Kritis ({{ $statusCounts->get('kritis', 0) }})
                        </a>
                        <a href="{{ route('admin.pengumuman.index', array_filter(['status' => 'waspada', 'q' => $search])) }}"
                           class="shrink-0 px-2.5 py-1 rounded-md text-xs font-medium transition-colors {{ $status === 'waspada' ? 'bg-[#B54708] text-white' : 'bg-[#FEF0C7] text-[#B54708] hover:bg-[#FEDF89]' }}">
                            Waspada ({{ $statusCounts->get('waspada', 0) }})
                        </a>
                        <a href="{{ route('admin.pengumuman.index', array_filter(['status' => 'aman', 'q' => $search])) }}"
                           class="shrink-0 px-2.5 py-1 rounded-md text-xs font-medium transition-colors {{ $status === 'aman' ? 'bg-[#027A48] text-white' : 'bg-[#ECFDF3] text-[#027A48] hover:bg-[#D1FADF]' }}">
                            Aman ({{ $statusCounts->get('aman', 0) }})
                        </a>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <div class="relative w-full sm:w-64">
                    @if ($status)
                        <input type="hidden" name="status" value="{{ $status }}">
                    @endif
                    <input
                        type="text"
                        name="q"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari judul / isi..."
                        class="w-full h-8 pl-8 pr-3 bg-white border border-[#D0D5DD] rounded-lg text-xs text-[#344054] placeholder-[#98A2B3] focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]"
                    >
                    <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-[#98A2B3]"></i>
                </div>
                <button type="submit" class="h-8 px-3 rounded-lg bg-[#F2F4F7] hover:bg-[#E4E7EC] text-[#344054] text-xs font-semibold transition-colors">
                    Cari
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Daftar Pengumuman --}}
    <div class="bg-white border border-[#EAECF0] rounded-xl overflow-hidden shadow-xs">
        @if ($pengumumans->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center px-4">
                <div class="w-12 h-12 rounded-full bg-[#F2F4F7] text-[#667085] flex items-center justify-center text-lg mb-3">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <h3 class="text-sm font-bold text-[#101828]">Belum ada pengumuman</h3>
                <p class="text-xs text-[#667085] mt-1 max-w-sm">
                    @if ($status)
                        Belum ada pengumuman dengan status {{ ucfirst($status) }}
                    @else
                        Belum ada pengumuman kedaruratan yang diterbitkan atau sesuai kriteria pencarian.
                    @endif
                </p>
                <a href="{{ route('admin.pengumuman.create') }}" class="mt-4 px-3.5 py-2 inline-flex items-center gap-1.5 rounded-lg bg-[#00629B] hover:bg-[#005180] text-white text-xs font-semibold shadow-xs transition-colors">
                    <i class="fa-solid fa-plus text-[11px]"></i>
                    <span>Buat Pengumuman Baru</span>
                </a>
            </div>
        @else
            <div class="overflow-x-auto px-2 sm:px-4">
                <table class="w-full min-w-[820px] text-left text-xs">
                    <thead class="bg-[#F8FAFC] border-b border-[#EAECF0] text-[#475467] font-semibold">
                        <tr>
                            <th class="py-3 px-4">Judul & Ringkasan</th>
                            <th class="py-3 px-4 w-32">Kedaruratan</th>
                            <th class="py-3 px-4 w-36">Waktu Terbit</th>
                            <th class="py-3 px-4 w-36 whitespace-nowrap">Status Publikasi</th>
                            <th class="py-3 pl-4 pr-6 w-36 text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAECF0] text-[#344054]">
                        @foreach ($pengumumans as $item)
                            <tr class="hover:bg-[#F8FAFC]/75 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-[#101828] text-sm">
                                        {{ $item->judul }}
                                    </div>
                                    <div class="text-[#667085] text-xs line-clamp-2 mt-0.5">
                                        {{ Str::limit(strip_tags($item->isi), 120) }}
                                    </div>
                                    <div class="text-[11px] text-[#98A2B3] mt-1 flex items-center gap-1.5">
                                        <i class="fa-regular fa-user text-[10px]"></i>
                                        <span>Dibuat oleh: {{ $item->creator->username ?? 'Admin BPBD' }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $item->badge_class }}">
                                        @if ($item->tingkat_kelayakan === 'kritis')
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#D92D20]"></span>
                                        @elseif ($item->tingkat_kelayakan === 'waspada')
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#B54708]"></span>
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#027A48]"></span>
                                        @endif
                                        <span>{{ $item->tingkat_label }}</span>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-[#667085] whitespace-nowrap">
                                    <div>{{ $item->published_at ? $item->published_at->translatedFormat('d M Y') : '-' }}</div>
                                    <div class="text-[11px] text-[#98A2B3]">{{ $item->published_at ? $item->published_at->format('H:i') . ' WIB' : '' }}</div>
                                </td>
                                <td class="py-3.5 px-4 w-36 whitespace-nowrap">
                                    <form action="{{ route('admin.pengumuman.toggle-aktif', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            type="submit"
                                            title="Klik untuk ubah status aktif/nonaktif"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold transition-colors border {{ $item->is_active ? 'bg-[#ECFDF3] border-[#A6F4C5] text-[#027A48] hover:bg-[#D1FADF]' : 'bg-[#F2F4F7] border-[#EAECF0] text-[#667085] hover:bg-[#E4E7EC]' }}"
                                        >
                                            <i class="fa-solid {{ $item->is_active ? 'fa-circle-check text-[#027A48]' : 'fa-circle-pause text-[#667085]' }}"></i>
                                            <span>{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                        </button>
                                    </form>
                                </td>
                                <td class="py-3.5 pl-4 pr-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            href="{{ route('admin.pengumuman.edit', $item) }}"
                                            class="h-7 px-2.5 rounded-md border border-[#D0D5DD] bg-white hover:bg-[#F2F4F7] text-[#344054] inline-flex items-center gap-1 transition-colors text-xs font-medium"
                                            title="Ubah Pengumuman"
                                        >
                                            <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                            <span>Ubah</span>
                                        </a>

                                        <form action="{{ route('admin.pengumuman.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="h-7 px-2 rounded-md border border-[#FECDCA] bg-white hover:bg-[#FEF3F2] text-[#B42318] inline-flex items-center transition-colors text-xs"
                                                title="Hapus Pengumuman"
                                            >
                                                <i class="fa-regular fa-trash-can text-[11px]"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($pengumumans->hasPages())
                <div class="p-3.5 border-t border-[#EAECF0] bg-white">
                    {{ $pengumumans->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
