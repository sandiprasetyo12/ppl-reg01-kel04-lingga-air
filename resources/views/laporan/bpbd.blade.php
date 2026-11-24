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
        *, html, body, input, button, select, textarea {
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #F8FAFC;
            color: #101828;
            font-size: 0.75rem; /* 12px compact institutional base */
        }
    </style>
    @include('layouts.sidebar-styles')
</head>
<body class="bg-[#F8FAFC] text-[#101828] min-h-screen antialiased font-sans role-sidebar-shell">

    @include('layouts.sidebar')

    <!-- ==================== KONTEN UTAMA (BPBD REKAP PENYALURAN) ==================== -->
    <main class="flex-1 flex flex-col min-w-0 overflow-y-auto role-admin-main">
        <!-- Topbar Sederhana (Ramping) -->
        <div class="bg-white/95 backdrop-blur-md border-b border-[#EAECF0] px-4 sm:px-5 py-2.5 flex flex-wrap items-center justify-between gap-2 sticky top-0 z-10 shadow-xs">
            <div>
                <h1 class="text-[13px] sm:text-sm font-bold text-[#101828] tracking-tight">Rekap Penyaluran Air Bersih</h1>
                <p class="text-[10px] text-[#475467] mt-0.5">Monitoring serah terima dan distribusi armada air bersih ke seluruh desa</p>
            </div>

            <!-- Tombol Aksi Ekspor (PBI-024) -->
            <div class="flex items-center gap-2">
                <a 
                    href="{{ route('laporan.export', array_merge(request()->query(), ['format' => 'excel'])) }}" 
                    class="h-8 px-3 inline-flex items-center gap-1.5 text-[11px] font-semibold text-[#027A48] bg-[#ECFDF3] border border-[#A6F4C5] rounded-lg hover:bg-emerald-100 transition shadow-xs"
                    title="Unduh berkas Excel sesuai filter aktif"
                >
                    <i class="fa-solid fa-file-excel text-[11px]"></i>
                    <span>Ekspor Excel</span>
                </a>
                <a 
                    href="{{ route('laporan.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" 
                    target="_blank"
                    class="h-8 px-3 inline-flex items-center gap-1.5 text-[11px] font-semibold text-white bg-[#00629B] hover:bg-[#004F7D] rounded-lg transition shadow-xs"
                    title="Cetak atau simpan Laporan Resmi PDF"
                >
                    <i class="fa-solid fa-file-pdf text-[11px]"></i>
                    <span>Ekspor PDF</span>
                </a>
            </div>
        </div>

        <div class="p-3 sm:p-4 max-w-7xl mx-auto w-full space-y-3">

            <!-- 1. KPI SUMMARY CARDS (EVALUASI PEMERATAAN PENYALURAN) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
                <!-- Card 1: Total Volume Terkirim -->
                <div class="bg-white border border-[#E4E7EC] p-3 rounded-xl shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[9.5px] font-bold text-[#667085] uppercase tracking-wider">Total Air Disalurkan</p>
                        <h3 class="text-base sm:text-lg font-bold text-[#101828] mt-0.5">
                            {{ number_format($ringkasan['total_volume_liter'] ?? 0, 0, ',', '.') }}
                            <span class="text-[10px] font-normal text-[#667085]">Liter</span>
                        </h3>
                        <p class="text-[9.5px] text-[#027A48] font-medium mt-0.5">
                            <i class="fa-solid fa-check-circle"></i> Status Terkirim Valid
                        </p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center text-sm">
                        <i class="fa-solid fa-droplet"></i>
                    </div>
                </div>

                <!-- Card 2: Total Ritase Armada Tangki -->
                <div class="bg-white border border-[#E4E7EC] p-3 rounded-xl shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[9.5px] font-bold text-[#667085] uppercase tracking-wider">Ritase Tangki</p>
                        <h3 class="text-base sm:text-lg font-bold text-[#101828] mt-0.5">
                            {{ number_format($ringkasan['total_tangki'] ?? 0, 0, ',', '.') }}
                            <span class="text-[10px] font-normal text-[#667085]">Tangki</span>
                        </h3>
                        <p class="text-[9.5px] text-[#475467] font-medium mt-0.5">
                            Kapasitas @ 5.000 Liter
                        </p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-[#FFFAEB] border border-[#FEDF89] text-[#B54708] flex items-center justify-center text-sm">
                        <i class="fa-solid fa-truck"></i>
                    </div>
                </div>

                <!-- Card 3: Total Desa Terlayani -->
                <div class="bg-white border border-[#E4E7EC] p-3 rounded-xl shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[9.5px] font-bold text-[#667085] uppercase tracking-wider">Desa Dijangkau</p>
                        <h3 class="text-base sm:text-lg font-bold text-[#101828] mt-0.5">
                            {{ $ringkasan['total_desa'] ?? 0 }}
                            <span class="text-[10px] font-normal text-[#667085]">Desa</span>
                        </h3>
                        <p class="text-[9.5px] text-[#00629B] font-medium mt-0.5">
                            Pemerataan kekeringan
                        </p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center text-sm">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                </div>

                <!-- Card 4: Status Selesai vs Proses -->
                <div class="bg-white border border-[#E4E7EC] p-3 rounded-xl shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[9.5px] font-bold text-[#667085] uppercase tracking-wider">Progres Penyaluran</p>
                        <div class="flex items-baseline gap-1.5 mt-0.5">
                            <span class="text-base sm:text-lg font-bold text-[#027A48]">{{ $ringkasan['total_selesai'] ?? 0 }}</span>
                            <span class="text-[10px] text-[#667085]">Selesai</span>
                            <span class="text-[10px] text-[#667085]">&bull;</span>
                            <span class="text-[12px] font-bold text-[#B54708]">{{ $ringkasan['total_proses'] ?? 0 }}</span>
                            <span class="text-[10px] text-[#667085]">Proses</span>
                        </div>
                        <p class="text-[9.5px] text-[#667085] mt-0.5">
                            Kendala: <span class="font-bold text-[#B42318]">{{ $ringkasan['total_kendala'] ?? 0 }}</span> titik
                        </p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-[#ECFDF3] border border-[#A6F4C5] text-[#027A48] flex items-center justify-center text-sm">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                </div>
            </div>

            <!-- 2. TOOLBAR FILTER MULTI-KRITERIA (PBI-022) -->
            <div class="bg-white border border-[#E4E7EC] rounded-xl p-3 shadow-xs">
                <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-[#EAECF0]">
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-filter text-[#00629B] text-xs"></i>
                        <h2 class="text-xs font-bold text-[#101828]">Filter Evaluasi Rekapitulasi</h2>
                    </div>
                    @if(request()->hasAny(['start_date', 'end_date', 'kecamatan', 'penyalur_id', 'status']))
                        <a href="{{ route('laporan.index') }}" class="text-[10px] font-medium text-[#667085] hover:text-[#B42318] flex items-center gap-1">
                            <i class="fa-solid fa-rotate-left text-[9px]"></i> Reset Filter
                        </a>
                    @endif
                </div>

                <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2">
                    <!-- Filter 1: Periode Tanggal Mulai -->
                    <div>
                        <label class="block text-[10px] font-semibold text-[#475467] mb-1">Tanggal Mulai</label>
                        <input 
                            type="date" 
                            name="start_date" 
                            value="{{ request('start_date') }}" 
                            class="w-full h-8 px-2.5 text-[11px] border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B] bg-white text-[#101828]"
                        >
                    </div>

                    <!-- Filter 2: Periode Tanggal Selesai -->
                    <div>
                        <label class="block text-[10px] font-semibold text-[#475467] mb-1">Tanggal Selesai</label>
                        <input 
                            type="date" 
                            name="end_date" 
                            value="{{ request('end_date') }}" 
                            class="w-full h-8 px-2.5 text-[11px] border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B] bg-white text-[#101828]"
                        >
                    </div>

                    <!-- Filter 3: Wilayah Kecamatan -->
                    <div>
                        <label class="block text-[10px] font-semibold text-[#475467] mb-1">Kecamatan</label>
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

                    <!-- Filter 4: Mitra Penyalur -->
                    <div>
                        <label class="block text-[10px] font-semibold text-[#475467] mb-1">Mitra Penyalur</label>
                        <select 
                            name="penyalur_id" 
                            class="w-full h-8 px-2.5 text-[11px] border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B] bg-white text-[#344054] cursor-pointer"
                        >
                            <option value="">Semua Mitra</option>
                            @foreach($penyalurList as $p)
                                <option value="{{ $p->id }}" {{ request('penyalur_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_penyalur }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter 5: Status Penyaluran & Submit -->
                    <div class="flex items-end gap-1.5">
                        <div class="flex-1">
                            <label class="block text-[10px] font-semibold text-[#475467] mb-1">Status</label>
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
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            <!-- 3. TABEL REKAP PENYALURAN (PBI-022) -->
            <div class="bg-white border border-[#E4E7EC] rounded-xl shadow-xs overflow-hidden">
                <div class="p-3 border-b border-[#EAECF0] flex flex-wrap items-center justify-between gap-2 bg-white">
                    <div>
                        <h2 class="text-xs font-bold text-[#101828]">Data Riwayat Penyaluran Air Bersih</h2>
                        <p class="text-[10px] text-[#667085] mt-0.5">Menampilkan seluruh catatan operasional penyaluran bantuan air bersih ke desa</p>
                    </div>
                    <span class="text-[10px] font-semibold text-[#00629B] bg-[#EBF5FB] border border-[#BAE3F5] px-2 py-0.5 rounded-md">
                        Total Data: <strong class="text-[#00629B]">{{ $penyaluran->total() }}</strong> baris
                    </span>
                </div>

                @if($penyaluran->isEmpty())
                    <!-- EMPTY STATE -->
                    <div class="p-8 text-center flex flex-col items-center justify-center">
                        <div class="w-10 h-10 rounded-lg bg-[#EBF5FB] border border-[#BAE3F5] text-[#00629B] flex items-center justify-center text-lg mb-2">
                            <i class="fa-solid fa-inbox"></i>
                        </div>
                        <h3 class="text-xs font-bold text-[#101828]">Data penyaluran tidak tersedia</h3>
                        <p class="text-[10px] text-[#667085] max-w-md mt-0.5 mb-3">
                            Tidak ditemukan data penyaluran air bersih yang sesuai dengan kriteria filter yang Anda pilih. Silakan sesuaikan periode tanggal atau reset filter Anda.
                        </p>
                        @if(request()->hasAny(['start_date', 'end_date', 'kecamatan', 'penyalur_id', 'status']))
                            <a href="{{ route('laporan.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1 text-[11px] font-semibold text-white bg-[#00629B] hover:bg-[#004F7D] rounded-lg transition shadow-xs">
                                <i class="fa-solid fa-rotate-left text-[9px]"></i>
                                Reset Semua Filter
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
                                    <th class="py-2 px-2.5">Tanggal</th>
                                    <th class="py-2 px-2.5">Tujuan / Wilayah</th>
                                    <th class="py-2 px-2.5">Mitra Penyalur & Armada</th>
                                    <th class="py-2 px-2.5 text-right">Volume (Liter)</th>
                                    <th class="py-2 px-2.5">Penerima & Bukti</th>
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

                                        <!-- Kode Penyaluran & Tiket -->
                                        <td class="py-2 px-2.5 font-semibold">
                                            <span class="text-[#00629B] block font-mono text-[10.5px]">{{ $item->kode_penyaluran }}</span>
                                            <span class="text-[9.5px] text-[#667085] block font-mono">Tiket: {{ $item->nomor_tiket ?? '-' }}</span>
                                        </td>

                                        <!-- Tanggal -->
                                        <td class="py-2 px-2.5 whitespace-nowrap text-[#344054]">
                                            <span class="font-medium block">{{ $item->tanggal_penyaluran ? \Carbon\Carbon::parse($item->tanggal_penyaluran)->translatedFormat('d M Y') : '-' }}</span>
                                            <span class="text-[9px] text-[#667085]">{{ $item->tanggal_penyaluran ? \Carbon\Carbon::parse($item->tanggal_penyaluran)->diffForHumans() : '' }}</span>
                                        </td>

                                        <!-- Wilayah & Titik Serah Terima -->
                                        <td class="py-2 px-2.5">
                                            <div class="font-bold text-[#101828] text-[10.5px]">
                                                Desa {{ $item->desa->nama_desa ?? 'Desa '.$item->kode_desa }}
                                            </div>
                                            <div class="text-[9.5px] text-[#475467]">
                                                Kec. {{ $item->desa->kecamatan ?? '-' }}
                                            </div>
                                            @if($item->titik_serah_terima)
                                                <div class="text-[9px] text-[#667085] mt-0.5 flex items-center gap-1">
                                                    <i class="fa-solid fa-map-pin text-[#00629B] text-[8px]"></i>
                                                    <span class="truncate max-w-[180px]" title="{{ $item->titik_serah_terima }}">{{ $item->titik_serah_terima }}</span>
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Mitra Penyalur & Armada -->
                                        <td class="py-2 px-2.5">
                                            <div class="font-semibold text-[#101828]">
                                                {{ $item->penyalur->nama_penyalur ?? 'BPBD Purbalingga' }}
                                            </div>
                                            <div class="text-[9.5px] text-[#475467] flex items-center gap-1.5 mt-0.5">
                                                <span class="font-mono bg-[#EBF5FB] text-[#00629B] px-1 py-0.2 rounded text-[9.5px]">
                                                    {{ $item->armada_nopol ?? 'Tangki Resmi' }}
                                                </span>
                                                @if($item->nama_driver)
                                                    <span class="text-[9px] text-[#667085]">Supir: {{ $item->nama_driver }}</span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Volume & Tangki -->
                                        <td class="py-2 px-2.5 text-right">
                                            <span class="text-[10.5px] font-bold text-[#101828] block">
                                                {{ number_format($item->volume_air_liter, 0, ',', '.') }} L
                                            </span>
                                            <span class="text-[9px] text-[#667085]">
                                                {{ $item->jumlah_tangki }} Tangki
                                            </span>
                                        </td>

                                        <!-- Penerima & Bukti Serah Terima -->
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
                                                    <i class="fa-solid fa-image"></i> Foto BAST
                                                </a>
                                            @endif
                                        </td>

                                        <!-- Status Badge -->
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

                    <!-- Pagination Links (PBI-022) -->
                    <div class="p-3 border-t border-[#EAECF0] bg-[#F8FAFC]">
                        {{ $penyaluran->links() }}
                    </div>
                @endif
            </div>

        </div>
    </main>

</body>
</html>
