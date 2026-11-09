<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Permohonan {{ $permohonan->nomor_tiket }} - Lingga Air</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">

    <!-- Top Navigation -->
    <nav class="bg-blue-900 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center font-bold text-xl shadow">
                        <i class="fa-solid fa-droplet text-cyan-300"></i>
                    </div>
                    <div>
                        <span class="font-bold text-lg tracking-wide">Lingga Air</span>
                        <span class="text-xs text-blue-200 block">Sistem Bantuan Air Bersih Darurat BPBD Purbalingga</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4 text-sm">
                    <a href="{{ route('verifikasi.index') }}" class="text-blue-200 hover:text-white flex items-center gap-1.5 text-xs font-semibold">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Antrean
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Flash Message Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start gap-3 shadow-sm animate-fade-in">
                <i class="fa-solid fa-circle-check text-emerald-600 text-xl mt-0.5"></i>
                <div class="flex-1">
                    <h4 class="font-bold text-sm">Aksi Verifikasi Berhasil!</h4>
                    <p class="text-xs text-emerald-800 mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 flex items-start gap-3 shadow-sm">
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

        <!-- Breadcrumb & Header -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <nav class="text-xs text-slate-500 mb-1 flex items-center gap-2">
                    <a href="{{ route('verifikasi.index') }}" class="hover:underline">Antrean Verifikasi</a>
                    <span>/</span>
                    <span class="text-slate-700 font-semibold font-mono">{{ $permohonan->nomor_tiket }}</span>
                </nav>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-slate-900">Detail Permohonan Desa {{ $permohonan->desa->nama_desa }}</h1>
                    @if($permohonan->status == 'Menunggu Verifikasi')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                            Menunggu Verifikasi
                        </span>
                    @elseif($permohonan->status == 'Disetujui')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center gap-1.5">
                            <i class="fa-solid fa-check"></i> Disetujui
                        </span>
                    @elseif($permohonan->status == 'Revisi')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200 flex items-center gap-1.5">
                            <i class="fa-solid fa-pen-to-square"></i> Perlu Revisi
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-200 flex items-center gap-1.5">
                            <i class="fa-solid fa-xmark"></i> Ditolak
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Diajukan pada: <strong class="text-slate-700">{{ $permohonan->created_at->format('d F Y, Pukul H:i') }} WIB</strong>
                    • Kecamatan: <strong class="text-slate-700">{{ $permohonan->desa->kecamatan }}</strong>
                </p>
            </div>
            
            <div class="flex items-center gap-2">
                <a href="{{ route('verifikasi.index') }}" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 shadow-sm transition">
                    &larr; Daftar Antrean
                </a>
            </div>
        </div>

        <!-- 2 Column Layout (PBI-010, PBI-011, PBI-012) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- LEFT 2 COLS: Data Permohonan & Berkas & Audit Trail -->
            <div class="lg:col-span-2 space-y-6">

                <!-- 1. Data Acuan Desa (Sinkron F-01 & F-02) -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-blue-600"></i> Data Acuan Desa & Kontak Penanggung Jawab
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-4">
                        <div>
                            <span class="text-xs text-slate-400 block">Nama Desa / Kelurahan</span>
                            <span class="font-semibold text-slate-800">Desa {{ $permohonan->desa->nama_desa }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block">Kecamatan</span>
                            <span class="font-semibold text-slate-800">Kec. {{ $permohonan->desa->kecamatan }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block">Penanggung Jawab Permohonan</span>
                            <span class="font-semibold text-slate-800">{{ $permohonan->desa->penanggung_jawab ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block">Nomor Kontak / WhatsApp</span>
                            <span class="font-semibold text-slate-800 flex items-center gap-1.5">
                                <i class="fa-brands fa-whatsapp text-emerald-500"></i>
                                {{ $permohonan->desa->kontak_wa ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <!-- Rincian Data Kependudukan Acuan Desa (Sesuai Form F-02) -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-4">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-3">
                            <i class="fa-solid fa-users-viewfinder text-blue-600 mr-1.5"></i> Profil Demografi Desa (Data Acuan):
                        </span>
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-center text-xs">
                            <div class="bg-white p-2.5 rounded-lg border border-slate-200">
                                <span class="text-slate-400 block text-[11px]">KK Total</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ number_format($permohonan->desa->jumlah_kk) }}</span>
                            </div>
                            <div class="bg-white p-2.5 rounded-lg border border-slate-200">
                                <span class="text-slate-400 block text-[11px]">Jiwa Total</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ number_format($permohonan->desa->jumlah_jiwa) }}</span>
                            </div>
                            <div class="bg-white p-2.5 rounded-lg border border-slate-200">
                                <span class="text-slate-400 block text-[11px]">Balita</span>
                                <span class="font-bold text-blue-600 text-sm mt-0.5 block">{{ $permohonan->desa->jumlah_balita }}</span>
                            </div>
                            <div class="bg-white p-2.5 rounded-lg border border-slate-200">
                                <span class="text-slate-400 block text-[11px]">Lansia</span>
                                <span class="font-bold text-purple-600 text-sm mt-0.5 block">{{ $permohonan->desa->jumlah_lansia }}</span>
                            </div>
                            <div class="bg-white p-2.5 rounded-lg border border-slate-200">
                                <span class="text-slate-400 block text-[11px]">Disabilitas</span>
                                <span class="font-bold text-amber-600 text-sm mt-0.5 block">{{ $permohonan->desa->jumlah_disabilitas }}</span>
                            </div>
                        </div>
                        <div class="text-[11px] text-slate-500 mt-2 text-right">
                            Total Kelompok Rentan: <strong class="text-slate-800">{{ $permohonan->desa->total_jiwa_rentan }} jiwa</strong> ({{ number_format(($permohonan->desa->total_jiwa_rentan / max(1, $permohonan->desa->jumlah_jiwa)) * 100, 1) }}% dari total jiwa)
                        </div>
                    </div>

                    <div class="bg-blue-50/50 p-3.5 rounded-lg border border-blue-200 text-xs">
                        <span class="text-slate-500 font-medium block">Titik Drop-Off Air Bersih Desa:</span>
                        <span class="font-semibold text-slate-900 mt-0.5 block flex items-center gap-2">
                            <i class="fa-solid fa-truck-droplet text-blue-600"></i>
                            {{ $permohonan->desa->titik_drop_air ?? 'Belum ditentukan oleh desa' }}
                        </span>
                    </div>
                </div>

                <!-- 2. Kondisi Lapangan (Parameter Formulir Fitur 2) -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-temperature-arrow-up text-amber-500"></i> Kondisi Lapangan & Parameter Kekeringan
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                        <div class="bg-blue-50/60 p-4 rounded-xl border border-blue-100 text-center">
                            <span class="text-xs text-blue-700 block font-medium">KK Terdampak</span>
                            <span class="text-2xl font-bold text-blue-900 mt-1 block">{{ $permohonan->kk_terdampak }}</span>
                            <span class="text-[10px] text-blue-600 block mt-0.5">dari total {{ $permohonan->desa->jumlah_kk }} KK ({{ number_format(($permohonan->kk_terdampak / max(1, $permohonan->desa->jumlah_kk)) * 100, 1) }}%)</span>
                        </div>
                        <div class="bg-amber-50/60 p-4 rounded-xl border border-amber-100 text-center">
                            <span class="text-xs text-amber-700 block font-medium">Hari Tanpa Air</span>
                            <span class="text-2xl font-bold text-amber-900 mt-1 block">{{ $permohonan->hari_tanpa_air }} Hari</span>
                            <span class="text-[10px] text-amber-600 block mt-0.5">Maks. normalisasi: 7 hari</span>
                        </div>
                        <div class="bg-rose-50/60 p-4 rounded-xl border border-rose-100 text-center">
                            <span class="text-xs text-rose-700 block font-medium">Jarak ke Sumber Air</span>
                            <span class="text-2xl font-bold text-rose-900 mt-1 block">{{ number_format($permohonan->jarak_sumber_air) }} m</span>
                            <span class="text-[10px] text-rose-600 block mt-0.5">Maks. normalisasi: 2.000 meter</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1.5">Alasan Kedaruratan dari Desa:</span>
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 text-sm text-slate-700 leading-relaxed italic">
                            &ldquo;{{ $permohonan->alasan_permohonan ?? 'Tidak ada catatan tambahan.' }}&rdquo;
                        </div>
                    </div>
                </div>

                <!-- 3. Berkas Pendukung (Surat Kades & Foto Lapangan) -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-paperclip text-slate-600"></i> Berkas Lampiran Pendukung Resmi
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Surat Kades -->
                        <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-2 text-rose-600 mb-2">
                                    <i class="fa-solid fa-file-pdf text-2xl"></i>
                                    <span class="font-bold text-slate-800 text-sm">Surat Permohonan Kepala Desa</span>
                                </div>
                                <p class="text-xs text-slate-500">Dokumen surat keterangan resmi yang telah ditandatangani dan dicap stempel desa.</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-200 flex items-center justify-between">
                                <span class="text-[11px] text-slate-400 font-mono">{{ $permohonan->surat_kades ?? 'surat_resmi.pdf' }}</span>
                                <span class="px-2.5 py-1 bg-white border border-slate-300 rounded text-xs font-semibold text-slate-700 hover:bg-slate-100 cursor-pointer">
                                    <i class="fa-solid fa-eye mr-1"></i> Periksa Surat
                                </span>
                            </div>
                        </div>

                        <!-- Foto Kondisi Lapangan -->
                        <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-2 text-blue-600 mb-2">
                                    <i class="fa-solid fa-image text-2xl"></i>
                                    <span class="font-bold text-slate-800 text-sm">Foto Kondisi Kekeringan Lapangan</span>
                                </div>
                                <p class="text-xs text-slate-500">Bukti visual kondisi sumur atau bak warga yang mengalami kekeringan.</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-200 flex items-center justify-between">
                                <span class="text-[11px] text-slate-400">Bukti Lapangan</span>
                                @if($permohonan->foto_lapangan)
                                    <a href="{{ $permohonan->foto_lapangan }}" target="_blank" class="px-2.5 py-1 bg-blue-600 text-white rounded text-xs font-semibold hover:bg-blue-700">
                                        <i class="fa-solid fa-up-right-from-square mr-1"></i> Lihat Foto Penuh
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">Tidak ada foto</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($permohonan->foto_lapangan)
                        <div class="mt-4 rounded-xl overflow-hidden border border-slate-200 max-h-72 bg-slate-900">
                            <img src="{{ $permohonan->foto_lapangan }}" alt="Foto Dokumentasi Lapangan" class="w-full h-72 object-cover hover:opacity-95 transition">
                        </div>
                    @endif
                </div>

                <!-- 4. Riwayat Keputusan / Audit Trail Log (PBI-011 & PBI-012) -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-slate-600"></i> Riwayat Telaah Verifikasi (Audit Trail Log)
                        </h3>
                        <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-medium">
                            {{ $permohonan->keputusanPermohonans->count() }} Catatan
                        </span>
                    </div>

                    @if($permohonan->keputusanPermohonans->count() > 0)
                        <div class="space-y-4">
                            @foreach($permohonan->keputusanPermohonans as $log)
                                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 relative">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs">
                                                <i class="fa-solid fa-user-check"></i>
                                            </div>
                                            <span class="text-sm font-bold text-slate-800">{{ $log->user->name ?? 'Petugas Verifikator BPBD' }}</span>
                                        </div>
                                        <span class="text-xs text-slate-400 flex items-center gap-1">
                                            <i class="fa-regular fa-clock"></i>
                                            {{ $log->created_at->format('d M Y, H:i') }} WIB
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-xs text-slate-500 font-medium">Status Keputusan:</span>
                                        @if($log->status_keputusan == 'Disetujui')
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center gap-1">
                                                <i class="fa-solid fa-check"></i> Disetujui
                                            </span>
                                            @if($log->volume_air_disetujui)
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                                    <i class="fa-solid fa-faucet-drip mr-1"></i> {{ number_format($log->volume_air_disetujui) }} Liter
                                                </span>
                                            @endif
                                        @elseif($log->status_keputusan == 'Revisi')
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200 flex items-center gap-1">
                                                <i class="fa-solid fa-pen-to-square"></i> Revisi Berkas
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200 flex items-center gap-1">
                                                <i class="fa-solid fa-xmark"></i> Ditolak
                                            </span>
                                        @endif
                                    </div>

                                    <div class="bg-white p-3 rounded-lg border border-slate-200 text-xs text-slate-700 italic">
                                        &ldquo;{{ $log->catatan_alasan }}&rdquo;
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-slate-400">
                            <i class="fa-regular fa-folder-open text-3xl mb-2 block"></i>
                            <p class="text-xs italic">Belum ada riwayat telaah verifikasi yang dicatat untuk permohonan ini.</p>
                        </div>
                    @endif
                </div>

            </div>

            <!-- RIGHT 1 COL: Kalkulasi Skor AHP-SAW & Form Keputusan -->
            <div class="space-y-6">

                <!-- Kartu Skor AHP-SAW (KUNCI PBI-010) -->
                <div class="bg-gradient-to-br from-blue-900 to-indigo-950 text-white rounded-2xl p-6 shadow-lg border border-blue-800">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <span class="text-xs uppercase font-bold tracking-wider text-blue-300 block">Hasil Penilaian SPK</span>
                            <span class="text-[10px] text-cyan-300 font-medium"><i class="fa-solid fa-circle-check mr-1"></i>Lolos Skrining F-02 (&ge; 40.00)</span>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $permohonan->skor_ahp_saw >= 70 ? 'bg-rose-500 text-white' : ($permohonan->skor_ahp_saw >= 40 ? 'bg-amber-400 text-slate-900' : 'bg-emerald-400 text-slate-900') }}">
                            {{ $permohonan->skor_ahp_saw >= 70 ? 'KRITIS' : ($permohonan->skor_ahp_saw >= 40 ? 'WASPADA' : 'AMAN') }}
                        </span>
                    </div>

                    <div class="text-center py-4 border-y border-blue-800/60 my-2">
                        <span class="text-5xl font-extrabold tracking-tight text-white block">
                            {{ number_format($permohonan->skor_ahp_saw, 2) }}
                        </span>
                        <span class="text-xs text-blue-200 mt-1 block">Skor Prioritas Akhir (0 - 100)</span>
                    </div>

                    <!-- Breakdown 4 Kriteria AHP-SAW -->
                    <div class="mt-4 space-y-2.5 text-xs">
                        <p class="font-semibold text-blue-200 uppercase tracking-wider text-[11px] mb-2">Rincian Nilai 4 Kriteria (AHP-SAW):</p>
                        
                        <div class="flex justify-between items-center bg-blue-800/40 p-2 rounded-lg">
                            <span class="text-slate-200">C1: Rasio KK Terdampak</span>
                            <span class="font-bold text-white">{{ $permohonan->rincian_kriteria['c1_rasio_kk'] ?? 0 }}%</span>
                        </div>
                        <div class="flex justify-between items-center bg-blue-800/40 p-2 rounded-lg">
                            <span class="text-slate-200">C2: Proporsi Jiwa Rentan</span>
                            <span class="font-bold text-white">{{ $permohonan->rincian_kriteria['c2_proporsi_rentan'] ?? 0 }}%</span>
                        </div>
                        <div class="flex justify-between items-center bg-blue-800/40 p-2 rounded-lg">
                            <span class="text-slate-200">C3: Hari Tanpa Air (Maks 7 Hari)</span>
                            <span class="font-bold text-white">{{ $permohonan->rincian_kriteria['c3_hari_tanpa_air'] ?? 0 }}%</span>
                        </div>
                        <div class="flex justify-between items-center bg-blue-800/40 p-2 rounded-lg">
                            <span class="text-slate-200">C4: Jarak Sumber (Maks 2.000 m)</span>
                            <span class="font-bold text-white">{{ $permohonan->rincian_kriteria['c4_jarak_sumber'] ?? 0 }}%</span>
                        </div>
                    </div>

                    <div class="mt-5 p-3 rounded-xl bg-blue-800/50 border border-blue-700 text-xs text-blue-100">
                        <i class="fa-solid fa-lightbulb text-amber-300 mr-1.5"></i>
                        @if($permohonan->skor_ahp_saw >= 70)
                            <strong>Rekomendasi Sistem (70 - 100):</strong> Segera disetujui untuk alokasi pengiriman truk tangki darurat (KRITIS).
                        @else
                            <strong>Rekomendasi Sistem (40 - 69.99):</strong> Dijadwalkan untuk pengiriman tahap kedua setelah telaah berkas (WASPADA).
                        @endif
                        <span class="block text-[11px] text-blue-300/80 mt-1 pt-1 border-t border-blue-700/60">
                            *Status AMAN (&lt; 40.00) telah otomatis dicegat di F-02 dan tidak masuk antrean verifikasi BPBD.
                        </span>
                    </div>
                </div>

                <!-- Panel Aksi Verifikasi (PBI-011 & PBI-012) -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">
                        Penetapan Keputusan Admin BPBD
                    </h3>

                    <p class="text-xs text-slate-500 mb-4">
                        Pilih keputusan telaah verifikasi untuk permohonan ini. Seluruh keputusan akan tercatat dalam audit trail sistem dan memperbarui status tiket.
                    </p>

                    <div class="space-y-3">
                        <!-- Tombol Setujui (PBI-011) -->
                        <button type="button" onclick="openModal('modal-setujui')" class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-sm hover:shadow transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check"></i> Setujui Permohonan Bantuan
                        </button>

                        <!-- Tombol Revisi (PBI-012) -->
                        <button type="button" onclick="openModal('modal-revisi')" class="w-full py-2.5 px-4 bg-purple-50 hover:bg-purple-100 active:bg-purple-200 text-purple-700 border border-purple-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-pen-to-square"></i> Minta Perbaikan Berkas (Revisi)
                        </button>

                        <!-- Tombol Tolak (PBI-011) -->
                        <button type="button" onclick="openModal('modal-tolak')" class="w-full py-2.5 px-4 bg-rose-50 hover:bg-rose-100 active:bg-rose-200 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-xmark"></i> Tolak Permohonan
                        </button>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-400 text-center">
                        <i class="fa-solid fa-shield-halved mr-1"></i> Tindakan tercatat resmi dalam audit trail log BPBD.
                    </div>
                </div>

            </div>

        </div>

    </main>

    <!-- ==================== MODAL DIALOGS ==================== -->

    <!-- 1. Modal Setujui Permohonan (PBI-011) -->
    <div id="modal-setujui" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 animate-scale-up">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-base">Setujui Permohonan Bantuan Air</h4>
                        <p class="text-xs text-slate-500">Tiket: <span class="font-mono font-semibold">{{ $permohonan->nomor_tiket }}</span> • Desa {{ $permohonan->desa->nama_desa }}</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modal-setujui')" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('verifikasi.keputusan', $permohonan->id) }}">
                @csrf
                <input type="hidden" name="status_keputusan" value="Disetujui">

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            Alokasi Volume Air yang Disetujui (Liter) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="volume_air_disetujui" value="{{ old('volume_air_disetujui', $permohonan->kebutuhan_tangki * 5000) }}" min="1000" step="500" required class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-xs text-slate-400 font-medium">Liter</span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1">
                            Estimasi desa: <strong>{{ $permohonan->kebutuhan_tangki }} truk tangki</strong> (~{{ number_format($permohonan->kebutuhan_tangki * 5000) }} liter @ 5.000 L/truk).
                        </p>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            Catatan Verifikator / Petunjuk Distribusi (Opsional)
                        </label>
                        <textarea name="catatan_alasan" rows="3" placeholder="Contoh: Pengiriman dijadwalkan shift pagi ke bak penampungan sentral RT 04 RW 02." class="w-full p-3 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">{{ old('catatan_alasan') }}</textarea>
                    </div>

                    <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 text-emerald-900 text-[11px] flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-emerald-600 text-base shrink-0"></i>
                        <span>Status permohonan akan diperbarui menjadi <strong>Disetujui</strong> dan diteruskan ke bagian penjadwalan armada distribusi tangki air.</span>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('modal-setujui')" class="px-4 py-2 border border-slate-300 text-slate-700 hover:bg-slate-100 rounded-xl text-xs font-semibold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow transition flex items-center gap-1.5">
                        <i class="fa-solid fa-check"></i> Konfirmasi Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. Modal Minta Revisi Berkas (PBI-012) -->
    <div id="modal-revisi" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 animate-scale-up">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-base">Minta Perbaikan Berkas (Revisi)</h4>
                        <p class="text-xs text-slate-500">Tiket: <span class="font-mono font-semibold">{{ $permohonan->nomor_tiket }}</span> • Desa {{ $permohonan->desa->nama_desa }}</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modal-revisi')" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('verifikasi.keputusan', $permohonan->id) }}">
                @csrf
                <input type="hidden" name="status_keputusan" value="Revisi">

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            Rincian Dokumen / Data yang Perlu Diperbaiki <span class="text-rose-500">* (Wajib diisi)</span>
                        </label>
                        <textarea name="catatan_alasan" rows="4" required placeholder="Contoh: Surat permohonan Kades belum dibubuhi stempel basah desa dan foto kondisi sumur terlalu buram. Mohon upload ulang dokumen resmi dan foto terkini." class="w-full p-3 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none">{{ old('catatan_alasan') }}</textarea>
                        <p class="text-[11px] text-slate-500 mt-1">Catatan ini akan dikirimkan kepada perwakilan desa sebagai instruksi perbaikan berkas.</p>
                    </div>

                    <div class="p-3 bg-purple-50 rounded-xl border border-purple-100 text-purple-900 text-[11px] flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-purple-600 text-base shrink-0"></i>
                        <span>Status permohonan akan menjadi <strong>Revisi</strong>. Pihak desa dapat memperbarui dokumen permohonan kembali.</span>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('modal-revisi')" class="px-4 py-2 border border-slate-300 text-slate-700 hover:bg-slate-100 rounded-xl text-xs font-semibold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow transition flex items-center gap-1.5">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Permintaan Revisi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. Modal Tolak Permohonan (PBI-011) -->
    <div id="modal-tolak" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 animate-scale-up">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-base">Tolak Permohonan Bantuan</h4>
                        <p class="text-xs text-slate-500">Tiket: <span class="font-mono font-semibold">{{ $permohonan->nomor_tiket }}</span> • Desa {{ $permohonan->desa->nama_desa }}</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modal-tolak')" class="text-slate-400 hover:text-slate-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('verifikasi.keputusan', $permohonan->id) }}">
                @csrf
                <input type="hidden" name="status_keputusan" value="Ditolak">

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            Alasan Tertulis Penolakan Permohonan <span class="text-rose-500">* (Wajib diisi)</span>
                        </label>
                        <textarea name="catatan_alasan" rows="4" required placeholder="Contoh: Berdasarkan telaah tim, jarak sumber mata air alternatif masih berada dalam radius 400 meter dan debit air masih mencukupi kebutuhan warga, sehingga permohonan belum memenuhi kategori darurat kekeringan." class="w-full p-3 border border-slate-300 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 outline-none">{{ old('catatan_alasan') }}</textarea>
                        <p class="text-[11px] text-slate-500 mt-1">Alasan penolakan wajib objektif dan akan disimpan permanen dalam audit trail log.</p>
                    </div>

                    <div class="p-3 bg-rose-50 rounded-xl border border-rose-100 text-rose-900 text-[11px] flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-rose-600 text-base shrink-0"></i>
                        <span>Status permohonan akan diubah menjadi <strong>Ditolak</strong>. Tiket tidak akan masuk antrean pengiriman tangki.</span>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('modal-tolak')" class="px-4 py-2 border border-slate-300 text-slate-700 hover:bg-slate-100 rounded-xl text-xs font-semibold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow transition flex items-center gap-1.5">
                        <i class="fa-solid fa-xmark"></i> Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
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

    <footer class="mt-12 py-6 border-t border-slate-200 text-center text-xs text-slate-500">
        &copy; 2026 Lingga Air - Proyek Perangkat Lunak (Kelompok 4). BPBD Kabupaten Purbalingga.
    </footer>

</body>
</html>
