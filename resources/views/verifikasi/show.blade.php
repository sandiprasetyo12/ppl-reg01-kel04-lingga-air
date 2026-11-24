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

    <!-- ==================== MAIN CONTENT AREA ==================== -->
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden role-admin-main">

        <!-- Top Header Bar (Dengan Button Back Ke Dashboard & Ke Antrean) -->
        <header class="bg-white/95 backdrop-blur-md border-b border-[#EAECF0] px-4 sm:px-6 py-3.5 sticky top-0 z-10 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <nav class="text-xs text-[#667085] mb-0.5 flex items-center gap-1.5 font-medium">
                    <a href="{{ route('verifikasi.index') }}" class="hover:underline flex items-center gap-1.5 text-[#00629B] font-bold">
                        <svg class="w-3.5 h-3.5 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                        </svg>
                        <span>Antrean Verifikasi</span>
                    </a>
                    <span class="text-[#D0D5DD]">/</span>
                    <span class="text-[#101828] font-semibold font-mono">{{ $permohonan->nomor_tiket }}</span>
                </nav>
                <h1 class="text-lg sm:text-xl font-extrabold text-[#101828] tracking-tight">Telaah Berkas Permohonan: Desa {{ $permohonan->desa->nama_desa ?? '-' }}</h1>
            </div>

            <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                <!-- Tombol Kembali ke Antrean -->
                <a 
                    href="{{ route('verifikasi.index') }}" 
                    class="min-h-[38px] px-3.5 py-1.5 bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] active:scale-[0.99] text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs"
                >
                    <i class="fa-solid fa-list-check text-xs"></i>
                    <span>Daftar Antrean</span>
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
                        <h4 class="font-bold text-sm">Aksi Verifikasi Berhasil!</h4>
                        <p class="text-xs text-[#027A48] mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-xl bg-[#FEF3F2] border border-[#FECDCA] text-[#B42318] flex items-start gap-3 shadow-xs">
                    <i class="fa-solid fa-triangle-exclamation text-[#B42318] text-xl mt-0.5"></i>
                    <div class="flex-1">
                        <h4 class="font-bold text-sm">Terdapat Kesalahan Input:</h4>
                        <ul class="list-disc list-inside text-xs text-[#B42318] mt-1 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Banner Header Tiket & Status -->
            <div class="bg-white rounded-2xl border border-[#E4E7EC] p-5 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h2 class="text-xl sm:text-2xl font-extrabold text-[#101828]">Desa {{ $permohonan->desa->nama_desa }}</h2>
                        @if($permohonan->status == 'Menunggu Verifikasi')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#FFFAEB] text-[#B54708] border border-[#FEDF89]">
                                Menunggu Verifikasi
                            </span>
                        @elseif($permohonan->status == 'Disetujui')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#ECFDF3] text-[#027A48] border border-[#A6F4C5] flex items-center gap-1.5">
                                <i class="fa-solid fa-check"></i> Disetujui
                            </span>
                        @elseif($permohonan->status == 'Revisi')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#FFFAEB] text-[#B54708] border border-[#FEDF89] flex items-center gap-1.5">
                                <i class="fa-solid fa-pen-to-square"></i> Perlu Revisi
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#FEF3F2] text-[#B42318] border border-[#FECDCA] flex items-center gap-1.5">
                                <i class="fa-solid fa-xmark"></i> Ditolak
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-[#667085] mt-1.5">
                        Nomor Tiket: <strong class="font-mono text-[#101828]">{{ $permohonan->nomor_tiket }}</strong>
                        &bull; Diajukan pada: <strong class="text-[#101828]">{{ $permohonan->created_at->format('d F Y, H:i') }} WIB</strong>
                        &bull; Kecamatan: <strong class="text-[#101828]">{{ $permohonan->desa->kecamatan }}</strong>
                    </p>
                </div>
                
                <div class="flex items-center gap-2">
                    <a href="{{ route('verifikasi.index') }}" class="px-3.5 py-2 bg-white border border-[#D0D5DD] rounded-xl text-xs font-bold text-[#344054] hover:bg-[#F2F4F7] transition shadow-xs flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                        </svg>
                        <span>Kembali ke Antrean</span>
                    </a>
                </div>
            </div>

            <!-- 2 Column Layout (PBI-010, PBI-011, PBI-012) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- LEFT 2 COLS: Data Permohonan & Berkas & Audit Trail -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- 1. Data Acuan Desa (Sinkron F-01 & F-02) -->
                    <div class="bg-white rounded-2xl border border-[#E4E7EC] shadow-xs p-5 sm:p-6">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#101828] border-b border-[#EAECF0] pb-3 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-[#00629B]"></i> Data Acuan Desa & Kontak Penanggung Jawab
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs mb-4">
                            <div>
                                <span class="text-[11px] text-[#667085] block">Nama Desa / Kelurahan</span>
                                <span class="font-bold text-[#101828] text-sm">Desa {{ $permohonan->desa->nama_desa }}</span>
                            </div>
                            <div>
                                <span class="text-[11px] text-[#667085] block">Kecamatan</span>
                                <span class="font-bold text-[#101828] text-sm">Kec. {{ $permohonan->desa->kecamatan }}</span>
                            </div>
                            <div>
                                <span class="text-[11px] text-[#667085] block">Penanggung Jawab Permohonan</span>
                                <span class="font-semibold text-[#101828]">{{ $permohonan->desa->penanggung_jawab ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-[11px] text-[#667085] block">Nomor Kontak / WhatsApp</span>
                                <span class="font-semibold text-[#101828] flex items-center gap-1.5">
                                    <i class="fa-brands fa-whatsapp text-emerald-500"></i>
                                    {{ $permohonan->desa->kontak_wa ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <!-- Rincian Data Kependudukan Acuan Desa -->
                        <div class="bg-[#F8FAFC] p-4 rounded-xl border border-[#E4E7EC] mb-4">
                            <span class="text-[11px] font-bold text-[#101828] uppercase tracking-wider block mb-3">
                                <i class="fa-solid fa-users-viewfinder text-[#00629B] mr-1.5"></i> Profil Demografi Desa (Data Acuan):
                            </span>
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5 text-center text-xs">
                                <div class="bg-white p-2.5 rounded-lg border border-[#E4E7EC]">
                                    <span class="text-[#667085] block text-[10px]">KK Total</span>
                                    <span class="font-bold text-[#101828] text-sm mt-0.5 block">{{ number_format($permohonan->desa->jumlah_kk) }}</span>
                                </div>
                                <div class="bg-white p-2.5 rounded-lg border border-[#E4E7EC]">
                                    <span class="text-[#667085] block text-[10px]">Jiwa Total</span>
                                    <span class="font-bold text-[#101828] text-sm mt-0.5 block">{{ number_format($permohonan->desa->jumlah_jiwa) }}</span>
                                </div>
                                <div class="bg-white p-2.5 rounded-lg border border-[#E4E7EC]">
                                    <span class="text-[#667085] block text-[10px]">Balita</span>
                                    <span class="font-bold text-[#00629B] text-sm mt-0.5 block">{{ $permohonan->desa->jumlah_balita }}</span>
                                </div>
                                <div class="bg-white p-2.5 rounded-lg border border-[#E4E7EC]">
                                    <span class="text-[#667085] block text-[10px]">Lansia</span>
                                    <span class="font-bold text-purple-600 text-sm mt-0.5 block">{{ $permohonan->desa->jumlah_lansia }}</span>
                                </div>
                                <div class="bg-white p-2.5 rounded-lg border border-[#E4E7EC]">
                                    <span class="text-[#667085] block text-[10px]">Disabilitas</span>
                                    <span class="font-bold text-amber-600 text-sm mt-0.5 block">{{ $permohonan->desa->jumlah_disabilitas }}</span>
                                </div>
                            </div>
                            <div class="text-[10.5px] text-[#667085] mt-2 text-right">
                                Total Kelompok Rentan: <strong class="text-[#101828]">{{ $permohonan->desa->total_jiwa_rentan }} jiwa</strong> ({{ number_format(($permohonan->desa->total_jiwa_rentan / max(1, $permohonan->desa->jumlah_jiwa)) * 100, 1) }}% dari total jiwa)
                            </div>
                        </div>

                        <div class="bg-[#EBF5FB] p-3.5 rounded-xl border border-[#BAE3F5] text-xs">
                            <span class="text-[#475467] font-medium block">Titik Drop-Off Air Bersih Desa:</span>
                            <span class="font-bold text-[#00629B] mt-0.5 block flex items-center gap-2">
                                <i class="fa-solid fa-truck-droplet text-[#00629B]"></i>
                                {{ $permohonan->desa->titik_drop_air ?? 'Belum ditentukan oleh desa' }}
                            </span>
                        </div>
                    </div>

                    <!-- 2. Kondisi Lapangan (Parameter Formulir Fitur 2) -->
                    <div class="bg-white rounded-2xl border border-[#E4E7EC] shadow-xs p-5 sm:p-6">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#101828] border-b border-[#EAECF0] pb-3 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-temperature-arrow-up text-amber-500"></i> Kondisi Lapangan & Parameter Kekeringan
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                            <div class="bg-[#EBF5FB] p-4 rounded-xl border border-[#BAE3F5] text-center">
                                <span class="text-xs text-[#00629B] block font-medium">KK Terdampak</span>
                                <span class="text-2xl font-bold text-[#00629B] mt-1 block">{{ $permohonan->kk_terdampak }}</span>
                                <span class="text-[10px] text-[#475467] block mt-0.5">dari total {{ $permohonan->desa->jumlah_kk }} KK ({{ number_format(($permohonan->kk_terdampak / max(1, $permohonan->desa->jumlah_kk)) * 100, 1) }}%)</span>
                            </div>
                            <div class="bg-[#FFFAEB] p-4 rounded-xl border border-[#FEDF89] text-center">
                                <span class="text-xs text-[#B54708] block font-medium">Hari Tanpa Air</span>
                                <span class="text-2xl font-bold text-[#B54708] mt-1 block">{{ $permohonan->hari_tanpa_air }} Hari</span>
                                <span class="text-[10px] text-[#B54708]/80 block mt-0.5">Maks. normalisasi: 7 hari</span>
                            </div>
                            <div class="bg-[#FEF3F2] p-4 rounded-xl border border-[#FECDCA] text-center">
                                <span class="text-xs text-[#B42318] block font-medium">Jarak ke Sumber Air</span>
                                <span class="text-2xl font-bold text-[#B42318] mt-1 block">{{ number_format($permohonan->jarak_sumber_air) }} m</span>
                                <span class="text-[10px] text-[#B42318]/80 block mt-0.5">Maks. normalisasi: 2.000 meter</span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <span class="text-xs font-semibold text-[#667085] uppercase tracking-wider block mb-1.5">Alasan Kedaruratan dari Desa:</span>
                            <div class="p-4 bg-[#F8FAFC] rounded-xl border border-[#E4E7EC] text-xs text-[#344054] leading-relaxed italic">
                                &ldquo;{{ $permohonan->alasan_permohonan ?? 'Tidak ada catatan tambahan.' }}&rdquo;
                            </div>
                        </div>
                    </div>

                    <!-- 3. Berkas Pendukung (Surat Kades & Foto Lapangan) -->
                    <div class="bg-white rounded-2xl border border-[#E4E7EC] shadow-xs p-5 sm:p-6">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#101828] border-b border-[#EAECF0] pb-3 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-paperclip text-[#00629B]"></i> Berkas Lampiran Pendukung Resmi
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Surat Kades -->
                            <div class="border border-[#E4E7EC] rounded-xl p-4 bg-[#F8FAFC] flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center gap-2 text-rose-600 mb-2">
                                        <i class="fa-solid fa-file-pdf text-2xl"></i>
                                        <span class="font-bold text-[#101828] text-xs">Surat Permohonan Kepala Desa</span>
                                    </div>
                                    <p class="text-[11px] text-[#667085]">Dokumen surat keterangan resmi yang telah ditandatangani dan dicap stempel desa.</p>
                                </div>
                                @php
                                    $suratPath = $permohonan->surat_kades;
                                    $suratIsUrl = $suratPath && \Illuminate\Support\Str::startsWith($suratPath, ['http://', 'https://']);
                                    $suratExists = $suratPath && !$suratIsUrl && \Illuminate\Support\Facades\Storage::disk('public')->exists($suratPath);
                                    $suratUrl = $suratIsUrl ? $suratPath : ($suratExists ? \Illuminate\Support\Facades\Storage::url($suratPath) : null);
                                @endphp
                                <div class="mt-4 pt-3 border-t border-[#EAECF0] flex items-center justify-between">
                                    <span class="text-[10px] text-[#667085] font-mono truncate max-w-[150px]">{{ $suratPath ? basename($suratPath) : 'Belum diunggah' }}</span>
                                    @if($suratUrl)
                                        <a href="{{ $suratUrl }}" target="_blank" class="px-2.5 py-1 bg-white border border-[#D0D5DD] rounded text-xs font-semibold text-[#344054] hover:bg-[#F2F4F7] shadow-xs">
                                            <i class="fa-solid fa-eye mr-1 text-[#00629B]"></i> Periksa Surat
                                        </a>
                                    @else
                                        <span class="text-xs text-[#667085]">Belum diunggah</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Foto Kondisi Lapangan -->
                            <div class="border border-[#E4E7EC] rounded-xl p-4 bg-[#F8FAFC] flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center gap-2 text-[#00629B] mb-2">
                                        <i class="fa-solid fa-image text-2xl"></i>
                                        <span class="font-bold text-[#101828] text-xs">Foto Kondisi Kekeringan Lapangan</span>
                                    </div>
                                    <p class="text-[11px] text-[#667085]">Bukti visual kondisi sumur atau bak warga yang mengalami kekeringan.</p>
                                </div>
                                <div class="mt-4 pt-3 border-t border-[#EAECF0] flex items-center justify-between">
                                    <span class="text-[10px] text-[#667085]">Bukti Lapangan</span>
                                    @if($permohonan->foto_lapangan)
                                        <a href="{{ $permohonan->foto_lapangan }}" target="_blank" class="px-2.5 py-1 bg-[#00629B] text-white rounded text-xs font-semibold hover:bg-[#004F7D]">
                                            <i class="fa-solid fa-up-right-from-square mr-1"></i> Lihat Foto Penuh
                                        </a>
                                    @else
                                        <span class="text-xs text-[#667085]">Tidak ada foto</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($permohonan->foto_lapangan)
                            <div class="mt-4 rounded-xl overflow-hidden border border-[#E4E7EC] max-h-72 bg-[#101828]">
                                <img src="{{ $permohonan->foto_lapangan }}" alt="Foto Dokumentasi Lapangan" class="w-full h-72 object-cover hover:opacity-95 transition">
                            </div>
                        @endif
                    </div>

                    <!-- 4. Riwayat Keputusan / Audit Trail Log (PBI-011 & PBI-012) -->
                    <div class="bg-white rounded-2xl border border-[#E4E7EC] shadow-xs p-5 sm:p-6">
                        <div class="flex items-center justify-between border-b border-[#EAECF0] pb-3 mb-4">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[#101828] flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-[#00629B]"></i> Riwayat Telaah Verifikasi (Audit Trail Log)
                            </h3>
                            <span class="text-xs bg-[#EBF5FB] text-[#00629B] border border-[#BAE3F5] px-2.5 py-0.5 rounded-full font-bold">
                                {{ $permohonan->keputusanPermohonans->count() }} Catatan
                            </span>
                        </div>

                        @if($permohonan->keputusanPermohonans->count() > 0)
                            <div class="space-y-3">
                                @foreach($permohonan->keputusanPermohonans as $log)
                                    <div class="p-4 bg-[#F8FAFC] rounded-xl border border-[#E4E7EC] relative">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-2">
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-full bg-[#00629B] text-white flex items-center justify-center font-bold text-xs">
                                                    <i class="fa-solid fa-user-check"></i>
                                                </div>
                                                <span class="text-xs font-bold text-[#101828]">{{ $log->user->name ?? 'Petugas Verifikator BPBD' }}</span>
                                            </div>
                                            <span class="text-[11px] text-[#667085] flex items-center gap-1">
                                                <i class="fa-regular fa-clock text-[#00629B]"></i>
                                                {{ $log->created_at->format('d M Y, H:i') }} WIB
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-[11px] text-[#475467] font-medium">Status Keputusan:</span>
                                            @if($log->status_keputusan == 'Disetujui')
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#ECFDF3] text-[#027A48] border border-[#A6F4C5] flex items-center gap-1">
                                                    <i class="fa-solid fa-check"></i> Disetujui
                                                </span>
                                                @if($log->volume_air_disetujui)
                                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#EBF5FB] text-[#00629B] border border-[#BAE3F5]">
                                                        <i class="fa-solid fa-faucet-drip mr-1 text-[#00629B]"></i> {{ number_format($log->volume_air_disetujui) }} Liter
                                                    </span>
                                                @endif
                                            @elseif($log->status_keputusan == 'Revisi')
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#FFFAEB] text-[#B54708] border border-[#FEDF89] flex items-center gap-1">
                                                    <i class="fa-solid fa-pen-to-square"></i> Revisi Berkas
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#FEF3F2] text-[#B42318] border border-[#FECDCA] flex items-center gap-1">
                                                    <i class="fa-solid fa-xmark"></i> Ditolak
                                                </span>
                                            @endif
                                        </div>

                                        <div class="bg-white p-3 rounded-lg border border-[#E4E7EC] text-xs text-[#344054] italic">
                                            &ldquo;{{ $log->catatan_alasan }}&rdquo;
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 text-[#667085]">
                                <i class="fa-regular fa-folder-open text-3xl mb-2 block text-[#BAE3F5]"></i>
                                <p class="text-xs italic">Belum ada riwayat telaah verifikasi yang dicatat untuk permohonan ini.</p>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- RIGHT 1 COL: Kalkulasi Skor AHP-SAW & Form Keputusan -->
                <div class="space-y-6">

                    <!-- Kartu Skor AHP-SAW (KUNCI PBI-010) -->
                    <div class="bg-gradient-to-br from-[#101828] via-[#003B5C] to-[#004F7D] text-white rounded-2xl p-6 shadow-md border border-[#BAE3F5]/30">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <span class="text-xs uppercase font-bold tracking-wider text-[#BAE3F5] block">Hasil Penilaian SPK</span>
                                <span class="text-[10px] text-cyan-200 font-medium"><i class="fa-solid fa-circle-check mr-1"></i>Lolos Skrining F-02 (&ge; 30.00)</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $permohonan->skor_ahp_saw >= 60 ? 'bg-[#B42318] text-white' : ($permohonan->skor_ahp_saw >= 30 ? 'bg-[#FEDF89] text-[#B54708]' : 'bg-[#A6F4C5] text-[#027A48]') }}">
                                {{ $permohonan->skor_ahp_saw >= 60 ? 'KRITIS' : ($permohonan->skor_ahp_saw >= 30 ? 'WASPADA' : 'AMAN') }}
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
                                <p class="font-bold text-[#BAE3F5] uppercase tracking-wider text-[11px]">Rincian 4 Kriteria (AHP-SAW):</p>
                                <span class="text-[10px] text-cyan-200 font-mono">SDG 10</span>
                            </div>
                            
                            <!-- C1: 50.47% -->
                            <div class="flex justify-between items-center bg-white/10 backdrop-blur-xs p-2.5 rounded-lg border border-white/15">
                                <div>
                                    <span class="font-bold text-white block">C1: Rasio KK Terdampak</span>
                                    <span class="text-[10px] text-slate-300">Bobot Baku: 50,47%</span>
                                </div>
                                <span class="font-extrabold text-base text-[#BAE3F5]">{{ $permohonan->rincian_kriteria['c1_rasio_kk'] ?? 0 }}%</span>
                            </div>

                            <!-- C3: 30.26% -->
                            <div class="flex justify-between items-center bg-white/10 backdrop-blur-xs p-2.5 rounded-lg border border-white/15">
                                <div>
                                    <span class="font-bold text-white block">C3: Hari Tanpa Air</span>
                                    <span class="text-[10px] text-slate-300">Bobot Baku: 30,26%</span>
                                </div>
                                <span class="font-extrabold text-base text-[#BAE3F5]">{{ $permohonan->rincian_kriteria['c3_hari_tanpa_air'] ?? 0 }}%</span>
                            </div>

                            <!-- C4: 11.00% -->
                            <div class="flex justify-between items-center bg-white/10 backdrop-blur-xs p-2.5 rounded-lg border border-white/15">
                                <div>
                                    <span class="font-bold text-white block">C4: Jarak ke Sumber Air</span>
                                    <span class="text-[10px] text-slate-300">Bobot Baku: 11,00%</span>
                                </div>
                                <span class="font-extrabold text-base text-[#BAE3F5]">{{ $permohonan->rincian_kriteria['c4_jarak_sumber'] ?? 0 }}%</span>
                            </div>

                            <!-- C2: 8.26% -->
                            <div class="flex justify-between items-center bg-white/10 backdrop-blur-xs p-2.5 rounded-lg border border-white/15">
                                <div>
                                    <span class="font-bold text-white block">C2: Proporsi Jiwa Rentan</span>
                                    <span class="text-[10px] text-slate-300">Bobot Baku: 8,26%</span>
                                </div>
                                <span class="font-extrabold text-base text-[#BAE3F5]">{{ $permohonan->rincian_kriteria['c2_proporsi_rentan'] ?? 0 }}%</span>
                            </div>
                        </div>

                        <div class="mt-5 p-3 rounded-xl bg-white/10 border border-white/15 text-xs text-slate-200">
                            <i class="fa-solid fa-lightbulb text-amber-300 mr-1.5"></i>
                            @if($permohonan->skor_ahp_saw >= 60)
                                <strong>Rekomendasi SPK (60 - 100):</strong> Segera disetujui untuk alokasi pengiriman truk tangki darurat (KRITIS).
                            @else
                                <strong>Rekomendasi SPK (30 - 59.99):</strong> Dijadwalkan untuk pengiriman tahap kedua setelah telaah berkas (WASPADA).
                            @endif
                        </div>
                    </div>

                    <!-- Panel Aksi Verifikasi (PBI-011 & PBI-012) -->
                    <div class="bg-white rounded-2xl border border-[#E4E7EC] shadow-xs p-6">
                        <h3 class="text-sm font-bold text-[#101828] border-b border-[#EAECF0] pb-3 mb-3">
                            Penetapan Keputusan Admin BPBD
                        </h3>

                        <p class="text-xs text-[#475467] mb-4">
                            Pilih keputusan telaah verifikasi untuk permohonan ini. Keputusan tercatat resmi dalam audit trail sistem.
                        </p>

                        <div class="space-y-2.5">
                            <!-- Tombol Setujui (PBI-011) -->
                            <button 
                                type="button" 
                                onclick="openModal('modal-setujui')" 
                                class="w-full py-2.5 px-4 bg-[#027A48] hover:bg-[#02663c] active:bg-[#01472a] text-white rounded-xl text-xs font-bold shadow-xs hover:shadow transition flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <i class="fa-solid fa-check"></i>
                                <span>Setujui Permohonan Bantuan</span>
                            </button>

                            <!-- Tombol Minta Revisi (PBI-012) -->
                            <button 
                                type="button" 
                                onclick="openModal('modal-revisi')" 
                                class="w-full py-2.5 px-4 bg-[#B54708] hover:bg-[#9a3c07] text-white rounded-xl text-xs font-bold shadow-xs hover:shadow transition flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Minta Perbaikan / Revisi Berkas</span>
                            </button>

                            <!-- Tombol Tolak (PBI-011) -->
                            <button 
                                type="button" 
                                onclick="openModal('modal-tolak')" 
                                class="w-full py-2.5 px-4 bg-[#B42318] hover:bg-[#911c13] active:bg-[#70150e] text-white rounded-xl text-xs font-bold shadow-xs hover:shadow transition flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <i class="fa-solid fa-xmark"></i>
                                <span>Tolak Permohonan</span>
                            </button>
                        </div>

                        <div class="mt-4 pt-3 border-t border-[#EAECF0] text-[10.5px] text-[#667085]">
                            <i class="fa-solid fa-shield-halved text-[#00629B] mr-1"></i>
                            Keputusan verifikasi BPBD bersifat final dan mengikat antrean distribusi air.
                        </div>
                    </div>

                </div>

            </div>

        </main>

        <!-- Modals Aksi Keputusan -->
        <!-- 1. Modal Setujui Permohonan -->
        <div id="modal-setujui" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-[#E4E7EC]">
                <div class="flex items-center justify-between pb-3 border-b border-[#EAECF0] mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-[#ECFDF3] text-[#027A48] flex items-center justify-center text-lg">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-[#101828] text-base">Persetujuan Permohonan Bantuan Air</h4>
                            <p class="text-xs text-[#667085]">Tiket: <span class="font-mono font-semibold">{{ $permohonan->nomor_tiket }}</span> • Desa {{ $permohonan->desa->nama_desa }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeModal('modal-setujui')" class="text-[#667085] hover:text-[#101828] text-lg cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('verifikasi.keputusan', $permohonan->id) }}">
                    @csrf
                    <input type="hidden" name="status_keputusan" value="Disetujui">

                    <div class="space-y-4 text-xs">
                        <div>
                            <label class="block font-bold text-[#344054] mb-1">
                                Alokasi Volume Air Bersih yang Disetujui (Liter) <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                name="volume_air_disetujui" 
                                value="5000" 
                                min="1000" 
                                step="1000" 
                                required 
                                class="w-full p-2.5 bg-white border border-[#D0D5DD] rounded-xl text-xs text-[#101828] focus:border-[#00629B] focus:ring-2 focus:ring-[#00629B]/20 outline-none"
                            >
                            <p class="text-[11px] text-[#667085] mt-1">Standar pasokan: 5.000 Liter per 1 armada tangki resmi.</p>
                        </div>

                        <div>
                            <label class="block font-bold text-[#344054] mb-1">
                                Catatan Rekomendasi Verifikasi (Opsional)
                            </label>
                            <textarea 
                                name="catatan_alasan" 
                                rows="3" 
                                placeholder="Contoh: Berkas lengkap, skor prioritas tinggi. Segera jadwalkan pengiriman tangki ke titik tandon RT 02." 
                                class="w-full p-2.5 bg-white border border-[#D0D5DD] rounded-xl text-xs text-[#101828] focus:border-[#00629B] focus:ring-2 focus:ring-[#00629B]/20 outline-none"
                            >{{ old('catatan_alasan') }}</textarea>
                        </div>

                        <div class="p-3 bg-[#ECFDF3] rounded-xl border border-[#A6F4C5] text-[#027A48] text-[11px] flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-[#027A48] text-base shrink-0"></i>
                            <span>Status tiket akan menjadi <strong>Disetujui</strong> dan masuk ke antrean Penugasan Armada Tangki.</span>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3 pt-3 border-t border-[#EAECF0]">
                        <button type="button" onclick="closeModal('modal-setujui')" class="px-4 py-2 border border-[#D0D5DD] text-[#344054] hover:bg-[#F2F4F7] rounded-xl text-xs font-semibold transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-[#027A48] hover:bg-[#02663c] text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-check"></i> Konfirmasi Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2. Modal Minta Revisi Berkas -->
        <div id="modal-revisi" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-[#E4E7EC]">
                <div class="flex items-center justify-between pb-3 border-b border-[#EAECF0] mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-[#FFFAEB] text-[#B54708] border border-[#FEDF89] flex items-center justify-center text-lg">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-[#101828] text-base">Minta Perbaikan Berkas (Revisi)</h4>
                            <p class="text-xs text-[#667085]">Tiket: <span class="font-mono font-semibold">{{ $permohonan->nomor_tiket }}</span> • Desa {{ $permohonan->desa->nama_desa }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeModal('modal-revisi')" class="text-[#667085] hover:text-[#101828] text-lg cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('verifikasi.keputusan', $permohonan->id) }}">
                    @csrf
                    <input type="hidden" name="status_keputusan" value="Revisi">

                    <div class="space-y-4 text-xs">
                        <div>
                            <label class="block font-bold text-[#344054] mb-1">
                                Rincian Dokumen / Data yang Perlu Diperbaiki <span class="text-rose-500">* (Wajib diisi)</span>
                            </label>
                            <textarea 
                                name="catatan_alasan" 
                                rows="4" 
                                required 
                                placeholder="Contoh: Surat permohonan Kades belum dibubuhi stempel basah desa. Mohon upload ulang dokumen resmi." 
                                class="w-full p-2.5 bg-white border border-[#D0D5DD] rounded-xl text-xs text-[#101828] focus:border-[#00629B] focus:ring-2 focus:ring-[#00629B]/20 outline-none"
                            >{{ old('catatan_alasan') }}</textarea>
                            <p class="text-[11px] text-[#667085] mt-1">Catatan ini akan dikirimkan kepada perwakilan desa sebagai instruksi perbaikan berkas.</p>
                        </div>

                        <div class="p-3 bg-[#FFFAEB] rounded-xl border border-[#FEDF89] text-[#B54708] text-[11px] flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-[#B54708] text-base shrink-0"></i>
                            <span>Status permohonan akan menjadi <strong>Revisi</strong>. Pihak desa dapat memperbarui dokumen permohonan kembali.</span>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3 pt-3 border-t border-[#EAECF0]">
                        <button type="button" onclick="closeModal('modal-revisi')" class="px-4 py-2 border border-[#D0D5DD] text-[#344054] hover:bg-[#F2F4F7] rounded-xl text-xs font-semibold transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-[#00629B] hover:bg-[#004F7D] text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Permintaan Revisi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 3. Modal Tolak Permohonan -->
        <div id="modal-tolak" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-[#E4E7EC]">
                <div class="flex items-center justify-between pb-3 border-b border-[#EAECF0] mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-[#FEF3F2] text-[#B42318] flex items-center justify-center text-lg">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-[#101828] text-base">Tolak Permohonan Bantuan</h4>
                            <p class="text-xs text-[#667085]">Tiket: <span class="font-mono font-semibold">{{ $permohonan->nomor_tiket }}</span> • Desa {{ $permohonan->desa->nama_desa }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeModal('modal-tolak')" class="text-[#667085] hover:text-[#101828] text-lg cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('verifikasi.keputusan', $permohonan->id) }}">
                    @csrf
                    <input type="hidden" name="status_keputusan" value="Ditolak">

                    <div class="space-y-4 text-xs">
                        <div>
                            <label class="block font-bold text-[#344054] mb-1">
                                Alasan Tertulis Penolakan Permohonan <span class="text-rose-500">* (Wajib diisi)</span>
                            </label>
                            <textarea 
                                name="catatan_alasan" 
                                rows="4" 
                                required 
                                placeholder="Contoh: Berdasarkan telaah tim, jarak sumber mata air alternatif masih berada dalam radius 400 meter dan debit air masih mencukupi." 
                                class="w-full p-2.5 bg-white border border-[#D0D5DD] rounded-xl text-xs text-[#101828] focus:border-[#B42318] focus:ring-2 focus:ring-[#B42318]/20 outline-none"
                            >{{ old('catatan_alasan') }}</textarea>
                            <p class="text-[11px] text-[#667085] mt-1">Alasan penolakan wajib objektif dan akan disimpan permanen dalam audit trail log.</p>
                        </div>

                        <div class="p-3 bg-[#FEF3F2] rounded-xl border border-[#FECDCA] text-[#B42318] text-[11px] flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation text-[#B42318] text-base shrink-0"></i>
                            <span>Status permohonan akan diubah menjadi <strong>Ditolak</strong>. Tiket tidak akan masuk antrean pengiriman tangki.</span>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3 pt-3 border-t border-[#EAECF0]">
                        <button type="button" onclick="closeModal('modal-tolak')" class="px-4 py-2 border border-[#D0D5DD] text-[#344054] hover:bg-[#F2F4F7] rounded-xl text-xs font-semibold transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-[#B42318] hover:bg-[#911c13] text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-xmark"></i> Konfirmasi Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer Institusional Bersih -->
        <footer class="mt-auto py-4 border-t border-[#EAECF0] bg-white text-xs text-[#667085]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left">
                <p>Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Purbalingga &middot; SPK Distribusi Air Bersih Darurat</p>
                <p>&copy; {{ date('Y') }} LinggaAir &middot; INA Digital Design System Standard</p>
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
