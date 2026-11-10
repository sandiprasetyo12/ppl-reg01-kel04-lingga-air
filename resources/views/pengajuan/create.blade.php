@extends('layouts.app')

@section('title', 'Pengajuan Bantuan Air Bersih | Lingga Air')

@section('content')
<div class="space-y-6">

    <!-- HEADER TITLE -->
    <div class="pb-3 border-b border-slate-200">
        <div class="eyebrow">Layanan Desa</div>
        <h1>Pengajuan Bantuan Air Bersih</h1>
        <p class="intro">Lengkapi formulir detail kebutuhan air berikut untuk desa Anda.</p>
    </div>

    <!-- PBI-009: Peringatan Tiket Aktif jika desa sudah punya tiket berjalan -->
    @if($hasActiveTicket && $activeTicket)
        <div class="card p-4 border-amber-300 bg-amber-50 text-amber-900 flex items-start gap-3">
            <span class="text-xl leading-none font-bold text-amber-700">⚠</span>
            <div class="text-xs">
                <p class="font-bold text-sm text-amber-950">Desa Anda Memiliki Tiket Bantuan Aktif</p>
                <p class="mt-1 text-slate-700">
                    Permohonan aktif dengan Nomor Tiket <strong>{{ $activeTicket->nomor_tiket }}</strong> saat ini berstatus <strong>{{ $activeTicket->status }}</strong>.
                    Sistem membatasi satu desa hanya boleh memiliki 1 tiket aktif. Anda tetap dapat menyimpan isian berikut sebagai <strong>Draf</strong>, namun tombol kirim ke antrean admin akan dikunci hingga tiket aktif diselesaikan.
                </p>
            </div>
        </div>
    @endif

    <!-- PERINGATAN BILA BELUM CEK KELAYAKAN -->
    @if(!$simulasi)
        <div class="card p-4 border-amber-300 bg-amber-50 text-amber-900 flex items-start gap-3">
            <span class="text-xl leading-none font-bold text-amber-700">ℹ</span>
            <div class="text-xs">
                <p class="font-bold text-sm text-amber-950">Data Cek Kelayakan Belum Diisi</p>
                <p class="mt-1 text-slate-700">
                    Formulir pengajuan bantuan air membutuhkan data dari hasil <strong>Cek Kelayakan Mandiri (F-02)</strong>. Nilai parameter kebutuhan akan terkunci dan terisi otomatis setelah Anda menjalankan simulasi kelayakan untuk desa Anda.
                </p>
                <a href="{{ route('cek-kelayakan.index') }}" class="submit mt-2 text-xs py-1.5 px-3 inline-flex">
                    Mulai Cek Kelayakan Sekarang →
                </a>
            </div>
        </div>
    @endif

    <!-- RINGKASAN HASIL CEK KELAYAKAN (SESUAI DESAIN HASIL CEK KELAYAKAN MANDIRI) -->
    <div class="card p-5" style="border-color: #cae4ef; background: #f0f8fb;">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
            
            <!-- Skor Prioritas AHP-SAW -->
            <div class="flex items-center gap-4">
                <div class="bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-center shadow-xs">
                    <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-500">SKOR PRIORITAS</span>
                    <span class="text-3xl font-extrabold tracking-tight" style="color: #086288; font-variant-numeric: tabular-nums;" id="disp-skor">
                        {{ $simulasi ? number_format($simulasi->skor_ahp_saw, 1) : '-' }}
                    </span>
                    <span class="text-xs font-semibold text-slate-400">/ 100</span>
                </div>

                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-600">Hasil Cek Kelayakan:</span>
                        @php
                            $status = $simulasi ? $simulasi->status_kelayakan : 'Belum Cek';
                            $statusClass = match(strtolower($status)) {
                                'kritis' => 'status-kritis',
                                'waspada' => 'status-waspada',
                                'aman' => 'status-aman',
                                default => 'status-draf',
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}" id="disp-status">
                            {{ $status }}
                        </span>
                    </div>
                    <p class="text-xs font-medium text-slate-700 mt-1.5" id="disp-rekomendasi">
                        {{ $simulasi ? ($simulasi->rekomendasi ?? 'Segera ajukan bantuan pasokan air bersih untuk kebutuhan warga terdampak.') : 'Data Cek Kelayakan belum tersedia. Silakan jalankan Cek Kelayakan Mandiri terlebih dahulu.' }}
                    </p>
                </div>
            </div>

            <!-- Catatan Kelayakan -->
            <div class="text-left md:text-right md:border-l md:border-slate-200 md:pl-5">
                <span class="text-[11px] text-slate-500 block">Metode SPK Kemanusiaan:</span>
                <span class="text-xs font-bold text-slate-800">AHP - Simple Additive Weighting (SAW)</span>
                <div class="mt-1">
                    <a href="{{ route('cek-kelayakan.index') }}" class="text-xs font-bold hover:underline" style="color: #086288;">
                        Cek Kelayakan Ulang &rarr;
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- FORMULIR PENGAJUAN -->
    <form id="formPengajuan" action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <input type="hidden" name="riwayat_id" value="{{ $riwayat->id ?? ($simulasi->id ?? '') }}">
        <input type="hidden" name="simulasi_id" value="{{ $simulasi->id ?? '' }}">
        <input type="hidden" name="action" id="formActionInput" value="draf">

        <div class="card p-6 sm:p-8 space-y-6">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-2">
                    <span class="text-lg leading-none" style="color: #086288;">▤</span>
                    <h2 class="text-base font-bold text-slate-900 m-0">Informasi Pengajuan</h2>
                </div>
                <span class="text-xs font-semibold px-3 py-1 rounded-full border border-sky-200 bg-sky-50 text-sky-800 flex items-center gap-1.5">
                    <span>✓</span>
                    <span>Data tersinkron dari Cek Kelayakan</span>
                </span>
            </div>

            <!-- Field 1: Desa Pemohon (Terkunci & Otomatis) -->
            <div>
                <label for="desa_pemohon_display">Desa Pemohon</label>
                <div class="input-wrap">
                    <input 
                        id="desa_pemohon_display"
                        type="text" 
                        value="{{ $desa->nama_desa }} (Kecamatan {{ $desa->kecamatan }}) - Kode: {{ $desa->kode_desa }}" 
                        disabled 
                    >
                    <span class="unit">🔒</span>
                </div>
                <p class="hint">Data desa terkunci otomatis berdasarkan akun aparatur desa yang terdaftar resmi.</p>
            </div>

            <!-- Field 2: Parameter Kebutuhan (Terkunci dari Cek Kelayakan) -->
            <div class="space-y-3 pt-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                        <span>🔒</span>
                        <span>Parameter Kebutuhan (Terkunci dari Cek Kelayakan F-02)</span>
                    </span>
                    <span class="text-[11px] font-bold text-amber-800 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-full">
                        Nilai Terkunci Otomatis
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <!-- KK Terdampak (Readonly / Locked) -->
                    <div>
                        <label for="jumlah_kk_display">Jumlah KK Terdampak</label>
                        <div class="input-wrap">
                            <input 
                                type="text" 
                                id="jumlah_kk_display" 
                                value="{{ $simulasi?->kk_terdampak ?? '' }}" 
                                placeholder="Belum ada data"
                                readonly 
                                class="font-bold text-slate-900"
                            >
                            <input type="hidden" name="jumlah_kk" id="jumlah_kk" value="{{ $simulasi?->kk_terdampak ?? '' }}">
                            <span class="unit">KK 🔒</span>
                        </div>
                        <p class="hint">Tersinkron dari Cek Kelayakan (total {{ $desa->jumlah_kk }} KK)</p>
                    </div>

                    <!-- Hari Tanpa Air (Readonly / Locked) -->
                    <div>
                        <label for="hari_tanpa_air_display">Lama Hari Tanpa Air</label>
                        <div class="input-wrap">
                            <input 
                                type="text" 
                                id="hari_tanpa_air_display" 
                                value="{{ $simulasi?->hari_tanpa_air ?? '' }}" 
                                placeholder="Belum ada data"
                                readonly 
                                class="font-bold text-slate-900"
                            >
                            <input type="hidden" name="hari_tanpa_air" id="hari_tanpa_air" value="{{ $simulasi?->hari_tanpa_air ?? '' }}">
                            <span class="unit">Hari 🔒</span>
                        </div>
                        <p class="hint">Durasi ketiadaan suplai air hasil simulasi</p>
                    </div>

                    <!-- Jarak Sumber Air (Readonly / Locked) -->
                    <div>
                        <label for="jarak_sumber_air_display">Jarak Sumber Air Alternatif</label>
                        <div class="input-wrap">
                            <input 
                                type="text" 
                                id="jarak_sumber_air_display" 
                                value="{{ $simulasi?->jarak_sumber_air ?? '' }}" 
                                placeholder="Belum ada data"
                                readonly 
                                class="font-bold text-slate-900"
                            >
                            <input type="hidden" name="jarak_sumber_air" id="jarak_sumber_air" value="{{ $simulasi?->jarak_sumber_air ?? '' }}">
                            <span class="unit">Meter 🔒</span>
                        </div>
                        <p class="hint">Jarak tempuh warga ke sumber alternatif</p>
                    </div>

                </div>

                <div class="border border-slate-200 rounded-xl p-3 bg-slate-50 text-xs text-slate-600 flex items-start gap-2">
                    <span class="font-bold text-slate-700">ℹ</span>
                    <p class="m-0 leading-relaxed">
                        Ketiga nilai di atas (Jumlah KK Terdampak, Lama Hari Tanpa Air, dan Jarak Sumber Air Alternatif) terisi otomatis dari hasil perhitungan Cek Kelayakan Mandiri dan <strong>tidak dapat diubah secara manual</strong> demi menjaga integritas serta objektivitas kalkulasi prioritas SPK (AHP-SAW).
                    </p>
                </div>
            </div>

            <!-- Field 3: Alasan Pengajuan -->
            <div class="pt-2">
                <label for="alasan_pengajuan">
                    Alasan Pengajuan &amp; Kondisi Sumber Air
                </label>
                <textarea 
                    name="alasan_pengajuan" 
                    id="alasan_pengajuan" 
                    rows="3" 
                    required 
                    placeholder="Contoh: Sumber air sumur gali dan mata air desa mengering total selama dua minggu terakhir akibat musim kemarau panjang. Warga terpaksa membeli air pikulan..." 
                >{{ old('alasan_pengajuan') }}</textarea>
                <p class="hint">Jelaskan kondisi riil sumur, embung, atau aliran air pipa warga saat ini.</p>
            </div>

            <!-- Field 4: Unggah Foto Bukti Kondisi Lapangan (PBI-007) -->
            <div class="border-t border-slate-100 pt-5 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="mb-0">
                            Foto Bukti Kondisi Lapangan / Kekeringan
                        </label>
                        <p class="hint mt-0.5">
                            Unggah maksimal 3 foto JPG/PNG, masing-masing paling besar 5 MB. Setiap foto dapat diganti atau dihapus.
                        </p>
                    </div>
                    <span class="text-xs font-bold border border-sky-200 bg-sky-50 text-sky-800 px-3 py-1 rounded-full" id="fotoCounterBadge">
                        0 / 3 Terpilih
                    </span>
                </div>

                <!-- Dropzone / Picker -->
                <div class="border-2 border-dashed border-slate-300 hover:border-sky-500 rounded-xl p-5 text-center bg-slate-50 hover:bg-sky-50/30 transition cursor-pointer" onclick="document.getElementById('foto_lapangan').click()">
                    <input 
                        type="file" 
                        name="foto_lapangan[]" 
                        id="foto_lapangan" 
                        accept="image/png, image/jpeg, image/jpg" 
                        multiple 
                        class="hidden" 
                        onchange="handleFotoSelection(event)"
                    >
                    <div class="flex flex-col items-center justify-center gap-1.5">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg" style="background: #eef4f8; color: #086288;">
                            📷
                        </div>
                        <p class="text-xs font-bold text-slate-800 m-0">
                            <span class="underline" style="color: #086288;">Klik untuk memilih foto</span> atau seret foto ke area ini
                        </p>
                        <p class="hint m-0">Hanya format JPG atau PNG (Maksimal 5 MB per foto)</p>
                    </div>
                </div>

                <!-- Preview Grid for Selected Photos -->
                <div id="fotoPreviewContainer" class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

            <!-- Field 5: Unggah Surat Pengantar Kepala Desa (PBI-007) -->
            <div class="border-t border-slate-100 pt-5 space-y-2">
                <div>
                    <label class="mb-0">
                        Surat Permohonan Resmi Kepala Desa
                    </label>
                    <p class="hint mt-0.5">
                        Unggah pindaian (scan) surat pengantar Kepala Desa berstempel basah (PDF / JPG / PNG, maks 5 MB).
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <input 
                        type="file" 
                        name="surat_kepala_desa" 
                        id="surat_kepala_desa" 
                        accept="application/pdf, image/png, image/jpeg, image/jpg"
                        class="text-xs text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border file:border-slate-300 file:text-xs file:font-bold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100"
                        onchange="handleSuratSelection(event)"
                    >
                    <div id="suratPreviewName" class="text-xs text-slate-600 font-medium hidden"></div>
                </div>
            </div>

            <!-- Field 6: Titik Lokasi Pengantaran & Peta Mini (Leaflet.js) -->
            <div class="border-t border-slate-100 pt-5 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="mb-0">
                            Lokasi Pengantaran / Titik Drop Point Tandon
                        </label>
                        <p class="hint mt-0.5">
                            Tandon komunal tempat armada tangki menuangkan air bantuan.
                        </p>
                    </div>
                    <span class="text-xs font-mono font-bold" style="color: #086288;" id="disp-coord">
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
                    >
                </div>

                <!-- Mini Leaflet Map Preview -->
                <div class="rounded-xl overflow-hidden border border-slate-300 shadow-inner h-48 relative" id="mapPreview"></div>
                <input type="hidden" name="latitude" id="input_latitude" value="{{ $desa->latitude ?? -7.2845100 }}">
                <input type="hidden" name="longitude" id="input_longitude" value="{{ $desa->longitude ?? 109.4321000 }}">
            </div>

            <!-- Field 7: Catatan Tambahan (Opsional) -->
            <div class="border-t border-slate-100 pt-5">
                <label for="catatan_tambahan">
                    Catatan Tambahan Akses Armada (Opsional)
                </label>
                <textarea 
                    name="catatan_tambahan" 
                    id="catatan_tambahan" 
                    rows="2" 
                    placeholder="Contoh: Akses mobil tangki muat melalui gerbang utama balai desa. Jalan aspal lebar 4 meter."
                >{{ old('catatan_tambahan') }}</textarea>
            </div>

        </div>

        <!-- TOMBOL AKSI BAWAH -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
            <a href="{{ route('pengajuan.index') }}" class="reset w-full sm:w-auto">
                ← Kembali
            </a>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <!-- Simpan sebagai Draf -->
                <button 
                    type="button" 
                    onclick="submitAsDraft()" 
                    @if(!$simulasi) 
                        disabled 
                        title="Silakan lakukan Cek Kelayakan terlebih dahulu" 
                        class="reset flex-1 sm:flex-none font-bold opacity-50 cursor-not-allowed"
                    @else
                        class="reset flex-1 sm:flex-none font-bold"
                    @endif
                >
                    Simpan sebagai Draf
                </button>

                <!-- Tombol Kirim Pengajuan -->
                <button 
                    type="button" 
                    onclick="openKirimModal()" 
                    @if(!$simulasi)
                        disabled
                        title="Silakan lakukan Cek Kelayakan terlebih dahulu"
                        class="submit flex-1 sm:flex-none opacity-50 cursor-not-allowed"
                    @elseif($hasActiveTicket) 
                        disabled 
                        title="Desa masih memiliki tiket aktif" 
                        class="submit flex-1 sm:flex-none opacity-50 cursor-not-allowed"
                    @elseif(($simulasi->status_kelayakan ?? '') === 'Aman')
                        disabled
                        title="Status kelayakan Aman tidak dapat diajukan"
                        class="submit flex-1 sm:flex-none opacity-50 cursor-not-allowed"
                    @else
                        class="submit flex-1 sm:flex-none"
                    @endif
                >
                    Kirim Pengajuan →
                </button>
            </div>
        </div>

    </form>

</div>

<!-- Modal Konfirmasi Kirim Pengajuan -->
<div id="modalKirim" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all my-8 animate-in fade-in duration-200">
        
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-100 flex items-start gap-3" style="background: #fafcfe;">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl shrink-0" style="background: #eef4f8; color: #086288;">
                ℹ
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900 m-0">Kirim pengajuan bantuan?</h3>
                <p class="text-xs text-slate-500 mt-1">Pastikan informasi berikut sudah sesuai sebelum diajukan ke antrean verifikasi petugas.</p>
            </div>
        </div>

        <!-- Modal Body: Ringkasan Pengajuan -->
        <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto text-xs">
            
            <!-- Desa & Status -->
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full" style="background: #086288;"></span>
                    <span class="font-bold text-sm text-slate-900">{{ $desa->nama_desa }}</span>
                    <span class="text-slate-400">({{ $desa->kecamatan }})</span>
                </div>
                <span class="status-badge {{ $statusClass }} text-[10px]">
                    {{ $status }} (Skor {{ $simulasi ? number_format($simulasi->skor_ahp_saw, 1) : '-' }})
                </span>
            </div>

            <!-- Stats 3 Kolom -->
            <div class="grid grid-cols-3 gap-2 text-center bg-slate-50 p-3 rounded-xl border border-slate-200">
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">KK Terdampak</span>
                    <span class="text-sm font-extrabold text-slate-800" id="modal-disp-kk">-</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Tanpa Air</span>
                    <span class="text-sm font-extrabold text-slate-800" id="modal-disp-hari">-</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Jarak Sumber</span>
                    <span class="text-sm font-extrabold text-slate-800" id="modal-disp-jarak">-</span>
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
                <p id="modal-disp-surat" class="text-slate-600 font-medium m-0">Tidak ada berkas terlampir</p>
            </div>

            <!-- Alasan -->
            <div>
                <span class="font-bold text-slate-700 block mb-0.5">Alasan Pengajuan:</span>
                <p id="modal-disp-alasan" class="text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100 leading-relaxed m-0"></p>
            </div>

            <!-- Lokasi Drop Point -->
            <div>
                <span class="font-bold text-slate-700 block mb-0.5">Lokasi Pengantaran:</span>
                <p id="modal-disp-lokasi" class="text-slate-600 m-0"></p>
            </div>

            <!-- Catatan Tambahan -->
            <div id="modal-wrapper-catatan" class="hidden">
                <span class="font-bold text-slate-700 block mb-0.5">Catatan Tambahan:</span>
                <p id="modal-disp-catatan" class="text-slate-600 m-0"></p>
            </div>

            <div class="border border-amber-200 rounded-xl p-3 bg-amber-50 text-amber-900 text-xs flex items-start gap-2">
                <span>⚠</span>
                <span>Pengajuan akan dimasukkan ke antrean verifikasi petugas BPBD. Pastikan jadwal kebutuhan dan titik drop tandon dalam kondisi siap tampung.</span>
            </div>

        </div>

        <!-- Modal Footer Actions -->
        <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
            <button type="button" onclick="closeKirimModal()" class="reset">
                Periksa Kembali
            </button>
            <button type="button" onclick="confirmAndSubmit()" class="submit font-bold">
                Ya, Kirim Pengajuan →
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
            card.className = "card p-2.5 flex items-center gap-3";

            const reader = new FileReader();
            reader.onload = function(e) {
                card.innerHTML = `
                    <img src="${e.target.result}" class="w-12 h-12 rounded-lg object-cover border border-slate-200">
                    <div class="flex-1 min-w-0 text-left">
                        <p class="text-xs font-bold text-slate-800 truncate m-0">${file.name}</p>
                        <p class="text-[10px] text-slate-400 m-0">${(file.size / (1024 * 1024)).toFixed(2)} MB</p>
                        <span class="inline-block px-1.5 py-0.5 bg-emerald-100 text-emerald-800 text-[9px] font-bold rounded">Tervalidasi (JPG/PNG)</span>
                    </div>
                    <button type="button" onclick="removeSelectedFoto(${index})" class="text-rose-500 hover:text-rose-700 p-1 rounded hover:bg-rose-50 transition" title="Hapus foto">
                        ✕
                    </button>
                `;
            };
            reader.readAsDataURL(file);
            container.appendChild(card);
        });

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
                <span class="inline-flex items-center gap-1.5 border border-sky-200 bg-sky-50 text-sky-800 px-3 py-1 rounded-lg">
                    📄 <span class="font-bold">${file.name}</span>
                    <span class="text-[10px] text-slate-500">(${(file.size / (1024*1024)).toFixed(2)} MB)</span>
                </span>
            `;
        }
    }

    // Submission triggers
    function submitAsDraft() {
        @if(!$simulasi)
            alert('Silakan lakukan Cek Kelayakan Mandiri terlebih dahulu sebelum menyimpan draf.');
            return;
        @endif
        document.getElementById('formActionInput').value = 'draf';
        document.getElementById('formPengajuan').submit();
    }

    function openKirimModal() {
        @if(!$simulasi)
            alert('Silakan lakukan Cek Kelayakan Mandiri terlebih dahulu sebelum mengirim pengajuan.');
            return;
        @endif
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

        const modalFoto = document.getElementById('modal-disp-foto');
        modalFoto.innerHTML = '';
        selectedFiles.forEach(f => {
            modalFoto.innerHTML += `
                <span class="bg-white border border-slate-200 px-2.5 py-1 rounded-md text-[11px] font-semibold flex items-center gap-1">
                    ✓ <span>${f.name}</span>
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
