@extends('layouts.desa')

@section('title', 'Beranda Desa - LinggaAir')
@section('page-title', 'Beranda')

@section('content')
<div class="space-y-5 pb-20 md:pb-6">
    
    <!-- 1. GREETING & INFO HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-[#EAECF0]">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-[#101828] tracking-tight">
                Portal Perwakilan Desa {{ $desa?->nama_desa ?? '' }}
            </h1>
            <p class="text-xs text-[#667085] mt-0.5">
                Kecamatan {{ $desa?->kecamatan ?? '-' }}, Kabupaten Purbalingga &middot; Kode Wilayah: <span class="font-mono text-[#344054] font-medium">{{ $desa?->kode_desa ?? '-' }}</span>
            </p>
        </div>

        <div class="inline-flex items-center gap-2 self-start sm:self-auto px-2.5 py-1 rounded-md bg-white text-[#344054] text-xs font-medium border border-[#D0D5DD] shadow-2xs">
            <i class="fa-regular fa-calendar text-[#667085]"></i>
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    {{-- PBI-018: Banner Peringatan Kedaruratan Aktif BPBD Purbalingga --}}
    @if (!empty($pengumumanDarurat))
        @php
            $announcementTone = match ($pengumumanDarurat->tingkat_kelayakan) {
                'kritis' => ['bg-[#FEF3F2] border-[#FECDCA] text-[#7A271A]', 'bg-[#FEE4E2] text-[#D92D20]', 'fa-triangle-exclamation'],
                'aman' => ['bg-[#ECFDF3] border-[#A6F4C5] text-[#05603A]', 'bg-[#D1FADF] text-[#027A48]', 'fa-circle-check'],
                default => ['bg-[#FEF0C7] border-[#FEDF89] text-[#7A2E0E]', 'bg-[#FDE293] text-[#B54708]', 'fa-triangle-exclamation'],
            };
        @endphp
        <div class="p-4 rounded-xl border {{ $announcementTone[0] }} flex items-start gap-3 shadow-xs">
            <div class="w-8 h-8 rounded-lg {{ $announcementTone[1] }} flex items-center justify-center shrink-0 text-sm mt-0.5">
                <i class="fa-solid {{ $announcementTone[2] }}"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider px-2 py-0.5 rounded-full text-[10px] {{ $pengumumanDarurat->badge_class }}">
                        Status {{ $pengumumanDarurat->tingkat_label }}
                    </span>
                    <span class="text-[11px] text-[#667085]">
                        Diterbitkan: {{ $pengumumanDarurat->published_at ? $pengumumanDarurat->published_at->translatedFormat('d F Y, H:i') : '' }} WIB
                    </span>
                </div>
                <h3 class="text-sm font-bold mt-1 text-[#101828]">
                    {{ $pengumumanDarurat->judul }}
                </h3>
                <p class="text-xs mt-1 text-[#344054] leading-relaxed">
                    {{ $pengumumanDarurat->isi }}
                </p>
            </div>
        </div>
    @endif

    {{-- Prakiraan cuaca BMKG untuk wilayah desa --}}
    @if ($desa)
        <section id="weather-card" data-url="{{ route('desa.weather') }}" class="rounded-xl border border-[#D0D5DD] bg-white p-4 shadow-xs" aria-live="polite">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-[#667085]">Prakiraan Cuaca Desa</p>
                    <h2 id="weather-location" class="mt-1 text-sm font-bold text-[#101828]">Memuat prakiraan cuaca…</h2>
                    <p id="weather-description" class="mt-1 text-xs text-[#475467]">Mengambil data dari BMKG</p>
                </div>
                <i class="fa-solid fa-cloud-sun text-xl text-[#00629B]" aria-hidden="true"></i>
            </div>
            <div id="weather-details" class="mt-3 hidden flex-wrap gap-x-4 gap-y-1 text-xs text-[#475467]"></div>
            <p id="weather-updated" class="mt-3 text-[10px] text-[#667085]"></p>
            <p class="mt-1 text-[10px] text-[#667085]">Sumber: <a href="https://data.bmkg.go.id/prakiraan-cuaca/" target="_blank" rel="noopener noreferrer" class="font-semibold text-[#00629B] underline">BMKG</a> · Data prakiraan, bukan penetapan status kekeringan.</p>
        </section>
        <script>
            (() => {
                const card = document.getElementById('weather-card');
                if (!card) return;

                fetch(card.dataset.url, { headers: { Accept: 'application/json' } })
                    .then((response) => {
                        if (!response.ok) throw new Error('Cuaca tidak tersedia');
                        return response.json();
                    })
                    .then((data) => {
                        document.getElementById('weather-location').textContent = data.location || @json($desa->nama_desa);
                        document.getElementById('weather-description').textContent = data.weather || 'Kondisi cuaca belum tersedia';

                        const details = document.getElementById('weather-details');
                        const values = [];
                        if (data.temperature !== null) values.push(`Suhu ${data.temperature}°C`);
                        if (data.humidity !== null) values.push(`Kelembapan ${data.humidity}%`);
                        if (data.wind_speed !== null) values.push(`Angin ${data.wind_speed} km/jam`);
                        details.textContent = values.join(' · ');
                        details.classList.remove('hidden');

                        const updated = data.local_datetime || data.analysis_date;
                        document.getElementById('weather-updated').textContent = updated ? `Waktu prakiraan: ${updated}` : '';
                    })
                    .catch(() => {
                        document.getElementById('weather-description').textContent = 'Prakiraan cuaca belum dapat dimuat. Coba muat ulang halaman.';
                    });
            })();
        </script>
    @endif

    <!-- 2. HERO STATUS CARD (F-02 Realtime Status & One Primary Action) -->
    @include('desa.components.hero-status')

    <!-- 3. PROGRESS STEPPER (5 Tahapan Penanganan Bantuan) -->
    @include('desa.components.stepper')

    <!-- GRID UTAMA 2 KOLOM (Pengajuan Terakhir & Ringkasan Desa) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 4. KARTU PENGAJUAN TERAKHIR (Mini Vertical Timeline) -->
        @include('desa.components.pengajuan-terakhir')

        <!-- 5. KARTU RINGKASAN DESA (Demografi, Peta Tandon GIS, Kontak WA, Kelengkapan Data) -->
        @include('desa.components.ringkasan-desa')
    </div>

    <!-- 6. KARTU AKTIVITAS TERBARU -->
    @include('desa.components.aktivitas-terbaru')

    <!-- 7. FOOTER BERANDA DESA -->
    <footer class="pt-6 border-t border-[#EAECF0] text-center text-xs text-[#667085]">
        <p>&copy; {{ date('Y') }} LinggaAir &ndash; BPBD Kabupaten Purbalingga</p>
    </footer>

</div>

<!-- BOTTOM NAVIGATION (Hanya Tampil di Layar Ponsel / Mobile) -->
@include('desa.components.mobile-nav')
@endsection
