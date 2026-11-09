@extends('layouts.app')

@section('title', 'Penyusunan Pengajuan Bantuan Air Bersih - Lingga Air')

@section('content')
<div class="space-y-6">

    <!-- BREADCRUMB -->
    <nav class="flex text-xs text-slate-500 space-x-2 items-center">
        <a href="{{ route('pengajuan.index') }}" class="hover:text-sky-600 transition">Beranda</a>
        <span>/</span>
        <span class="text-slate-800 font-semibold">Pengajuan Baru</span>
    </nav>

    <!-- HEADER TITLE -->
    <div class="border-b border-slate-200/80 pb-4">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pengajuan Bantuan Air Bersih</h1>
        <p class="text-sm text-slate-500 mt-1">Lengkapi formulir detail kebutuhan air berikut untuk desa Anda.</p>
    </div>

    <!-- PBI-009: Peringatan Tiket Aktif (Anti-Spam) jika desa sudah punya tiket berjalan -->
    @if($hasActiveTicket && $activeTicket)
        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-xl shadow-sm text-amber-900">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="text-xs">
                    <p class="font-bold text-sm text-amber-950">Desa Anda Memiliki Tiket Bantuan Aktif</p>
                    <p class="mt-0.5">
                        Permohonan aktif dengan Nomor Tiket <strong>{{ $activeTicket->nomor_tiket }}</strong> saat ini berstatus <strong>{{ $activeTicket->status }}</strong>.
                        Sistem membatasi satu desa hanya boleh memiliki 1 tiket aktif. Anda tetap dapat menyimpan isian berikut sebagai <strong>Draf</strong>, namun tombol kirim ke antrean admin akan dikunci hingga tiket aktif diselesaikan.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- RINGKASAN HASIL CEK KELAYAKAN (KARTU KUNING SESUAI MOCKUP HALAMAN 57) -->
    <div class="bg-gradient-to-r from-amber-50 via-amber-50/70 to-orange-50 border border-amber-200 rounded-2xl p-5 shadow-sm">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            
            <!-- Skor AHP-SAW -->
            <div class="flex items-center space-x-4">
                <div class="bg-white/80 border border-amber-300 rounded-xl px-4 py-2 text-center shadow-inner">
                    <span class="block text-[11px] font-bold text-amber-800 uppercase tracking-wider">SKOR PRIORITAS</span>
                    <span class="text-3xl font-extrabold text-amber-600 tracking-tight" id="disp-skor">
                        {{ number_format($simulasi->skor_ahp_saw ?? 92.4, 1) }}
                    </span>
                    <span class="text-xs font-semibold text-slate-400">/ 100</span>
                </div>

                <div>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs font-semibold text-slate-600">Hasil Cek Kelayakan:</span>
                        @php
                            $status = $simulasi->status_kelayakan ?? 'Kritis';
                            $statusColor = match($status) {
                                'Kritis' => 'bg-rose-600 text-white',
                                'Waspada' => 'bg-amber-500 text-white',
                                default => 'bg-emerald-600 text-white',
                            };
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $statusColor }}" id="disp-status">
                            {{ $status }}
                        </span>
                    </div>
                    <p class="text-xs text-amber-900 font-medium mt-1" id="disp-rekomendasi">
                        {{ $simulasi->rekomendasi ?? 'Segera ajukan bantuan pasokan air bersih untuk kebutuhan warga terdampak.' }}
                    </p>
                </div>
            </div>

            <!-- Catatan Kelayakan -->
            <div class="text-right sm:border-l sm:border-amber-200 sm:pl-4">
                <span class="text-[11px] text-slate-500 block">Metode SPK Kemanusiaan:</span>
                <span class="text-xs font-bold text-slate-700">AHP - Simple Additive Weighting (SAW)</span>
                @if(($simulasi->status_kelayakan ?? '') === 'Aman')
                    <span class="mt-1 inline-block text-[11px] text-rose-600 font-bold bg-rose-100 px-2 py-0.5 rounded">
                        Pengiriman Ditolak (Status Aman)
                    </span>
                @endif
            </div>

        </div>
    </div>

    <!-- FORMULIR PENGAJUAN RESMI (MOCKUP HALAMAN 57) -->
    <form id="formPengajuan" action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <input type="hidden" name="simulasi_id" value="{{ $simulasi->id ?? '' }}">
        <input type="hidden" name="action" id="formActionInput" value="draf">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-8 space-y-6">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="font-bold text-base text-slate-900">Informasi Pengajuan</h3>
                </div>
                <span class="text-xs text-rose-500 font-semibold">* Wajib diisi</span>
            </div>

            <!-- Field 1: Desa Pemohon (Terkunci & Otomatis) -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Desa Pemohon
                </label>
                <div class="relative">
                    <input 
                        type="text" 
                        value="{{ $desa->nama_desa }} (Kecamatan {{ $desa->kecamatan }}) - Kode: {{ $desa->kode_desa }}" 
                        disabled 
                        class="w-full bg-slate-100/80 border border-slate-300 text-slate-600 text-sm rounded-xl px-4 py-2.5 cursor-not-allowed font-medium"
                    >
                    <div class="absolute right-3.5 top-2.5 text-slate-400" title="Data desa terisi otomatis dari sistem">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Data desa terkunci otomatis berdasarkan akun aparatur desa yang terdaftar resmi.</p>
            </div>

            <!-- Field 2: Data Kebutuhan (Grid 3 Kolom - Terkunci dari Hasil Cek Kelayakan Mandiri) -->
            <div class="space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center space-x-1.5">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>Parameter Kebutuhan (Terkunci dari Cek Kelayakan F-02)</span>
                    </span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">
                        <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Nilai Terkunci Otomatis
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <!-- KK Terdampak (Readonly / Locked) -->
                    <div>
                        <label for="jumlah_kk_display" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 flex items-center justify-between">
                            <span>Jumlah KK Terdampak</span>
                            <span class="text-[10px] text-slate-400 font-semibold flex items-center gap-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Terkunci
                            </span>
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="jumlah_kk_display" 
                                value="{{ $simulasi->kk_terdampak ?? 120 }}" 
                                readonly 
                                class="w-full bg-slate-100/90 border border-slate-300 rounded-xl pl-4 pr-16 py-2.5 text-sm cursor-not-allowed font-extrabold text-slate-800 select-none shadow-inner"
                            >
                            <input type="hidden" name="jumlah_kk" id="jumlah_kk" value="{{ $simulasi->kk_terdampak ?? 120 }}">
                            <div class="absolute right-3.5 top-2.5 flex items-center space-x-1.5 text-slate-400 pointer-events-none">
                                <span class="text-xs font-bold text-slate-500">KK</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-1 block">Tersinkron dari Cek Kelayakan (total {{ $desa->jumlah_kk }} KK)</span>
                    </div>

                    <!-- Hari Tanpa Air (Readonly / Locked) -->
                    <div>
                        <label for="hari_tanpa_air_display" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 flex items-center justify-between">
                            <span>Lama Hari Tanpa Air</span>
                            <span class="text-[10px] text-slate-400 font-semibold flex items-center gap-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Terkunci
                            </span>
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="hari_tanpa_air_display" 
                                value="{{ $simulasi->hari_tanpa_air ?? 14 }}" 
                                readonly 
                                class="w-full bg-slate-100/90 border border-slate-300 rounded-xl pl-4 pr-16 py-2.5 text-sm cursor-not-allowed font-extrabold text-slate-800 select-none shadow-inner"
                            >
                            <input type="hidden" name="hari_tanpa_air" id="hari_tanpa_air" value="{{ $simulasi->hari_tanpa_air ?? 14 }}">
                            <div class="absolute right-3.5 top-2.5 flex items-center space-x-1.5 text-slate-400 pointer-events-none">
                                <span class="text-xs font-bold text-slate-500">Hari</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-1 block">Durasi ketiadaan suplai air hasil simulasi</span>
                    </div>

                    <!-- Jarak Sumber Air (Readonly / Locked) -->
                    <div>
                        <label for="jarak_sumber_air_display" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 flex items-center justify-between">
                            <span>Jarak Sumber Air</span>
                            <span class="text-[10px] text-slate-400 font-semibold flex items-center gap-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Terkunci
                            </span>
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="jarak_sumber_air_display" 
                                value="{{ $simulasi->jarak_sumber_air ?? 1200 }}" 
                                readonly 
                                class="w-full bg-slate-100/90 border border-slate-300 rounded-xl pl-4 pr-16 py-2.5 text-sm cursor-not-allowed font-extrabold text-slate-800 select-none shadow-inner"
                            >
                            <input type="hidden" name="jarak_sumber_air" id="jarak_sumber_air" value="{{ $simulasi->jarak_sumber_air ?? 1200 }}">
                            <div class="absolute right-3.5 top-2.5 flex items-center space-x-1.5 text-slate-400 pointer-events-none">
                                <span class="text-xs font-bold text-slate-500">Meter</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-1 block">Jarak tempuh warga ke sumber alternatif</span>
                    </div>

                </div>

                <div class="bg-amber-50/80 border border-amber-200/90 rounded-xl p-3 text-xs text-amber-900 flex items-start space-x-2.5">
                    <svg class="w-4 h-4 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <span class="font-bold">Data Kebutuhan Terkunci dari Cek Kelayakan Mandiri (F-02):</span>
                        <p class="mt-0.5 text-[11px] text-amber-800 leading-relaxed">
                            Ketiga nilai di atas (Jumlah KK Terdampak, Lama Hari Tanpa Air, dan Jarak Sumber Air Alternatif) terisi otomatis dari hasil perhitungan Cek Kelayakan Mandiri dan <strong>tidak dapat diubah secara manual</strong> demi menjaga integritas serta objektivitas kalkulasi prioritas SPK (AHP-SAW).
                        </p>
                    </div>
                </div>
            </div>

            <!-- Field 3: Alasan Pengajuan -->
            <div>
                <label for="alasan_pengajuan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Alasan Pengajuan & Kondisi Sumber Air <span class="text-rose-500">*</span>
                </label>
                <textarea 
                    name="alasan_pengajuan" 
                    id="alasan_pengajuan" 
                    rows="3" 
                    required 
                    placeholder="Contoh: Sumber air sumur gali dan mata air desa mengering total selama dua minggu terakhir akibat musim kemarau panjang. Warga terpaksa membeli air pikulan..." 
                    class="w-full bg-white border border-slate-300 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                >{{ old('alasan_pengajuan', 'Sumber air sumur gali dan mata air desa mengering total selama dua minggu terakhir akibat musim kemarau panjang. Warga membutuhkan pasokan air bersih darurat untuk kebutuhan harian.') }}</textarea>
                <p class="text-[11px] text-slate-400 mt-1">Jelaskan kondisi riil sumur, embung, atau aliran air pipa warga saat ini.</p>
            </div>

            <!-- Field 4: Unggah Foto Bukti Kondisi Lapangan (PBI-007) -->
            <div class="border-t border-slate-100 pt-5 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                            Foto Bukti Kondisi Lapangan / Kekeringan <span class="text-rose-500">*</span>
                        </label>
                        <p class="text-xs text-slate-500">
                            Unggah maksimal 3 foto JPG/PNG, masing-masing paling besar 5 MB. Setiap foto dapat diganti atau dihapus.
                        </p>
                    </div>
                    <span class="text-xs font-bold bg-sky-100 text-sky-800 px-2.5 py-0.5 rounded-full" id="fotoCounterBadge">
                        0 / 3 Terpilih
                    </span>
                </div>

                <!-- Dropzone / Picker -->
                <div class="border-2 border-dashed border-slate-300 hover:border-sky-500 rounded-2xl p-5 text-center bg-slate-50 hover:bg-sky-50/40 transition cursor-pointer" onclick="document.getElementById('foto_lapangan').click()">
                    <input 
                        type="file" 
                        name="foto_lapangan[]" 
                        id="foto_lapangan" 
                        accept="image/png, image/jpeg, image/jpg" 
                        multiple 
                        class="hidden" 
                        onchange="handleFotoSelection(event)"
                    >
                    <div class="flex flex-col items-center justify-center space-y-2">
                        <div class="w-12 h-12 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-slate-700">
                            <span class="text-sky-600 underline">Klik untuk memilih foto</span> atau seret foto ke area ini
                        </p>
                        <p class="text-[11px] text-slate-400">Hanya format JPG atau PNG (Maksimal 5 MB per foto)</p>
                    </div>
                </div>

                <!-- Preview Grid for Selected Photos -->
                <div id="fotoPreviewContainer" class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

            <!-- Field 5: Unggah Surat Pengantar Kepala Desa (PBI-007) -->
            <div class="border-t border-slate-100 pt-5 space-y-3">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                        Surat Permohonan Resmi Kepala Desa
                    </label>
                    <p class="text-xs text-slate-500">
                        Unggah pindaian (scan) surat pengantar Kepala Desa berstempel basah (PDF / JPG / PNG, maks 5 MB).
                    </p>
                </div>

                <div class="flex items-center space-x-3">
                    <input 
                        type="file" 
                        name="surat_kepala_desa" 
                        id="surat_kepala_desa" 
                        accept="application/pdf, image/png, image/jpeg, image/jpg"
                        class="text-xs text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 transition"
                        onchange="handleSuratSelection(event)"
                    >
                    <div id="suratPreviewName" class="text-xs text-slate-600 font-medium hidden"></div>
                </div>
            </div>

            <!-- Field 6: Titik Lokasi Pengantaran & Peta Mini (Leaflet.js) -->
            <div class="border-t border-slate-100 pt-5 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                            Lokasi Pengantaran / Titik Drop Point Tandon
                        </label>
                        <p class="text-xs text-slate-500">
                            Tandon komunal tempat armada tangki menuangkan air bantuan.
                        </p>
                    </div>
                    <span class="text-xs text-sky-700 font-mono" id="disp-coord">
                        {{ $desa->latitude }}, {{ $desa->longitude }}
                    </span>
                </div>

                <div>
                    <input 
                        type="text" 
                        name="lokasi_pengantaran" 
                        id="lokasi_pengantaran" 
                        value="{{ old('lokasi_pengantaran', $desa->lokasi_drop_point ?? 'Halaman Balai Desa ' . $desa->nama_desa) }}" 
                        placeholder="Contoh: Halaman Balai Desa Tanalum, RT 02 / RW 03"
                        class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-slate-800 font-medium"
                    >
                </div>

                <!-- Mini Leaflet Map Preview -->
                <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-inner h-52 relative" id="mapPreview"></div>
                <input type="hidden" name="latitude" id="input_latitude" value="{{ $desa->latitude ?? -7.2845100 }}">
                <input type="hidden" name="longitude" id="input_longitude" value="{{ $desa->longitude ?? 109.4321000 }}">
            </div>

            <!-- Field 7: Catatan Tambahan (Opsional) -->
            <div class="border-t border-slate-100 pt-5">
                <label for="catatan_tambahan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Catatan Tambahan Akses Armada (Opsional)
                </label>
                <textarea 
                    name="catatan_tambahan" 
                    id="catatan_tambahan" 
                    rows="2" 
                    placeholder="Contoh: Akses mobil tangki muat melalui gerbang utama balai desa. Jalan aspal lebar 4 meter."
                    class="w-full bg-white border border-slate-300 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                >{{ old('catatan_tambahan') }}</textarea>
            </div>

        </div>

        <!-- TOMBOL AKSI BAWAH (MOCKUP HALAMAN 57) -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
            <a href="{{ route('pengajuan.index') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 font-semibold text-sm text-center transition">
                Kembali
            </a>

            <div class="flex items-center space-x-3 w-full sm:w-auto">
                <!-- Tombol Simpan sebagai Draf -->
                <button 
                    type="button" 
                    onclick="submitAsDraft()" 
                    class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl border border-sky-600 text-sky-700 bg-white hover:bg-sky-50 font-bold text-sm transition shadow-sm"
                >
                    Simpan sebagai Draf
                </button>

                <!-- Tombol Buka Konfirmasi Kirim -->
                <button 
                    type="button" 
                    onclick="openKirimModal()" 
                    @if($hasActiveTicket) 
                        disabled 
                        title="Desa masih memiliki tiket aktif" 
                        class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl bg-slate-300 text-slate-500 font-bold text-sm cursor-not-allowed shadow-none"
                    @elseif(($simulasi->status_kelayakan ?? '') === 'Aman')
                        disabled
                        title="Status kelayakan Aman tidak dapat diajukan"
                        class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl bg-slate-300 text-slate-500 font-bold text-sm cursor-not-allowed shadow-none"
                    @else
                        class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm transition shadow-md shadow-sky-600/30 flex items-center justify-center space-x-2"
                    @endif
                >
                    <span>Kirim Pengajuan</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </div>

    </form>

</div>

<!-- ========================================== -->
<!-- MODAL JENDELA KONFIRMASI KIRIM (MOCKUP HALAMAN 58 & 59) -->
<!-- ========================================== -->
<div id="modalKirim" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl max-w-xl w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all my-8 animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-100 flex items-start space-x-3 bg-slate-50/50">
            <div class="w-10 h-10 rounded-2xl bg-sky-100 text-sky-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900 leading-tight">Kirim pengajuan bantuan?</h3>
                <p class="text-xs text-slate-500 mt-1">Pastikan informasi berikut sudah sesuai sebelum diajukan ke antrean verifikasi petugas.</p>
            </div>
        </div>

        <!-- Modal Body: Ringkasan Pengajuan (Mockup Halaman 58) -->
        <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto text-xs">
            
            <!-- Desa & Status -->
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-sky-600"></span>
                    <span class="font-bold text-sm text-slate-900">{{ $desa->nama_desa }}</span>
                    <span class="text-slate-400">({{ $desa->kecamatan }})</span>
                </div>
                <span class="px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider text-[10px] {{ $statusColor }}">
                    {{ $status }} (Skor {{ number_format($simulasi->skor_ahp_saw ?? 92.4, 1) }})
                </span>
            </div>

            <!-- Stats 3 Kolom -->
            <div class="grid grid-cols-3 gap-2 text-center bg-slate-50 p-3 rounded-xl border border-slate-100">
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">KK Terdampak</span>
                    <span class="text-sm font-extrabold text-slate-800" id="modal-disp-kk">120 KK</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Tanpa Air</span>
                    <span class="text-sm font-extrabold text-slate-800" id="modal-disp-hari">14 Hari</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Jarak Sumber</span>
                    <span class="text-sm font-extrabold text-slate-800" id="modal-disp-jarak">1.200 Meter</span>
                </div>
            </div>

            <!-- Foto Bukti Terlampir -->
            <div>
                <span class="font-bold text-slate-700 block mb-1">Foto Bukti Kondisi Lapangan:</span>
                <div id="modal-disp-foto" class="flex flex-wrap gap-2 text-slate-500">
                    Belum ada foto yang dipilih
                </div>
            </div>

            <!-- Surat Kades -->
            <div>
                <span class="font-bold text-slate-700 block mb-0.5">Surat Pengantar Kepala Desa:</span>
                <p id="modal-disp-surat" class="text-slate-600 font-medium">Tidak ada berkas terlampir</p>
            </div>

            <!-- Alasan -->
            <div>
                <span class="font-bold text-slate-700 block mb-0.5">Alasan Pengajuan:</span>
                <p id="modal-disp-alasan" class="text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100 leading-relaxed"></p>
            </div>

            <!-- Lokasi Drop Point -->
            <div>
                <span class="font-bold text-slate-700 block mb-0.5">Lokasi Pengantaran:</span>
                <p id="modal-disp-lokasi" class="text-slate-600"></p>
            </div>

            <!-- Catatan Tambahan -->
            <div id="modal-wrapper-catatan" class="hidden">
                <span class="font-bold text-slate-700 block mb-0.5">Catatan Tambahan:</span>
                <p id="modal-disp-catatan" class="text-slate-600"></p>
            </div>

            <!-- Warning Box Mockup Hal 58 -->
            <div class="bg-amber-50/80 border border-amber-200 rounded-xl p-3 text-amber-900 text-[11px] flex items-start space-x-2">
                <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Pengajuan akan dimasukkan ke antrean verifikasi petugas BPBD. Pastikan jadwal kebutuhan dan titik drop tandon dalam kondisi siap tampung.</span>
            </div>

        </div>

        <!-- Modal Footer Actions -->
        <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-3">
            <button type="button" onclick="closeKirimModal()" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-200 font-semibold text-xs transition">
                Periksa Kembali
            </button>
            <button type="button" onclick="confirmAndSubmit()" class="px-5 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs shadow-md shadow-sky-600/30 transition flex items-center space-x-1.5">
                <span>Ya, Kirim</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </div>

    </div>
</div>

@push('scripts')
<script>
    // Leaflet Mini Map Preview
    let lat = {{ $desa->latitude ?? -7.2845100 }};
    let lng = {{ $desa->longitude ?? 109.4321000 }};

    const map = L.map('mapPreview').setView([lat, lng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const marker = L.marker([lat, lng], { draggable: true }).addTo(map);
    marker.bindPopup("<b>Titik Tandon Komunal</b><br>{{ $desa->nama_desa }}").openPopup();

    marker.on('dragend', function (e) {
        const position = marker.getLatLng();
        document.getElementById('input_latitude').value = position.lat.toFixed(7);
        document.getElementById('input_longitude').value = position.lng.toFixed(7);
        document.getElementById('disp-coord').innerText = position.lat.toFixed(6) + ', ' + position.lng.toFixed(6);
    });

    // File selection & preview management
    let selectedFiles = [];

    function handleFotoSelection(event) {
        const files = Array.from(event.target.files);
        const container = document.getElementById('fotoPreviewContainer');
        const badge = document.getElementById('fotoCounterBadge');

        // Validasi ekstensi & ukuran
        for (let file of files) {
            const ext = file.name.split('.').pop().toLowerCase();
            if (!['jpg', 'jpeg', 'png'].includes(ext)) {
                alert('Berkas "' + file.name + '" ditolak! Hanya format JPG dan PNG yang diterima.');
                continue;
            }
            if (file.size > 5 * 1024 * 1024) {
                alert('Berkas "' + file.name + '" terlalu besar! Batas maksimal adalah 5 MB.');
                continue;
            }

            if (selectedFiles.length < 3) {
                selectedFiles.push(file);
            } else {
                alert('Maksimal hanya 3 foto bukti yang dapat diunggah.');
                break;
            }
        }

        renderFotoPreviews();
    }

    function renderFotoPreviews() {
        const container = document.getElementById('fotoPreviewContainer');
        const badge = document.getElementById('fotoCounterBadge');
        container.innerHTML = '';

        badge.innerText = selectedFiles.length + ' / 3 Terpilih';

        selectedFiles.forEach((file, index) => {
            const card = document.createElement('div');
            card.className = "relative bg-white border border-slate-200 rounded-xl p-2.5 flex items-center space-x-3 shadow-xs";

            const reader = new FileReader();
            reader.onload = function(e) {
                card.innerHTML = `
                    <img src="${e.target.result}" class="w-12 h-12 rounded-lg object-cover border border-slate-200">
                    <div class="flex-1 min-w-0 text-left">
                        <p class="text-xs font-bold text-slate-800 truncate">${file.name}</p>
                        <p class="text-[10px] text-slate-400">${(file.size / (1024 * 1024)).toFixed(2)} MB</p>
                        <span class="inline-block px-1.5 py-0.2 bg-emerald-100 text-emerald-800 text-[9px] font-bold rounded">Tervalidasi (JPG/PNG)</span>
                    </div>
                    <button type="button" onclick="removeSelectedFoto(${index})" class="text-rose-500 hover:text-rose-700 p-1 rounded-lg hover:bg-rose-50 transition" title="Hapus foto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                `;
            };
            reader.readAsDataURL(file);
            container.appendChild(card);
        });

        // Sync input files transfer
        updateInputFiles();
    }

    function removeSelectedFoto(index) {
        selectedFiles.splice(index, 1);
        renderFotoPreviews();
    }

    function updateInputFiles() {
        const input = document.getElementById('foto_lapangan');
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        input.files = dataTransfer.files;
    }

    function handleSuratSelection(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('suratPreviewName');
        if (file) {
            preview.classList.remove('hidden');
            preview.innerHTML = `
                <span class="inline-flex items-center space-x-1 bg-sky-50 text-sky-800 border border-sky-200 px-2.5 py-1 rounded-lg">
                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-bold">${file.name}</span>
                    <span class="text-[10px] text-slate-500">(${(file.size / (1024*1024)).toFixed(2)} MB)</span>
                </span>
            `;
        }
    }

    // Submission triggers
    function submitAsDraft() {
        document.getElementById('formActionInput').value = 'draf';
        document.getElementById('formPengajuan').submit();
    }

    function openKirimModal() {
        const kk = document.getElementById('jumlah_kk').value;
        const hari = document.getElementById('hari_tanpa_air').value;
        const jarak = document.getElementById('jarak_sumber_air').value;
        const alasan = document.getElementById('alasan_pengajuan').value;
        const lokasi = document.getElementById('lokasi_pengantaran').value;
        const catatan = document.getElementById('catatan_tambahan').value;

        if (!kk || !hari || !jarak || !alasan) {
            alert('Mohon lengkapi semua isian wajib (KK terdampak, hari tanpa air, jarak sumber air, dan alasan pengajuan) terlebih dahulu.');
            return;
        }

        if (selectedFiles.length === 0) {
            alert('Foto bukti kondisi lapangan wajib diunggah minimal 1 foto sebelum mengirimkan permohonan.');
            return;
        }

        // Fill modal preview
        document.getElementById('modal-disp-kk').innerText = kk + ' KK';
        document.getElementById('modal-disp-hari').innerText = hari + ' Hari';
        document.getElementById('modal-disp-jarak').innerText = Number(jarak).toLocaleString('id-ID') + ' Meter';
        document.getElementById('modal-disp-alasan').innerText = alasan;
        document.getElementById('modal-disp-lokasi').innerText = lokasi || '-';

        if (catatan.trim()) {
            document.getElementById('modal-wrapper-catatan').classList.remove('hidden');
            document.getElementById('modal-disp-catatan').innerText = catatan;
        } else {
            document.getElementById('modal-wrapper-catatan').classList.add('hidden');
        }

        // Display files list in modal
        const modalFoto = document.getElementById('modal-disp-foto');
        modalFoto.innerHTML = '';
        selectedFiles.forEach(f => {
            modalFoto.innerHTML += `
                <span class="bg-white border border-slate-200 px-2 py-1 rounded-md text-[11px] font-medium flex items-center space-x-1">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>${f.name}</span>
                </span>
            `;
        });

        const suratInput = document.getElementById('surat_kepala_desa');
        if (suratInput.files && suratInput.files[0]) {
            document.getElementById('modal-disp-surat').innerText = suratInput.files[0].name + ' (Tersedia)';
        } else {
            document.getElementById('modal-disp-surat').innerText = 'Tidak ada surat pengantar tambahan terlampir';
        }

        document.getElementById('modalKirim').classList.remove('hidden');
    }

    function closeKirimModal() {
        document.getElementById('modalKirim').classList.add('hidden');
    }

    function confirmAndSubmit() {
        document.getElementById('formActionInput').value = 'kirim';
        document.getElementById('formPengajuan').submit();
    }
</script>
@endpush
@endsection

