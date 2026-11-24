@extends('layouts.dashboard')

@section('title', 'Rekap Penyaluran Air - ' . ($penyalur->nama_penyalur ?? 'Mitra Penyalur') . ' | LinggaAir')
@section('page-title', 'Rekap Penyaluran Armada')

@section('content')
<div class="space-y-6">

    <!-- 1. HEADER BANNER MITRA PENYALUR & ISOLASI DATA (PBI-023) -->
    <div class="bg-white border border-[#E4E7EC] rounded-2xl p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-5">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center text-2xl shrink-0">
                <i class="fa-solid fa-truck-droplet"></i>
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-xl font-bold text-[#101828]">
                        {{ $penyalur->nama_penyalur }}
                    </h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#ECFDF3] text-[#027A48] border border-[#A6F4C5]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#027A48] mr-1"></span>
                        Armada Aktif
                    </span>
                </div>
                <p class="text-xs text-[#667085] mt-1 flex flex-wrap items-center gap-4">
                    <span><i class="fa-solid fa-phone text-[#00629B] mr-1"></i> Kontak: {{ $penyalur->no_telepon ?? '-' }}</span>
                    <span><i class="fa-solid fa-gauge-high text-[#00629B] mr-1"></i> Kapasitas Tangki: {{ number_format($penyalur->kapasitas_tangki_liter ?? 5000, 0, ',', '.') }} L</span>
                </p>
                <!-- Notice Privasi & Isolasi Data Sesuai PBI-023 -->
                <div class="mt-2 text-[11px] text-[#00629B] bg-[#EBF5FB] border border-[#BAE3F5] px-2.5 py-1 rounded-lg inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Rekap terisolasi khusus data armada Anda. Data mitra lain tidak dapat diakses.</span>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi Ekspor (PBI-024) -->
        <div class="flex flex-wrap items-center gap-2.5 self-start md:self-center">
            <a 
                href="{{ route('penyalur.laporan.export', array_merge(request()->query(), ['format' => 'excel'])) }}" 
                class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-[#027A48] bg-[#ECFDF3] border border-[#A6F4C5] rounded-xl hover:bg-emerald-100 transition shadow-xs"
                title="Unduh laporan Excel armada Anda"
            >
                <i class="fa-solid fa-file-excel text-sm"></i>
                <span>Ekspor Excel (.xls)</span>
            </a>
            <a 
                href="{{ route('penyalur.laporan.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" 
                target="_blank"
                class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-white bg-[#00629B] hover:bg-[#004F7D] rounded-xl transition shadow-xs"
                title="Cetak Berkas PDF Resmi Penyaluran"
            >
                <i class="fa-solid fa-file-pdf text-sm"></i>
                <span>Ekspor PDF</span>
            </a>
        </div>
    </div>

    <!-- 2. KPI SUMMARY CARDS KINERJA PENYALUR -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Volume Terkirim -->
        <div class="bg-white border border-[#E4E7EC] p-5 rounded-2xl shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-[#667085] uppercase tracking-wide">Total Air Disalurkan</p>
                <h3 class="text-2xl font-extrabold text-[#101828] mt-1">
                    {{ number_format($ringkasan['total_volume_liter'] ?? 0, 0, ',', '.') }}
                    <span class="text-sm font-semibold text-[#667085]">Liter</span>
                </h3>
                <p class="text-[11px] text-[#027A48] font-medium mt-1">
                    <i class="fa-solid fa-check"></i> Sukses Terdistribusi
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center text-lg">
                <i class="fa-solid fa-droplet"></i>
            </div>
        </div>

        <!-- Ritase Tangki -->
        <div class="bg-white border border-[#E4E7EC] p-5 rounded-2xl shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-[#667085] uppercase tracking-wide">Ritase Distribusi</p>
                <h3 class="text-2xl font-extrabold text-[#101828] mt-1">
                    {{ number_format($ringkasan['total_tangki'] ?? 0, 0, ',', '.') }}
                    <span class="text-sm font-semibold text-[#667085]">Trip</span>
                </h3>
                <p class="text-[11px] text-[#475467] font-medium mt-1">
                    Total pengiriman tangki
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-truck"></i>
            </div>
        </div>

        <!-- Desa Terlayani -->
        <div class="bg-white border border-[#E4E7EC] p-5 rounded-2xl shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-[#667085] uppercase tracking-wide">Desa Dijangkau</p>
                <h3 class="text-2xl font-extrabold text-[#101828] mt-1">
                    {{ $ringkasan['total_desa'] ?? 0 }}
                    <span class="text-sm font-semibold text-[#667085]">Desa</span>
                </h3>
                <p class="text-[11px] text-[#00629B] font-medium mt-1">
                    Titik penerima bantuan
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center text-lg">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
        </div>

        <!-- Status Distribusi -->
        <div class="bg-white border border-[#E4E7EC] p-5 rounded-2xl shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-[#667085] uppercase tracking-wide">Status Operasional</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-2xl font-extrabold text-[#027A48]">{{ $ringkasan['total_selesai'] ?? 0 }}</span>
                    <span class="text-xs text-[#667085]">Tuntas</span>
                    <span class="text-xs text-[#667085]">&bull;</span>
                    <span class="text-base font-bold text-amber-600">{{ $ringkasan['total_proses'] ?? 0 }}</span>
                    <span class="text-xs text-[#667085]">Jalan</span>
                </div>
                <p class="text-[11px] text-[#667085] mt-1">
                    Total catatan: <strong class="text-[#101828]">{{ $ringkasan['total_semua'] ?? 0 }}</strong>
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#ECFDF3] border border-[#A6F4C5] text-[#027A48] flex items-center justify-center text-lg">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
        </div>
    </div>

    <!-- 3. FILTER REKAP PENYALUR (PBI-023) -->
    <div class="bg-white border border-[#E4E7EC] rounded-2xl p-5 shadow-xs">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#EAECF0]">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-filter text-[#00629B] text-sm"></i>
                <h2 class="text-sm font-bold text-[#101828]">Filter Rekapitulasi Kerja</h2>
            </div>
            @if(request()->hasAny(['start_date', 'end_date', 'kecamatan', 'status']))
                <a href="{{ route('penyalur.laporan.index') }}" class="text-xs font-semibold text-[#B42318] hover:underline flex items-center gap-1">
                    <i class="fa-solid fa-rotate-left"></i> Reset Filter
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('penyalur.laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
            <!-- Filter Tanggal Mulai -->
            <div>
                <label class="block text-[11px] font-semibold text-[#475467] mb-1.5">Tanggal Mulai</label>
                <input 
                    type="date" 
                    name="start_date" 
                    value="{{ request('start_date') }}" 
                    class="w-full text-xs px-3 py-2 border border-[#D0D5DD] rounded-xl focus:ring-2 focus:ring-[#00629B] focus:border-[#00629B] bg-[#F8FAFC]"
                >
            </div>

            <!-- Filter Tanggal Selesai -->
            <div>
                <label class="block text-[11px] font-semibold text-[#475467] mb-1.5">Tanggal Selesai</label>
                <input 
                    type="date" 
                    name="end_date" 
                    value="{{ request('end_date') }}" 
                    class="w-full text-xs px-3 py-2 border border-[#D0D5DD] rounded-xl focus:ring-2 focus:ring-[#00629B] focus:border-[#00629B] bg-[#F8FAFC]"
                >
            </div>

            <!-- Filter Wilayah Kecamatan -->
            <div>
                <label class="block text-[11px] font-semibold text-[#475467] mb-1.5">Kecamatan Tujuan</label>
                <select 
                    name="kecamatan" 
                    class="w-full text-xs px-3 py-2 border border-[#D0D5DD] rounded-xl focus:ring-2 focus:ring-[#00629B] focus:border-[#00629B] bg-[#F8FAFC]"
                >
                    <option value="">Semua Kecamatan</option>
                    @foreach($kecamatanList as $kec)
                        <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>
                            Kec. {{ $kec }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status & Cari -->
            <div class="flex items-end gap-2">
                <div class="flex-1">
                    <label class="block text-[11px] font-semibold text-[#475467] mb-1.5">Status Pengiriman</label>
                    <select 
                        name="status" 
                        class="w-full text-xs px-3 py-2 border border-[#D0D5DD] rounded-xl focus:ring-2 focus:ring-[#00629B] focus:border-[#00629B] bg-[#F8FAFC]"
                    >
                        <option value="">Semua Status</option>
                        @foreach($statusList as $st)
                            <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                                {{ $st }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button 
                    type="submit" 
                    class="h-[38px] px-4 bg-[#00629B] hover:bg-[#004F7D] text-white text-xs font-semibold rounded-xl transition flex items-center justify-center shrink-0 shadow-xs"
                >
                    <i class="fa-solid fa-magnifying-glass mr-1.5"></i>
                    Terapkan
                </button>
            </div>
        </form>
    </div>

    <!-- 4. TABEL REKAP OPERASIONAL PENYALUR (PBI-023) -->
    <div class="bg-white border border-[#E4E7EC] rounded-2xl shadow-xs overflow-hidden">
        <div class="p-5 border-b border-[#EAECF0] flex flex-wrap items-center justify-between gap-4 bg-white">
            <div>
                <h2 class="text-sm font-bold text-[#101828]">Riwayat Distribusi Air Bersih Armada</h2>
                <p class="text-xs text-[#667085] mt-0.5">Daftar penugasan dan serah terima air bersih oleh {{ $penyalur->nama_penyalur }}</p>
            </div>
            <span class="text-xs font-semibold text-[#475467] bg-[#F8FAFC] border border-[#EAECF0] px-3 py-1.5 rounded-lg">
                Total Penyaluran: <strong class="text-[#00629B]">{{ $penyaluran->total() }}</strong> baris
            </span>
        </div>

        @if($penyaluran->isEmpty())
            <!-- EMPTY STATE -->
            <div class="p-12 text-center flex flex-col items-center justify-center">
                <div class="w-16 h-16 rounded-full bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>
                <h3 class="text-base font-bold text-[#101828]">Data penyaluran tidak tersedia</h3>
                <p class="text-xs text-[#667085] max-w-md mt-1 mb-5">
                    Tidak ditemukan catatan penyaluran air armada Anda untuk filter yang dipilih. Silakan ubah filter periode atau reset pencarian.
                </p>
                @if(request()->hasAny(['start_date', 'end_date', 'kecamatan', 'status']))
                    <a href="{{ route('penyalur.laporan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-[#00629B] hover:bg-[#004F7D] rounded-xl transition shadow-xs">
                        <i class="fa-solid fa-rotate-left"></i>
                        Reset Filter
                    </a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[#F8FAFC] border-b border-[#EAECF0] text-[#475467] font-bold uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-4 text-center w-12">No</th>
                            <th class="py-3 px-4">Kode & No Tiket</th>
                            <th class="py-3 px-4">Tanggal Pengiriman</th>
                            <th class="py-3 px-4">Tujuan & Titik Distribusi</th>
                            <th class="py-3 px-4">Armada / Driver</th>
                            <th class="py-3 px-4 text-right">Volume (Liter)</th>
                            <th class="py-3 px-4">Penerima Manfaat</th>
                            <th class="py-3 px-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAECF0]">
                        @foreach($penyaluran as $index => $item)
                            <tr class="hover:bg-[#F8FAFC] transition">
                                <!-- No -->
                                <td class="py-3.5 px-4 text-center text-[#667085] font-semibold">
                                    {{ $penyaluran->firstItem() + $index }}
                                </td>

                                <!-- Kode Penyaluran -->
                                <td class="py-3.5 px-4 font-semibold">
                                    <span class="text-[#00629B] block font-mono text-[11px]">{{ $item->kode_penyaluran }}</span>
                                    <span class="text-[10px] text-[#667085] block font-mono">Tiket: {{ $item->nomor_tiket ?? '-' }}</span>
                                </td>

                                <!-- Tanggal -->
                                <td class="py-3.5 px-4 whitespace-nowrap text-[#344054]">
                                    <span class="font-medium block">{{ $item->tanggal_penyaluran ? \Carbon\Carbon::parse($item->tanggal_penyaluran)->translatedFormat('d M Y') : '-' }}</span>
                                    <span class="text-[10px] text-[#667085]">{{ $item->tanggal_penyaluran ? \Carbon\Carbon::parse($item->tanggal_penyaluran)->diffForHumans() : '' }}</span>
                                </td>

                                <!-- Tujuan -->
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-[#101828]">
                                        Desa {{ $item->desa->nama_desa ?? 'Desa '.$item->kode_desa }}
                                    </div>
                                    <div class="text-[11px] text-[#475467]">
                                        Kec. {{ $item->desa->kecamatan ?? '-' }}
                                    </div>
                                    @if($item->titik_serah_terima)
                                        <div class="text-[10px] text-[#667085] mt-0.5 flex items-center gap-1">
                                            <i class="fa-solid fa-location-dot text-[#00629B] text-[9px]"></i>
                                            <span class="truncate max-w-[200px]" title="{{ $item->titik_serah_terima }}">{{ $item->titik_serah_terima }}</span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Armada -->
                                <td class="py-3.5 px-4">
                                    <div class="font-mono text-xs font-semibold text-[#101828]">
                                        {{ $item->armada_nopol ?? '-' }}
                                    </div>
                                    <div class="text-[10px] text-[#667085] mt-0.5">
                                        Driver: <span class="font-medium text-[#344054]">{{ $item->nama_driver ?? '-' }}</span>
                                    </div>
                                </td>

                                <!-- Volume -->
                                <td class="py-3.5 px-4 text-right">
                                    <span class="text-xs font-bold text-[#101828] block">
                                        {{ number_format($item->volume_air_liter, 0, ',', '.') }} L
                                    </span>
                                    <span class="text-[10px] text-[#667085]">
                                        {{ $item->jumlah_tangki }} Tangki
                                    </span>
                                </td>

                                <!-- Penerima -->
                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-[#101828]">
                                        {{ $item->nama_penerima ?? '-' }}
                                    </div>
                                    @if($item->no_telepon_penerima)
                                        <div class="text-[10px] text-[#667085]">
                                            <i class="fa-solid fa-phone text-[8px] text-[#00629B]"></i> {{ $item->no_telepon_penerima }}
                                        </div>
                                    @endif
                                    @if($item->foto_bukti)
                                        <a href="{{ $item->foto_bukti }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-semibold text-[#00629B] hover:underline mt-0.5">
                                            <i class="fa-solid fa-image"></i> BAST Foto
                                        </a>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    @if($item->status === 'Terkirim')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#ECFDF3] text-[#027A48] border border-[#A6F4C5]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#027A48]"></span>
                                            Terkirim
                                        </span>
                                    @elseif($item->status === 'Dalam Pengiriman')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#EBF5FB] text-[#00629B] border border-[#BAE3F5]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#00629B] animate-pulse"></span>
                                            Pengiriman
                                        </span>
                                    @elseif($item->status === 'Dijadwalkan')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#FFFAEB] text-[#B54708] border border-[#FEDF89]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#B54708]"></span>
                                            Dijadwalkan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#FEF3F2] text-[#B42318] border border-[#FECDCA]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#B42318]"></span>
                                            Kendala
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links (PBI-023) -->
            <div class="p-4 border-t border-[#EAECF0] bg-[#F8FAFC]">
                {{ $penyaluran->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
