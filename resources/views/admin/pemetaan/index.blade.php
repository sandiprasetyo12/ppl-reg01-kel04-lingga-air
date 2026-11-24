@extends('layouts.dashboard')

@section('title', 'Pemetaan Desa (GIS) - LinggaAir')
@section('page-title', 'Pemetaan Sebaran Desa (GIS)')

@push('styles')
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""
/>
<style>
    /* Isolasi z-index container Leaflet agar tidak menembus modal dialog */
    .leaflet-container {
        z-index: 1 !important;
        isolation: isolate;
        font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
    }
    .leaflet-pane,
    .leaflet-tile-pane {
        z-index: 2 !important;
    }
    .leaflet-overlay-pane {
        z-index: 3 !important;
    }
    .leaflet-marker-pane {
        z-index: 4 !important;
    }
    .leaflet-tooltip-pane,
    .leaflet-popup-pane {
        z-index: 5 !important;
    }
    .leaflet-top,
    .leaflet-bottom {
        z-index: 6 !important;
    }
    @media (prefers-reduced-motion: reduce) {
        .gis-transition {
            transition: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-5">
    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" class="text-sm text-[#52525b] flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-[#1b1b18] underline min-h-[44px] inline-flex items-center">
            Dashboard
        </a>
        <span aria-hidden="true">/</span>
        <span class="text-[#1b1b18] font-medium">Pemetaan Desa (GIS)</span>
    </nav>

    <!-- Notifikasi Sukses Pembaruan Koordinat (Tanpa Reload) -->
    <div id="gis-status-banner" role="status" aria-live="polite" class="hidden p-3.5 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between gap-3">
        <span id="gis-status-banner-text"></span>
        <button type="button" id="gis-status-banner-close" class="min-h-[44px] px-3 text-xs font-medium text-emerald-900 underline">
            Tutup
        </button>
    </div>

    <!-- Ringkasan Status Wilayah & Legenda Warna PBI-025 -->
    <section aria-label="Ringkasan status pemetaan dan filter cepat" class="bg-white border border-[#e3e3e0] rounded-lg p-5 shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b border-[#e3e3e0] pb-4">
            <div>
                <h2 class="text-base font-semibold text-[#1b1b18]">
                    Sebaran Status Bantuan Air Bersih Kabupaten Purbalingga
                </h2>
                <p class="text-sm text-[#52525b] mt-0.5">
                    Menampilkan <strong id="count-ditampilkan" class="text-[#1b1b18]">{{ number_format($summary['ditampilkan'] ?? 0) }}</strong>
                    dari <strong id="count-terpetakan" class="text-[#1b1b18]">{{ number_format($summary['total_terpetakan'] ?? 0) }}</strong> desa berkoordinat
                    ({{ $kecamatans->count() }} kecamatan). Klik kategori untuk menyaring penanda peta.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a
                    href="#section-unmapped"
                    class="min-h-[44px] inline-flex items-center gap-2 px-3.5 py-2 rounded-md border border-[#d1d5db] bg-[#F9FAFB] hover:bg-[#f3f4f6] text-xs font-medium text-[#1b1b18] gis-transition"
                >
                    <span>Tanpa Koordinat:</span>
                    <span id="count-unmapped" class="font-semibold">{{ number_format($summary['tanpa_koordinat'] ?? 0) }}</span>
                </a>
                <button
                    type="button"
                    id="btn-refresh-map"
                    class="min-h-[44px] inline-flex items-center justify-center px-4 py-2 bg-[#1b1b18] hover:bg-black text-white text-sm font-medium rounded-md gis-transition"
                >
                    Muat Ulang Peta
                </button>
            </div>
        </div>

        <!-- Bar Kategori Status (Tombol Filter Fungsional) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3" role="group" aria-label="Filter warna status pengajuan">
            <button
                type="button"
                data-status-filter="merah"
                class="status-quick-btn min-h-[44px] text-left p-3.5 rounded-md border border-[#e3e3e0] bg-white hover:bg-[#FEF2F2] gis-transition flex items-center justify-between gap-3"
                aria-pressed="false"
            >
                <span class="flex items-center gap-2.5">
                    <span class="w-3.5 h-3.5 rounded-full shrink-0 bg-[#DC2626]" aria-hidden="true"></span>
                    <span>
                        <span class="block text-sm font-semibold text-[#1b1b18]">Perlu Penanganan</span>
                        <span class="block text-xs text-[#52525b]">Diajukan / Telaah / Revisi</span>
                    </span>
                </span>
                <span id="count-merah" class="text-lg font-semibold text-[#991B1B] tabular-nums">{{ number_format($summary['merah'] ?? 0) }}</span>
            </button>

            <button
                type="button"
                data-status-filter="kuning"
                class="status-quick-btn min-h-[44px] text-left p-3.5 rounded-md border border-[#e3e3e0] bg-white hover:bg-[#FFFBEB] gis-transition flex items-center justify-between gap-3"
                aria-pressed="false"
            >
                <span class="flex items-center gap-2.5">
                    <span class="w-3.5 h-3.5 rounded-full shrink-0 bg-[#D97706]" aria-hidden="true"></span>
                    <span>
                        <span class="block text-sm font-semibold text-[#1b1b18]">Proses Penyaluran</span>
                        <span class="block text-xs text-[#52525b]">Disetujui / Dijadwalkan</span>
                    </span>
                </span>
                <span id="count-kuning" class="text-lg font-semibold text-[#92400E] tabular-nums">{{ number_format($summary['kuning'] ?? 0) }}</span>
            </button>

            <button
                type="button"
                data-status-filter="hijau"
                class="status-quick-btn min-h-[44px] text-left p-3.5 rounded-md border border-[#e3e3e0] bg-white hover:bg-[#ECFDF5] gis-transition flex items-center justify-between gap-3"
                aria-pressed="false"
            >
                <span class="flex items-center gap-2.5">
                    <span class="w-3.5 h-3.5 rounded-full shrink-0 bg-[#059669]" aria-hidden="true"></span>
                    <span>
                        <span class="block text-sm font-semibold text-[#1b1b18]">Tersalurkan</span>
                        <span class="block text-xs text-[#52525b]">Pengiriman Selesai</span>
                    </span>
                </span>
                <span id="count-hijau" class="text-lg font-semibold text-[#065F46] tabular-nums">{{ number_format($summary['hijau'] ?? 0) }}</span>
            </button>

            <button
                type="button"
                data-status-filter="abu_abu"
                class="status-quick-btn min-h-[44px] text-left p-3.5 rounded-md border border-[#e3e3e0] bg-white hover:bg-[#F3F4F6] gis-transition flex items-center justify-between gap-3"
                aria-pressed="false"
            >
                <span class="flex items-center gap-2.5">
                    <span class="w-3.5 h-3.5 rounded-full shrink-0 bg-[#6B7280]" aria-hidden="true"></span>
                    <span>
                        <span class="block text-sm font-semibold text-[#1b1b18]">Tanpa Tiket</span>
                        <span class="block text-xs text-[#52525b]">Belum Ada Laporan Aktif</span>
                    </span>
                </span>
                <span id="count-abu_abu" class="text-lg font-semibold text-[#374151] tabular-nums">{{ number_format($summary['abu_abu'] ?? 0) }}</span>
            </button>
        </div>
    </section>

    <!-- Toolbar Filter Kecamatan, Status, Pencarian & Layer Poligon -->
    <section aria-label="Filter peta desa" class="bg-white border border-[#e3e3e0] rounded-lg p-5 shadow-sm">
        <form id="filter-form" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-4">
                <label for="filter-kecamatan" class="block text-sm font-medium text-[#1b1b18] mb-1">
                    Filter Kecamatan
                </label>
                <select
                    id="filter-kecamatan"
                    name="kecamatan"
                    class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-sm text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                >
                    <option value="">Semua Kecamatan ({{ $kecamatans->count() }} Kecamatan)</option>
                    @foreach ($kecamatans as $kec)
                        <option value="{{ $kec }}">Kecamatan {{ $kec }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3">
                <label for="filter-status" class="block text-sm font-medium text-[#1b1b18] mb-1">
                    Status Penanda
                </label>
                <select
                    id="filter-status"
                    name="status"
                    class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-sm text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                >
                    <option value="">Semua Status Warna</option>
                    <option value="merah">Merah: Perlu Penanganan</option>
                    <option value="kuning">Kuning: Proses Penyaluran</option>
                    <option value="hijau">Hijau: Tersalurkan</option>
                    <option value="abu_abu">Abu-abu: Tanpa Tiket</option>
                </select>
            </div>

            <div class="md:col-span-3">
                <label for="filter-search" class="block text-sm font-medium text-[#1b1b18] mb-1">
                    Cari Desa atau Kode Wilayah
                </label>
                <input
                    id="filter-search"
                    type="text"
                    name="search"
                    placeholder="Contoh: Serang atau 33.03.10..."
                    class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-sm text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                >
            </div>

            <div class="md:col-span-2 flex items-center gap-2">
                <button
                    type="submit"
                    class="flex-1 min-h-[44px] inline-flex items-center justify-center px-4 py-2 bg-[#1b1b18] hover:bg-black text-white text-sm font-medium rounded-md gis-transition"
                >
                    Terapkan
                </button>
                <button
                    type="button"
                    id="btn-reset-filter"
                    class="min-h-[44px] inline-flex items-center justify-center px-3 py-2 bg-white border border-[#d1d5db] hover:bg-[#f5f5f4] text-[#1b1b18] text-sm font-medium rounded-md gis-transition"
                >
                    Reset
                </button>
            </div>
        </form>

        <div class="mt-3 pt-3 border-t border-[#e3e3e0] flex flex-wrap items-center justify-between gap-3">
            <label for="toggle-polygon-layer" class="inline-flex items-center gap-2.5 text-sm text-[#1b1b18] min-h-[44px]">
                <input
                    id="toggle-polygon-layer"
                    type="checkbox"
                    checked
                    class="w-4 h-4 rounded border-[#d1d5db] text-[#1b1b18] focus:ring-[#1b1b18]"
                >
                <span>Tampilkan batas wilayah poligon 239 desa</span>
            </label>
            <span id="geojson-status-note" class="text-xs text-[#52525b]">
                Memuat layer batas administrasi...
            </span>
        </div>
    </section>

    <!-- Area Utama: Peta Sebaran (Kiri 8 Kolom) & Panel Detail Desa (Kanan 4 Kolom) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
        <!-- Peta Interaktif Leaflet -->
        <section aria-label="Peta interaktif desa" class="lg:col-span-8 bg-white border border-[#e3e3e0] rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-[#e3e3e0] flex flex-wrap items-center justify-between gap-2">
                <div>
                    <h2 class="text-base font-semibold text-[#1b1b18]">Peta Sebaran Desa</h2>
                    <p class="text-xs text-[#52525b]">Desa tanpa koordinat (NULL atau 0) disembunyikan otomatis dari peta.</p>
                </div>
                <button
                    type="button"
                    id="btn-fit-bounds"
                    class="min-h-[44px] inline-flex items-center px-3 py-1.5 text-xs font-medium text-[#1b1b18] bg-white border border-[#d1d5db] rounded-md hover:bg-[#f5f5f4] gis-transition"
                >
                    Pusatkan Peta
                </button>
            </div>

            <!-- Reserved Space Container agar zero CLS -->
            <div class="relative w-full h-[460px] lg:h-[560px] bg-[#F3F4F6]">
                <div
                    id="gis-map"
                    role="region"
                    aria-label="Peta sebaran desa Kabupaten Purbalingga"
                    class="w-full h-full"
                ></div>

                <!-- Loading State -->
                <div id="map-loading" class="hidden absolute inset-0 z-10 bg-white/85 flex flex-col items-center justify-center p-6 text-center">
                    <div class="w-8 h-8 rounded-full border-2 border-[#1b1b18] border-t-transparent animate-spin mb-3" aria-hidden="true"></div>
                    <p class="text-sm font-medium text-[#1b1b18]">Memuat data spasial desa...</p>
                </div>

                <!-- Empty State -->
                <div id="map-empty" class="hidden absolute inset-0 z-10 bg-white/95 flex flex-col items-center justify-center p-6 text-center">
                    <p class="text-base font-semibold text-[#1b1b18]">Tidak ada penanda desa yang cocok</p>
                    <p class="text-sm text-[#52525b] mt-1 max-w-md">
                        Filter kecamatan, status, atau kata kunci yang dipilih belum memiliki desa siap peta.
                    </p>
                    <button
                        type="button"
                        id="btn-empty-reset"
                        class="mt-4 min-h-[44px] inline-flex items-center px-4 py-2 bg-[#1b1b18] text-white text-sm font-medium rounded-md hover:bg-black gis-transition"
                    >
                        Reset Filter Peta
                    </button>
                </div>

                <!-- Error State -->
                <div id="map-error" role="alert" class="hidden absolute inset-0 z-10 bg-white/95 flex flex-col items-center justify-center p-6 text-center">
                    <p class="text-base font-semibold text-[#991B1B]">Gagal memuat peta sebaran desa</p>
                    <p id="map-error-text" class="text-sm text-[#52525b] mt-1 max-w-md">
                        Terjadi gangguan saat mengambil data GIS dari server.
                    </p>
                    <button
                        type="button"
                        id="btn-retry-map"
                        class="mt-4 min-h-[44px] inline-flex items-center px-4 py-2 bg-[#1b1b18] text-white text-sm font-medium rounded-md hover:bg-black gis-transition"
                    >
                        Coba Muat Ulang
                    </button>
                </div>
            </div>
        </section>

        <!-- Panel Detail Desa Terpilih (PBI-026) -->
        <aside aria-label="Panel detail desa terpilih" class="lg:col-span-4 bg-white border border-[#e3e3e0] rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-[#e3e3e0]">
                <h2 class="text-base font-semibold text-[#1b1b18]">Detail Desa</h2>
                <p class="text-xs text-[#52525b]">Klik penanda atau wilayah poligon desa pada peta.</p>
            </div>

            <div class="p-5">
                <!-- Idle Placeholder -->
                <div id="detail-empty" class="py-8 text-center space-y-2">
                    <p class="text-sm font-medium text-[#1b1b18]">Belum ada desa dipilih</p>
                    <p class="text-xs text-[#52525b] leading-relaxed">
                        Pilih salah satu penanda di peta untuk memeriksa skor AHP-SAW, status pengajuan aktif, estimasi tangki, atau memperbarui koordinat desa.
                    </p>
                </div>

                <!-- Detail Loading -->
                <div id="detail-loading" class="hidden py-10 text-center space-y-2">
                    <div class="w-6 h-6 rounded-full border-2 border-[#1b1b18] border-t-transparent animate-spin mx-auto" aria-hidden="true"></div>
                    <p class="text-sm text-[#52525b]">Mengambil detail desa...</p>
                </div>

                <!-- Detail Error -->
                <div id="detail-error" role="alert" class="hidden p-4 rounded-md bg-red-50 border border-red-200 text-red-800 text-sm space-y-2">
                    <p id="detail-error-text">Gagal memuat informasi detail desa.</p>
                    <button type="button" id="btn-retry-detail" class="min-h-[44px] px-3 py-1.5 bg-white border border-red-300 rounded-md text-xs font-medium text-red-900">
                        Coba Lagi
                    </button>
                </div>

                <!-- Detail Terisi -->
                <div id="detail-content" class="hidden space-y-4">
                    <div class="border-b border-[#e3e3e0] pb-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h3 id="detail-nama" class="text-lg font-semibold text-[#1b1b18]"></h3>
                                <p id="detail-kecamatan" class="text-sm text-[#52525b]"></p>
                            </div>
                            <span id="detail-kode" class="font-mono text-xs px-2 py-1 rounded bg-[#F3F4F6] text-[#1b1b18] border border-[#e3e3e0]"></span>
                        </div>

                        <!-- 2 Badge Terpisah: Status GIS Tiket + Kelayakan AHP-SAW (Ambang 30/60) -->
                        <div class="mt-3 space-y-2">
                            <div>
                                <span class="block text-[11px] font-medium uppercase tracking-wide text-[#52525b] mb-1">Status Penanganan Peta</span>
                                <span id="badge-status-gis" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold border"></span>
                            </div>
                            <div>
                                <span class="block text-[11px] font-medium uppercase tracking-wide text-[#52525b] mb-1">Kelayakan AHP-SAW (Ambang 30 / 60)</span>
                                <span id="badge-kelayakan" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold border"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Rincian Pengajuan Aktif (Bila Ada) -->
                    <div id="detail-pengajuan-box" class="hidden rounded-md border border-[#e3e3e0] bg-[#F9FAFB] p-3.5 space-y-2 text-xs">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-semibold text-[#1b1b18]">Pengajuan / Tiket Terkini</span>
                            <span id="detail-tiket-nomor" class="font-mono font-semibold text-[#1b1b18]"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-[#52525b]">
                            <div>
                                <span class="block">Status Tiket</span>
                                <strong id="detail-tiket-status" class="text-[#1b1b18]"></strong>
                            </div>
                            <div>
                                <span class="block">KK Terdampak</span>
                                <strong id="detail-tiket-kk" class="text-[#1b1b18]"></strong>
                            </div>
                            <div>
                                <span class="block">Hari Tanpa Air</span>
                                <strong id="detail-tiket-hari" class="text-[#1b1b18]"></strong>
                            </div>
                            <div>
                                <span class="block">Jarak Sumber</span>
                                <strong id="detail-tiket-jarak" class="text-[#1b1b18]"></strong>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-[#e3e3e0] flex items-center justify-between gap-2">
                            <span class="text-[#52525b]">Kebutuhan Air (@ 5.000 L)</span>
                            <strong id="detail-tiket-estimasi" class="text-[#1b1b18]"></strong>
                        </div>
                        <p id="detail-tiket-tanggal" class="text-[11px] text-[#52525b]"></p>
                        <div id="detail-verifikasi-wrap" class="hidden pt-1">
                            <a
                                id="detail-verifikasi-link"
                                href="#"
                                class="min-h-[44px] w-full inline-flex items-center justify-center px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-xs font-semibold text-[#1b1b18] hover:bg-[#f5f5f4] gis-transition"
                            >
                                Buka Telaah Verifikasi (F-04)
                            </a>
                        </div>
                    </div>

                    <div id="detail-no-pengajuan" class="hidden rounded-md border border-[#e3e3e0] bg-[#F9FAFB] p-3 text-xs text-[#52525b]">
                        Belum ada pengajuan bantuan air bersih yang aktif untuk desa ini.
                    </div>

                    <!-- Koordinat & Titik Drop -->
                    <dl class="space-y-2 text-xs border-t border-[#e3e3e0] pt-3">
                        <div>
                            <dt class="text-[#52525b]">Titik Drop Air</dt>
                            <dd id="detail-titik-drop" class="font-medium text-[#1b1b18] mt-0.5"></dd>
                        </div>
                        <div>
                            <dt class="text-[#52525b]">Koordinat (Lat, Lng)</dt>
                            <dd id="detail-koordinat" class="font-mono font-medium text-[#1b1b18] mt-0.5"></dd>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <dt class="text-[#52525b]">KK / Jiwa Total</dt>
                                <dd id="detail-demografi" class="font-medium text-[#1b1b18] mt-0.5"></dd>
                            </div>
                            <div>
                                <dt class="text-[#52525b]">Jiwa Rentan</dt>
                                <dd id="detail-rentan" class="font-medium text-[#1b1b18] mt-0.5"></dd>
                            </div>
                        </div>
                        <div>
                            <dt class="text-[#52525b]">Penanggung Jawab &amp; WhatsApp</dt>
                            <dd id="detail-kontak" class="font-medium text-[#1b1b18] mt-0.5"></dd>
                        </div>
                    </dl>

                    <!-- Aksi Ubah Koordinat PBI-027 -->
                    <div class="pt-2 border-t border-[#e3e3e0] flex flex-col gap-2">
                        <button
                            type="button"
                            id="btn-open-koordinat-modal"
                            class="min-h-[44px] w-full inline-flex items-center justify-center px-4 py-2 bg-[#1b1b18] hover:bg-black text-white text-sm font-medium rounded-md gis-transition"
                        >
                            Ubah Koordinat Desa
                        </button>
                        <a
                            id="detail-master-link"
                            href="#"
                            class="min-h-[44px] w-full inline-flex items-center justify-center px-4 py-2 bg-white border border-[#d1d5db] hover:bg-[#f5f5f4] text-[#1b1b18] text-xs font-medium rounded-md gis-transition"
                        >
                            Lihat Profil di Master Data Desa
                        </a>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <!-- Daftar Desa Belum Berkoordinat (PBI-025 & PBI-027) -->
    <section id="section-unmapped" aria-label="Daftar desa belum memiliki koordinat" class="bg-white border border-[#e3e3e0] rounded-lg shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-[#e3e3e0] flex flex-wrap items-center justify-between gap-2">
            <div>
                <h2 class="text-base font-semibold text-[#1b1b18]">Desa Belum Berkoordinat</h2>
                <p class="text-xs text-[#52525b]">
                    Desa yang koordinatnya NULL atau 0 tidak ditampilkan di peta hingga Admin BPBD menetapkan koordinat valid.
                </p>
            </div>
            <span id="unmapped-badge-count" class="text-xs font-medium px-2.5 py-1 rounded-md bg-[#F3F4F6] text-[#1b1b18] border border-[#e3e3e0]">
                {{ number_format($summary['tanpa_koordinat'] ?? 0) }} desa
            </span>
        </div>

        <div id="unmapped-empty" class="px-5 py-6 text-sm text-[#52525b]">
            Seluruh desa pada filter aktif sudah memiliki koordinat wilayah yang valid.
        </div>

        <div id="unmapped-table-wrap" class="hidden overflow-x-auto">
            <table class="min-w-full divide-y divide-[#e3e3e0] text-sm text-left">
                <thead class="bg-[#FDFDFC] text-[#52525b] text-xs uppercase font-medium">
                    <tr>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Kode Desa</th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Nama Desa</th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Kecamatan</th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Status Tiket</th>
                        <th scope="col" class="px-4 py-3 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody id="unmapped-tbody" class="divide-y divide-[#e3e3e0] bg-white"></tbody>
            </table>
        </div>
    </section>
</div>

<!-- Modal Kelola Koordinat Desa (PBI-027) -->
<div
    id="modal-koordinat"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-koordinat-title"
    class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 overflow-y-auto"
>
    <div id="modal-koordinat-panel" class="bg-white border border-[#e3e3e0] rounded-lg max-w-2xl w-full shadow-lg overflow-hidden my-auto">
        <div class="px-5 py-4 border-b border-[#e3e3e0] flex items-center justify-between gap-3">
            <div>
                <h2 id="modal-koordinat-title" class="text-base font-semibold text-[#1b1b18]">Kelola Koordinat Desa</h2>
                <p id="modal-koordinat-subtitle" class="text-xs text-[#52525b]"></p>
            </div>
            <button
                type="button"
                id="btn-close-koordinat-modal"
                aria-label="Tutup modal ubah koordinat"
                class="min-h-[44px] min-w-[44px] inline-flex items-center justify-center rounded-md border border-[#d1d5db] text-sm font-semibold text-[#1b1b18] hover:bg-[#f5f5f4] gis-transition"
            >
                &times;
            </button>
        </div>

        <form id="form-koordinat" novalidate class="p-5 space-y-4">
            <div id="modal-error" role="alert" class="hidden p-3 rounded-md bg-red-50 border border-red-200 text-red-800 text-xs space-y-1"></div>

            <!-- Picker Peta Mini -->
            <div class="space-y-1.5">
                <p class="text-xs font-medium text-[#1b1b18]">
                    Klik pada peta mini atau geser pin biru untuk menentukan titik koordinat secara visual:
                </p>
                <div id="picker-map" class="w-full h-56 rounded-md border border-[#d1d5db] bg-[#F3F4F6]"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="input-latitude" class="block text-sm font-medium text-[#1b1b18] mb-1">
                        Latitude <span class="text-[#991B1B]">*</span>
                    </label>
                    <input
                        id="input-latitude"
                        name="latitude"
                        type="number"
                        step="0.000001"
                        required
                        placeholder="-7.388000"
                        class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-sm font-mono text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                    >
                </div>

                <div>
                    <label for="input-longitude" class="block text-sm font-medium text-[#1b1b18] mb-1">
                        Longitude <span class="text-[#991B1B]">*</span>
                    </label>
                    <input
                        id="input-longitude"
                        name="longitude"
                        type="number"
                        step="0.000001"
                        required
                        placeholder="109.364000"
                        class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-sm font-mono text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                    >
                </div>
            </div>

            <p class="text-xs text-[#52525b]">
                Batas valid Kabupaten Purbalingga: Latitude <code>-7.55</code> s.d. <code>-7.15</code> dan Longitude <code>109.15</code> s.d. <code>109.65</code>.
            </p>

            <div>
                <label for="input-titik-drop" class="block text-sm font-medium text-[#1b1b18] mb-1">
                    Titik Drop Air (Opsional)
                </label>
                <input
                    id="input-titik-drop"
                    name="titik_drop_air"
                    type="text"
                    maxlength="150"
                    placeholder="Contoh: Bak Penampungan Sentral RT 04 RW 02"
                    class="w-full min-h-[44px] px-3 py-2 bg-white border border-[#d1d5db] rounded-md text-sm text-[#1b1b18] focus:outline-none focus:border-[#1b1b18]"
                >
            </div>

            <div class="pt-3 border-t border-[#e3e3e0] flex flex-wrap items-center justify-end gap-2">
                <button
                    type="button"
                    id="btn-cancel-koordinat"
                    class="min-h-[44px] inline-flex items-center px-4 py-2 bg-white border border-[#d1d5db] hover:bg-[#f5f5f4] text-[#1b1b18] text-sm font-medium rounded-md gis-transition"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    id="btn-save-koordinat"
                    class="min-h-[44px] inline-flex items-center px-5 py-2 bg-[#1b1b18] hover:bg-black text-white text-sm font-medium rounded-md gis-transition"
                >
                    Simpan Koordinat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""
></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const CONFIG = {
        dataUrl: @json(route('admin.pemetaan.data')),
        showUrlTemplate: @json(route('admin.pemetaan.show', ['kode_desa' => '__KODE__'])),
        updateUrlTemplate: @json(route('admin.pemetaan.koordinat', ['kode_desa' => '__KODE__'])),
        masterShowUrlTemplate: @json(route('admin.desa.show', ['desa' => '__KODE__'])),
        geojsonUrl: @json(asset('geojson/purbalingga_desa.geojson')),
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        defaultCenter: [-7.355, 109.38],
        defaultZoom: 11,
        reduceMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
    };

    const state = {
        markers: [],
        unmapped: [],
        markerMap: new Map(),
        markerLayers: new Map(),
        selectedKode: null,
        selectedDetail: null,
        geojsonData: null,
        activeEditDesa: null,
        lastFocusedElement: null,
    };

    // Elemen DOM
    const el = {
        filterForm: document.getElementById('filter-form'),
        filterKecamatan: document.getElementById('filter-kecamatan'),
        filterStatus: document.getElementById('filter-status'),
        filterSearch: document.getElementById('filter-search'),
        btnResetFilter: document.getElementById('btn-reset-filter'),
        btnEmptyReset: document.getElementById('btn-empty-reset'),
        btnRefreshMap: document.getElementById('btn-refresh-map'),
        btnRetryMap: document.getElementById('btn-retry-map'),
        btnFitBounds: document.getElementById('btn-fit-bounds'),
        togglePolygon: document.getElementById('toggle-polygon-layer'),
        geojsonStatusNote: document.getElementById('geojson-status-note'),
        statusQuickBtns: document.querySelectorAll('.status-quick-btn'),

        countDitampilkan: document.getElementById('count-ditampilkan'),
        countTerpetakan: document.getElementById('count-terpetakan'),
        countUnmapped: document.getElementById('count-unmapped'),
        countMerah: document.getElementById('count-merah'),
        countKuning: document.getElementById('count-kuning'),
        countHijau: document.getElementById('count-hijau'),
        countAbuAbu: document.getElementById('count-abu_abu'),

        mapLoading: document.getElementById('map-loading'),
        mapEmpty: document.getElementById('map-empty'),
        mapError: document.getElementById('map-error'),
        mapErrorText: document.getElementById('map-error-text'),

        statusBanner: document.getElementById('gis-status-banner'),
        statusBannerText: document.getElementById('gis-status-banner-text'),
        statusBannerClose: document.getElementById('gis-status-banner-close'),

        detailEmpty: document.getElementById('detail-empty'),
        detailLoading: document.getElementById('detail-loading'),
        detailError: document.getElementById('detail-error'),
        detailErrorText: document.getElementById('detail-error-text'),
        btnRetryDetail: document.getElementById('btn-retry-detail'),
        detailContent: document.getElementById('detail-content'),
        detailNama: document.getElementById('detail-nama'),
        detailKecamatan: document.getElementById('detail-kecamatan'),
        detailKode: document.getElementById('detail-kode'),
        badgeStatusGis: document.getElementById('badge-status-gis'),
        badgeKelayakan: document.getElementById('badge-kelayakan'),
        detailPengajuanBox: document.getElementById('detail-pengajuan-box'),
        detailNoPengajuan: document.getElementById('detail-no-pengajuan'),
        detailTiketNomor: document.getElementById('detail-tiket-nomor'),
        detailTiketStatus: document.getElementById('detail-tiket-status'),
        detailTiketKk: document.getElementById('detail-tiket-kk'),
        detailTiketHari: document.getElementById('detail-tiket-hari'),
        detailTiketJarak: document.getElementById('detail-tiket-jarak'),
        detailTiketEstimasi: document.getElementById('detail-tiket-estimasi'),
        detailTiketTanggal: document.getElementById('detail-tiket-tanggal'),
        detailVerifikasiWrap: document.getElementById('detail-verifikasi-wrap'),
        detailVerifikasiLink: document.getElementById('detail-verifikasi-link'),
        detailTitikDrop: document.getElementById('detail-titik-drop'),
        detailKoordinat: document.getElementById('detail-koordinat'),
        detailDemografi: document.getElementById('detail-demografi'),
        detailRentan: document.getElementById('detail-rentan'),
        detailKontak: document.getElementById('detail-kontak'),
        btnOpenKoordinatModal: document.getElementById('btn-open-koordinat-modal'),
        detailMasterLink: document.getElementById('detail-master-link'),

        unmappedBadgeCount: document.getElementById('unmapped-badge-count'),
        unmappedEmpty: document.getElementById('unmapped-empty'),
        unmappedTableWrap: document.getElementById('unmapped-table-wrap'),
        unmappedTbody: document.getElementById('unmapped-tbody'),

        modalKoordinat: document.getElementById('modal-koordinat'),
        modalSubtitle: document.getElementById('modal-koordinat-subtitle'),
        modalError: document.getElementById('modal-error'),
        formKoordinat: document.getElementById('form-koordinat'),
        inputLatitude: document.getElementById('input-latitude'),
        inputLongitude: document.getElementById('input-longitude'),
        inputTitikDrop: document.getElementById('input-titik-drop'),
        btnCloseModal: document.getElementById('btn-close-koordinat-modal'),
        btnCancelModal: document.getElementById('btn-cancel-koordinat'),
        btnSaveModal: document.getElementById('btn-save-koordinat'),
    };

    function escapeHtml(value) {
        if (value === null || value === undefined) {
            return '';
        }
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function formatNumber(num) {
        if (num === null || num === undefined || Number.isNaN(Number(num))) {
            return '-';
        }
        return new Intl.NumberFormat('id-ID').format(Number(num));
    }

    function getBadgeClasses(warnaKey) {
        switch (warnaKey) {
            case 'merah':
                return 'bg-red-50 text-[#991B1B] border-red-200';
            case 'kuning':
                return 'bg-amber-50 text-[#92400E] border-amber-200';
            case 'hijau':
                return 'bg-emerald-50 text-[#065F46] border-emerald-200';
            default:
                return 'bg-[#F3F4F6] text-[#374151] border-[#d1d5db]';
        }
    }

    function applyBadge(element, warnaKey, dotColor, labelText) {
        element.className = `inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold border ${getBadgeClasses(warnaKey)}`;
        element.innerHTML = '';
        const dot = document.createElement('span');
        dot.className = 'w-2 h-2 rounded-full shrink-0';
        dot.style.backgroundColor = dotColor || '#6B7280';
        dot.setAttribute('aria-hidden', 'true');
        const spanText = document.createElement('span');
        spanText.textContent = labelText;
        element.appendChild(dot);
        element.appendChild(spanText);
    }

    function setMapOverlay(mode, errorMessage = '') {
        el.mapLoading.classList.toggle('hidden', mode !== 'loading');
        el.mapEmpty.classList.toggle('hidden', mode !== 'empty');
        el.mapError.classList.toggle('hidden', mode !== 'error');
        if (mode === 'error' && errorMessage) {
            el.mapErrorText.textContent = errorMessage;
        }
    }

    function setDetailPanelMode(mode, errorMessage = '') {
        el.detailEmpty.classList.toggle('hidden', mode !== 'empty');
        el.detailLoading.classList.toggle('hidden', mode !== 'loading');
        el.detailError.classList.toggle('hidden', mode !== 'error');
        el.detailContent.classList.toggle('hidden', mode !== 'content');
        if (mode === 'error' && errorMessage) {
            el.detailErrorText.textContent = errorMessage;
        }
    }

    // Pastikan library Leaflet tersedia sebelum inisialisasi
    if (typeof window.L === 'undefined') {
        setMapOverlay('error', 'Library peta Leaflet tidak dapat dimuat. Periksa koneksi internet Anda lalu coba muat ulang.');
        return;
    }

    // Inisialisasi Peta Utama
    const map = L.map('gis-map', {
        center: CONFIG.defaultCenter,
        zoom: CONFIG.defaultZoom,
        zoomControl: true,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);

    const polygonLayerGroup = L.layerGroup().addTo(map);
    const markerLayerGroup = L.layerGroup().addTo(map);
    let geojsonLayer = null;

    // Inisialisasi Mini-Map Picker di dalam Modal
    let pickerMap = null;
    let pickerMarker = null;

    function ensurePickerMap(lat, lng) {
        if (!pickerMap) {
            pickerMap = L.map('picker-map', {
                center: [lat, lng],
                zoom: 13,
                zoomControl: true,
            });
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(pickerMap);

            pickerMarker = L.marker([lat, lng], { draggable: true }).addTo(pickerMap);
            pickerMarker.on('dragend', () => {
                const pos = pickerMarker.getLatLng();
                el.inputLatitude.value = pos.lat.toFixed(6);
                el.inputLongitude.value = pos.lng.toFixed(6);
            });

            pickerMap.on('click', (evt) => {
                const { lat: clickLat, lng: clickLng } = evt.latlng;
                pickerMarker.setLatLng([clickLat, clickLng]);
                el.inputLatitude.value = clickLat.toFixed(6);
                el.inputLongitude.value = clickLng.toFixed(6);
            });
        } else {
            pickerMarker.setLatLng([lat, lng]);
            pickerMap.setView([lat, lng], 13, { animate: false });
        }

        window.setTimeout(() => {
            pickerMap?.invalidateSize();
        }, 60);
    }

    function syncQuickStatusButtons() {
        const currentStatus = el.filterStatus.value;
        el.statusQuickBtns.forEach((btn) => {
            const isActive = btn.dataset.statusFilter === currentStatus;
            btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            btn.classList.toggle('border-[#1b1b18]', isActive);
            btn.classList.toggle('ring-1', isActive);
            btn.classList.toggle('ring-[#1b1b18]', isActive);
        });
    }

    function updateSummary(summary) {
        if (!summary) return;
        el.countDitampilkan.textContent = formatNumber(summary.ditampilkan ?? 0);
        el.countTerpetakan.textContent = formatNumber(summary.total_terpetakan ?? 0);
        el.countUnmapped.textContent = formatNumber(summary.tanpa_koordinat ?? 0);
        el.unmappedBadgeCount.textContent = `${formatNumber(summary.tanpa_koordinat ?? 0)} desa`;
        el.countMerah.textContent = formatNumber(summary.merah ?? 0);
        el.countKuning.textContent = formatNumber(summary.kuning ?? 0);
        el.countHijau.textContent = formatNumber(summary.hijau ?? 0);
        el.countAbuAbu.textContent = formatNumber(summary.abu_abu ?? 0);
    }

    function buildPopupHtml(marker) {
        const nama = escapeHtml(marker.nama_desa);
        const kec = escapeHtml(marker.kecamatan);
        const kode = escapeHtml(marker.kode_desa);
        const statusLabel = escapeHtml(marker.status_gis?.status || 'Tanpa Tiket');
        const kelayakanLabel = escapeHtml(marker.kelayakan?.label || 'Belum Ada Skor');
        const dropAir = escapeHtml(marker.titik_drop_air || 'Belum ditentukan');
        const tiketHtml = marker.pengajuan_aktif
            ? `<div style="margin-top:6px;padding-top:6px;border-top:1px solid #e3e3e0;font-size:12px;">
                    <strong>Tiket:</strong> ${escapeHtml(marker.pengajuan_aktif.nomor_tiket || '-')} (${escapeHtml(marker.pengajuan_aktif.status)})<br>
                    <strong>Kebutuhan:</strong> ${formatNumber(marker.pengajuan_aktif.estimasi_liter)} L (${formatNumber(marker.pengajuan_aktif.estimasi_tangki)} tangki @ 5.000 L)
               </div>`
            : `<div style="margin-top:6px;padding-top:6px;border-top:1px solid #e3e3e0;font-size:12px;color:#52525b;">
                    Belum ada pengajuan bantuan aktif.
               </div>`;

        return `
            <div style="min-width:220px;font-family:inherit;color:#1b1b18;">
                <div style="font-weight:700;font-size:14px;">Desa ${nama}</div>
                <div style="font-size:12px;color:#52525b;margin-bottom:6px;">Kec. ${kec} (${kode})</div>
                <div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:6px;">
                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;padding:2px 8px;border-radius:4px;background:#F3F4F6;border:1px solid #d1d5db;">
                        <span style="width:7px;height:7px;border-radius:999px;background:${escapeHtml(marker.status_gis?.kode_warna || '#6B7280')};"></span>
                        ${statusLabel}
                    </span>
                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;padding:2px 8px;border-radius:4px;background:#F3F4F6;border:1px solid #d1d5db;">
                        SPK: ${kelayakanLabel}
                    </span>
                </div>
                <div style="font-size:12px;"><strong>Titik Drop:</strong> ${dropAir}</div>
                ${tiketHtml}
                <div style="display:flex;gap:6px;margin-top:10px;">
                    <button type="button" class="popup-action-detail" data-kode="${kode}" style="flex:1;min-height:36px;padding:4px 8px;border-radius:5px;border:1px solid #d1d5db;background:#fff;color:#1b1b18;font-size:12px;font-weight:600;cursor:pointer;">
                        Detail
                    </button>
                    <button type="button" class="popup-action-edit" data-kode="${kode}" style="flex:1;min-height:36px;padding:4px 8px;border-radius:5px;border:1px solid #1b1b18;background:#1b1b18;color:#fff;font-size:12px;font-weight:600;cursor:pointer;">
                        Ubah Titik
                    </button>
                </div>
            </div>
        `;
    }

    function getMarkerRadius(warnaKey, isSelected) {
        const base = warnaKey === 'merah' ? 9 : (warnaKey === 'kuning' ? 8.5 : (warnaKey === 'hijau' ? 8 : 6.5));
        return isSelected ? base + 3 : base;
    }

    function renderMarkers() {
        markerLayerGroup.clearLayers();
        state.markerLayers.clear();
        state.markerMap.clear();

        if (!state.markers.length) {
            renderGeojsonLayer();
            setMapOverlay('empty');
            return;
        }

        setMapOverlay('none');
        const boundsCoords = [];

        state.markers.forEach((m) => {
            state.markerMap.set(m.kode_desa, m);
            const latLng = [m.latitude, m.longitude];
            boundsCoords.push(latLng);

            const isSelected = state.selectedKode === m.kode_desa;
            const circle = L.circleMarker(latLng, {
                radius: getMarkerRadius(m.status_gis?.warna_key, isSelected),
                color: isSelected ? '#1b1b18' : '#ffffff',
                weight: isSelected ? 3 : 2,
                fillColor: m.status_gis?.kode_warna || '#6B7280',
                fillOpacity: 0.92,
            });

            circle.bindPopup(buildPopupHtml(m), { maxWidth: 280 });
            circle.on('click', () => {
                selectDesa(m.kode_desa);
            });
            circle.on('popupopen', (evt) => {
                const popupNode = evt.popup.getElement();
                if (!popupNode) return;
                popupNode.querySelector('.popup-action-detail')?.addEventListener('click', () => {
                    selectDesa(m.kode_desa);
                });
                popupNode.querySelector('.popup-action-edit')?.addEventListener('click', (clickEvt) => {
                    openKoordinatModalFromCode(m.kode_desa, clickEvt.currentTarget);
                });
            });

            circle.addTo(markerLayerGroup);
            state.markerLayers.set(m.kode_desa, circle);
        });

        renderGeojsonLayer();
    }

    function fitMapToVisible() {
        const coords = state.markers.map((m) => [m.latitude, m.longitude]);
        if (coords.length === 1) {
            map.setView(coords[0], 14, { animate: !CONFIG.reduceMotion });
            return;
        }
        if (coords.length > 1) {
            map.fitBounds(coords, { padding: [28, 28], maxZoom: 14, animate: !CONFIG.reduceMotion });
            return;
        }
        map.setView(CONFIG.defaultCenter, CONFIG.defaultZoom, { animate: !CONFIG.reduceMotion });
    }

    function renderUnmappedTable() {
        const list = state.unmapped || [];
        if (!list.length) {
            el.unmappedEmpty.classList.remove('hidden');
            el.unmappedTableWrap.classList.add('hidden');
            el.unmappedTbody.innerHTML = '';
            return;
        }

        el.unmappedEmpty.classList.add('hidden');
        el.unmappedTableWrap.classList.remove('hidden');
        el.unmappedTbody.innerHTML = '';

        list.forEach((item) => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-[#FDFDFC]';

            const tdKode = document.createElement('td');
            tdKode.className = 'px-4 py-3 font-mono text-xs text-[#52525b] whitespace-nowrap';
            tdKode.textContent = item.kode_desa;

            const tdNama = document.createElement('td');
            tdNama.className = 'px-4 py-3 font-medium text-[#1b1b18] whitespace-nowrap';
            tdNama.textContent = item.nama_desa;

            const tdKec = document.createElement('td');
            tdKec.className = 'px-4 py-3 text-[#52525b] whitespace-nowrap';
            tdKec.textContent = `Kec. ${item.kecamatan}`;

            const tdStatus = document.createElement('td');
            tdStatus.className = 'px-4 py-3 whitespace-nowrap';
            const badge = document.createElement('span');
            applyBadge(badge, item.status_gis?.warna_key, item.status_gis?.kode_warna, item.status_gis?.status || 'Tanpa Tiket');
            tdStatus.appendChild(badge);

            const tdAksi = document.createElement('td');
            tdAksi.className = 'px-4 py-3 text-right whitespace-nowrap';
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'min-h-[44px] inline-flex items-center px-3 py-1.5 bg-[#1b1b18] hover:bg-black text-white rounded-md text-xs font-medium gis-transition';
            btn.textContent = `Tetapkan Koordinat (${item.nama_desa})`;
            btn.addEventListener('click', (evt) => {
                openKoordinatModalFromObject({
                    kode_desa: item.kode_desa,
                    nama_desa: item.nama_desa,
                    kecamatan: item.kecamatan,
                    latitude: null,
                    longitude: null,
                    titik_drop_air: item.titik_drop_air || '',
                }, evt.currentTarget);
            });
            tdAksi.appendChild(btn);

            tr.appendChild(tdKode);
            tr.appendChild(tdNama);
            tr.appendChild(tdKec);
            tr.appendChild(tdStatus);
            tr.appendChild(tdAksi);
            el.unmappedTbody.appendChild(tr);
        });
    }

    function getGeojsonFeatureStyle(feature) {
        const hasFilter = Boolean(el.filterKecamatan.value || el.filterStatus.value || el.filterSearch.value.trim());
        const kode = feature?.properties?.kode_desa;
        const matchedMarker = kode ? state.markerMap.get(kode) : null;
        const isSelected = Boolean(kode && state.selectedKode === kode);

        if (matchedMarker) {
            const color = matchedMarker.status_gis?.kode_warna || '#6B7280';
            return {
                color: isSelected ? '#1b1b18' : color,
                weight: isSelected ? 2.5 : 1.2,
                opacity: 0.85,
                fillColor: color,
                fillOpacity: isSelected ? 0.34 : 0.16,
            };
        }

        return {
            color: '#9ca3af',
            weight: 0.7,
            opacity: hasFilter ? 0.18 : 0.45,
            fillColor: '#6B7280',
            fillOpacity: hasFilter ? 0.02 : 0.05,
        };
    }

    function highlightSelection() {
        state.markerLayers.forEach((circle, kode) => {
            const markerData = state.markerMap.get(kode);
            const isSelected = state.selectedKode === kode;
            circle.setStyle({
                radius: getMarkerRadius(markerData?.status_gis?.warna_key, isSelected),
                color: isSelected ? '#1b1b18' : '#ffffff',
                weight: isSelected ? 3 : 2,
                fillColor: markerData?.status_gis?.kode_warna || '#6B7280',
                fillOpacity: 0.92,
            });
            if (isSelected && typeof circle.bringToFront === 'function') {
                circle.bringToFront();
            }
        });

        if (geojsonLayer && typeof geojsonLayer.setStyle === 'function') {
            geojsonLayer.setStyle(getGeojsonFeatureStyle);
        }
    }

    function renderGeojsonLayer() {
        polygonLayerGroup.clearLayers();
        geojsonLayer = null;
        if (!el.togglePolygon.checked || !state.geojsonData) {
            return;
        }

        geojsonLayer = L.geoJSON(state.geojsonData, {
            style: getGeojsonFeatureStyle,
            onEachFeature: (feature, layer) => {
                const kode = feature?.properties?.kode_desa;
                if (!kode) return;
                layer.on('click', () => {
                    selectDesa(kode);
                });
            },
        }).addTo(polygonLayerGroup);
    }

    async function loadGeojson() {
        try {
            const response = await fetch(CONFIG.geojsonUrl, {
                headers: { 'Accept': 'application/json' },
            });
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            state.geojsonData = await response.json();
            const featureCount = Array.isArray(state.geojsonData?.features) ? state.geojsonData.features.length : 0;
            el.geojsonStatusNote.textContent = `Layer poligon siap (${formatNumber(featureCount)} desa).`;
            renderGeojsonLayer();
        } catch (err) {
            el.geojsonStatusNote.textContent = 'Layer poligon batas wilayah gagal dimuat; penanda titik tetap aktif.';
        }
    }

    async function loadMapData({ fit = true } = {}) {
        setMapOverlay('loading');
        syncQuickStatusButtons();

        const params = new URLSearchParams();
        if (el.filterKecamatan.value) {
            params.set('kecamatan', el.filterKecamatan.value);
        }
        if (el.filterStatus.value) {
            params.set('status', el.filterStatus.value);
        }
        const searchValue = el.filterSearch.value.trim();
        if (searchValue) {
            params.set('search', searchValue);
        }

        const queryString = params.toString();
        const url = queryString ? `${CONFIG.dataUrl}?${queryString}` : CONFIG.dataUrl;

        try {
            const response = await fetch(url, {
                headers: { 'Accept': 'application/json' },
            });
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            const data = await response.json();
            state.markers = Array.isArray(data.markers) ? data.markers : [];
            state.unmapped = Array.isArray(data.unmapped) ? data.unmapped : [];

            updateSummary(data.summary);
            renderMarkers();
            renderUnmappedTable();
            if (fit) {
                fitMapToVisible();
            }
        } catch (err) {
            setMapOverlay('error', 'Terjadi kesalahan saat mengambil data peta desa. Silakan coba muat ulang.');
        }
    }

    function populateDetailPanel(detail) {
        state.selectedDetail = detail;

        el.detailNama.textContent = `Desa ${detail.nama_desa}`;
        el.detailKecamatan.textContent = `Kecamatan ${detail.kecamatan}`;
        el.detailKode.textContent = detail.kode_desa;

        applyBadge(
            el.badgeStatusGis,
            detail.status_gis?.warna_key,
            detail.status_gis?.kode_warna,
            `${detail.status_gis?.status || 'Tanpa Tiket'} (${detail.status_gis?.label || ''})`
        );

        const kel = detail.kelayakan || {};
        const kelText = kel.skor !== null && kel.skor !== undefined
            ? `${kel.status} (Skor ${String(kel.skor).replace('.', ',')})`
            : (kel.label || 'Belum Ada Skor AHP-SAW');
        applyBadge(el.badgeKelayakan, kel.warna_key, kel.badge_warna, kelText);

        if (detail.pengajuan_aktif) {
            const t = detail.pengajuan_aktif;
            el.detailPengajuanBox.classList.remove('hidden');
            el.detailNoPengajuan.classList.add('hidden');
            el.detailTiketNomor.textContent = t.nomor_tiket || '-';
            el.detailTiketStatus.textContent = t.status || '-';
            el.detailTiketKk.textContent = `${formatNumber(t.jumlah_kk)} KK`;
            el.detailTiketHari.textContent = `${formatNumber(t.hari_tanpa_air)} hari`;
            el.detailTiketJarak.textContent = `${formatNumber(t.jarak_sumber_air)} m`;
            el.detailTiketEstimasi.textContent = `${formatNumber(t.estimasi_liter)} L (${formatNumber(t.estimasi_tangki)} tangki)`;
            el.detailTiketTanggal.textContent = t.tanggal_pengajuan ? `Diajukan: ${t.tanggal_pengajuan}` : '';

            if (t.verifikasi_url) {
                el.detailVerifikasiWrap.classList.remove('hidden');
                el.detailVerifikasiLink.setAttribute('href', t.verifikasi_url);
            } else {
                el.detailVerifikasiWrap.classList.add('hidden');
                el.detailVerifikasiLink.setAttribute('href', '#');
            }
        } else {
            el.detailPengajuanBox.classList.add('hidden');
            el.detailNoPengajuan.classList.remove('hidden');
        }

        el.detailTitikDrop.textContent = detail.titik_drop_air || 'Belum ditentukan';
        el.detailKoordinat.textContent = detail.koordinat_siap
            ? `${Number(detail.latitude).toFixed(6)}, ${Number(detail.longitude).toFixed(6)}`
            : 'Belum memiliki koordinat valid';

        const demo = detail.demografi || {};
        const kkStr = demo.jumlah_kk_total !== null && demo.jumlah_kk_total !== undefined
            ? `${formatNumber(demo.jumlah_kk_total)} KK / ${formatNumber(demo.jumlah_jiwa_total)} jiwa`
            : 'Belum dilengkapi';
        el.detailDemografi.textContent = kkStr;
        el.detailRentan.textContent = demo.total_jiwa_rentan !== null && demo.total_jiwa_rentan !== undefined
            ? `${formatNumber(demo.total_jiwa_rentan)} jiwa`
            : '-';

        const pj = detail.nama_pj_darurat || '-';
        const wa = detail.no_wa_darurat || '-';
        el.detailKontak.textContent = `${pj} (${wa})`;

        el.detailMasterLink.setAttribute('href', CONFIG.masterShowUrlTemplate.replace('__KODE__', encodeURIComponent(detail.kode_desa)));
        setDetailPanelMode('content');
    }

    async function selectDesa(kodeDesa, { panTo = false } = {}) {
        state.selectedKode = kodeDesa;
        highlightSelection();
        setDetailPanelMode('loading');

        try {
            const url = CONFIG.showUrlTemplate.replace('__KODE__', encodeURIComponent(kodeDesa));
            const response = await fetch(url, {
                headers: { 'Accept': 'application/json' },
            });
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            const detail = await response.json();
            populateDetailPanel(detail);

            if (panTo && detail.koordinat_siap) {
                map.setView([detail.latitude, detail.longitude], Math.max(map.getZoom(), 13), {
                    animate: !CONFIG.reduceMotion,
                });
            }
        } catch (err) {
            setDetailPanelMode('error', 'Gagal memuat detail desa yang dipilih. Silakan coba lagi.');
        }
    }

    function openKoordinatModalFromCode(kodeDesa, triggerEl = null) {
        if (state.selectedDetail && state.selectedDetail.kode_desa === kodeDesa) {
            openKoordinatModalFromObject(state.selectedDetail, triggerEl);
            return;
        }
        const marker = state.markerMap.get(kodeDesa);
        if (marker) {
            openKoordinatModalFromObject(marker, triggerEl);
        }
    }

    function openKoordinatModalFromObject(desaObj, triggerEl = null) {
        state.lastFocusedElement = triggerEl || document.activeElement;
        state.activeEditDesa = desaObj;

        const hasValidLat = desaObj.latitude !== null && desaObj.latitude !== undefined && Number(desaObj.latitude) !== 0;
        const hasValidLng = desaObj.longitude !== null && desaObj.longitude !== undefined && Number(desaObj.longitude) !== 0;
        const initLat = hasValidLat ? Number(desaObj.latitude) : -7.388000;
        const initLng = hasValidLng ? Number(desaObj.longitude) : 109.364000;

        el.modalSubtitle.textContent = `Desa ${desaObj.nama_desa}, Kec. ${desaObj.kecamatan} (${desaObj.kode_desa})`;
        el.inputLatitude.value = initLat.toFixed(6);
        el.inputLongitude.value = initLng.toFixed(6);
        el.inputTitikDrop.value = desaObj.titik_drop_air || '';

        el.modalError.classList.add('hidden');
        el.modalError.innerHTML = '';
        el.modalKoordinat.classList.remove('hidden');

        ensurePickerMap(initLat, initLng);
        el.inputLatitude.focus();
    }

    function closeKoordinatModal() {
        el.modalKoordinat.classList.add('hidden');
        state.activeEditDesa = null;
        if (state.lastFocusedElement && typeof state.lastFocusedElement.focus === 'function') {
            state.lastFocusedElement.focus();
        }
    }

    function syncInputsToPicker() {
        const lat = Number(el.inputLatitude.value);
        const lng = Number(el.inputLongitude.value);
        if (!pickerMap || !pickerMarker || Number.isNaN(lat) || Number.isNaN(lng)) {
            return;
        }
        if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
            return;
        }
        pickerMarker.setLatLng([lat, lng]);
        pickerMap.panTo([lat, lng], { animate: false });
    }

    async function handleSaveKoordinat(evt) {
        evt.preventDefault();
        if (!state.activeEditDesa) return;

        el.modalError.classList.add('hidden');
        el.modalError.innerHTML = '';
        el.btnSaveModal.disabled = true;
        el.btnSaveModal.textContent = 'Menyimpan...';

        const kode = state.activeEditDesa.kode_desa;
        const url = CONFIG.updateUrlTemplate.replace('__KODE__', encodeURIComponent(kode));
        const body = {
            latitude: el.inputLatitude.value,
            longitude: el.inputLongitude.value,
            titik_drop_air: el.inputTitikDrop.value.trim() || null,
        };

        try {
            const response = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CONFIG.csrfToken,
                },
                body: JSON.stringify(body),
            });

            const json = await response.json();

            if (!response.ok) {
                const errors = json?.errors ? Object.values(json.errors).flat() : [json?.message || 'Gagal menyimpan koordinat desa.'];
                el.modalError.innerHTML = '';
                errors.forEach((msg) => {
                    const p = document.createElement('p');
                    p.textContent = msg;
                    el.modalError.appendChild(p);
                });
                el.modalError.classList.remove('hidden');
                return;
            }

            closeKoordinatModal();
            el.statusBannerText.textContent = json.message || 'Koordinat desa berhasil diperbarui.';
            el.statusBanner.classList.remove('hidden');

            await loadMapData({ fit: false });
            await selectDesa(kode, { panTo: true });
            state.markerLayers.get(kode)?.openPopup();
        } catch (err) {
            el.modalError.innerHTML = '<p>Terjadi kesalahan jaringan saat menyimpan koordinat.</p>';
            el.modalError.classList.remove('hidden');
        } finally {
            el.btnSaveModal.disabled = false;
            el.btnSaveModal.textContent = 'Simpan Koordinat';
        }
    }

    // Event Bindings
    el.filterForm.addEventListener('submit', (evt) => {
        evt.preventDefault();
        loadMapData({ fit: true });
    });

    el.filterKecamatan.addEventListener('change', () => {
        loadMapData({ fit: true });
    });

    el.filterStatus.addEventListener('change', () => {
        loadMapData({ fit: true });
    });

    el.statusQuickBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetStatus = btn.dataset.statusFilter || '';
            el.filterStatus.value = el.filterStatus.value === targetStatus ? '' : targetStatus;
            loadMapData({ fit: true });
        });
    });

    function resetAllFilters() {
        el.filterKecamatan.value = '';
        el.filterStatus.value = '';
        el.filterSearch.value = '';
        loadMapData({ fit: true });
    }

    el.btnResetFilter.addEventListener('click', resetAllFilters);
    el.btnEmptyReset.addEventListener('click', resetAllFilters);
    el.btnRefreshMap.addEventListener('click', () => loadMapData({ fit: false }));
    el.btnRetryMap.addEventListener('click', () => loadMapData({ fit: true }));
    el.btnFitBounds.addEventListener('click', fitMapToVisible);
    el.togglePolygon.addEventListener('change', renderGeojsonLayer);

    el.statusBannerClose.addEventListener('click', () => {
        el.statusBanner.classList.add('hidden');
    });

    el.btnRetryDetail.addEventListener('click', () => {
        if (state.selectedKode) {
            selectDesa(state.selectedKode);
        }
    });

    el.btnOpenKoordinatModal.addEventListener('click', (evt) => {
        if (state.selectedDetail) {
            openKoordinatModalFromObject(state.selectedDetail, evt.currentTarget);
        }
    });

    el.btnCloseModal.addEventListener('click', closeKoordinatModal);
    el.btnCancelModal.addEventListener('click', closeKoordinatModal);
    el.modalKoordinat.addEventListener('click', (evt) => {
        if (evt.target === el.modalKoordinat) {
            closeKoordinatModal();
        }
    });
    document.addEventListener('keydown', (evt) => {
        if (evt.key === 'Escape' && !el.modalKoordinat.classList.contains('hidden')) {
            closeKoordinatModal();
        }
    });

    el.inputLatitude.addEventListener('input', syncInputsToPicker);
    el.inputLongitude.addEventListener('input', syncInputsToPicker);
    el.formKoordinat.addEventListener('submit', handleSaveKoordinat);

    // Load awal
    loadGeojson();
    loadMapData({ fit: true });
});
</script>
@endpush
