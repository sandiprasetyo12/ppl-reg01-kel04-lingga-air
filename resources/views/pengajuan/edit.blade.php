@extends('layouts.app')

@section('title', 'Ubah Draf Pengajuan - Lingga Air')

@section('content')
<div class="space-y-6">

    <!-- BREADCRUMB -->
    <nav class="flex text-xs text-slate-500 space-x-2 items-center">
        <a href="{{ route('pengajuan.index') }}" class="hover:text-sky-600 transition">Beranda</a>
        <span>/</span>
        <a href="{{ route('pengajuan.index') }}" class="hover:text-sky-600 transition">Draf</a>
        <span>/</span>
        <span class="text-slate-800 font-semibold">Ubah Draf #{{ str_pad($pengajuan->id, 4, '0', STR_PAD_LEFT) }}</span>
    </nav>

    <!-- HEADER TITLE -->
    <div class="flex items-center justify-between border-b border-slate-200/80 pb-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Ubah Draf Pengajuan</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui data kebutuhan atau ganti berkas pendukung sebelum dikirim.</p>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
            Mode Ubah Draf
        </span>
    </div>

    <!-- FORM EDIT DRAF -->
    <form id="formEditDraf" action="{{ route('pengajuan.update', $pengajuan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <input type="hidden" name="action" id="editActionInput" value="draf">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-8 space-y-6">
            
            <!-- Desa Pemohon (Read-only) -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Desa Pemohon
                </label>
                <input 
                    type="text" 
                    value="{{ $desa->nama_desa }} (Kecamatan {{ $desa->kecamatan }}) - Kode: {{ $desa->kode_desa }}" 
                    disabled 
                    class="w-full bg-slate-100/80 border border-slate-300 text-slate-600 text-sm rounded-xl px-4 py-2.5 cursor-not-allowed font-medium"
                >
            </div>

            <!-- Grid 3 Kolom Kebutuhan (Terkunci dari Cek Kelayakan) -->
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
                                value="{{ old('jumlah_kk', $pengajuan->jumlah_kk) }}" 
                                readonly 
                                class="w-full bg-slate-100/90 border border-slate-300 rounded-xl pl-4 pr-16 py-2.5 text-sm cursor-not-allowed font-extrabold text-slate-800 select-none shadow-inner"
                            >
                            <input type="hidden" name="jumlah_kk" id="jumlah_kk" value="{{ old('jumlah_kk', $pengajuan->jumlah_kk) }}">
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
                                value="{{ old('hari_tanpa_air', $pengajuan->hari_tanpa_air) }}" 
                                readonly 
                                class="w-full bg-slate-100/90 border border-slate-300 rounded-xl pl-4 pr-16 py-2.5 text-sm cursor-not-allowed font-extrabold text-slate-800 select-none shadow-inner"
                            >
                            <input type="hidden" name="hari_tanpa_air" id="hari_tanpa_air" value="{{ old('hari_tanpa_air', $pengajuan->hari_tanpa_air) }}">
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
                                value="{{ old('jarak_sumber_air', $pengajuan->jarak_sumber_air) }}" 
                                readonly 
                                class="w-full bg-slate-100/90 border border-slate-300 rounded-xl pl-4 pr-16 py-2.5 text-sm cursor-not-allowed font-extrabold text-slate-800 select-none shadow-inner"
                            >
                            <input type="hidden" name="jarak_sumber_air" id="jarak_sumber_air" value="{{ old('jarak_sumber_air', $pengajuan->jarak_sumber_air) }}">
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
                            Ketiga nilai di atas (Jumlah KK Terdampak, Lama Hari Tanpa Air, dan Jarak Sumber Air Alternatif) terikat dengan hasil Cek Kelayakan Mandiri yang tersimpan di sistem dan <strong>tidak dapat diubah di form draf</strong> demi menjaga objektivitas penilaian SPK.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Alasan Pengajuan -->
            <div>
                <label for="alasan_pengajuan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Alasan Pengajuan & Kondisi Sumber Air <span class="text-rose-500">*</span>
                </label>
                <textarea 
                    name="alasan_pengajuan" 
                    id="alasan_pengajuan" 
                    rows="3" 
                    required 
                    class="w-full bg-white border border-slate-300 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-sky-500"
                >{{ old('alasan_pengajuan', $pengajuan->alasan_pengajuan) }}</textarea>
            </div>

            <!-- Foto Bukti yang Sudah Ada -->
            <div class="border-t border-slate-100 pt-5 space-y-3">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                    Foto Bukti Tersimpan di Draf Ini ({{ $pengajuan->fotoKondisi->count() }} Foto)
                </label>
                
                @if($pengajuan->fotoKondisi->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach($pengajuan->fotoKondisi as $foto)
                            <div class="relative bg-slate-50 border border-slate-200 rounded-xl p-2.5 flex items-center space-x-3" id="foto-item-{{ $foto->id }}">
                                <img src="{{ $foto->url }}" class="w-12 h-12 rounded-lg object-cover border border-slate-200">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-slate-800 truncate">{{ $foto->nama_file }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $foto->humanFileSize() }}</p>
                                </div>
                                <button type="button" onclick="hapusBerkasAjax({{ $foto->id }})" class="p-1 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded" title="Hapus foto dari draf">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">Belum ada foto yang diunggah.</p>
                @endif

                <!-- Tambah Foto Baru -->
                <div class="pt-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tambah Foto Lapangan Baru (Maks 3 foto total, JPG/PNG maks 5MB):</label>
                    <input type="file" name="foto_lapangan[]" accept="image/png, image/jpeg, image/jpg" multiple class="text-xs text-slate-600 file:mr-4 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
                </div>
            </div>

            <!-- Surat Pengantar Kades -->
            <div class="border-t border-slate-100 pt-5 space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                    Surat Permohonan Resmi Kepala Desa
                </label>
                @if($pengajuan->suratKepalaDesa)
                    <div class="text-xs text-slate-600 flex items-center space-x-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                        <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Saat ini terlampir: <strong>{{ $pengajuan->suratKepalaDesa->nama_file }}</strong> ({{ $pengajuan->suratKepalaDesa->humanFileSize() }})</span>
                    </div>
                @endif
                <p class="text-[11px] text-slate-400">Pilih berkas baru jika ingin mengganti surat pengantar (PDF/JPG/PNG maks 5MB):</p>
                <input type="file" name="surat_kepala_desa" accept="application/pdf, image/png, image/jpeg, image/jpg" class="text-xs text-slate-600 file:mr-4 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
            </div>

            <!-- Lokasi Pengantaran -->
            <div class="border-t border-slate-100 pt-5 space-y-3">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                    Lokasi Pengantaran / Titik Drop Point
                </label>
                <input 
                    type="text" 
                    name="lokasi_pengantaran" 
                    id="lokasi_pengantaran" 
                    value="{{ old('lokasi_pengantaran', $pengajuan->lokasi_pengantaran) }}" 
                    class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-sky-500 text-slate-800 font-medium"
                >
                <div id="mapEdit" class="h-44 rounded-xl border border-slate-200 overflow-hidden"></div>
                <input type="hidden" name="latitude" id="edit_latitude" value="{{ $pengajuan->latitude ?? $desa->latitude }}">
                <input type="hidden" name="longitude" id="edit_longitude" value="{{ $pengajuan->longitude ?? $desa->longitude }}">
            </div>

            <!-- Catatan Tambahan -->
            <div class="border-t border-slate-100 pt-5">
                <label for="catatan_tambahan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Catatan Tambahan Akses Armada (Opsional)
                </label>
                <textarea 
                    name="catatan_tambahan" 
                    id="catatan_tambahan" 
                    rows="2" 
                    class="w-full bg-white border border-slate-300 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-sky-500"
                >{{ old('catatan_tambahan', $pengajuan->catatan_tambahan) }}</textarea>
            </div>

        </div>

        <!-- Tombol Aksi -->
        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('pengajuan.show', $pengajuan->id) }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 font-semibold text-sm transition">
                Batal
            </a>

            <div class="flex items-center space-x-3">
                <button type="submit" onclick="document.getElementById('editActionInput').value = 'draf'" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm shadow-sm transition">
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
    const editMap = L.map('mapEdit').setView([elLat, elLng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(editMap);
    const editMarker = L.marker([elLat, elLng], { draggable: true }).addTo(editMap);

    editMarker.on('dragend', function (e) {
        const pos = editMarker.getLatLng();
        document.getElementById('edit_latitude').value = pos.lat.toFixed(7);
        document.getElementById('edit_longitude').value = pos.lng.toFixed(7);
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

