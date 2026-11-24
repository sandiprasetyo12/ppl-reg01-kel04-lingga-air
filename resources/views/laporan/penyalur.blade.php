@extends('layouts.dashboard')

@section('title', 'Rekap Penyaluran Air - ' . ($penyalur->nama_penyalur ?? 'Mitra Penyalur') . ' | LinggaAir')
@section('page-title', 'Rekap Penyaluran Armada')

@section('content')
<div class="space-y-3">

    <!-- 1. HEADER BANNER MITRA PENYALUR & ISOLASI DATA (PBI-023) -->
    <div class="bg-white border border-[#E4E7EC] rounded-xl p-3.5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center text-sm shrink-0">
                <i class="fa-solid fa-truck-droplet"></i>
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-sm font-bold text-[#101828]">
                        {{ $penyalur->nama_penyalur }}
                    </h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-semibold bg-[#ECFDF3] text-[#027A48] border border-[#A6F4C5]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#027A48] mr-1"></span>
                        Armada Aktif
                    </span>
                </div>
                <p class="text-[10px] text-[#667085] mt-0.5 flex flex-wrap items-center gap-3">
                    <span><i class="fa-solid fa-phone text-[#00629B] mr-1"></i> Kontak: {{ $penyalur->no_telepon ?? '-' }}</span>
                    <span><i class="fa-solid fa-gauge-high text-[#00629B] mr-1"></i> Kapasitas Tangki: {{ number_format($penyalur->kapasitas_tangki_liter ?? 5000, 0, ',', '.') }} L</span>
                </p>
                <!-- Notice Privasi & Isolasi Data Sesuai PBI-023 -->
                <div class="mt-1.5 text-[9.5px] text-[#00629B] bg-[#EBF5FB] border border-[#BAE3F5] px-2 py-0.5 rounded inline-flex items-center gap-1">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Rekap terisolasi khusus data armada Anda. Data mitra lain tidak dapat diakses.</span>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi Ekspor (PBI-024) -->
        <div class="flex flex-wrap items-center gap-2 self-start md:self-center">
            <a 
                href="{{ route('penyalur.laporan.export', array_merge(request()->query(), ['format' => 'excel'])) }}" 
                class="h-8 px-3 inline-flex items-center gap-1.5 text-[11px] font-semibold text-[#027A48] bg-[#ECFDF3] border border-[#A6F4C5] rounded-lg hover:bg-emerald-100 transition shadow-xs"
                title="Unduh laporan Excel armada Anda"
            >
                <i class="fa-solid fa-file-excel text-[11px]"></i>
                <span>Ekspor Excel</span>
            </a>
            <a 
                href="{{ route('penyalur.laporan.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" 
                target="_blank"
                class="h-8 px-3 inline-flex items-center gap-1.5 text-[11px] font-semibold text-white bg-[#00629B] hover:bg-[#004F7D] rounded-lg transition shadow-xs"
                title="Cetak Berkas PDF Resmi Penyaluran"
            >
                <i class="fa-solid fa-file-pdf text-[11px]"></i>
                <span>Ekspor PDF</span>
            </a>
        </div>
    </div>

    <!-- 2. KPI SUMMARY CARDS KINERJA PENYALUR -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
        <!-- Volume Terkirim -->
        <div class="bg-white border border-[#E4E7EC] p-3 rounded-xl shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[9.5px] font-bold text-[#667085] uppercase tracking-wider">Total Air Disalurkan</p>
                <h3 class="text-base sm:text-lg font-bold text-[#101828] mt-0.5">
                    {{ number_format($ringkasan['total_volume_liter'] ?? 0, 0, ',', '.') }}
                    <span class="text-[10px] font-normal text-[#667085]">Liter</span>
                </h3>
                <p class="text-[9.5px] text-[#027A48] font-medium mt-0.5">
                    <i class="fa-solid fa-check"></i> Sukses Terdistribusi
                </p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center text-sm">
                <i class="fa-solid fa-droplet"></i>
            </div>
        </div>

        <!-- Ritase Tangki -->
        <div class="bg-white border border-[#E4E7EC] p-3 rounded-xl shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[9.5px] font-bold text-[#667085] uppercase tracking-wider">Ritase Distribusi</p>
                <h3 class="text-base sm:text-lg font-bold text-[#101828] mt-0.5">
                    {{ number_format($ringkasan['total_tangki'] ?? 0, 0, ',', '.') }}
                    <span class="text-[10px] font-normal text-[#667085]">Trip</span>
                </h3>
                <p class="text-[9.5px] text-[#475467] font-medium mt-0.5">
                    Total pengiriman tangki
                </p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-[#FFFAEB] border border-[#FEDF89] text-[#B54708] flex items-center justify-center text-sm">
                <i class="fa-solid fa-truck"></i>
            </div>
        </div>

        <!-- Desa Terlayani -->
        <div class="bg-white border border-[#E4E7EC] p-3 rounded-xl shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[9.5px] font-bold text-[#667085] uppercase tracking-wider">Desa Dijangkau</p>
                <h3 class="text-base sm:text-lg font-bold text-[#101828] mt-0.5">
                    {{ $ringkasan['total_desa'] ?? 0 }}
                    <span class="text-[10px] font-normal text-[#667085]">Desa</span>
                </h3>
                <p class="text-[9.5px] text-[#00629B] font-medium mt-0.5">
                    Titik penerima bantuan
                </p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center text-sm">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
        </div>

        <!-- Status Distribusi -->
        <div class="bg-white border border-[#E4E7EC] p-3 rounded-xl shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[9.5px] font-bold text-[#667085] uppercase tracking-wider">Status Operasional</p>
                <div class="flex items-baseline gap-1.5 mt-0.5">
                    <span class="text-base sm:text-lg font-bold text-[#027A48]">{{ $ringkasan['total_selesai'] ?? 0 }}</span>
                    <span class="text-[10px] text-[#667085]">Tuntas</span>
                    <span class="text-[10px] text-[#667085]">&bull;</span>
                    <span class="text-[12px] font-bold text-[#B54708]">{{ $ringkasan['total_proses'] ?? 0 }}</span>
                    <span class="text-[10px] text-[#667085]">Jalan</span>
                </div>
                <p class="text-[9.5px] text-[#667085] mt-0.5">
                    Total catatan: <strong class="text-[#101828]">{{ $ringkasan['total_semua'] ?? 0 }}</strong>
                </p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-[#ECFDF3] border border-[#A6F4C5] text-[#027A48] flex items-center justify-center text-sm">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
        </div>
    </div>

    <!-- 3. FILTER REKAP PENYALUR (PBI-023) -->
    <div class="bg-white border border-[#E4E7EC] rounded-xl p-3 shadow-xs">
        <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-[#EAECF0]">
            <div class="flex items-center gap-1.5">
                <i class="fa-solid fa-filter text-[#00629B] text-xs"></i>
                <h2 class="text-xs font-bold text-[#101828]">Filter Rekapitulasi Kerja</h2>
            </div>
            @if(request()->hasAny(['start_date', 'end_date', 'kecamatan', 'status']))
                <a href="{{ route('penyalur.laporan.index') }}" class="text-[10px] font-medium text-[#667085] hover:text-[#B42318] flex items-center gap-1">
                    <i class="fa-solid fa-rotate-left text-[9px]"></i> Reset Filter
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('penyalur.laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
            <!-- Filter Tanggal Mulai -->
            <div>
                <label class="block text-[10px] font-semibold text-[#475467] mb-1">Tanggal Mulai</label>
                <input 
                    type="date" 
                    name="start_date" 
                    value="{{ request('start_date') }}" 
                    class="w-full h-8 px-2.5 text-[11px] border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B] bg-white text-[#101828]"
                >
            </div>

            <!-- Filter Tanggal Selesai -->
            <div>
                <label class="block text-[10px] font-semibold text-[#475467] mb-1">Tanggal Selesai</label>
                <input 
                    type="date" 
                    name="end_date" 
                    value="{{ request('end_date') }}" 
                    class="w-full h-8 px-2.5 text-[11px] border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B] bg-white text-[#101828]"
                >
            </div>

            <!-- Filter Wilayah Kecamatan -->
            <div>
                <label class="block text-[10px] font-semibold text-[#475467] mb-1">Kecamatan Tujuan</label>
                <select 
                    name="kecamatan" 
                    class="w-full h-8 px-2.5 text-[11px] border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B] bg-white text-[#344054] cursor-pointer"
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
            <div class="flex items-end gap-1.5">
                <div class="flex-1">
                    <label class="block text-[10px] font-semibold text-[#475467] mb-1">Status Pengiriman</label>
                    <select 
                        name="status" 
                        class="w-full h-8 px-2.5 text-[11px] border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B] bg-white text-[#344054] cursor-pointer"
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
                    class="h-8 px-3.5 bg-[#00629B] hover:bg-[#004F7D] text-white text-[11px] font-semibold rounded-lg transition flex items-center justify-center shrink-0 shadow-xs cursor-pointer"
                >
                    <i class="fa-solid fa-magnifying-glass mr-1 text-[9px]"></i>
                    Terapkan
                </button>
            </div>
        </form>
    </div>

    <!-- 4. TABEL REKAP OPERASIONAL PENYALUR (PBI-023) -->
    <div class="bg-white border border-[#E4E7EC] rounded-xl shadow-xs overflow-hidden">
        <div class="p-3 border-b border-[#EAECF0] flex flex-wrap items-center justify-between gap-2 bg-white">
            <div>
                <h2 class="text-xs font-bold text-[#101828]">Riwayat Distribusi Air Bersih Armada</h2>
                <p class="text-[10px] text-[#667085] mt-0.5">Daftar penugasan dan serah terima air bersih oleh {{ $penyalur->nama_penyalur }}</p>
            </div>
            <span class="text-[10px] font-semibold text-[#00629B] bg-[#EBF5FB] border border-[#BAE3F5] px-2 py-0.5 rounded-md">
                Total Penyaluran: <strong class="text-[#00629B]">{{ $penyaluran->total() }}</strong> baris
            </span>
        </div>

        @if($penyaluran->isEmpty())
            <!-- EMPTY STATE -->
            <div class="p-8 text-center flex flex-col items-center justify-center">
                <div class="w-10 h-10 rounded-lg bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center text-lg mb-2">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>
                <h3 class="text-xs font-bold text-[#101828]">Data penyaluran tidak tersedia</h3>
                <p class="text-[10px] text-[#667085] max-w-md mt-0.5 mb-3">
                    Tidak ditemukan catatan penyaluran air armada Anda untuk filter yang dipilih. Silakan ubah filter periode atau reset pencarian.
                </p>
                @if(request()->hasAny(['start_date', 'end_date', 'kecamatan', 'status']))
                    <a href="{{ route('penyalur.laporan.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1 text-[11px] font-semibold text-white bg-[#00629B] hover:bg-[#004F7D] rounded-lg transition shadow-xs">
                        <i class="fa-solid fa-rotate-left text-[9px]"></i>
                        Reset Filter
                    </a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-[10.5px]">
                    <thead>
                        <tr class="bg-[#F8FAFC] border-b border-[#EAECF0] text-[#475467] font-bold uppercase tracking-wider text-[9.5px]">
                            <th class="py-2 px-2.5 text-center w-10">No</th>
                            <th class="py-2 px-2.5">Kode & No Tiket</th>
                            <th class="py-2 px-2.5">Tanggal Pengiriman</th>
                            <th class="py-2 px-2.5">Tujuan & Titik Distribusi</th>
                            <th class="py-2 px-2.5">Armada / Driver</th>
                            <th class="py-2 px-2.5 text-right">Volume (Liter)</th>
                            <th class="py-2 px-2.5">Penerima Manfaat</th>
                            <th class="py-2 px-2.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAECF0] text-[10px]">
                        @foreach($penyaluran as $index => $item)
                            <tr class="hover:bg-[#F8FAFC] transition">
                                <!-- No: Plain number without circles -->
                                <td class="py-2 px-2.5 text-center text-[#475467] font-medium text-[10.5px]">
                                    {{ $penyaluran->firstItem() + $index }}
                                </td>

                                <!-- Kode Penyaluran -->
                                <td class="py-2 px-2.5 font-semibold">
                                    <span class="text-[#00629B] block font-mono text-[10.5px]">{{ $item->kode_penyaluran }}</span>
                                    <span class="text-[9.5px] text-[#667085] block font-mono">Tiket: {{ $item->nomor_tiket ?? '-' }}</span>
                                </td>

                                <!-- Tanggal -->
                                <td class="py-2 px-2.5 whitespace-nowrap text-[#344054]">
                                    <span class="font-medium block">{{ $item->tanggal_penyaluran ? \Carbon\Carbon::parse($item->tanggal_penyaluran)->translatedFormat('d M Y') : '-' }}</span>
                                    <span class="text-[9px] text-[#667085]">{{ $item->tanggal_penyaluran ? \Carbon\Carbon::parse($item->tanggal_penyaluran)->diffForHumans() : '' }}</span>
                                </td>

                                <!-- Tujuan -->
                                <td class="py-2 px-2.5">
                                    <div class="font-bold text-[#101828] text-[10.5px]">
                                        Desa {{ $item->desa->nama_desa ?? 'Desa '.$item->kode_desa }}
                                    </div>
                                    <div class="text-[9.5px] text-[#475467]">
                                        Kec. {{ $item->desa->kecamatan ?? '-' }}
                                    </div>
                                    @if($item->titik_serah_terima)
                                        <div class="text-[9px] text-[#667085] mt-0.5 flex items-center gap-1">
                                            <i class="fa-solid fa-location-dot text-[#00629B] text-[8px]"></i>
                                            <span class="truncate max-w-[180px]" title="{{ $item->titik_serah_terima }}">{{ $item->titik_serah_terima }}</span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Armada -->
                                <td class="py-2 px-2.5">
                                    <div class="font-mono text-[10.5px] font-semibold text-[#101828]">
                                        {{ $item->armada_nopol ?? '-' }}
                                    </div>
                                    <div class="text-[9px] text-[#667085]">
                                        Driver: <span class="font-medium text-[#344054]">{{ $item->nama_driver ?? '-' }}</span>
                                    </div>
                                </td>

                                <!-- Volume -->
                                <td class="py-2 px-2.5 text-right">
                                    <span class="text-[10.5px] font-bold text-[#101828] block">
                                        {{ number_format($item->volume_air_liter, 0, ',', '.') }} L
                                    </span>
                                    <span class="text-[9px] text-[#667085]">
                                        {{ $item->jumlah_tangki }} Tangki
                                    </span>
                                </td>

                                <!-- Penerima -->
                                <td class="py-2 px-2.5">
                                    <div class="font-medium text-[#101828]">
                                        {{ $item->nama_penerima ?? '-' }}
                                    </div>
                                    @if($item->no_telepon_penerima)
                                        <div class="text-[9px] text-[#667085]">
                                            <i class="fa-solid fa-phone text-[7.5px] text-[#00629B]"></i> {{ $item->no_telepon_penerima }}
                                        </div>
                                    @endif
                                    @if($item->foto_bukti)
                                        <a href="{{ $item->foto_bukti }}" target="_blank" class="inline-flex items-center gap-1 text-[9px] font-semibold text-[#00629B] hover:underline mt-0.5">
                                            <i class="fa-solid fa-image"></i> BAST Foto
                                        </a>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="py-2 px-2.5 text-center whitespace-nowrap">
                                    @if($item->status === 'Terkirim')
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-semibold bg-[#ECFDF3] text-[#027A48] border border-[#A6F4C5]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#027A48]"></span>
                                            Terkirim
                                        </span>
                                    @elseif($item->status === 'Dalam Pengiriman')
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-semibold bg-[#EBF5FB] text-[#00629B] border border-[#BAE3F5]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#00629B] animate-pulse"></span>
                                            Pengiriman
                                        </span>
                                    @elseif($item->status === 'Dijadwalkan')
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-semibold bg-[#FFFAEB] text-[#B54708] border border-[#FEDF89]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#B54708]"></span>
                                            Dijadwalkan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-semibold bg-[#FEF3F2] text-[#B42318] border border-[#FECDCA]">
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
            <div class="p-3 border-t border-[#EAECF0] bg-[#F8FAFC]">
                {{ $penyaluran->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
