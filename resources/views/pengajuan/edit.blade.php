@extends('layouts.app')

@section('title', 'Ubah Draf Pengajuan | Lingga Air')

@section('content')
<div class="space-y-6">

    <!-- HEADER TITLE -->
    <div class="pb-3 border-b border-slate-200">
        <div class="eyebrow">Layanan Desa</div>
        <h1>Ubah Draf Pengajuan</h1>
        <p class="intro">Perbarui data kebutuhan atau ganti berkas pendukung draf permohonan sebelum dikirim ke antrean BPBD.</p>
    </div>

    <!-- FORM EDIT DRAF -->
    <form id="formEditDraf" action="{{ route('pengajuan.update', $pengajuan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <input type="hidden" name="action" id="editActionInput" value="draf">

        <div class="card p-6 sm:p-8 space-y-6">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-2">
                    <span class="text-lg leading-none" style="color: #086288;">▤</span>
                    <h2 class="text-base font-bold text-slate-900 m-0">Informasi Draf Pengajuan</h2>
                </div>
                <span class="status-badge status-waspada text-[10px]">
                    Mode Ubah Draf
                </span>
            </div>

            <!-- Desa Pemohon (Read-only) -->
            <div>
                <label for="desa_pemohon_edit">Desa Pemohon</label>
                <div class="input-wrap">
                    <input 
                        id="desa_pemohon_edit"
                        type="text" 
                        value="{{ $desa->nama_desa }} (Kecamatan {{ $desa->kecamatan }}) - Kode: {{ $desa->kode_desa }}" 
                        disabled 
                    >
                    <span class="unit">🔒</span>
                </div>
            </div>

            <!-- Grid 3 Kolom Kebutuhan (Terkunci dari Cek Kelayakan) -->
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
                                value="{{ old('jumlah_kk', $pengajuan->jumlah_kk) }}" 
                                readonly 
                                class="font-bold text-slate-900"
                            >
                            <input type="hidden" name="jumlah_kk" id="jumlah_kk" value="{{ old('jumlah_kk', $pengajuan->jumlah_kk) }}">
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
                                value="{{ old('hari_tanpa_air', $pengajuan->hari_tanpa_air) }}" 
                                readonly 
                                class="font-bold text-slate-900"
                            >
                            <input type="hidden" name="hari_tanpa_air" id="hari_tanpa_air" value="{{ old('hari_tanpa_air', $pengajuan->hari_tanpa_air) }}">
                            <span class="unit">Hari 🔒</span>
                        </div>
                        <p class="hint">Durasi ketiadaan suplai air hasil simulasi</p>
                    </div>

                    <!-- Jarak Sumber Air (Readonly / Locked) -->
                    <div>
                        <label for="jarak_sumber_air_display">Jarak Sumber Air</label>
                        <div class="input-wrap">
                            <input 
                                type="text" 
                                id="jarak_sumber_air_display" 
                                value="{{ old('jarak_sumber_air', $pengajuan->jarak_sumber_air) }}" 
                                readonly 
                                class="font-bold text-slate-900"
                            >
                            <input type="hidden" name="jarak_sumber_air" id="jarak_sumber_air" value="{{ old('jarak_sumber_air', $pengajuan->jarak_sumber_air) }}">
                            <span class="unit">Meter 🔒</span>
                        </div>
                        <p class="hint">Jarak tempuh warga ke sumber alternatif</p>
                    </div>
                </div>

                <div class="border border-slate-200 rounded-xl p-3 bg-slate-50 text-xs text-slate-600 flex items-start gap-2">
                    <span class="font-bold text-slate-700">ℹ</span>
                    <p class="m-0 leading-relaxed">
                        Ketiga nilai di atas (Jumlah KK Terdampak, Lama Hari Tanpa Air, dan Jarak Sumber Air Alternatif) terikat dengan hasil Cek Kelayakan Mandiri yang tersimpan di sistem dan <strong>tidak dapat diubah di form draf</strong> demi menjaga objektivitas penilaian SPK.
                    </p>
                </div>
            </div>

            <!-- Alasan Pengajuan -->
            <div class="pt-2">
                <label for="alasan_pengajuan">
                    Alasan Pengajuan &amp; Kondisi Sumber Air
                </label>
                <textarea 
                    name="alasan_pengajuan" 
                    id="alasan_pengajuan" 
                    rows="3" 
                    required 
                >{{ old('alasan_pengajuan', $pengajuan->alasan_pengajuan) }}</textarea>
            </div>

            <!-- Foto Bukti yang Sudah Ada -->
            <div class="border-t border-slate-100 pt-5 space-y-3">
                <label class="mb-0">
                    Foto Bukti Tersimpan di Draf Ini ({{ $pengajuan->fotoKondisi->count() }} Foto)
                </label>
                
                @if($pengajuan->fotoKondisi->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach($pengajuan->fotoKondisi as $foto)
                            <div class="card p-2.5 flex items-center gap-3" id="foto-item-{{ $foto->id }}">
                                <img src="{{ $foto->url }}" class="w-12 h-12 rounded-lg object-cover border border-slate-200">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-slate-800 truncate m-0">{{ $foto->nama_file }}</p>
                                    <p class="text-[10px] text-slate-400 m-0">{{ $foto->humanFileSize() }}</p>
                                </div>
                                <button type="button" onclick="hapusBerkasAjax({{ $foto->id }})" class="text-rose-500 hover:text-rose-700 p-1 rounded hover:bg-rose-50 transition" title="Hapus foto dari draf">
                                    ✕
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="hint italic">Belum ada foto yang diunggah.</p>
                @endif

                <!-- Tambah Foto Baru -->
                <div class="pt-2">
                    <label class="mb-1 text-xs">Tambah Foto Lapangan Baru (Maks 3 foto total, JPG/PNG maks 5MB):</label>
                    <input type="file" name="foto_lapangan[]" accept="image/png, image/jpeg, image/jpg" multiple class="text-xs text-slate-600 file:mr-4 file:py-2 file:px-3 file:rounded-lg file:border file:border-slate-300 file:text-xs file:font-bold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100">
                </div>
            </div>

            <!-- Surat Pengantar Kades -->
            <div class="border-t border-slate-100 pt-5 space-y-2">
                <label class="mb-0">
                    Surat Permohonan Resmi Kepala Desa
                </label>
                @if($pengajuan->suratKepalaDesa)
                    <div class="text-xs text-slate-700 flex items-center gap-2 border border-sky-200 bg-sky-50 p-2.5 rounded-xl">
                        <span>📄</span>
                        <span>Saat ini terlampir: <strong>{{ $pengajuan->suratKepalaDesa->nama_file }}</strong> ({{ $pengajuan->suratKepalaDesa->humanFileSize() }})</span>
                    </div>
                @endif
                <p class="hint">Pilih berkas baru jika ingin mengganti surat pengantar (PDF/JPG/PNG maks 5MB):</p>
                <input type="file" name="surat_kepala_desa" accept="application/pdf, image/png, image/jpeg, image/jpg" class="text-xs text-slate-600 file:mr-4 file:py-2 file:px-3 file:rounded-lg file:border file:border-slate-300 file:text-xs file:font-bold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100">
            </div>

            <!-- Lokasi Pengantaran -->
            <div class="border-t border-slate-100 pt-5 space-y-3">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <label class="mb-0">
                        Lokasi Pengantaran / Titik Drop Point
                    </label>
                    <a 
                        id="btnGmapsEdit"
                        href="https://www.google.com/maps?q={{ $pengajuan->latitude ?? $desa->latitude ?? -7.2845100 }},{{ $pengajuan->longitude ?? $desa->longitude ?? 109.4321000 }}" 
                        target="_blank" 
                        rel="noopener noreferrer" 
                        class="text-[11px] font-bold text-[#086288] hover:underline inline-flex items-center gap-1"
                    >
                        <span>🗺</span>
                        <span>Buka Google Maps ↗</span>
                    </a>
                </div>
                <input 
                    type="text" 
                    name="lokasi_pengantaran" 
                    id="lokasi_pengantaran" 
                    value="{{ old('lokasi_pengantaran', $pengajuan->lokasi_pengantaran) }}" 
                    autocomplete="off"
                >
                <div id="mapEdit" class="h-52 rounded-xl border border-slate-300 overflow-hidden" style="position: relative; z-index: 1; isolation: isolate;"></div>
                <input type="hidden" name="latitude" id="edit_latitude" value="{{ $pengajuan->latitude ?? $desa->latitude }}">
                <input type="hidden" name="longitude" id="edit_longitude" value="{{ $pengajuan->longitude ?? $desa->longitude }}">
            </div>

            <!-- Catatan Tambahan -->
            <div class="border-t border-slate-100 pt-5">
                <label for="catatan_tambahan">
                    Catatan Tambahan Akses Armada (Opsional)
                </label>
                <textarea 
                    name="catatan_tambahan" 
                    id="catatan_tambahan" 
                    rows="2" 
                >{{ old('catatan_tambahan', $pengajuan->catatan_tambahan) }}</textarea>
            </div>

        </div>

        <!-- Tombol Aksi -->
        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('pengajuan.show', $pengajuan->id) }}" class="reset">
                ← Batal
            </a>

            <div class="flex items-center gap-3">
                <button type="submit" onclick="document.getElementById('editActionInput').value = 'draf'" class="submit font-bold">
                    Simpan Perubahan Draf
                </button>
            </div>
        </div>

    </form>

</div>

@push('scripts')
<script>
    const elLat = {{ $pengajuan->latitude ?? $desa->latitude ?? -7.2845100 }};
    const elLng = {{ $pengajuan->longitude ?? $desa->longitude ?? 109.4321000 }};
    const editMap = L.map('mapEdit', { zoomControl: true }).setView([elLat, elLng], 15);

    // Google Maps Layer 1: Roadmap (Peta Jalan Asli)
    const googleRoadmap = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '&copy; Google Maps'
    }).addTo(editMap);

    // Google Maps Layer 2: Hybrid (Satelit)
    const googleSatellite = L.tileLayer('https://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '&copy; Google Maps Satelit'
    });

    L.control.layers({
        "Google Maps (Peta Jalan)": googleRoadmap,
        "Google Maps (Satelit)": googleSatellite
    }, null, { position: 'topright' }).addTo(editMap);

    const editMarker = L.marker([elLat, elLng], { draggable: true, title: "Geser pin untuk ubah lokasi" }).addTo(editMap);
    editMarker.bindPopup("<b>Titik Tandon Drop Point</b><br><span style='font-size:11px; color:#64748b;'>Geser pin atau klik peta untuk mengubah titik koordinat</span>").openPopup();

    function updateEditCoordinates(pos) {
        const pLat = pos.lat.toFixed(7);
        const pLng = pos.lng.toFixed(7);
        document.getElementById('edit_latitude').value = pLat;
        document.getElementById('edit_longitude').value = pLng;
        const btn = document.getElementById('btnGmapsEdit');
        if (btn) {
            btn.href = `https://www.google.com/maps?q=${pLat},${pLng}`;
        }
    }

    editMarker.on('dragend', function (e) {
        updateEditCoordinates(editMarker.getLatLng());
    });

    editMap.on('click', function (e) {
        editMarker.setLatLng(e.latlng);
        updateEditCoordinates(e.latlng);
        editMarker.openPopup();
    });

    function hapusBerkasAjax(id) {
        if (!confirm('Hapus berkas ini dari draf?')) return;
        fetch('/berkas/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(r => r.json()).then(res => {
            if (res.success) {
                const el = document.getElementById('foto-item-' + id);
                if (el) el.remove();
            }
        });
    }
</script>
@endpush
@endsection
