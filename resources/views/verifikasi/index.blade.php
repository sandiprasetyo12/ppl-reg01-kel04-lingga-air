<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrean Verifikasi Pengajuan - Lingga Air | BPBD Purbalingga</title>
    
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
</head>
<body class="bg-[#F8FAFC] text-[#101828] min-h-screen antialiased flex flex-col lg:flex-row font-sans">

    <!-- ==================== SIDEBAR KIRI (ADMIN BPBD) ==================== -->
    <aside class="w-full lg:w-64 bg-white text-[#101828] flex flex-col justify-between shrink-0 border-r border-[#EAECF0] z-20 shadow-xs">
        <div>
            <!-- 1. Logo & Brand Header -->
            <div class="p-5 border-b border-[#EAECF0]">
                <a href="{{ url('/') }}" class="flex items-center gap-2 block">
                    <img 
                        src="{{ asset('images/logo-lingga.png') }}" 
                        alt="Lingga Air" 
                        class="h-12 sm:h-14 w-auto max-w-[190px] object-contain" 
                        onerror="this.onerror=null; this.src='https://placehold.co/180x60/00629B/FFFFFF?text=Lingga+Air';"
                    >
                </a>
                <div class="mt-2.5">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#EBF5FB] text-[#00629B] border border-[#BAE3F5]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#00629B] animate-pulse"></span>
                        Portal Verifikator BPBD
                    </span>
                </div>
            </div>

            <!-- 2. Navigation Links -->
            <nav class="p-4 space-y-6">
                <!-- Group 1: Menu Operasional BPBD -->
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-[#667085] block mb-2">
                        Menu Utama BPBD
                    </span>
                    <ul class="space-y-1 text-xs font-semibold">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[#344054] hover:text-[#00629B] hover:bg-[#EBF5FB] transition">
                                <i class="fa-solid fa-chart-pie w-4 text-center text-[#00629B]"></i>
                                <span>Dashboard Statistik</span>
                            </a>
                        </li>
                        <li>
                            <!-- Active Menu Fitur 4 (Verifikasi Pengajuan) -->
                            <a href="{{ route('verifikasi.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-[#00629B] text-white shadow-xs font-bold transition">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-clipboard-check w-4 text-center text-white"></i>
                                    <span>Verifikasi Pengajuan</span>
                                </div>
                                <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                    {{ $ringkasan['menunggu_verifikasi'] ?? 0 }}
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[#475467] hover:text-[#00629B] hover:bg-[#EBF5FB] transition">
                                <i class="fa-solid fa-truck-droplet w-4 text-center text-[#667085]"></i>
                                <span>Penugasan Armada</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[#475467] hover:text-[#00629B] hover:bg-[#EBF5FB] transition">
                                <i class="fa-solid fa-map-location-dot w-4 text-center text-[#667085]"></i>
                                <span>Peta Sebaran (GIS)</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[#475467] hover:text-[#00629B] hover:bg-[#EBF5FB] transition">
                                <i class="fa-solid fa-file-invoice w-4 text-center text-[#667085]"></i>
                                <span>Laporan Penyaluran</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Group 2: Master Data Wilayah -->
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-[#667085] block mb-2">
                        Data Master
                    </span>
                    <ul class="space-y-1 text-xs font-semibold">
                        <li>
                            <a href="{{ route('admin.desa.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[#344054] hover:text-[#00629B] hover:bg-[#EBF5FB] transition">
                                <i class="fa-solid fa-building-columns w-4 text-center text-[#00629B]"></i>
                                <span>Data Acuan Desa</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- 3. Sidebar Footer: Profil Petugas BPBD -->
        <div class="m-3 p-3.5 bg-[#F8FAFC] border border-[#E4E7EC] rounded-xl">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-[#00629B] text-white flex items-center justify-center font-bold text-xs shadow-xs">
                    BP
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-[#101828] truncate">Petugas Verifikator</p>
                    <p class="text-[10px] text-[#667085] truncate">admin@bpbd.purbalinggakab.go.id</p>
                </div>
            </div>
            <div class="mt-2.5 pt-2 border-t border-[#EAECF0] flex items-center justify-between text-[10px] text-[#667085]">
                <span class="flex items-center gap-1.5 font-medium text-emerald-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Online
                </span>
                
                <!-- Tombol Logout -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold flex items-center gap-1 cursor-pointer">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- ==================== MAIN CONTENT AREA ==================== -->
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden">

        <!-- Top Header Bar (Dengan Button Back Ke Dashboard) -->
        <header class="bg-white/95 backdrop-blur-md border-b border-[#EAECF0] px-4 sm:px-6 py-3.5 sticky top-0 z-10 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-lg sm:text-xl font-extrabold text-[#101828] tracking-tight">Antrean Verifikasi Permohonan Air Bersih</h1>
                <p class="text-xs text-[#475467] mt-0.5">Penetapan prioritas bantuan air bersih darurat Kabupaten Purbalingga</p>
            </div>

            <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                <!-- Tombol Back ke Dashboard -->
                <a 
                    href="{{ route('admin.dashboard') }}" 
                    class="px-3 py-1.5 bg-white text-[#344054] hover:bg-[#F2F4F7] hover:text-[#101828] border border-[#D0D5DD] rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-xs"
                    title="Kembali ke Dashboard Utama BPBD"
                >
                    <i class="fa-solid fa-arrow-left text-[#00629B]"></i>
                    <span>Kembali ke Dashboard</span>
                </a>

                <span class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#EBF5FB] text-[#00629B] border border-[#BAE3F5]">
                    <i class="fa-regular fa-calendar text-[#00629B]"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>

                <a 
                    href="{{ route('verifikasi.index') }}" 
                    class="px-3.5 py-1.5 bg-[#00629B] text-white hover:bg-[#004F7D] rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-xs"
                >
                    <i class="fa-solid fa-rotate-right"></i>
                    <span>Refresh</span>
                </a>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6">

            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="p-4 rounded-xl bg-[#ECFDF3] border border-[#A6F4C5] text-[#027A48] flex items-start gap-3 shadow-xs animate-fade-in">
                    <i class="fa-solid fa-circle-check text-[#027A48] text-xl mt-0.5"></i>
                    <div class="flex-1">
                        <h4 class="font-bold text-sm">Aksi Berhasil!</h4>
                        <p class="text-xs text-[#027A48] mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Metric Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- 1. Total Permohonan -->
                <div class="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-[#667085] uppercase tracking-wider">Total Lolos Skrining</p>
                        <p class="text-2xl font-extrabold text-[#101828] mt-1">{{ $ringkasan['total_pengajuan'] }}</p>
                        <p class="text-[11px] text-[#667085] mt-0.5">Permohonan terdaftar</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-[#EBF5FB] text-[#00629B] flex items-center justify-center text-lg">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                </div>

                <!-- 2. Menunggu Verifikasi -->
                <div class="bg-white p-5 rounded-2xl border border-[#FEDF89] shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-[#B54708] uppercase tracking-wider">Menunggu Verifikasi</p>
                        <p class="text-2xl font-extrabold text-[#101828] mt-1">{{ $ringkasan['menunggu_verifikasi'] }}</p>
                        <p class="text-[11px] text-[#475467] mt-0.5">Memerlukan telaah BPBD</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-[#FFFAEB] text-[#B54708] border border-[#FEDF89] flex items-center justify-center text-lg font-bold">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>

                <!-- 3. Disetujui -->
                <div class="bg-white p-5 rounded-2xl border border-[#A6F4C5] shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-[#027A48] uppercase tracking-wider">Disetujui</p>
                        <p class="text-2xl font-extrabold text-[#027A48] mt-1">{{ $ringkasan['disetujui'] }}</p>
                        <p class="text-[11px] text-[#027A48]/80 mt-0.5">Siap alokasi armada tangki</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-[#ECFDF3] text-[#027A48] border border-[#A6F4C5] flex items-center justify-center text-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>

                <!-- 4. Perlu Tindak Lanjut -->
                <div class="bg-white p-5 rounded-2xl border border-[#FECDCA] shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-[#B42318] uppercase tracking-wider">Perlu Tindak Lanjut</p>
                        <p class="text-2xl font-extrabold text-[#B42318] mt-1">{{ $ringkasan['perlu_tindak_lanjut'] }}</p>
                        <p class="text-[11px] text-[#B42318]/80 mt-0.5">Revisi dokumen / Ditolak</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-[#FEF3F2] text-[#B42318] border border-[#FECDCA] flex items-center justify-center text-lg">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Toolbar -->
            <div class="bg-white p-4 rounded-2xl border border-[#E4E7EC] shadow-xs">
                <form method="GET" action="{{ route('verifikasi.index') }}" class="flex flex-col md:flex-row items-center gap-3">
                    <div class="relative flex-1 w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-[#667085]">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Cari nama desa, kecamatan, atau nomor tiket..." 
                            class="w-full pl-9 pr-4 py-2 bg-white border border-[#D0D5DD] rounded-lg text-xs text-[#101828] placeholder-[#667085] focus:outline-none focus:ring-2 focus:ring-[#00629B]/20 focus:border-[#00629B] transition"
                        >
                    </div>

                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <select 
                            name="status" 
                            class="w-full md:w-auto px-3 py-2 bg-white border border-[#D0D5DD] rounded-lg text-xs text-[#101828] focus:outline-none focus:ring-2 focus:ring-[#00629B]/20 focus:border-[#00629B] transition"
                        >
                            <option value="">Semua Status Tindakan</option>
                            <option value="Menunggu Verifikasi" {{ request('status') == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="Revisi" {{ request('status') == 'Revisi' ? 'selected' : '' }}>Revisi</option>
                            <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>

                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-[#00629B] hover:bg-[#004F7D] text-white rounded-lg text-xs font-bold transition shrink-0 shadow-xs cursor-pointer"
                        >
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('verifikasi.index') }}" class="px-3 py-2 text-[#667085] hover:text-[#101828] text-xs font-semibold">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Banner Informasi Prioritas & Bobot Penilaian -->
            <div class="bg-white rounded-2xl border border-[#E4E7EC] p-4 shadow-xs">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    
                    <!-- Kiri: Kategori Status Kelayakan -->
                    <div class="space-y-1.5">
                        <span class="text-xs font-bold text-[#101828] uppercase tracking-wide flex items-center gap-1.5">
                            <i class="fa-solid fa-scale-balanced text-[#00629B]"></i>
                            Kategori Prioritas:
                        </span>
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-[#FEF3F2] text-[#B42318] border border-[#FECDCA] font-bold">
                                <span class="w-2 h-2 rounded-full bg-[#B42318]"></span>
                                Kritis (&ge; 70.00)
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-[#FFFAEB] text-[#B54708] border border-[#FEDF89] font-bold">
                                <span class="w-2 h-2 rounded-full bg-[#B54708]"></span>
                                Waspada (40.00 – 69.99)
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-[#ECFDF3] text-[#027A48] border border-[#A6F4C5] font-semibold">
                                <span class="w-2 h-2 rounded-full bg-[#027A48]"></span>
                                Aman (&lt; 40.00)
                            </span>
                        </div>
                    </div>

                    <!-- Kanan: Bobot Penilaian (AHP-SAW) -->
                    <div class="lg:border-l lg:border-[#EAECF0] lg:pl-6 space-y-1.5">
                        <span class="text-xs font-bold text-[#101828] uppercase tracking-wide block">
                            Bobot Penilaian (Metode AHP-SAW):
                        </span>
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="px-2.5 py-1 rounded-lg bg-[#F8FAFC] text-[#344054] border border-[#E4E7EC] font-medium">
                                KK Terdampak: <strong class="font-bold text-[#101828]">50,47%</strong>
                            </span>
                            <span class="px-2.5 py-1 rounded-lg bg-[#F8FAFC] text-[#344054] border border-[#E4E7EC] font-medium">
                                Hari Tanpa Air: <strong class="font-bold text-[#101828]">30,26%</strong>
                            </span>
                            <span class="px-2.5 py-1 rounded-lg bg-[#F8FAFC] text-[#344054] border border-[#E4E7EC] font-medium">
                                Jarak Sumber Air: <strong class="font-bold text-[#101828]">11,00%</strong>
                            </span>
                            <span class="px-2.5 py-1 rounded-lg bg-[#F8FAFC] text-[#344054] border border-[#E4E7EC] font-medium">
                                Kelompok Rentan: <strong class="font-bold text-[#101828]">8,26%</strong>
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Ranked Queue Table (PBI-010 Acceptance Criteria 1) -->
            <div class="bg-white rounded-2xl border border-[#E4E7EC] shadow-xs overflow-hidden">
                <div class="p-4 border-b border-[#EAECF0] bg-[#F8FAFC] flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-[#101828] text-sm">Daftar Antrean Permohonan Bantuan Air Bersih</h2>
                        <p class="text-xs text-[#475467]">Otomatis terurut berdasarkan skor prioritas AHP-SAW tertinggi</p>
                    </div>
                    <span class="text-xs text-[#00629B] bg-[#EBF5FB] border border-[#BAE3F5] px-3 py-1 rounded-full font-bold">
                        Menampilkan <strong>{{ $antrean->count() }}</strong> permohonan
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-[#101828]">
                        <thead class="bg-[#101828] text-white text-[11px] uppercase font-bold tracking-wider">
                            <tr>
                                <th class="px-4 py-3.5 text-center w-16">Peringkat</th>
                                <th class="px-4 py-3.5">Nomor Tiket & Waktu</th>
                                <th class="px-4 py-3.5">Desa & Kecamatan</th>
                                <th class="px-4 py-3.5 text-center">Parameter Lapangan</th>
                                <th class="px-4 py-3.5 text-center">Skor AHP-SAW</th>
                                <th class="px-4 py-3.5 text-center">Status Kelayakan</th>
                                <th class="px-4 py-3.5 text-center">Status Tiket</th>
                                <th class="px-4 py-3.5 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#EAECF0] text-xs">
                            @forelse($antrean as $index => $item)
                                <tr class="hover:bg-[#F8FAFC] transition duration-150 {{ $index === 0 ? 'bg-[#EBF5FB]/30' : '' }}">
                                    <!-- Peringkat -->
                                    <td class="px-4 py-4 text-center">
                                        @if($index === 0)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#00629B] text-white font-extrabold text-xs shadow-xs ring-2 ring-[#BAE3F5]" title="Prioritas Tertinggi #1">
                                                #1
                                            </span>
                                        @elseif($index === 1)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#EBF5FB] text-[#00629B] border border-[#BAE3F5] font-bold text-xs">
                                                #2
                                            </span>
                                        @elseif($index === 2)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#F2F4F7] text-[#344054] font-bold text-xs border border-[#D0D5DD]">
                                                #3
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-100 text-[#475467] font-semibold text-xs border border-slate-300">
                                                #{{ $index + 1 }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Nomor Tiket & Waktu -->
                                    <td class="px-4 py-4">
                                        <span class="font-bold text-[#101828] block font-mono text-xs">{{ $item->nomor_tiket }}</span>
                                        <span class="text-[11px] text-[#667085] block mt-0.5">
                                            <i class="fa-regular fa-calendar-days mr-1 text-[#00629B]"></i>{{ $item->created_at->format('d M Y, H:i') }}
                                        </span>
                                    </td>

                                    <!-- Desa & Kecamatan -->
                                    <td class="px-4 py-4">
                                        <span class="font-bold text-[#101828] block">Desa {{ $item->desa->nama_desa ?? '-' }}</span>
                                        <span class="text-xs text-[#475467] block">Kec. {{ $item->desa->kecamatan ?? '-' }}</span>
                                    </td>

                                    <!-- Parameter Lapangan -->
                                    <td class="px-4 py-4 text-center">
                                        <div class="inline-flex items-center gap-2.5 text-xs bg-[#F8FAFC] px-2.5 py-1.5 rounded-lg border border-[#E4E7EC]">
                                            <span title="Kepala Keluarga Terdampak"><i class="fa-solid fa-users text-[#00629B] mr-1"></i><strong>{{ $item->kk_terdampak }}</strong> KK</span>
                                            <span class="text-[#D0D5DD]">|</span>
                                            <span title="Hari Tanpa Pasokan Air"><i class="fa-solid fa-sun text-amber-500 mr-1"></i><strong>{{ $item->hari_tanpa_air }}</strong> hari</span>
                                        </div>
                                    </td>

                                    <!-- Skor AHP-SAW (PBI-010) -->
                                    <td class="px-4 py-4 text-center">
                                        <div class="inline-block">
                                            <span class="text-base font-extrabold {{ $item->skor_ahp_saw >= 70 ? 'text-[#B42318]' : ($item->skor_ahp_saw >= 40 ? 'text-[#B54708]' : 'text-[#027A48]') }}">
                                                {{ number_format($item->skor_ahp_saw, 2) }}
                                            </span>
                                            <span class="text-[10px] text-[#667085] block">/ 100</span>
                                        </div>
                                    </td>

                                    <!-- Kategori Kelayakan -->
                                    <td class="px-4 py-4 text-center">
                                        @if($item->skor_ahp_saw >= 70)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FEF3F2] text-[#B42318] border border-[#FECDCA]">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#B42318] mr-1.5 animate-pulse"></span> Kritis
                                            </span>
                                        @elseif($item->skor_ahp_saw >= 40)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FFFAEB] text-[#B54708] border border-[#FEDF89]">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#B54708] mr-1.5"></span> Waspada
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#ECFDF3] text-[#027A48] border border-[#A6F4C5]">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#027A48] mr-1.5"></span> Aman
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Status Verifikasi -->
                                    <td class="px-4 py-4 text-center">
                                        @if($item->status == 'Menunggu Verifikasi')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-[#FFFAEB] text-[#B54708] border border-[#FEDF89]">
                                                Menunggu
                                            </span>
                                        @elseif($item->status == 'Disetujui')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-[#ECFDF3] text-[#027A48] border border-[#A6F4C5]">
                                                Disetujui
                                            </span>
                                        @elseif($item->status == 'Revisi')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-[#FFFAEB] text-[#B54708] border border-[#FEDF89]">
                                                Revisi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-[#FEF3F2] text-[#B42318] border border-[#FECDCA]">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Tombol Aksi Detail -->
                                    <td class="px-4 py-4 text-center">
                                        <a 
                                            href="{{ route('verifikasi.show', $item->id) }}" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#00629B] hover:bg-[#004F7D] text-white rounded-lg text-xs font-bold transition shadow-xs"
                                        >
                                            <span>Detail</span>
                                            <i class="fa-solid fa-chevron-right text-[9px]"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-12 text-[#667085]">
                                        <i class="fa-solid fa-inbox text-4xl mb-3 block text-[#BAE3F5]"></i>
                                        <p class="font-medium">Tidak ada permohonan dalam antrean saat ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>

        <!-- Footer -->
        <footer class="mt-auto py-3 border-t border-[#00629B] text-center text-[10.5px] text-[#98A2B3] bg-[#101828]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-1">
                <p>BPBD Kabupaten Purbalingga &middot; Sistem Informasi Pendukung Keputusan Distribusi Air Bersih Darurat</p>
                <p>&copy; {{ date('Y') }} Lingga Air &middot; Proyek Perangkat Lunak Kelompok 4</p>
            </div>
        </footer>

    </div>

</body>
</html>
