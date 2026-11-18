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
                        'la-peach': '#F4BAA3',
                        'la-beige': '#F4F0E2',
                        'la-pastel': '#A6C8E2',
                        'la-blue': '#619DC6',
                        'la-blue-dark': '#4888B5',
                        'la-navy': '#1B3B5A',
                        'la-navy-dark': '#132A40',
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
            background-color: #F4F0E2;
            color: #1B3B5A;
        }
    </style>
</head>
<body class="bg-[#F4F0E2] text-[#1B3B5A] min-h-screen antialiased flex flex-col lg:flex-row font-sans">

    <!-- ==================== SIDEBAR KIRI (ADMIN BPBD) ==================== -->
    <aside class="w-full lg:w-64 bg-white text-[#1B3B5A] flex flex-col justify-between shrink-0 border-r border-[#A6C8E2]/70 z-20 shadow-xs">
        <div>
            <!-- 1. Logo & Brand Header -->
            <div class="p-5 border-b border-[#A6C8E2]/40">
                <a href="{{ url('/') }}" class="flex items-center gap-2 block">
                    <img 
                        src="{{ asset('images/logo-lingga.png') }}" 
                        alt="Lingga Air" 
                        class="h-12 sm:h-14 w-auto max-w-[190px] object-contain" 
                        onerror="this.onerror=null; this.src='https://placehold.co/180x60/619DC6/FFFFFF?text=Lingga+Air';"
                    >
                </a>
                <div class="mt-2.5">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#F4F0E2] text-[#1B3B5A] border border-[#A6C8E2]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#619DC6] animate-pulse"></span>
                        Portal Verifikator BPBD
                    </span>
                </div>
            </div>

            <!-- 2. Navigation Links -->
            <nav class="p-4 space-y-6">
                <!-- Group 1: Menu Operasional BPBD -->
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2">
                        Menu Utama BPBD
                    </span>
                    <ul class="space-y-1 text-xs font-semibold">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[#1B3B5A] hover:text-[#619DC6] hover:bg-[#A6C8E2]/15 transition">
                                <i class="fa-solid fa-chart-pie w-4 text-center text-[#619DC6]"></i>
                                <span>Dashboard Statistik</span>
                            </a>
                        </li>
                        <li>
                            <!-- Active Menu Fitur 4 (Verifikasi Pengajuan) -->
                            <a href="{{ route('verifikasi.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-[#619DC6] text-white shadow-sm font-bold transition">
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
                            <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:text-[#619DC6] hover:bg-[#A6C8E2]/15 transition">
                                <i class="fa-solid fa-truck-droplet w-4 text-center text-slate-400"></i>
                                <span>Penugasan Armada</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:text-[#619DC6] hover:bg-[#A6C8E2]/15 transition">
                                <i class="fa-solid fa-map-location-dot w-4 text-center text-slate-400"></i>
                                <span>Peta Sebaran (GIS)</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:text-[#619DC6] hover:bg-[#A6C8E2]/15 transition">
                                <i class="fa-solid fa-file-invoice w-4 text-center text-slate-400"></i>
                                <span>Laporan Penyaluran</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Group 2: Master Data Wilayah -->
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2">
                        Data Master
                    </span>
                    <ul class="space-y-1 text-xs font-semibold">
                        <li>
                            <a href="{{ route('admin.desa.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[#1B3B5A] hover:text-[#619DC6] hover:bg-[#A6C8E2]/15 transition">
                                <i class="fa-solid fa-building-columns w-4 text-center text-[#619DC6]"></i>
                                <span>Data Acuan Desa</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- 3. Sidebar Footer: Profil Petugas BPBD -->
        <div class="m-3 p-3.5 bg-[#F4F0E2] border border-[#A6C8E2] rounded-xl">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-[#1B3B5A] text-[#F4BAA3] flex items-center justify-center font-bold text-xs shadow-xs">
                    BP
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-[#1B3B5A] truncate">Petugas Verifikator</p>
                    <p class="text-[10px] text-slate-500 truncate">admin@bpbd.purbalinggakab.go.id</p>
                </div>
            </div>
            <div class="mt-2.5 pt-2 border-t border-[#A6C8E2] flex items-center justify-between text-[10px] text-slate-500">
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
        <header class="bg-white/95 backdrop-blur-md border-b border-[#A6C8E2]/70 px-4 sm:px-6 py-3.5 sticky top-0 z-10 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-lg sm:text-xl font-extrabold text-[#1B3B5A] tracking-tight">Antrean Verifikasi Permohonan Air Bersih</h1>
                <p class="text-xs text-slate-500 mt-0.5">Penetapan prioritas bantuan air bersih darurat Kabupaten Purbalingga</p>
            </div>

            <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                <!-- Tombol Back ke Dashboard -->
                <a 
                    href="{{ route('admin.dashboard') }}" 
                    class="px-3 py-1.5 bg-white text-[#1B3B5A] hover:bg-[#1B3B5A] hover:text-white border border-[#A6C8E2] rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-xs"
                    title="Kembali ke Dashboard Utama BPBD"
                >
                    <i class="fa-solid fa-arrow-left text-[#619DC6]"></i>
                    <span>Kembali ke Dashboard</span>
                </a>

                <span class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#F4F0E2] text-[#1B3B5A] border border-[#A6C8E2]">
                    <i class="fa-regular fa-calendar text-[#619DC6]"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>

                <a 
                    href="{{ route('verifikasi.index') }}" 
                    class="px-3.5 py-1.5 bg-[#619DC6] text-white hover:bg-[#4888B5] rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-xs"
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
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start gap-3 shadow-xs animate-fade-in">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-xl mt-0.5"></i>
                    <div class="flex-1">
                        <h4 class="font-bold text-sm">Aksi Berhasil!</h4>
                        <p class="text-xs text-emerald-800 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Metric Summary Cards (Menggunakan Palet Warna Lingga Air) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- 1. Total Permohonan -->
                <div class="bg-white p-5 rounded-2xl border border-[#A6C8E2] shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Lolos Skrining</p>
                        <p class="text-2xl font-extrabold text-[#1B3B5A] mt-1">{{ $ringkasan['total_pengajuan'] }}</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Permohonan terdaftar</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-[#A6C8E2]/25 text-[#619DC6] flex items-center justify-center text-lg">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                </div>

                <!-- 2. Menunggu Verifikasi -->
                <div class="bg-white p-5 rounded-2xl border border-[#F4BAA3] shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-[#1B3B5A] uppercase tracking-wider">Menunggu Verifikasi</p>
                        <p class="text-2xl font-extrabold text-[#1B3B5A] mt-1">{{ $ringkasan['menunggu_verifikasi'] }}</p>
                        <p class="text-[11px] text-[#1B3B5A]/70 mt-0.5">Memerlukan telaah BPBD</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-[#F4BAA3]/40 text-[#1B3B5A] flex items-center justify-center text-lg font-bold">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>

                <!-- 3. Disetujui -->
                <div class="bg-white p-5 rounded-2xl border border-emerald-200 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider">Disetujui</p>
                        <p class="text-2xl font-extrabold text-emerald-700 mt-1">{{ $ringkasan['disetujui'] }}</p>
                        <p class="text-[11px] text-emerald-700/70 mt-0.5">Siap alokasi armada tangki</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>

                <!-- 4. Perlu Tindak Lanjut -->
                <div class="bg-white p-5 rounded-2xl border border-rose-200 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-rose-800 uppercase tracking-wider">Perlu Tindak Lanjut</p>
                        <p class="text-2xl font-extrabold text-rose-700 mt-1">{{ $ringkasan['perlu_tindak_lanjut'] }}</p>
                        <p class="text-[11px] text-rose-700/70 mt-0.5">Revisi dokumen / Ditolak</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Toolbar -->
            <div class="bg-white p-4 rounded-2xl border border-[#A6C8E2] shadow-xs">
                <form method="GET" action="{{ route('verifikasi.index') }}" class="flex flex-col md:flex-row items-center gap-3">
                    <div class="relative flex-1 w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Cari nama desa, kecamatan, atau nomor tiket..." 
                            class="w-full pl-9 pr-4 py-2 bg-[#F4F0E2]/40 border border-[#A6C8E2] rounded-lg text-xs text-[#1B3B5A] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#619DC6] focus:border-[#619DC6] transition"
                        >
                    </div>

                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <select 
                            name="status" 
                            class="w-full md:w-auto px-3 py-2 bg-[#F4F0E2]/40 border border-[#A6C8E2] rounded-lg text-xs text-[#1B3B5A] focus:outline-none focus:ring-2 focus:ring-[#619DC6] transition"
                        >
                            <option value="">Semua Status Tindakan</option>
                            <option value="Menunggu Verifikasi" {{ request('status') == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="Revisi" {{ request('status') == 'Revisi' ? 'selected' : '' }}>Revisi</option>
                            <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>

                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-[#619DC6] hover:bg-[#4888B5] text-white rounded-lg text-xs font-bold transition shrink-0 shadow-xs cursor-pointer"
                        >
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('verifikasi.index') }}" class="px-3 py-2 text-slate-500 hover:text-[#1B3B5A] text-xs font-semibold">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Banner Informasi Prioritas & Bobot Penilaian -->
            <div class="bg-white rounded-2xl border border-[#A6C8E2] p-4 shadow-xs">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    
                    <!-- Kiri: Kategori Status Kelayakan -->
                    <div class="space-y-1.5">
                        <span class="text-xs font-bold text-[#1B3B5A] uppercase tracking-wide flex items-center gap-1.5">
                            <i class="fa-solid fa-scale-balanced text-[#619DC6]"></i>
                            Kategori Prioritas:
                        </span>
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-rose-50 text-rose-800 border border-rose-200 font-bold">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                Kritis (&ge; 70.00)
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-amber-50 text-amber-800 border border-amber-200 font-bold">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                Waspada (40.00 – 69.99)
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-[#A6C8E2]/25 text-[#1B3B5A] border border-[#A6C8E2] font-semibold">
                                <span class="w-2 h-2 rounded-full bg-[#619DC6]"></span>
                                Aman (&lt; 40.00)
                            </span>
                        </div>
                    </div>

                    <!-- Kanan: Bobot Penilaian (AHP-SAW) -->
                    <div class="lg:border-l lg:border-[#A6C8E2]/60 lg:pl-6 space-y-1.5">
                        <span class="text-xs font-bold text-[#1B3B5A] uppercase tracking-wide block">
                            Bobot Penilaian (Metode AHP-SAW):
                        </span>
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="px-2.5 py-1 rounded-lg bg-[#F4F0E2] text-[#1B3B5A] border border-[#A6C8E2]/60 font-medium">
                                KK Terdampak: <strong class="font-bold">50,47%</strong>
                            </span>
                            <span class="px-2.5 py-1 rounded-lg bg-[#F4F0E2] text-[#1B3B5A] border border-[#A6C8E2]/60 font-medium">
                                Hari Tanpa Air: <strong class="font-bold">30,26%</strong>
                            </span>
                            <span class="px-2.5 py-1 rounded-lg bg-[#F4F0E2] text-[#1B3B5A] border border-[#A6C8E2]/60 font-medium">
                                Jarak Sumber Air: <strong class="font-bold">11,00%</strong>
                            </span>
                            <span class="px-2.5 py-1 rounded-lg bg-[#F4F0E2] text-[#1B3B5A] border border-[#A6C8E2]/60 font-medium">
                                Kelompok Rentan: <strong class="font-bold">8,26%</strong>
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Ranked Queue Table (PBI-010 Acceptance Criteria 1) -->
            <div class="bg-white rounded-2xl border border-[#A6C8E2] shadow-xs overflow-hidden">
                <div class="p-4 border-b border-[#A6C8E2]/60 bg-[#F4F0E2]/60 flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-[#1B3B5A] text-sm">Daftar Antrean Permohonan Bantuan Air Bersih</h2>
                        <p class="text-xs text-slate-500">Otomatis terurut berdasarkan skor prioritas AHP-SAW tertinggi</p>
                    </div>
                    <span class="text-xs text-[#1B3B5A] bg-white border border-[#A6C8E2] px-3 py-1 rounded-full font-bold">
                        Menampilkan <strong>{{ $antrean->count() }}</strong> permohonan
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-[#1B3B5A]">
                        <thead class="bg-[#1B3B5A] text-white text-[11px] uppercase font-bold tracking-wider">
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
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @forelse($antrean as $index => $item)
                                <tr class="hover:bg-[#F4F0E2]/40 transition duration-150 {{ $index === 0 ? 'bg-[#F4BAA3]/10' : '' }}">
                                    <!-- Peringkat -->
                                    <td class="px-4 py-4 text-center">
                                        @if($index === 0)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#F4BAA3] text-[#1B3B5A] font-extrabold text-xs shadow-xs ring-2 ring-[#619DC6]" title="Prioritas Tertinggi #1">
                                                #1
                                            </span>
                                        @elseif($index === 1)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#A6C8E2] text-[#1B3B5A] font-bold text-xs">
                                                #2
                                            </span>
                                        @elseif($index === 2)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#F4F0E2] text-[#1B3B5A] font-bold text-xs border border-[#A6C8E2]">
                                                #3
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-100 text-slate-600 font-semibold text-xs border border-slate-300">
                                                #{{ $index + 1 }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Nomor Tiket & Waktu -->
                                    <td class="px-4 py-4">
                                        <span class="font-bold text-[#1B3B5A] block font-mono text-xs">{{ $item->nomor_tiket }}</span>
                                        <span class="text-[11px] text-slate-400 block mt-0.5">
                                            <i class="fa-regular fa-calendar-days mr-1 text-[#619DC6]"></i>{{ $item->created_at->format('d M Y, H:i') }}
                                        </span>
                                    </td>

                                    <!-- Desa & Kecamatan -->
                                    <td class="px-4 py-4">
                                        <span class="font-bold text-[#1B3B5A] block">Desa {{ $item->desa->nama_desa ?? '-' }}</span>
                                        <span class="text-xs text-slate-500 block">Kec. {{ $item->desa->kecamatan ?? '-' }}</span>
                                    </td>

                                    <!-- Parameter Lapangan -->
                                    <td class="px-4 py-4 text-center">
                                        <div class="inline-flex items-center gap-2.5 text-xs bg-[#F4F0E2]/70 px-2.5 py-1.5 rounded-lg border border-[#A6C8E2]/60">
                                            <span title="Kepala Keluarga Terdampak"><i class="fa-solid fa-users text-[#619DC6] mr-1"></i><strong>{{ $item->kk_terdampak }}</strong> KK</span>
                                            <span class="text-[#A6C8E2]">|</span>
                                            <span title="Hari Tanpa Pasokan Air"><i class="fa-solid fa-sun text-amber-500 mr-1"></i><strong>{{ $item->hari_tanpa_air }}</strong> hari</span>
                                        </div>
                                    </td>

                                    <!-- Skor AHP-SAW (PBI-010) -->
                                    <td class="px-4 py-4 text-center">
                                        <div class="inline-block">
                                            <span class="text-base font-extrabold {{ $item->skor_ahp_saw >= 70 ? 'text-rose-600' : ($item->skor_ahp_saw >= 40 ? 'text-amber-600' : 'text-emerald-600') }}">
                                                {{ number_format($item->skor_ahp_saw, 2) }}
                                            </span>
                                            <span class="text-[10px] text-slate-400 block">/ 100</span>
                                        </div>
                                    </td>

                                    <!-- Kategori Kelayakan -->
                                    <td class="px-4 py-4 text-center">
                                        @if($item->skor_ahp_saw >= 70)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-600 mr-1.5 animate-pulse"></span> Kritis
                                            </span>
                                        @elseif($item->skor_ahp_saw >= 40)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600 mr-1.5"></span> Waspada
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 mr-1.5"></span> Aman
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Status Verifikasi -->
                                    <td class="px-4 py-4 text-center">
                                        @if($item->status == 'Menunggu Verifikasi')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-[#F4BAA3]/30 text-[#1B3B5A] border border-[#F4BAA3]">
                                                Menunggu
                                            </span>
                                        @elseif($item->status == 'Disetujui')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                Disetujui
                                            </span>
                                        @elseif($item->status == 'Revisi')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                                Revisi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Tombol Aksi Detail -->
                                    <td class="px-4 py-4 text-center">
                                        <a 
                                            href="{{ route('verifikasi.show', $item->id) }}" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#619DC6] hover:bg-[#4888B5] text-white rounded-lg text-xs font-bold transition shadow-xs"
                                        >
                                            <span>Detail</span>
                                            <i class="fa-solid fa-chevron-right text-[9px]"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-12 text-slate-400">
                                        <i class="fa-solid fa-inbox text-4xl mb-3 block text-[#A6C8E2]"></i>
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
        <footer class="mt-auto py-2.5 border-t-2 border-[#619DC6] text-center text-[10.5px] text-slate-400 bg-[#1B3B5A]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-1">
                <p>BPBD Kabupaten Purbalingga &middot; Sistem Informasi Pendukung Keputusan Distribusi Air Bersih Darurat</p>
                <p>&copy; {{ date('Y') }} Lingga Air &middot; Proyek Perangkat Lunak Kelompok 4</p>
            </div>
        </footer>

    </div>

</body>
</html>
