<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekap Penyaluran Air Bersih - Lingga Air | BPBD Purbalingga</title>
    
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        idds: {
                            blue: {
                                DEFAULT: '#00629B',
                                hover: '#004F7D',
                                active: '#003B5C',
                                tint: '#EBF5FB',
                                stroke: '#BAE3F5',
                            },
                            neutral: {
                                heading: '#101828',
                                body: '#344054',
                                secondary: '#475467',
                                caption: '#667085',
                                strokeShell: '#EAECF0',
                                strokeCard: '#E4E7EC',
                                strokeInput: '#D0D5DD',
                                canvas: '#F8FAFC',
                            },
                            status: {
                                kritisText: '#B42318',
                                kritisBg: '#FEF3F2',
                                kritisBorder: '#FECDCA',
                                waspadaText: '#B54708',
                                waspadaBg: '#FFFAEB',
                                waspadaBorder: '#FEDF89',
                                suksesText: '#027A48',
                                suksesBg: '#ECFDF3',
                                suksesBorder: '#A6F4C5',
                            }
                        }
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F8FAFC;
            color: #101828;
        }
    </style>
    @include('layouts.sidebar-styles')
</head>
<body class="bg-[#F8FAFC] text-[#101828] min-h-screen antialiased font-sans role-sidebar-shell">

    @include('layouts.sidebar')

    <!-- ==================== KONTEN UTAMA (BPBD REKAP PENYALURAN) ==================== -->
    <main class="flex-1 flex flex-col min-w-0 overflow-y-auto role-admin-main">
        <!-- Topbar Sederhana -->
        <div class="bg-white border-b border-[#EAECF0] px-6 py-4 flex flex-wrap items-center justify-between gap-4 sticky top-0 z-10 shadow-xs">
            <div>
<h1 class="text-xl font-bold text-[#101828]">Rekap Penyaluran Air Bersih</h1>
            </div>

            <!-- Tombol Aksi Ekspor (PBI-024) -->
            <div class="flex items-center gap-2.5">
                <a 
                    href="{{ route('laporan.export', array_merge(request()->query(), ['format' => 'excel'])) }}" 
                    class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-[#027A48] bg-[#ECFDF3] border border-[#A6F4C5] rounded-xl hover:bg-emerald-100 transition shadow-xs"
                    title="Unduh berkas Excel sesuai filter aktif"
                >
                    <i class="fa-solid fa-file-excel text-sm"></i>
                    <span>Ekspor Excel (.xls)</span>
                </a>
                <a 
                    href="{{ route('laporan.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" 
                    target="_blank"
                    class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-white bg-[#00629B] hover:bg-[#004F7D] rounded-xl transition shadow-xs"
                    title="Cetak atau simpan Laporan Resmi PDF"
                >
                    <i class="fa-solid fa-file-pdf text-sm"></i>
                    <span>Ekspor PDF Resmi</span>
                </a>
            </div>
        </div>

        <div class="p-6 space-y-6">

            <!-- 1. KPI SUMMARY CARDS (EVALUASI PEMERATAAN PENYALURAN) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Total Volume Terkirim -->
                <div class="bg-white border border-[#E4E7EC] p-5 rounded-2xl shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#667085] uppercase tracking-wide">Total Air Disalurkan</p>
                        <h3 class="text-2xl font-extrabold text-[#101828] mt-1">
                            {{ number_format($ringkasan['total_volume_liter'] ?? 0, 0, ',', '.') }}
                            <span class="text-sm font-semibold text-[#667085]">Liter</span>
                        </h3>
                        <p class="text-[11px] text-[#027A48] font-medium mt-1">
                            <i class="fa-solid fa-check-circle"></i> Status Terkirim Valid
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center text-lg">
                        <i class="fa-solid fa-droplet"></i>
                    </div>
                </div>

                <!-- Card 2: Total Ritase Armada Tangki -->
                <div class="bg-white border border-[#E4E7EC] p-5 rounded-2xl shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#667085] uppercase tracking-wide">Ritase Tangki</p>
                        <h3 class="text-2xl font-extrabold text-[#101828] mt-1">
                            {{ number_format($ringkasan['total_tangki'] ?? 0, 0, ',', '.') }}
                            <span class="text-sm font-semibold text-[#667085]">Tangki</span>
                        </h3>
                        <p class="text-[11px] text-[#475467] font-medium mt-1">
                            Kapasitas @ 5.000 Liter
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-truck"></i>
                    </div>
                </div>

                <!-- Card 3: Total Desa Terlayani -->
                <div class="bg-white border border-[#E4E7EC] p-5 rounded-2xl shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#667085] uppercase tracking-wide">Desa Terdampak Terlayani</p>
                        <h3 class="text-2xl font-extrabold text-[#101828] mt-1">
                            {{ $ringkasan['total_desa'] ?? 0 }}
                            <span class="text-sm font-semibold text-[#667085]">Desa</span>
                        </h3>
                        <p class="text-[11px] text-[#00629B] font-medium mt-1">
                            Pemerataan wilayah kekeringan
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center text-lg">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                </div>

                <!-- Card 4: Status Selesai vs Proses -->
                <div class="bg-white border border-[#E4E7EC] p-5 rounded-2xl shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-[#667085] uppercase tracking-wide">Progres Penyaluran</p>
                        <div class="flex items-baseline gap-2 mt-1">
                            <span class="text-2xl font-extrabold text-[#027A48]">{{ $ringkasan['total_selesai'] ?? 0 }}</span>
                            <span class="text-xs text-[#667085]">Selesai</span>
                            <span class="text-xs text-[#667085]">&bull;</span>
                            <span class="text-base font-bold text-amber-600">{{ $ringkasan['total_proses'] ?? 0 }}</span>
                            <span class="text-xs text-[#667085]">Proses</span>
                        </div>
                        <p class="text-[11px] text-[#667085] mt-1">
                            Kendala: <span class="font-bold text-[#B42318]">{{ $ringkasan['total_kendala'] ?? 0 }}</span> titik
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-[#ECFDF3] border border-[#A6F4C5] text-[#027A48] flex items-center justify-center text-lg">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                </div>
            </div>

            <!-- 2. TOOLBAR FILTER MULTI-KRITERIA (PBI-022) -->
            <div class="bg-white border border-[#E4E7EC] rounded-2xl p-5 shadow-xs">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#EAECF0]">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-filter text-[#00629B] text-sm"></i>
                        <h2 class="text-sm font-bold text-[#101828]">Filter Evaluasi Rekapitulasi</h2>
                    </div>
                    @if(request()->hasAny(['start_date', 'end_date', 'kecamatan', 'penyalur_id', 'status']))
                        <a href="{{ route('laporan.index') }}" class="text-xs font-semibold text-[#B42318] hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-rotate-left"></i> Reset Filter
                        </a>
                    @endif
                </div>

                <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
                    <!-- Filter 1: Periode Tanggal Mulai -->
                    <div>
                        <label class="block text-[11px] font-semibold text-[#475467] mb-1.5">Tanggal Mulai</label>
                        <input 
                            type="date" 
                            name="start_date" 
                            value="{{ request('start_date') }}" 
                            class="w-full text-xs px-3 py-2 border border-[#D0D5DD] rounded-xl focus:ring-2 focus:ring-[#00629B] focus:border-[#00629B] bg-[#F8FAFC]"
                        >
                    </div>

                    <!-- Filter 2: Periode Tanggal Selesai -->
                    <div>
                        <label class="block text-[11px] font-semibold text-[#475467] mb-1.5">Tanggal Selesai</label>
                        <input 
                            type="date" 
                            name="end_date" 
                            value="{{ request('end_date') }}" 
                            class="w-full text-xs px-3 py-2 border border-[#D0D5DD] rounded-xl focus:ring-2 focus:ring-[#00629B] focus:border-[#00629B] bg-[#F8FAFC]"
                        >
                    </div>

                    <!-- Filter 3: Wilayah Kecamatan -->
                    <div>
                        <label class="block text-[11px] font-semibold text-[#475467] mb-1.5">Kecamatan</label>
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

                    <!-- Filter 4: Mitra Penyalur -->
                    <div>
                        <label class="block text-[11px] font-semibold text-[#475467] mb-1.5">Mitra Penyalur</label>
                        <select 
                            name="penyalur_id" 
                            class="w-full text-xs px-3 py-2 border border-[#D0D5DD] rounded-xl focus:ring-2 focus:ring-[#00629B] focus:border-[#00629B] bg-[#F8FAFC]"
                        >
                            <option value="">Semua Mitra Penyalur</option>
                            @foreach($penyalurList as $p)
                                <option value="{{ $p->id }}" {{ request('penyalur_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_penyalur }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter 5: Status Penyaluran & Submit -->
                    <div class="flex items-end gap-2">
                        <div class="flex-1">
                            <label class="block text-[11px] font-semibold text-[#475467] mb-1.5">Status Penyaluran</label>
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
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            <!-- 3. TABEL REKAP PENYALURAN (PBI-022) -->
            <div class="bg-white border border-[#E4E7EC] rounded-2xl shadow-xs overflow-hidden">
                <div class="p-5 border-b border-[#EAECF0] flex flex-wrap items-center justify-between gap-4 bg-white">
                    <div>
                        <h2 class="text-sm font-bold text-[#101828]">Data Riwayat Penyaluran Air Bersih</h2>
                        <p class="text-xs text-[#667085] mt-0.5">Menampilkan seluruh catatan operasional penyaluran bantuan air bersih ke desa</p>
                    </div>
                    <span class="text-xs font-semibold text-[#475467] bg-[#F8FAFC] border border-[#EAECF0] px-3 py-1.5 rounded-lg">
                        Total Data: <strong class="text-[#00629B]">{{ $penyaluran->total() }}</strong> baris
                    </span>
                </div>

                @if($penyaluran->isEmpty())
                    <!-- EMPTY STATE (Sesuai PBI-022 Acceptance Criteria 2) -->
                    <div class="p-12 text-center flex flex-col items-center justify-center">
                        <div class="w-16 h-16 rounded-full bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center text-2xl mb-4">
                            <i class="fa-solid fa-inbox"></i>
                        </div>
                        <h3 class="text-base font-bold text-[#101828]">Data penyaluran tidak tersedia</h3>
                        <p class="text-xs text-[#667085] max-w-md mt-1 mb-5">
                            Tidak ditemukan data penyaluran air bersih yang sesuai dengan kriteria filter yang Anda pilih. Silakan sesuaikan periode tanggal atau reset filter Anda.
                        </p>
                        @if(request()->hasAny(['start_date', 'end_date', 'kecamatan', 'penyalur_id', 'status']))
                            <a href="{{ route('laporan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-[#00629B] hover:bg-[#004F7D] rounded-xl transition shadow-xs">
                                <i class="fa-solid fa-rotate-left"></i>
                                Reset Semua Filter
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
                                    <th class="py-3 px-4">Tanggal</th>
                                    <th class="py-3 px-4">Tujuan / Wilayah</th>
                                    <th class="py-3 px-4">Mitra Penyalur & Armada</th>
                                    <th class="py-3 px-4 text-right">Volume (Liter)</th>
                                    <th class="py-3 px-4">Penerima & Bukti</th>
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

                                        <!-- Kode Penyaluran & Tiket -->
                                        <td class="py-3.5 px-4 font-semibold">
                                            <span class="text-[#00629B] block font-mono text-[11px]">{{ $item->kode_penyaluran }}</span>
                                            <span class="text-[10px] text-[#667085] block font-mono">Tiket: {{ $item->nomor_tiket ?? '-' }}</span>
                                        </td>

                                        <!-- Tanggal -->
                                        <td class="py-3.5 px-4 whitespace-nowrap text-[#344054]">
                                            <span class="font-medium block">{{ $item->tanggal_penyaluran ? \Carbon\Carbon::parse($item->tanggal_penyaluran)->translatedFormat('d M Y') : '-' }}</span>
                                            <span class="text-[10px] text-[#667085]">{{ $item->tanggal_penyaluran ? \Carbon\Carbon::parse($item->tanggal_penyaluran)->diffForHumans() : '' }}</span>
                                        </td>

                                        <!-- Wilayah & Titik Serah Terima -->
                                        <td class="py-3.5 px-4">
                                            <div class="font-bold text-[#101828]">
                                                Desa {{ $item->desa->nama_desa ?? 'Desa '.$item->kode_desa }}
                                            </div>
                                            <div class="text-[11px] text-[#475467]">
                                                Kec. {{ $item->desa->kecamatan ?? '-' }}
                                            </div>
                                            @if($item->titik_serah_terima)
                                                <div class="text-[10px] text-[#667085] mt-0.5 flex items-center gap-1">
                                                    <i class="fa-solid fa-map-pin text-[#00629B] text-[9px]"></i>
                                                    <span class="truncate max-w-[200px]" title="{{ $item->titik_serah_terima }}">{{ $item->titik_serah_terima }}</span>
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Mitra Penyalur & Armada -->
                                        <td class="py-3.5 px-4">
                                            <div class="font-semibold text-[#101828]">
                                                {{ $item->penyalur->nama_penyalur ?? 'BPBD Purbalingga' }}
                                            </div>
                                            <div class="text-[11px] text-[#475467] flex items-center gap-2 mt-0.5">
                                                <span class="font-mono bg-[#EBF5FB] text-[#00629B] px-1.5 py-0.5 rounded text-[10px]">
                                                    {{ $item->armada_nopol ?? 'Tangki Resmi' }}
                                                </span>
                                                @if($item->nama_driver)
                                                    <span class="text-[10px] text-[#667085]">Supir: {{ $item->nama_driver }}</span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Volume & Tangki -->
                                        <td class="py-3.5 px-4 text-right">
                                            <span class="text-xs font-bold text-[#101828] block">
                                                {{ number_format($item->volume_air_liter, 0, ',', '.') }} L
                                            </span>
                                            <span class="text-[10px] text-[#667085]">
                                                {{ $item->jumlah_tangki }} Tangki
                                            </span>
                                        </td>

                                        <!-- Penerima & Bukti Serah Terima -->
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
                                                    <i class="fa-solid fa-image"></i> Lihat Foto BAST
                                                </a>
                                            @endif
                                        </td>

                                        <!-- Status Badge -->
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

                    <!-- Pagination Links (PBI-022) -->
                    <div class="p-4 border-t border-[#EAECF0] bg-[#F8FAFC]">
                        {{ $penyaluran->links() }}
                    </div>
                @endif
            </div>

        </div>
    </main>

</body>
</html>
