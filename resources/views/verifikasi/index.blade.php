<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrean Verifikasi Pengajuan - LinggaAir | BPBD Kabupaten Purbalingga</title>
    
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
                                dark: '#003B5C',
                                tint: '#EBF5FB',
                                stroke: '#BAE3F5',
                            },
                            neutral: {
                                25: '#FCFDFD',
                                50: '#F8FAFC',
                                100: '#F2F4F7',
                                200: '#E4E7EC',
                                300: '#D0D5DD',
                                500: '#667085',
                                600: '#475467',
                                700: '#344054',
                                900: '#101828',
                            },
                            status: {
                                error: {
                                    text: '#B42318',
                                    bg: '#FEF3F2',
                                    border: '#FECDCA',
                                },
                                success: {
                                    text: '#027A48',
                                    bg: '#ECFDF3',
                                    border: '#A6F4C5',
                                },
                                warning: {
                                    text: '#B54708',
                                    bg: '#FFFAEB',
                                    border: '#FEDF89',
                                },
                            }
                        }
                    },
                    fontFamily: {
                        sans: ['Poppins', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        'xs': '0 1px 2px 0 rgba(16, 24, 40, 0.05)',
                    }
                }
            }
        }
    </script>
    
    <style>
        *, html, body, input, button, select, textarea { 
            font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; 
        }
        body {
            background-color: #F8FAFC;
            color: #101828;
            font-size: 0.75rem; /* 12px compact institutional base */
        }
        :focus-visible { 
            outline: 2px solid #00629B; 
            outline-offset: 2px; 
        }
        button, a[href] { 
            cursor: pointer; 
        }
    </style>
    @include('layouts.sidebar-styles')
</head>
<body class="bg-[#F8FAFC] text-[#101828] min-h-screen antialiased font-sans role-sidebar-shell">

    {{-- SIDEBAR ADMIN TERPADU --}}
    @include('layouts.sidebar')

    <!-- ==================== MAIN CONTENT AREA ==================== -->
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden role-admin-main">

        <!-- Top Header Bar (Ramping & Proporsional) -->
        <header class="bg-white/95 backdrop-blur-md border-b border-[#EAECF0] px-4 sm:px-5 py-2.5 sticky top-0 z-10 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h1 class="text-[13px] sm:text-sm font-bold text-[#101828] tracking-tight">Antrean Verifikasi Permohonan Air Bersih</h1>
                <p class="text-[10px] text-[#475467] mt-0.5">Penetapan prioritas bantuan air bersih darurat Kabupaten Purbalingga (Metode AHP-SAW)</p>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[10px] font-semibold bg-[#EBF5FB] text-[#00629B] border border-[#BAE3F5]">
                    <i class="fa-regular fa-calendar text-[#00629B]"></i>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                </span>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="flex-1 p-3 sm:p-4 max-w-7xl mx-auto w-full space-y-3">

            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="p-3 rounded-lg bg-[#ECFDF3] border border-[#A6F4C5] text-[#027A48] flex items-start gap-2 shadow-xs animate-fade-in text-[11px]">
                    <i class="fa-solid fa-circle-check text-sm mt-0.5 shrink-0"></i>
                    <div class="flex-1">
                        <h4 class="font-bold text-[11px]">Aksi Berhasil!</h4>
                        <p class="text-[10px] text-[#027A48] mt-0.5 leading-relaxed">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Metric Summary Cards (4 Kartu Indikator Utama yang Ramping & Ringkas) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
                
                <!-- 1. Total Permohonan -->
                <div class="bg-white p-3 rounded-xl border border-[#E4E7EC] shadow-xs flex items-center justify-between transition-all hover:shadow-sm">
                    <div>
                        <p class="text-[9.5px] font-bold text-[#667085] uppercase tracking-wider">Total Lolos Skrining</p>
                        <p class="text-base sm:text-lg font-bold text-[#101828] mt-0.5">{{ number_format($ringkasan['total_pengajuan']) }}</p>
                        <p class="text-[9.5px] text-[#667085] mt-0.5">Permohonan terdaftar</p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-[#EBF5FB] text-[#00629B] flex items-center justify-center text-sm shrink-0">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                </div>

                <!-- 2. Menunggu Verifikasi -->
                <div class="bg-white p-3 rounded-xl border border-[#FEDF89] bg-gradient-to-br from-white to-[#FFFAEB]/30 shadow-xs flex items-center justify-between transition-all hover:shadow-sm">
                    <div>
                        <p class="text-[9.5px] font-bold text-[#B54708] uppercase tracking-wider">Menunggu Verifikasi</p>
                        <p class="text-base sm:text-lg font-bold text-[#B54708] mt-0.5">{{ number_format($ringkasan['menunggu_verifikasi']) }}</p>
                        <p class="text-[9.5px] text-[#475467] mt-0.5">Memerlukan telaah BPBD</p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-[#FFFAEB] text-[#B54708] border border-[#FEDF89] flex items-center justify-center text-sm shrink-0">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>

                <!-- 3. Disetujui -->
                <div class="bg-white p-3 rounded-xl border border-[#A6F4C5] bg-gradient-to-br from-white to-[#ECFDF3]/30 shadow-xs flex items-center justify-between transition-all hover:shadow-sm">
                    <div>
                        <p class="text-[9.5px] font-bold text-[#027A48] uppercase tracking-wider">Disetujui</p>
                        <p class="text-base sm:text-lg font-bold text-[#027A48] mt-0.5">{{ number_format($ringkasan['disetujui']) }}</p>
                        <p class="text-[9.5px] text-[#027A48]/80 mt-0.5">Siap alokasi armada</p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-[#ECFDF3] text-[#027A48] border border-[#A6F4C5] flex items-center justify-center text-sm shrink-0">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>

                <!-- 4. Perlu Tindak Lanjut -->
                <div class="bg-white p-3 rounded-xl border border-[#FECDCA] bg-gradient-to-br from-white to-[#FEF3F2]/30 shadow-xs flex items-center justify-between transition-all hover:shadow-sm">
                    <div>
                        <p class="text-[9.5px] font-bold text-[#B42318] uppercase tracking-wider">Perlu Tindak Lanjut</p>
                        <p class="text-base sm:text-lg font-bold text-[#B42318] mt-0.5">{{ number_format($ringkasan['perlu_tindak_lanjut']) }}</p>
                        <p class="text-[9.5px] text-[#B42318]/80 mt-0.5">Revisi / Ditolak</p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-[#FEF3F2] text-[#B42318] border border-[#FECDCA] flex items-center justify-center text-sm shrink-0">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>

            </div>

            <!-- Filter & Search Toolbar (Ukuran Ringkas & Presisi) -->
            <div class="bg-white p-3 rounded-xl border border-[#E4E7EC] shadow-xs">
                <form method="GET" action="{{ route('verifikasi.index') }}" class="flex flex-col md:flex-row items-center gap-2">
                    <div class="relative flex-1 w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 pointer-events-none text-[#667085]">
                            <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                        </span>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Cari nama desa, kecamatan, atau nomor tiket..." 
                            class="w-full h-8 pl-7 pr-3 bg-white border border-[#D0D5DD] rounded-lg text-[11px] text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B] transition"
                        >
                    </div>

                    <div class="flex items-center gap-1.5 w-full md:w-auto">
                        <select 
                            name="status" 
                            class="w-full md:w-auto h-8 px-2.5 bg-white border border-[#D0D5DD] rounded-lg text-[11px] text-[#344054] focus:outline-none focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B] transition cursor-pointer"
                        >
                            <option value="">Semua Status Tindakan</option>
                            <option value="Menunggu Verifikasi" {{ request('status') == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="Revisi" {{ request('status') == 'Revisi' ? 'selected' : '' }}>Revisi</option>
                            <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>

                        <button 
                            type="submit" 
                            class="h-8 px-3 bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] active:scale-[0.99] text-white rounded-lg text-[11px] font-semibold transition shrink-0 shadow-xs cursor-pointer flex items-center gap-1.5"
                        >
                            <i class="fa-solid fa-filter text-[9px]"></i>
                            <span>Filter</span>
                        </button>

                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('verifikasi.index') }}" class="h-8 px-2.5 text-[#667085] hover:text-[#101828] text-[11px] font-medium flex items-center gap-1 transition">
                                <i class="fa-solid fa-rotate-left text-[9px]"></i>
                                <span>Reset</span>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Banner Informasi Prioritas & Bobot Penilaian (Metode AHP-SAW) -->
            <div class="bg-white rounded-xl border border-[#E4E7EC] p-3 shadow-xs">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-2.5">
                    
                    <!-- Kiri: Kategori Status Kelayakan -->
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-[#101828] uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-scale-balanced text-[#00629B] text-[11px]"></i>
                            Kategori Prioritas:
                        </span>
                        <div class="flex flex-wrap items-center gap-1.5 text-[10px]">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-[#FEF3F2] text-[#B42318] border border-[#FECDCA] font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#B42318] animate-pulse"></span>
                                Prioritas 1: Kritis (&ge; 60.00)
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-[#FFFAEB] text-[#B54708] border border-[#FEDF89] font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#B54708]"></span>
                                Prioritas 2: Waspada (30.00 - 59.99)
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-[#ECFDF3] text-[#027A48] border border-[#A6F4C5] font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#027A48]"></span>
                                Prioritas 3: Aman (&lt; 30.00)
                            </span>
                        </div>
                    </div>

                    <!-- Kanan: Bobot Penilaian (AHP-SAW) -->
                    <div class="lg:border-l lg:border-[#EAECF0] lg:pl-4 space-y-1">
                        <span class="text-[10px] font-bold text-[#101828] uppercase tracking-wider block">
                            Bobot Kriteria Penilaian (AHP-SAW):
                        </span>
                        <div class="flex flex-wrap items-center gap-1 text-[10px]">
                            <span class="px-2 py-0.5 rounded-md bg-[#F8FAFC] text-[#344054] border border-[#E4E7EC] font-medium">
                                KK: <strong class="font-bold text-[#101828]">50,47%</strong>
                            </span>
                            <span class="px-2 py-0.5 rounded-md bg-[#F8FAFC] text-[#344054] border border-[#E4E7EC] font-medium">
                                Hari Tanpa Air: <strong class="font-bold text-[#101828]">30,26%</strong>
                            </span>
                            <span class="px-2 py-0.5 rounded-md bg-[#F8FAFC] text-[#344054] border border-[#E4E7EC] font-medium">
                                Jarak: <strong class="font-bold text-[#101828]">11,00%</strong>
                            </span>
                            <span class="px-2 py-0.5 rounded-md bg-[#F8FAFC] text-[#344054] border border-[#E4E7EC] font-medium">
                                Rentan: <strong class="font-bold text-[#101828]">8,26%</strong>
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Ranked Queue Table (PBI-010: Antrean Berdasarkan Skor Prioritas AHP-SAW) -->
            <div class="bg-white rounded-xl border border-[#E4E7EC] shadow-xs overflow-hidden">
                <div class="p-3 border-b border-[#EAECF0] bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="font-bold text-[#101828] text-xs">Daftar Antrean Permohonan Bantuan Air Bersih</h2>
                        <p class="text-[10px] text-[#667085] mt-0.5">Otomatis terurut berdasarkan skor prioritas AHP-SAW tertinggi (Prioritas #1 didahulukan)</p>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[10px] text-[#00629B] bg-[#EBF5FB] border border-[#BAE3F5] px-2 py-0.5 rounded-md font-bold self-start sm:self-auto">
                        <i class="fa-solid fa-list-ol text-[9px]"></i>
                        <span>Menampilkan <strong>{{ $antrean->count() }}</strong> permohonan</span>
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-[10.5px] text-[#101828]">
                        <thead class="bg-[#F8FAFC] border-b border-[#EAECF0] text-[#475467] text-[9.5px] uppercase font-bold tracking-wider">
                            <tr>
                                <th class="px-2.5 py-2 text-center w-12">No</th>
                                <th class="px-2.5 py-2">Nomor Tiket & Waktu</th>
                                <th class="px-2.5 py-2">Desa & Kecamatan</th>
                                <th class="px-2.5 py-2 text-center">Parameter Lapangan</th>
                                <th class="px-2.5 py-2 text-center">Skor AHP-SAW</th>
                                <th class="px-2.5 py-2 text-center">Status Kelayakan</th>
                                <th class="px-2.5 py-2 text-center">Status Tiket</th>
                                <th class="px-2.5 py-2 text-center w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#EAECF0] text-[10px]">
                            @forelse($antrean as $index => $item)
                                <tr class="hover:bg-[#F8FAFC] transition duration-150 {{ $index === 0 ? 'bg-[#EBF5FB]/30 hover:bg-[#EBF5FB]/50' : '' }}">
                                    
                                    <!-- Nomor Urut -->
                                    <td class="px-2.5 py-2 text-center font-medium text-[#475467] text-[10.5px]">
                                        {{ $index + 1 }}
                                    </td>

                                    <!-- Nomor Tiket & Waktu -->
                                    <td class="px-2.5 py-2">
                                        <span class="font-bold text-[#101828] block font-mono text-[10.5px]">{{ $item->nomor_tiket }}</span>
                                        <span class="text-[9px] text-[#667085] block mt-0.5 flex items-center gap-1">
                                            <i class="fa-regular fa-clock text-[#00629B]"></i>
                                            <span>{{ $item->created_at->format('d M Y, H:i') }}</span>
                                        </span>
                                    </td>

                                    <!-- Desa & Kecamatan -->
                                    <td class="px-2.5 py-2">
                                        <span class="font-bold text-[#101828] block text-[10.5px]">Desa {{ $item->desa->nama_desa ?? '-' }}</span>
                                        <span class="text-[9.5px] text-[#475467] block">Kec. {{ $item->desa->kecamatan ?? '-' }}</span>
                                    </td>

                                    <!-- Parameter Lapangan -->
                                    <td class="px-2.5 py-2 text-center">
                                        <div class="inline-flex items-center gap-1.5 text-[9.5px] bg-[#F8FAFC] px-1.5 py-0.5 rounded border border-[#E4E7EC]">
                                            <span title="Kepala Keluarga Terdampak"><i class="fa-solid fa-users text-[#00629B] mr-0.5"></i><strong>{{ $item->kk_terdampak }}</strong> KK</span>
                                            <span class="text-[#D0D5DD]">|</span>
                                            <span title="Hari Tanpa Pasokan Air"><i class="fa-solid fa-sun text-amber-500 mr-0.5"></i><strong>{{ $item->hari_tanpa_air }}</strong> hari</span>
                                        </div>
                                    </td>

                                    <!-- Skor AHP-SAW (PBI-010) -->
                                    <td class="px-2.5 py-2 text-center">
                                        <div class="inline-block">
                                            <span class="text-[11px] font-extrabold {{ $item->skor_ahp_saw >= 60 ? 'text-[#B42318]' : ($item->skor_ahp_saw >= 30 ? 'text-[#B54708]' : 'text-[#027A48]') }}">
                                                {{ number_format($item->skor_ahp_saw, 2) }}
                                            </span>
                                            <span class="text-[8.5px] text-[#667085] block leading-none">/ 100</span>
                                        </div>
                                    </td>

                                    <!-- Kategori Kelayakan -->
                                    <td class="px-2.5 py-2 text-center">
                                        @if($item->skor_ahp_saw >= 60)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-[#FEF3F2] text-[#B42318] border border-[#FECDCA]">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#B42318] mr-1 animate-pulse"></span>
                                                Kritis
                                            </span>
                                        @elseif($item->skor_ahp_saw >= 30)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-[#FFFAEB] text-[#B54708] border border-[#FEDF89]">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#B54708] mr-1"></span>
                                                Waspada
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-[#ECFDF3] text-[#027A48] border border-[#A6F4C5]">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#027A48] mr-1"></span>
                                                Aman
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Status Verifikasi -->
                                    <td class="px-2.5 py-2 text-center">
                                        @if($item->status == 'Menunggu Verifikasi')
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-semibold bg-[#FFFAEB] text-[#B54708] border border-[#FEDF89]">
                                                Menunggu
                                            </span>
                                        @elseif($item->status == 'Disetujui')
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-semibold bg-[#ECFDF3] text-[#027A48] border border-[#A6F4C5]">
                                                Disetujui
                                            </span>
                                        @elseif($item->status == 'Revisi')
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-semibold bg-[#FFFAEB] text-[#B54708] border border-[#FEDF89]">
                                                Revisi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-semibold bg-[#FEF3F2] text-[#B42318] border border-[#FECDCA]">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Tombol Aksi Detail -->
                                    <td class="px-2.5 py-2 text-center">
                                        <a 
                                            href="{{ route('verifikasi.show', $item->id) }}" 
                                            class="inline-flex items-center justify-center gap-1 px-2 py-0.5 bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] active:scale-[0.99] text-white rounded text-[9.5px] font-semibold transition shadow-xs"
                                            title="Telaah dokumen permohonan Desa {{ $item->desa->nama_desa ?? '' }}"
                                        >
                                            <span>Detail</span>
                                            <i class="fa-solid fa-arrow-right text-[8px]"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-10 text-[#667085]">
                                        <div class="w-8 h-8 rounded-lg bg-[#EBF5FB] text-[#00629B] flex items-center justify-center text-sm mx-auto mb-1.5">
                                            <i class="fa-solid fa-inbox"></i>
                                        </div>
                                        <p class="font-bold text-[11px] text-[#101828]">Tidak Ada Permohonan Dalam Antrean</p>
                                        <p class="text-[10px] text-[#667085] mt-0.5">Seluruh pengajuan bantuan air bersih telah selesai ditelaah oleh tim BPBD.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>

        <!-- Footer Institusional Bersih -->
        <footer class="mt-auto py-2.5 border-t border-[#EAECF0] bg-white text-[10px] text-[#667085]">
            <div class="max-w-7xl mx-auto px-4 sm:px-5 flex flex-col sm:flex-row items-center justify-between gap-1 text-center sm:text-left">
                <p>Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Purbalingga &middot; SPK Distribusi Air Bersih Darurat</p>
                <p>&copy; {{ date('Y') }} LinggaAir &middot; INA Digital Design System Standard</p>
            </div>
        </footer>

    </div>

</body>
</html>
