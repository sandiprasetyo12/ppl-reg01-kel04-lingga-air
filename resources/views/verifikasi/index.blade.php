<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrean Verifikasi Pengajuan - Lingga Air</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen antialiased flex flex-col lg:flex-row">

    <!-- ==================== SIDEBAR KIRI (ADMIN BPBD) ==================== -->
     <aside class="w-full lg:w-64 bg-white text-slate-700 flex flex-col justify-between shrink-0 border-r border-slate-200 z-20">
        <div>
            <!-- 1. Logo & Brand Header -->
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#086288] flex items-center justify-center font-bold text-white shadow-md shadow-[#086288]/20">
                        <i class="fa-solid fa-droplet text-lg"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-slate-900 text-base tracking-tight block">Lingga Air</span>
                        <span class="text-[11px] text-[#086288] block font-semibold">BPBD Kab. Purbalingga</span>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#f0f8fb] text-[#086288] border border-[#cae4ef]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#086288] animate-pulse"></span>
                        Portal Verifikator
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
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:text-[#086288] hover:bg-slate-50 transition">
                                <i class="fa-solid fa-chart-pie w-4 text-center text-slate-400"></i>
                                <span>Dashboard Statistik</span>
                            </a>
                        </li>
                        <li>
                            <!-- Active Menu Fitur 4 (Verifikasi Pengajuan) -->
                            <a href="{{ route('verifikasi.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-[#086288] text-white shadow-sm font-bold transition">
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
                            <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:text-[#086288] hover:bg-slate-50 transition">
                                <i class="fa-solid fa-truck-droplet w-4 text-center text-slate-400"></i>
                                <span>Penugasan Armada</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:text-[#086288] hover:bg-slate-50 transition">
                                <i class="fa-solid fa-map-location-dot w-4 text-center text-slate-400"></i>
                                <span>Peta Sebaran (GIS)</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:text-[#086288] hover:bg-slate-50 transition">
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
                            <a href="{{ route('admin.desa.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-600 hover:text-[#086288] hover:bg-slate-50 transition">
                                <i class="fa-solid fa-building-columns w-4 text-center text-slate-400"></i>
                                <span>Data Acuan Desa</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- 3. Sidebar Footer: Profil Petugas BPBD -->
        <div class="m-3 p-3.5 bg-[#f0f8fb] border border-[#cae4ef] rounded-xl">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-[#086288] text-white flex items-center justify-center font-bold text-xs shadow-sm">
                    BP
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-slate-800 truncate">Petugas Verifikator</p>
                    <p class="text-[10px] text-slate-500 truncate">admin@bpbd.purbalinggakab.go.id</p>
                </div>
            </div>
                        <div class="mt-2.5 pt-2 border-t border-[#cae4ef] flex items-center justify-between text-[10px] text-slate-500">
                <span class="flex items-center gap-1.5">
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
    </aside>

    <!-- ==================== MAIN CONTENT AREA ==================== -->
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden">

        <!-- Top Header Bar -->
                <!-- Top Header Bar -->
        <header class="bg-white border-b border-slate-200 px-6 py-4 sticky top-0 z-10 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Antrean Verifikasi Permohonan Air Bersih</h1>
                <p class="text-xs text-slate-500 mt-0.5">Penetapan prioritas bantuan air bersih darurat Kabupaten Purbalingga</p>
            </div>

            <div class="flex items-center gap-3">
                <span class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                    <i class="fa-regular fa-calendar text-slate-400"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
                <a href="{{ route('verifikasi.index') }}" class="px-3.5 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white border border-blue-200 rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-rotate-right"></i> Refresh
                </a>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="flex-1 p-6 lg:p-8 max-w-7xl mx-auto w-full">

            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start gap-3 shadow-sm animate-fade-in">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-xl mt-0.5"></i>
                    <div class="flex-1">
                        <h4 class="font-bold text-sm">Aksi Berhasil!</h4>
                        <p class="text-xs text-emerald-800 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Metric Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Lolos Skrining</p>
                        <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $ringkasan['total_pengajuan'] }}</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Permohonan terdaftar</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-amber-200 bg-amber-50/20 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Menunggu Verifikasi</p>
                        <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $ringkasan['menunggu_verifikasi'] }}</p>
                        <p class="text-[11px] text-amber-700/70 mt-0.5">Memerlukan telaah BPBD</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-emerald-200 bg-emerald-50/20 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Disetujui</p>
                        <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $ringkasan['disetujui'] }}</p>
                        <p class="text-[11px] text-emerald-700/70 mt-0.5">Siap alokasi armada tangki</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-rose-200 bg-rose-50/20 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Perlu Tindak Lanjut</p>
                        <p class="text-2xl font-extrabold text-rose-600 mt-1">{{ $ringkasan['perlu_tindak_lanjut'] }}</p>
                        <p class="text-[11px] text-rose-700/70 mt-0.5">Revisi dokumen / Ditolak</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Toolbar -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6">
                <form method="GET" action="{{ route('verifikasi.index') }}" class="flex flex-col md:flex-row items-center gap-3">
                    <div class="relative flex-1 w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama desa, kecamatan, atau nomor tiket..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>

                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <select name="status" class="w-full md:w-auto px-3.5 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">Semua Status Tindakan</option>
                            <option value="Menunggu Verifikasi" {{ request('status') == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="Revisi" {{ request('status') == 'Revisi' ? 'selected' : '' }}>Revisi</option>
                            <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>

                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition shrink-0 shadow-sm">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('verifikasi.index') }}" class="px-3 py-2 text-slate-500 hover:text-slate-700 text-sm">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

                                              <!-- ==================== BANNER INFORMASI PRIORITAS & BOBOT PENILAIAN ==================== -->
            <div class="bg-white rounded-xl border border-slate-200 p-4 mb-6 shadow-sm">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    
                    <!-- Kiri: Kategori Status Kelayakan -->
                    <div class="space-y-1.5">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wide flex items-center gap-1.5">
                            <i class="fa-solid fa-scale-balanced text-[#086288]"></i>
                            Kategori Prioritas:
                        </span>
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-rose-50 text-rose-700 border border-rose-200 font-bold">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                Kritis (&ge; 60.00)
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 font-bold">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                Waspada (30.00 - 59.99)
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-50 text-slate-600 border border-slate-200 font-medium">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                Aman (&lt; 30.00)
                            </span>
                        </div>
                    </div>

                    <!-- Kanan: Bobot Penilaian (Bahasa Manusiawi & Jelas) -->
                    <div class="lg:border-l lg:border-slate-200 lg:pl-6 space-y-1.5">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wide block">
                            Bobot Penilaian:
                        </span>
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="px-2.5 py-1 rounded-lg bg-slate-50 text-slate-700 border border-slate-200 font-medium">
                                KK Terdampak: <strong class="text-slate-900 font-bold">50,47%</strong>
                            </span>
                            <span class="px-2.5 py-1 rounded-lg bg-slate-50 text-slate-700 border border-slate-200 font-medium">
                                Hari Tanpa Air: <strong class="text-slate-900 font-bold">30,26%</strong>
                            </span>
                            <span class="px-2.5 py-1 rounded-lg bg-slate-50 text-slate-700 border border-slate-200 font-medium">
                                Jarak Sumber Air: <strong class="text-slate-900 font-bold">11,00%</strong>
                            </span>
                            <span class="px-2.5 py-1 rounded-lg bg-slate-50 text-slate-700 border border-slate-200 font-medium">
                                Kelompok Rentan: <strong class="text-slate-900 font-bold">8,26%</strong>
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Ranked Queue Table (PBI-010 Acceptance Criteria 1) -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-200 bg-slate-50/70 flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-slate-800 text-sm">Daftar Antrean Permohonan Bantuan Air Bersih</h2>
                        <p class="text-xs text-slate-500">Otomatis terurut berdasarkan skor prioritas AHP-SAW tertinggi</p>
                    </div>
                    <span class="text-xs text-slate-600 bg-slate-200/70 px-2.5 py-1 rounded-full font-medium">
                        Menampilkan <strong>{{ $antrean->count() }}</strong> permohonan
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-700">
                        <thead class="bg-slate-100/80 text-xs uppercase font-semibold text-slate-600 tracking-wider border-b border-slate-200">
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
                        <tbody class="divide-y divide-slate-200">
                            @forelse($antrean as $index => $item)
                                <tr class="hover:bg-slate-50/80 transition duration-150 {{ $index === 0 ? 'bg-amber-50/20' : '' }}">
                                    <!-- Peringkat -->
                                    <td class="px-4 py-4 text-center">
                                        @if($index === 0)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-500 text-white font-bold text-xs shadow ring-2 ring-amber-200" title="Prioritas Tertinggi #1">
                                                #1
                                            </span>
                                        @elseif($index === 1)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-400 text-white font-bold text-xs">
                                                #2
                                            </span>
                                        @elseif($index === 2)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-700 text-white font-bold text-xs">
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
                                        <span class="font-bold text-blue-900 block font-mono text-xs">{{ $item->nomor_tiket }}</span>
                                        <span class="text-xs text-slate-400 block mt-0.5">
                                            <i class="fa-regular fa-calendar-days mr-1"></i>{{ $item->created_at->format('d M Y, H:i') }}
                                        </span>
                                    </td>

                                    <!-- Desa & Kecamatan -->
                                    <td class="px-4 py-4">
                                        <span class="font-semibold text-slate-900 block">Desa {{ $item->desa->nama_desa ?? '-' }}</span>
                                        <span class="text-xs text-slate-500 block">Kec. {{ $item->desa->kecamatan ?? '-' }}</span>
                                    </td>

                                    <!-- Parameter Lapangan -->
                                    <td class="px-4 py-4 text-center">
                                        <div class="inline-flex items-center gap-3 text-xs bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-200">
                                            <span title="Kepala Keluarga Terdampak"><i class="fa-solid fa-users text-blue-600 mr-1"></i><strong>{{ $item->kk_terdampak }}</strong> KK</span>
                                            <span class="text-slate-300">|</span>
                                            <span title="Hari Tanpa Pasokan Air"><i class="fa-solid fa-sun text-amber-500 mr-1"></i><strong>{{ $item->hari_tanpa_air }}</strong> hari</span>
                                        </div>
                                    </td>

                                    <!-- Skor AHP-SAW (PBI-010) -->
                                    <td class="px-4 py-4 text-center">
                                        <div class="inline-block">
                                            <span class="text-base font-extrabold {{ $item->skor_ahp_saw >= 60 ? 'text-rose-600' : ($item->skor_ahp_saw >= 30 ? 'text-amber-600' : 'text-emerald-600') }}">
                                                {{ number_format($item->skor_ahp_saw, 2) }}
                                            </span>
                                            <span class="text-[10px] text-slate-400 block">/ 100</span>
                                        </div>
                                    </td>

                                    <!-- Kategori Kelayakan -->
                                    <td class="px-4 py-4 text-center">
                                        @if($item->skor_ahp_saw >= 60)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-600 mr-1.5 animate-pulse"></span> Kritis
                                            </span>
                                        @elseif($item->skor_ahp_saw >= 30)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600 mr-1.5"></span> Waspada
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 mr-1.5"></span> Aman
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Status Verifikasi -->
                                    <td class="px-4 py-4 text-center">
                                        @if($item->status == 'Menunggu Verifikasi')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                                Menunggu
                                            </span>
                                        @elseif($item->status == 'Disetujui')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Disetujui
                                            </span>
                                        @elseif($item->status == 'Revisi')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                                Revisi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Tombol Aksi Detail -->
                                    <td class="px-4 py-4 text-center">
                                        <a href="{{ route('verifikasi.show', $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white rounded-lg text-xs font-semibold border border-blue-200 hover:border-blue-600 transition shadow-sm">
                                            <span>Detail</span>
                                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-12 text-slate-400">
                                        <i class="fa-solid fa-inbox text-4xl mb-3 block"></i>
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
        <footer class="mt-auto py-5 border-t border-slate-200 text-center text-xs text-slate-500 bg-white">
            &copy; 2026 Lingga Air - Proyek Perangkat Lunak (Kelompok 4). BPBD Kabupaten Purbalingga.
        </footer>

    </div>

</body>
</html>
