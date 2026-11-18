<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Permohonan {{ $permohonan->nomor_tiket }} - Lingga Air | BPBD Purbalingga</title>
    
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

        <!-- Top Header Bar (Dengan Button Back Ke Dashboard & Ke Antrean) -->
        <header class="bg-white/95 backdrop-blur-md border-b border-[#A6C8E2]/70 px-4 sm:px-6 py-3.5 sticky top-0 z-10 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <nav class="text-xs text-slate-500 mb-0.5 flex items-center gap-1.5 font-medium">
                    <a href="{{ route('verifikasi.index') }}" class="hover:underline flex items-center gap-1 text-[#619DC6] font-bold">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i> Antrean Verifikasi
                    </a>
                    <span class="text-slate-300">/</span>
                    <span class="text-[#1B3B5A] font-semibold font-mono">{{ $permohonan->nomor_tiket }}</span>
                </nav>
                <h1 class="text-lg sm:text-xl font-extrabold text-[#1B3B5A] tracking-tight">Telaah Berkas Permohonan: Desa {{ $permohonan->desa->nama_desa ?? '-' }}</h1>
            </div>

            <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                <!-- Tombol Back ke Dashboard -->
                <a 
                    href="{{ route('admin.dashboard') }}" 
                    class="px-3 py-1.5 bg-white text-[#1B3B5A] hover:bg-[#1B3B5A] hover:text-white border border-[#A6C8E2] rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-xs"
                    title="Kembali ke Dashboard Utama BPBD"
                >
                    <i class="fa-solid fa-gauge-high text-[#619DC6]"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Tombol Kembali ke Antrean -->
                <a 
                    href="{{ route('verifikasi.index') }}" 
                    class="px-3 py-1.5 bg-[#619DC6] text-white hover:bg-[#4888B5] rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-xs"
                >
                    <i class="fa-solid fa-list-check"></i>
                    <span>Daftar Antrean</span>
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
                        <h4 class="font-bold text-sm">Aksi Verifikasi Berhasil!</h4>
                        <p class="text-xs text-emerald-800 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 flex items-start gap-3 shadow-xs">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600 text-xl mt-0.5"></i>
                    <div class="flex-1">
                        <h4 class="font-bold text-sm">Terdapat Kesalahan Input:</h4>
                        <ul class="list-disc list-inside text-xs text-rose-800 mt-1 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Banner Header Tiket & Status -->
            <div class="bg-white rounded-2xl border border-[#A6C8E2] p-5 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h2 class="text-xl sm:text-2xl font-extrabold text-[#1B3B5A]">Desa {{ $permohonan->desa->nama_desa }}</h2>
                        @if($permohonan->status == 'Menunggu Verifikasi')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#F4BAA3]/30 text-[#1B3B5A] border border-[#F4BAA3]">
                                Menunggu Verifikasi
                            </span>
                        @elseif($permohonan->status == 'Disetujui')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1.5">
                                <i class="fa-solid fa-check"></i> Disetujui
                            </span>
                        @elseif($permohonan->status == 'Revisi')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 flex items-center gap-1.5">
                                <i class="fa-solid fa-pen-to-square"></i> Perlu Revisi
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-800 border border-rose-200 flex items-center gap-1.5">
                                <i class="fa-solid fa-xmark"></i> Ditolak
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mt-1.5">
                        Nomor Tiket: <strong class="font-mono text-[#1B3B5A]">{{ $permohonan->nomor_tiket }}</strong>
                        &bull; Diajukan pada: <strong class="text-[#1B3B5A]">{{ $permohonan->created_at->format('d F Y, H:i') }} WIB</strong>
                        &bull; Kecamatan: <strong class="text-[#1B3B5A]">{{ $permohonan->desa->kecamatan }}</strong>
                    </p>
                </div>
                
                <div class="flex items-center gap-2">
                    <a href="{{ route('verifikasi.index') }}" class="px-3.5 py-2 bg-[#F4F0E2] border border-[#A6C8E2] rounded-xl text-xs font-bold text-[#1B3B5A] hover:bg-[#A6C8E2]/30 transition shadow-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Kembali ke Antrean</span>
                    </a>
                </div>
            </div>

            <!-- 2 Column Layout (PBI-010, PBI-011, PBI-012) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- LEFT 2 COLS: Data Permohonan & Berkas & Audit Trail -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- 1. Data Acuan Desa (Sinkron F-01 & F-02) -->
                    <div class="bg-white rounded-2xl border border-[#A6C8E2] shadow-xs p-5 sm:p-6">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#1B3B5A] border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-[#619DC6]"></i> Data Acuan Desa & Kontak Penanggung Jawab
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs mb-4">
                            <div>
                                <span class="text-[11px] text-slate-400 block">Nama Desa / Kelurahan</span>
                                <span class="font-bold text-[#1B3B5A] text-sm">Desa {{ $permohonan->desa->nama_desa }}</span>
                            </div>
                            <div>
                                <span class="text-[11px] text-slate-400 block">Kecamatan</span>
                                <span class="font-bold text-[#1B3B5A] text-sm">Kec. {{ $permohonan->desa->kecamatan }}</span>
                            </div>
                            <div>
                                <span class="text-[11px] text-slate-400 block">Penanggung Jawab Permohonan</span>
                                <span class="font-semibold text-[#1B3B5A]">{{ $permohonan->desa->penanggung_jawab ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-[11px] text-slate-400 block">Nomor Kontak / WhatsApp</span>
                                <span class="font-semibold text-[#1B3B5A] flex items-center gap-1.5">
                                    <i class="fa-brands fa-whatsapp text-emerald-500"></i>
                                    {{ $permohonan->desa->kontak_wa ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <!-- Rincian Data Kependudukan Acuan Desa -->
                        <div class="bg-[#F4F0E2]/50 p-4 rounded-xl border border-[#A6C8E2]/60 mb-4">
                            <span class="text-[11px] font-bold text-[#1B3B5A] uppercase tracking-wider block mb-3">
                                <i class="fa-solid fa-users-viewfinder text-[#619DC6] mr-1.5"></i> Profil Demografi Desa (Data Acuan):
                            </span>
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5 text-center text-xs">
                                <div class="bg-white p-2.5 rounded-lg border border-[#A6C8E2]/60">
                                    <span class="text-slate-400 block text-[10px]">KK Total</span>
                                    <span class="font-bold text-[#1B3B5A] text-sm mt-0.5 block">{{ number_format($permohonan->desa->jumlah_kk) }}</span>
                                </div>
                                <div class="bg-white p-2.5 rounded-lg border border-[#A6C8E2]/60">
                                    <span class="text-slate-400 block text-[10px]">Jiwa Total</span>
                                    <span class="font-bold text-[#1B3B5A] text-sm mt-0.5 block">{{ number_format($permohonan->desa->jumlah_jiwa) }}</span>
                                </div>
                                <div class="bg-white p-2.5 rounded-lg border border-[#A6C8E2]/60">
                                    <span class="text-slate-400 block text-[10px]">Balita</span>
                                    <span class="font-bold text-[#619DC6] text-sm mt-0.5 block">{{ $permohonan->desa->jumlah_balita }}</span>
                                </div>
                                <div class="bg-white p-2.5 rounded-lg border border-[#A6C8E2]/60">
                                    <span class="text-slate-400 block text-[10px]">Lansia</span>
                                    <span class="font-bold text-purple-600 text-sm mt-0.5 block">{{ $permohonan->desa->jumlah_lansia }}</span>
                                </div>
                                <div class="bg-white p-2.5 rounded-lg border border-[#A6C8E2]/60">
                                    <span class="text-slate-400 block text-[10px]">Disabilitas</span>
                                    <span class="font-bold text-amber-600 text-sm mt-0.5 block">{{ $permohonan->desa->jumlah_disabilitas }}</span>
                                </div>
                            </div>
                            <div class="text-[10.5px] text-slate-500 mt-2 text-right">
                                Total Kelompok Rentan: <strong class="text-[#1B3B5A]">{{ $permohonan->desa->total_jiwa_rentan }} jiwa</strong> ({{ number_format(($permohonan->desa->total_jiwa_rentan / max(1, $permohonan->desa->jumlah_jiwa)) * 100, 1) }}% dari total jiwa)
                            </div>
                        </div>

                        <div class="bg-[#A6C8E2]/20 p-3.5 rounded-xl border border-[#A6C8E2] text-xs">
                            <span class="text-slate-500 font-medium block">Titik Drop-Off Air Bersih Desa:</span>
                            <span class="font-bold text-[#1B3B5A] mt-0.5 block flex items-center gap-2">
                                <i class="fa-solid fa-truck-droplet text-[#619DC6]"></i>
                                {{ $permohonan->desa->titik_drop_air ?? 'Belum ditentukan oleh desa' }}
                            </span>
                        </div>
                    </div>

                    <!-- 2. Kondisi Lapangan (Parameter Formulir Fitur 2) -->
                    <div class="bg-white rounded-2xl border border-[#A6C8E2] shadow-xs p-5 sm:p-6">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#1B3B5A] border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-temperature-arrow-up text-amber-500"></i> Kondisi Lapangan & Parameter Kekeringan
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                            <div class="bg-[#A6C8E2]/20 p-4 rounded-xl border border-[#A6C8E2] text-center">
                                <span class="text-xs text-[#1B3B5A] block font-medium">KK Terdampak</span>
                                <span class="text-2xl font-bold text-[#1B3B5A] mt-1 block">{{ $permohonan->kk_terdampak }}</span>
                                <span class="text-[10px] text-slate-500 block mt-0.5">dari total {{ $permohonan->desa->jumlah_kk }} KK ({{ number_format(($permohonan->kk_terdampak / max(1, $permohonan->desa->jumlah_kk)) * 100, 1) }}%)</span>
                            </div>
                            <div class="bg-amber-50/70 p-4 rounded-xl border border-amber-200 text-center">
                                <span class="text-xs text-amber-800 block font-medium">Hari Tanpa Air</span>
                                <span class="text-2xl font-bold text-amber-900 mt-1 block">{{ $permohonan->hari_tanpa_air }} Hari</span>
                                <span class="text-[10px] text-amber-600 block mt-0.5">Maks. normalisasi: 7 hari</span>
                            </div>
                            <div class="bg-rose-50/70 p-4 rounded-xl border border-rose-200 text-center">
                                <span class="text-xs text-rose-800 block font-medium">Jarak ke Sumber Air</span>
                                <span class="text-2xl font-bold text-rose-900 mt-1 block">{{ number_format($permohonan->jarak_sumber_air) }} m</span>
                                <span class="text-[10px] text-rose-600 block mt-0.5">Maks. normalisasi: 2.000 meter</span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1.5">Alasan Kedaruratan dari Desa:</span>
                            <div class="p-4 bg-[#F4F0E2]/40 rounded-xl border border-[#A6C8E2]/60 text-xs text-[#1B3B5A] leading-relaxed italic">
                                &ldquo;{{ $permohonan->alasan_permohonan ?? 'Tidak ada catatan tambahan.' }}&rdquo;
                            </div>
                        </div>
                    </div>

                    <!-- 3. Berkas Pendukung (Surat Kades & Foto Lapangan) -->
                    <div class="bg-white rounded-2xl border border-[#A6C8E2] shadow-xs p-5 sm:p-6">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#1B3B5A] border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-paperclip text-[#619DC6]"></i> Berkas Lampiran Pendukung Resmi
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Surat Kades -->
                            <div class="border border-[#A6C8E2] rounded-xl p-4 bg-[#F4F0E2]/40 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center gap-2 text-rose-600 mb-2">
                                        <i class="fa-solid fa-file-pdf text-2xl"></i>
                                        <span class="font-bold text-[#1B3B5A] text-xs">Surat Permohonan Kepala Desa</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500">Dokumen surat keterangan resmi yang telah ditandatangani dan dicap stempel desa.</p>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-200 flex items-center justify-between">
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $permohonan->surat_kades ?? 'surat_resmi.pdf' }}</span>
                                    <span class="px-2.5 py-1 bg-white border border-[#A6C8E2] rounded text-xs font-semibold text-[#1B3B5A] hover:bg-[#F4F0E2] cursor-pointer shadow-xs">
                                        <i class="fa-solid fa-eye mr-1 text-[#619DC6]"></i> Periksa Surat
                                    </span>
                                </div>
                            </div>

                            <!-- Foto Kondisi Lapangan -->
                            <div class="border border-[#A6C8E2] rounded-xl p-4 bg-[#F4F0E2]/40 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center gap-2 text-[#619DC6] mb-2">
                                        <i class="fa-solid fa-image text-2xl"></i>
                                        <span class="font-bold text-[#1B3B5A] text-xs">Foto Kondisi Kekeringan Lapangan</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500">Bukti visual kondisi sumur atau bak warga yang mengalami kekeringan.</p>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-200 flex items-center justify-between">
                                    <span class="text-[10px] text-slate-400">Bukti Lapangan</span>
                                    @if($permohonan->foto_lapangan)
                                        <a href="{{ $permohonan->foto_lapangan }}" target="_blank" class="px-2.5 py-1 bg-[#619DC6] text-white rounded text-xs font-semibold hover:bg-[#4888B5]">
                                            <i class="fa-solid fa-up-right-from-square mr-1"></i> Lihat Foto Penuh
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">Tidak ada foto</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($permohonan->foto_lapangan)
                            <div class="mt-4 rounded-xl overflow-hidden border border-[#A6C8E2] max-h-72 bg-[#1B3B5A]">
                                <img src="{{ $permohonan->foto_lapangan }}" alt="Foto Dokumentasi Lapangan" class="w-full h-72 object-cover hover:opacity-95 transition">
                            </div>
                        @endif
                    </div>

                    <!-- 4. Riwayat Keputusan / Audit Trail Log (PBI-011 & PBI-012) -->
                    <div class="bg-white rounded-2xl border border-[#A6C8E2] shadow-xs p-5 sm:p-6">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[#1B3B5A] flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-[#619DC6]"></i> Riwayat Telaah Verifikasi (Audit Trail Log)
                            </h3>
                            <span class="text-xs bg-[#F4F0E2] text-[#1B3B5A] border border-[#A6C8E2] px-2.5 py-0.5 rounded-full font-bold">
                                {{ $permohonan->keputusanPermohonans->count() }} Catatan
                            </span>
                        </div>

                        @if($permohonan->keputusanPermohonans->count() > 0)
                            <div class="space-y-3">
                                @foreach($permohonan->keputusanPermohonans as $log)
                                    <div class="p-4 bg-[#F4F0E2]/40 rounded-xl border border-[#A6C8E2]/60 relative">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-2">
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-full bg-[#619DC6] text-white flex items-center justify-center font-bold text-xs">
                                                    <i class="fa-solid fa-user-check"></i>
                                                </div>
                                                <span class="text-xs font-bold text-[#1B3B5A]">{{ $log->user->name ?? 'Petugas Verifikator BPBD' }}</span>
                                            </div>
                                            <span class="text-[11px] text-slate-400 flex items-center gap-1">
                                                <i class="fa-regular fa-clock text-[#619DC6]"></i>
                                                {{ $log->created_at->format('d M Y, H:i') }} WIB
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-[11px] text-slate-500 font-medium">Status Keputusan:</span>
                                            @if($log->status_keputusan == 'Disetujui')
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center gap-1">
                                                    <i class="fa-solid fa-check"></i> Disetujui
                                                </span>
                                                @if($log->volume_air_disetujui)
                                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#A6C8E2]/30 text-[#1B3B5A] border border-[#A6C8E2]">
                                                        <i class="fa-solid fa-faucet-drip mr-1 text-[#619DC6]"></i> {{ number_format($log->volume_air_disetujui) }} Liter
                                                    </span>
                                                @endif
                                            @elseif($log->status_keputusan == 'Revisi')
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200 flex items-center gap-1">
                                                    <i class="fa-solid fa-pen-to-square"></i> Revisi Berkas
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200 flex items-center gap-1">
                                                    <i class="fa-solid fa-xmark"></i> Ditolak
                                                </span>
                                            @endif
                                        </div>

                                        <div class="bg-white p-3 rounded-lg border border-[#A6C8E2]/50 text-xs text-[#1B3B5A] italic">
                                            &ldquo;{{ $log->catatan_alasan }}&rdquo;
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 text-slate-400">
                                <i class="fa-regular fa-folder-open text-3xl mb-2 block text-[#A6C8E2]"></i>
                                <p class="text-xs italic">Belum ada riwayat telaah verifikasi yang dicatat untuk permohonan ini.</p>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- RIGHT 1 COL: Kalkulasi Skor AHP-SAW & Form Keputusan -->
                <div class="space-y-6">

                    <!-- Kartu Skor AHP-SAW (KUNCI PBI-010) -->
                    <div class="bg-gradient-to-br from-[#1B3B5A] via-[#132A40] to-[#1E466E] text-white rounded-2xl p-6 shadow-md border border-[#619DC6]/40">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <span class="text-xs uppercase font-bold tracking-wider text-[#A6C8E2] block">Hasil Penilaian SPK</span>
                                <span class="text-[10px] text-cyan-300 font-medium"><i class="fa-solid fa-circle-check mr-1"></i>Lolos Skrining F-02 (&ge; 40.00)</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $permohonan->skor_ahp_saw >= 70 ? 'bg-rose-500 text-white' : ($permohonan->skor_ahp_saw >= 40 ? 'bg-amber-400 text-slate-900' : 'bg-emerald-400 text-slate-900') }}">
                                {{ $permohonan->skor_ahp_saw >= 70 ? 'KRITIS' : ($permohonan->skor_ahp_saw >= 40 ? 'WASPADA' : 'AMAN') }}
                            </span>
                        </div>

                        <div class="text-center py-4 border-y border-white/15 my-2">
                            <span class="text-5xl font-extrabold tracking-tight text-white block">
                                {{ number_format($permohonan->skor_ahp_saw, 2) }}
                            </span>
                            <span class="text-xs text-slate-300 mt-1 block">Skor Prioritas Akhir (0 - 100)</span>
                        </div>

                        <!-- Breakdown 4 Kriteria AHP-SAW Berbobot Baku -->
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex items-center justify-between mb-2">
                                <p class="font-bold text-[#A6C8E2] uppercase tracking-wider text-[11px]">Rincian 4 Kriteria (AHP-SAW):</p>
                                <span class="text-[10px] text-cyan-300 font-mono">SDG 10</span>
                            </div>
                            
                            <!-- C1: 50.47% -->
                            <div class="flex justify-between items-center bg-white/10 backdrop-blur-xs p-2.5 rounded-lg border border-white/15">
                                <div>
                                    <span class="font-bold text-white block">C1: Rasio KK Terdampak</span>
                                    <span class="text-[10px] text-slate-300">Bobot Baku: 50,47%</span>
                                </div>
                                <span class="font-extrabold text-base text-[#F4BAA3]">{{ $permohonan->rincian_kriteria['c1_rasio_kk'] ?? 0 }}%</span>
                            </div>

                            <!-- C3: 30.26% -->
                            <div class="flex justify-between items-center bg-white/10 backdrop-blur-xs p-2.5 rounded-lg border border-white/15">
                                <div>
                                    <span class="font-bold text-white block">C3: Hari Tanpa Air</span>
                                    <span class="text-[10px] text-slate-300">Bobot Baku: 30,26%</span>
                                </div>
                                <span class="font-extrabold text-base text-[#F4BAA3]">{{ $permohonan->rincian_kriteria['c3_hari_tanpa_air'] ?? 0 }}%</span>
                            </div>

                            <!-- C4: 11.00% -->
                            <div class="flex justify-between items-center bg-white/10 backdrop-blur-xs p-2.5 rounded-lg border border-white/15">
                                <div>
                                    <span class="font-bold text-white block">C4: Jarak ke Sumber Air</span>
                                    <span class="text-[10px] text-slate-300">Bobot Baku: 11,00%</span>
                                </div>
                                <span class="font-extrabold text-base text-[#F4BAA3]">{{ $permohonan->rincian_kriteria['c4_jarak_sumber'] ?? 0 }}%</span>
                            </div>

                            <!-- C2: 8.26% -->
                            <div class="flex justify-between items-center bg-white/10 backdrop-blur-xs p-2.5 rounded-lg border border-white/15">
                                <div>
                                    <span class="font-bold text-white block">C2: Proporsi Jiwa Rentan</span>
                                    <span class="text-[10px] text-slate-300">Bobot Baku: 8,26%</span>
                                </div>
                                <span class="font-extrabold text-base text-[#F4BAA3]">{{ $permohonan->rincian_kriteria['c2_proporsi_rentan'] ?? 0 }}%</span>
                            </div>
                        </div>

                        <div class="mt-5 p-3 rounded-xl bg-white/10 border border-white/15 text-xs text-slate-200">
                            <i class="fa-solid fa-lightbulb text-[#F4BAA3] mr-1.5"></i>
                            @if($permohonan->skor_ahp_saw >= 70)
                                <strong>Rekomendasi SPK (70 - 100):</strong> Segera disetujui untuk alokasi pengiriman truk tangki darurat (KRITIS).
                            @else
                                <strong>Rekomendasi SPK (40 - 69.99):</strong> Dijadwalkan untuk pengiriman tahap kedua setelah telaah berkas (WASPADA).
                            @endif
                        </div>
                    </div>

                    <!-- Panel Aksi Verifikasi (PBI-011 & PBI-012) -->
                    <div class="bg-white rounded-2xl border border-[#A6C8E2] shadow-xs p-6">
                        <h3 class="text-sm font-bold text-[#1B3B5A] border-b border-slate-100 pb-3 mb-3">
                            Penetapan Keputusan Admin BPBD
                        </h3>

                        <p class="text-xs text-slate-500 mb-4">
                            Pilih keputusan telaah verifikasi untuk permohonan ini. Keputusan tercatat resmi dalam audit trail sistem.
                        </p>

                        <div class="space-y-2.5">
                            <!-- Tombol Setujui (PBI-011) -->
                            <button 
                                type="button" 
                                onclick="openModal('modal-setujui')" 
                                class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-xs hover:shadow transition flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <i class="fa-solid fa-check"></i>
                                <span>Setujui Permohonan Bantuan</span>
                            </button>

                            <!-- Tombol Minta Revisi (PBI-012) -->
                            <button 
                                type="button" 
                                onclick="openModal('modal-revisi')" 
                                class="w-full py-2.5 px-4 bg-[#F4BAA3] hover:bg-[#e69f83] text-[#1B3B5A] rounded-xl text-xs font-bold shadow-xs hover:shadow transition flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Minta Perbaikan / Revisi Berkas</span>
                            </button>

                            <!-- Tombol Tolak (PBI-011) -->
                            <button 
                                type="button" 
                                onclick="openModal('modal-tolak')" 
                                class="w-full py-2.5 px-4 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white rounded-xl text-xs font-bold shadow-xs hover:shadow transition flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <i class="fa-solid fa-xmark"></i>
                                <span>Tolak Permohonan</span>
                            </button>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 text-[10.5px] text-slate-400">
                            <i class="fa-solid fa-shield-halved text-[#619DC6] mr-1"></i>
                            Keputusan verifikasi BPBD bersifat final dan mengikat antrean distribusi air.
                        </div>
                    </div>

                </div>

            </div>

        </main>

        <!-- Modals Aksi Keputusan -->
        <!-- 1. Modal Setujui Permohonan -->
        <div id="modal-setujui" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-[#A6C8E2]">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-[#1B3B5A] text-base">Persetujuan Permohonan Bantuan Air</h4>
                            <p class="text-xs text-slate-500">Tiket: <span class="font-mono font-semibold">{{ $permohonan->nomor_tiket }}</span> • Desa {{ $permohonan->desa->nama_desa }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeModal('modal-setujui')" class="text-slate-400 hover:text-slate-600 text-lg cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('verifikasi.keputusan', $permohonan->id) }}">
                    @csrf
                    <input type="hidden" name="status_keputusan" value="Disetujui">

                    <div class="space-y-4 text-xs">
                        <div>
                            <label class="block font-bold text-[#1B3B5A] mb-1">
                                Alokasi Volume Air Bersih yang Disetujui (Liter) <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                name="volume_air_disetujui" 
                                value="5000" 
                                min="1000" 
                                step="1000" 
                                required 
                                class="w-full p-2.5 bg-[#F4F0E2]/40 border border-[#A6C8E2] rounded-xl text-xs text-[#1B3B5A] focus:ring-2 focus:ring-[#619DC6] outline-none"
                            >
                            <p class="text-[11px] text-slate-500 mt-1">Standar pasokan: 5.000 Liter per 1 armada tangki resmi.</p>
                        </div>

                        <div>
                            <label class="block font-bold text-[#1B3B5A] mb-1">
                                Catatan Rekomendasi Verifikasi (Opsional)
                            </label>
                            <textarea 
                                name="catatan_alasan" 
                                rows="3" 
                                placeholder="Contoh: Berkas lengkap, skor prioritas tinggi. Segera jadwalkan pengiriman tangki ke titik tandon RT 02." 
                                class="w-full p-2.5 bg-[#F4F0E2]/40 border border-[#A6C8E2] rounded-xl text-xs text-[#1B3B5A] focus:ring-2 focus:ring-[#619DC6] outline-none"
                            >{{ old('catatan_alasan') }}</textarea>
                        </div>

                        <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 text-emerald-900 text-[11px] flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-emerald-600 text-base shrink-0"></i>
                            <span>Status tiket akan menjadi <strong>Disetujui</strong> dan masuk ke antrean Penugasan Armada Tangki.</span>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" onclick="closeModal('modal-setujui')" class="px-4 py-2 border border-slate-300 text-slate-700 hover:bg-slate-100 rounded-xl text-xs font-semibold transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-check"></i> Konfirmasi Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2. Modal Minta Revisi Berkas -->
        <div id="modal-revisi" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-[#A6C8E2]">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-[#F4BAA3]/40 text-[#1B3B5A] flex items-center justify-center text-lg">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-[#1B3B5A] text-base">Minta Perbaikan Berkas (Revisi)</h4>
                            <p class="text-xs text-slate-500">Tiket: <span class="font-mono font-semibold">{{ $permohonan->nomor_tiket }}</span> • Desa {{ $permohonan->desa->nama_desa }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeModal('modal-revisi')" class="text-slate-400 hover:text-slate-600 text-lg cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('verifikasi.keputusan', $permohonan->id) }}">
                    @csrf
                    <input type="hidden" name="status_keputusan" value="Revisi">

                    <div class="space-y-4 text-xs">
                        <div>
                            <label class="block font-bold text-[#1B3B5A] mb-1">
                                Rincian Dokumen / Data yang Perlu Diperbaiki <span class="text-rose-500">* (Wajib diisi)</span>
                            </label>
                            <textarea 
                                name="catatan_alasan" 
                                rows="4" 
                                required 
                                placeholder="Contoh: Surat permohonan Kades belum dibubuhi stempel basah desa. Mohon upload ulang dokumen resmi." 
                                class="w-full p-2.5 bg-[#F4F0E2]/40 border border-[#A6C8E2] rounded-xl text-xs text-[#1B3B5A] focus:ring-2 focus:ring-[#619DC6] outline-none"
                            >{{ old('catatan_alasan') }}</textarea>
                            <p class="text-[11px] text-slate-500 mt-1">Catatan ini akan dikirimkan kepada perwakilan desa sebagai instruksi perbaikan berkas.</p>
                        </div>

                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-100 text-amber-900 text-[11px] flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-amber-600 text-base shrink-0"></i>
                            <span>Status permohonan akan menjadi <strong>Revisi</strong>. Pihak desa dapat memperbarui dokumen permohonan kembali.</span>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" onclick="closeModal('modal-revisi')" class="px-4 py-2 border border-slate-300 text-slate-700 hover:bg-slate-100 rounded-xl text-xs font-semibold transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-[#619DC6] hover:bg-[#4888B5] text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Permintaan Revisi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 3. Modal Tolak Permohonan -->
        <div id="modal-tolak" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-[#A6C8E2]">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-[#1B3B5A] text-base">Tolak Permohonan Bantuan</h4>
                            <p class="text-xs text-slate-500">Tiket: <span class="font-mono font-semibold">{{ $permohonan->nomor_tiket }}</span> • Desa {{ $permohonan->desa->nama_desa }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeModal('modal-tolak')" class="text-slate-400 hover:text-slate-600 text-lg cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('verifikasi.keputusan', $permohonan->id) }}">
                    @csrf
                    <input type="hidden" name="status_keputusan" value="Ditolak">

                    <div class="space-y-4 text-xs">
                        <div>
                            <label class="block font-bold text-[#1B3B5A] mb-1">
                                Alasan Tertulis Penolakan Permohonan <span class="text-rose-500">* (Wajib diisi)</span>
                            </label>
                            <textarea 
                                name="catatan_alasan" 
                                rows="4" 
                                required 
                                placeholder="Contoh: Berdasarkan telaah tim, jarak sumber mata air alternatif masih berada dalam radius 400 meter dan debit air masih mencukupi." 
                                class="w-full p-2.5 bg-[#F4F0E2]/40 border border-[#A6C8E2] rounded-xl text-xs text-[#1B3B5A] focus:ring-2 focus:ring-rose-500 outline-none"
                            >{{ old('catatan_alasan') }}</textarea>
                            <p class="text-[11px] text-slate-500 mt-1">Alasan penolakan wajib objektif dan akan disimpan permanen dalam audit trail log.</p>
                        </div>

                        <div class="p-3 bg-rose-50 rounded-xl border border-rose-100 text-rose-900 text-[11px] flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation text-rose-600 text-base shrink-0"></i>
                            <span>Status permohonan akan diubah menjadi <strong>Ditolak</strong>. Tiket tidak akan masuk antrean pengiriman tangki.</span>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" onclick="closeModal('modal-tolak')" class="px-4 py-2 border border-slate-300 text-slate-700 hover:bg-slate-100 rounded-xl text-xs font-semibold transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-xmark"></i> Konfirmasi Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-auto py-2.5 border-t-2 border-[#619DC6] text-center text-[10.5px] text-slate-400 bg-[#1B3B5A]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-1">
                <p>BPBD Kabupaten Purbalingga &middot; Sistem Informasi Pendukung Keputusan Distribusi Air Bersih Darurat</p>
                <p>&copy; {{ date('Y') }} Lingga Air &middot; Proyek Perangkat Lunak Kelompok 4</p>
            </div>
        </footer>

    </div>

    <!-- JavaScript Interaktif untuk Modal -->
    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        // Tutup modal jika user menekan tombol Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                ['modal-setujui', 'modal-revisi', 'modal-tolak'].forEach(closeModal);
            }
        });

        // Tutup modal jika user mengklik area gelap di luar modal
        ['modal-setujui', 'modal-revisi', 'modal-tolak'].forEach(function(modalId) {
            const el = document.getElementById(modalId);
            if (el) {
                el.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeModal(modalId);
                    }
                });
            }
        });
    </script>
</body>
</html>
