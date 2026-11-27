{{-- Kartu Ringkasan Desa (Data Demografi, Peta Tandon, Kontak Perwakilan, & Kelengkapan Profil) --}}
<div class="bg-white rounded-xl border border-[#D0D5DD] p-5 shadow-xs space-y-4">
    
    <!-- Header Kartu & Kelengkapan Profil -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-[#EAECF0]">
        <div>
            <h2 class="text-xs sm:text-sm font-bold text-[#101828]">Ringkasan Wilayah Desa</h2>
            <p class="text-xs text-[#667085]">Desa {{ $desa?->nama_desa ?? '-' }} &middot; Kecamatan {{ $desa?->kecamatan ?? '-' }}</p>
        </div>

        <!-- Kelengkapan Profil Desa -->
        <div class="flex items-center gap-2.5">
            <div class="text-right">
                <span class="text-[10px] text-[#667085] block font-medium">Kelengkapan</span>
                <span class="text-xs font-bold text-[#101828]">{{ $ringkasan['completeness'] }}%</span>
            </div>
            <div class="w-12 h-1.5 bg-[#EAECF0] rounded-full overflow-hidden">
                <div class="h-full bg-[#00629B] rounded-full" style="width: {{ $ringkasan['completeness'] }}%;"></div>
            </div>
            <a 
                href="{{ route('desa.profile.edit') }}" 
                class="h-7 px-2.5 inline-flex items-center gap-1 text-xs font-medium text-[#344054] bg-white border border-[#D0D5DD] hover:bg-[#F2F4F7] rounded-md transition"
                title="Lengkapi atau perbarui profil desa"
            >
                <span>Edit Profil</span>
                <i class="fa-solid fa-pen text-[9px] text-[#667085]"></i>
            </a>
        </div>
    </div>

    <!-- Data Demografi & Kelompok Rentan (Tabel Metrik Formal) -->
    <div class="space-y-1.5">
        <span class="text-[11px] font-bold text-[#667085] uppercase tracking-wider block">
            Data Demografi &amp; Kelompok Rentan
        </span>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 text-center">
            <div class="p-2 bg-[#F8FAFC] border border-[#EAECF0] rounded-lg">
                <span class="text-[10px] font-semibold text-[#667085] uppercase tracking-wide block">Total KK</span>
                <span class="text-xs sm:text-sm font-bold text-[#101828] mt-0.5 block">{{ number_format($ringkasan['total_kk']) }}</span>
            </div>
            <div class="p-2 bg-[#F8FAFC] border border-[#EAECF0] rounded-lg">
                <span class="text-[10px] font-semibold text-[#667085] uppercase tracking-wide block">Total Jiwa</span>
                <span class="text-xs sm:text-sm font-bold text-[#101828] mt-0.5 block">{{ number_format($ringkasan['total_jiwa']) }}</span>
            </div>
            <div class="p-2 bg-[#F8FAFC] border border-[#EAECF0] rounded-lg">
                <span class="text-[10px] font-semibold text-[#667085] uppercase tracking-wide block">Balita</span>
                <span class="text-xs sm:text-sm font-bold text-[#101828] mt-0.5 block">{{ number_format($ringkasan['balita']) }}</span>
            </div>
            <div class="p-2 bg-[#F8FAFC] border border-[#EAECF0] rounded-lg">
                <span class="text-[10px] font-semibold text-[#667085] uppercase tracking-wide block">Lansia</span>
                <span class="text-xs sm:text-sm font-bold text-[#101828] mt-0.5 block">{{ number_format($ringkasan['lansia']) }}</span>
            </div>
            <div class="p-2 bg-[#F8FAFC] border border-[#EAECF0] rounded-lg">
                <span class="text-[10px] font-semibold text-[#667085] uppercase tracking-wide block">Disabilitas</span>
                <span class="text-xs sm:text-sm font-bold text-[#101828] mt-0.5 block">{{ number_format($ringkasan['disabilitas']) }}</span>
            </div>
        </div>
    </div>

    <!-- Peta Preview Titik Tandon / Drop Air -->
    <div class="space-y-1.5">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-[#667085] uppercase tracking-wider flex items-center gap-1.5">
                <i class="fa-solid fa-map-pin text-[#667085]"></i>
                Lokasi Tandon Utama (Drop Point)
            </span>
            <span class="text-xs text-[#344054] font-medium truncate max-w-[200px]">
                {{ $ringkasan['titik_drop'] }}
            </span>
        </div>

        <div class="rounded-lg overflow-hidden border border-[#D0D5DD] relative">
            <!-- Wadah Peta Leaflet -->
            <div id="village-map-preview" class="w-full h-36 bg-[#F2F4F7] z-0"></div>

            <!-- Overlay Keterangan Titik Drop -->
            <div class="absolute bottom-2 left-2 right-2 bg-white/95 px-2.5 py-1 rounded border border-[#D0D5DD] text-[11px] text-[#344054] flex items-center justify-between shadow-xs z-10">
                <div class="flex items-center gap-1.5 truncate">
                    <i class="fa-solid fa-location-dot text-[#667085]"></i>
                    <span class="truncate">{{ $ringkasan['titik_drop'] }}</span>
                </div>
                @if ($ringkasan['has_coords'])
                    <span class="text-[10px] text-[#027A48] font-semibold bg-[#ECFDF3] border border-[#A6F4C5] px-1.5 py-0.5 rounded shrink-0">
                        GPS Terisi
                    </span>
                @else
                    <span class="text-[10px] text-[#B54708] font-semibold bg-[#FFFAEB] border border-[#FEDF89] px-1.5 py-0.5 rounded shrink-0">
                        Belum Diset
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Kontak Perwakilan Desa -->
    <div class="p-3 rounded-lg bg-[#F8FAFC] border border-[#EAECF0] flex items-center gap-2.5">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-md bg-white border border-[#D0D5DD] text-[#667085] flex items-center justify-center font-bold text-xs shrink-0">
                <i class="fa-regular fa-user"></i>
            </div>
            <div>
                <span class="text-[10px] text-[#667085] block font-medium">Kontak Perwakilan Desa:</span>
                <strong class="text-xs text-[#101828] block">{{ $ringkasan['nama_pj'] }}</strong>
                <span class="text-[11px] text-[#475467] font-mono">{{ $ringkasan['no_wa'] }}</span>
            </div>
        </div>

    </div>

</div>

{{-- Leaflet Map Init Script --}}
@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mapContainer = document.getElementById('village-map-preview');
        if (!mapContainer) return;

        const lat = {{ $ringkasan['lat'] }};
        const lng = {{ $ringkasan['lng'] }};
        const hasCoords = {{ $ringkasan['has_coords'] ? 'true' : 'false' }};
        const titikDrop = "{{ addslashes($ringkasan['titik_drop']) }}";

        const map = L.map('village-map-preview', {
            zoomControl: false,
            dragging: false,
            touchZoom: false,
            scrollWheelZoom: false,
            doubleClickZoom: false,
            boxZoom: false,
            keyboard: false,
            attributionControl: false
        }).setView([lat, lng], hasCoords ? 15 : 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        if (hasCoords) {
            L.marker([lat, lng])
                .addTo(map)
                .bindPopup(`<strong>Titik Drop Air</strong><br>${titikDrop}`)
                .openPopup();
        }
    });
</script>
@endpush
