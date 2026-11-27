@extends('layouts.dashboard')

@section('title', 'Dashboard BPBD - LinggaAir')
@section('page-title', 'Dashboard BPBD')

@push('styles')
<style>
    /* Sembunyikan default page heading dari layout agar header dashboard menyatu utuh, rapi, dan proporsional */
    .dashboard-page-heading {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    {{-- Header Section (Selaras Mockup Admin BPBD) --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-[#EAECF0]">
        <div>
            {{-- Title & Subtitle --}}
            <h1 class="text-xl lg:text-2xl font-bold text-[#101828] tracking-tight">
                Dashboard BPBD
            </h1>
            <p class="text-xs text-[#667085] mt-1">
                Pantau pengajuan dan koordinasikan penyaluran bantuan di Purbalingga.
            </p>
        </div>

        {{-- Action Controls (Sejajar di sebelah kanan) --}}
        <div class="flex items-center gap-2.5 self-start sm:self-center flex-wrap">
            {{-- Interactive Month Filter Dropdown --}}
            <div class="relative inline-block text-left" id="container-filter-bulan">
                <button
                    type="button"
                    id="btn-filter-bulan"
                    class="h-9 px-3.5 inline-flex items-center gap-2 rounded-lg border {{ $selectedBulan ? 'border-[#00629B] bg-[#EBF5FB] text-[#00629B]' : 'border-[#D0D5DD] bg-white text-[#344054]' }} text-xs font-semibold shadow-xs hover:bg-[#F8FAFC] transition-colors focus:ring-2 focus:ring-[#00629B]/20"
                    aria-expanded="false"
                    aria-haspopup="true"
                >
                    <i class="fa-regular fa-calendar {{ $selectedBulan ? 'text-[#00629B]' : 'text-[#667085]' }}"></i>
                    <span id="label-filter-bulan">{{ $selectedBulanLabel }}</span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-[#667085] transition-transform duration-200" id="icon-filter-bulan"></i>
                </button>

                {{-- Dropdown Menu Popover --}}
                <div
                    id="menu-filter-bulan"
                    class="hidden absolute right-0 top-full mt-1.5 w-72 rounded-xl bg-white border border-[#EAECF0] shadow-xl p-3 z-50 text-xs"
                    role="menu"
                    aria-orientation="vertical"
                >
                    <div class="flex items-center justify-between pb-2 mb-2 border-b border-[#F2F4F7]">
                        <span class="font-bold text-[#101828]">Pilih Periode Laporan</span>
                        @if ($selectedBulan)
                            <a href="{{ route('admin.dashboard') }}" class="text-[11px] font-semibold text-[#00629B] hover:underline">
                                Reset
                            </a>
                        @endif
                    </div>

                    {{-- Form Input Bulan Langsung --}}
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="mb-3">
                        <label for="input-bulan-custom" class="block text-[11px] font-medium text-[#475467] mb-1">
                            Pilih Bulan & Tahun
                        </label>
                        <div class="flex items-center gap-1.5">
                            <input
                                type="month"
                                id="input-bulan-custom"
                                name="bulan"
                                value="{{ $selectedBulan ?? \Carbon\Carbon::now()->format('Y-m') }}"
                                class="w-full text-xs px-2.5 py-1.5 rounded-lg border border-[#D0D5DD] focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]"
                                required
                            >
                            <button
                                type="submit"
                                class="px-3 py-1.5 rounded-lg bg-[#00629B] hover:bg-[#005180] text-white font-semibold whitespace-nowrap transition-colors"
                            >
                                Terapkan
                            </button>
                        </div>
                    </form>

                    {{-- Pilihan Cepat --}}
                    <div class="space-y-1">
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-[#98A2B3] px-2 py-0.5">
                            Pilihan Cepat
                        </span>

                        {{-- Opsi: Semua Periode --}}
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="flex items-center justify-between px-2.5 py-1.5 rounded-lg {{ !$selectedBulan ? 'bg-[#EBF5FB] font-semibold text-[#00629B]' : 'text-[#344054] hover:bg-[#F8FAFC]' }}"
                        >
                            <span>Semua Periode (Akumulasi)</span>
                            @if (!$selectedBulan)
                                <i class="fa-solid fa-check text-[11px] text-[#00629B]"></i>
                            @endif
                        </a>

                        {{-- Daftar 6 Bulan Terakhir --}}
                        @foreach ($recentMonths as $month)
                            @php
                                $isActive = ($selectedBulan === $month['value']);
                            @endphp
                            <a
                                href="{{ route('admin.dashboard', ['bulan' => $month['value']]) }}"
                                class="flex items-center justify-between px-2.5 py-1.5 rounded-lg {{ $isActive ? 'bg-[#EBF5FB] font-semibold text-[#00629B]' : 'text-[#344054] hover:bg-[#F8FAFC]' }}"
                            >
                                <span>{{ $month['label'] }}</span>
                                @if ($isActive)
                                    <i class="fa-solid fa-check text-[11px] text-[#00629B]"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            @if (Route::has('laporan.index'))
                <a href="{{ route('laporan.index') }}" class="h-9 px-3.5 inline-flex items-center gap-2 rounded-lg bg-[#00629B] hover:bg-[#005180] text-white text-xs font-semibold shadow-xs transition-colors">
                    <i class="fa-solid fa-download text-[11px]"></i>
                    <span>Unduh Laporan</span>
                </a>
            @endif
        </div>
    </div>

    {{-- PBI-016: 4 Kartu Metrik Agregat Utama --}}
    <section aria-labelledby="section-ringkasan-metrik">
        <h2 id="section-ringkasan-metrik" class="sr-only">Ringkasan Metrik Dashboard</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Kartu 1: Total Pengajuan --}}
            <div class="bg-white border border-[#EAECF0] rounded-xl p-4 shadow-sm hover:shadow transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-[#475467]">Total pengajuan</span>
                    <div class="w-8 h-8 rounded-lg bg-[#EBF5FB] text-[#00629B] flex items-center justify-center text-sm">
                        <i class="fa-regular fa-file-lines"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-2xl lg:text-3xl font-bold text-[#101828] tabular-nums tracking-tight">
                        {{ number_format($totalPengajuan, 0, ',', '.') }}
                    </span>
                    <p class="text-xs text-[#667085] mt-1 flex items-center gap-1">
                        <span>Dari {{ number_format($desaTerdampak, 0, ',', '.') }} desa terdampak</span>
                    </p>
                </div>
            </div>

            {{-- Kartu 2: Menunggu Verifikasi --}}
            <div class="bg-white border border-[#EAECF0] rounded-xl p-4 shadow-sm hover:shadow transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-[#475467]">Menunggu verifikasi</span>
                    <div class="w-8 h-8 rounded-lg bg-[#FEF0C7] text-[#D97706] flex items-center justify-center text-sm">
                        <i class="fa-regular fa-clock"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-2xl lg:text-3xl font-bold text-[#D97706] tabular-nums tracking-tight">
                        {{ number_format($menungguVerifikasi, 0, ',', '.') }}
                    </span>
                    <p class="text-xs text-[#667085] mt-1 flex items-center gap-1">
                        @if ($menungguVerifikasi > 0)
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#D97706]"></span>
                            <span>Status Diajukan & Dalam Telaah</span>
                        @else
                            <span class="text-[#059669]">Tidak ada antrean tertunda</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Kartu 3: Desa Terdampak --}}
            <div class="bg-white border border-[#EAECF0] rounded-xl p-4 shadow-sm hover:shadow transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-[#475467]">Desa terdampak</span>
                    <div class="w-8 h-8 rounded-lg bg-[#F2F4F7] text-[#344054] flex items-center justify-center text-sm">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-2xl lg:text-3xl font-bold text-[#101828] tabular-nums tracking-tight">
                        {{ number_format($desaTerdampak, 0, ',', '.') }}
                    </span>
                    <p class="text-xs text-[#667085] mt-1 flex items-center gap-1">
                        <span>Dari {{ number_format($totalDesa, 0, ',', '.') }} desa di Purbalingga</span>
                    </p>
                </div>
            </div>

            {{-- Kartu 4: Distribusi Selesai --}}
            <div class="bg-white border border-[#EAECF0] rounded-xl p-4 shadow-sm hover:shadow transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-[#475467]">Penyaluran selesai</span>
                    <div class="w-8 h-8 rounded-lg bg-[#D1FADF] text-[#059669] flex items-center justify-center text-sm">
                        <i class="fa-regular fa-circle-check"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-2xl lg:text-3xl font-bold text-[#059669] tabular-nums tracking-tight">
                        {{ number_format($distribusiSelesai, 0, ',', '.') }}
                    </span>
                    <p class="text-xs text-[#667085] mt-1 flex items-center gap-1">
                        <span>Penyaluran air selesai tuntas</span>
                    </p>
                </div>
            </div>

        </div>
    </section>

    {{-- Sebaran per Kecamatan: 2 Grafik Terpisah (Volume Air + Jumlah Pengajuan) --}}
    <div class="bg-white border border-[#EAECF0] rounded-xl p-5 shadow-sm">
        <div class="pb-3 border-b border-[#F2F4F7]">
            <h3 class="text-sm font-bold text-[#101828]">Grafik Sebaran & Kebutuhan Air Bersih</h3>
            <p class="text-xs text-[#667085] mt-0.5">Agregasi volume air (liter) dan frekuensi pengajuan darurat per kecamatan</p>
        </div>

        {{-- Form Filter Periode & Kecamatan (Tanpa Reload Halaman) --}}
        <form id="chart-filter-form" class="mt-3.5 grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-end bg-[#F8FAFC] border border-[#EAECF0] rounded-lg p-3">
            <div class="sm:col-span-4">
                <label for="chart-filter-kecamatan" class="block text-[11px] font-semibold text-[#475467] mb-1">
                    Kecamatan
                </label>
                <select id="chart-filter-kecamatan" class="w-full h-8 px-2.5 bg-white border border-[#D0D5DD] rounded-lg text-xs text-[#344054] focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]">
                    <option value="">Semua Kecamatan ({{ $kecamatans->count() }})</option>
                    @foreach ($kecamatans as $kec)
                        <option value="{{ $kec }}">{{ $kec }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-3">
                <label for="chart-filter-start" class="block text-[11px] font-semibold text-[#475467] mb-1">
                    Tanggal Mulai
                </label>
                <input
                    type="date"
                    id="chart-filter-start"
                    class="w-full h-8 px-2.5 bg-white border border-[#D0D5DD] rounded-lg text-xs text-[#344054] focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]"
                >
            </div>

            <div class="sm:col-span-3">
                <label for="chart-filter-end" class="block text-[11px] font-semibold text-[#475467] mb-1">
                    Tanggal Akhir
                </label>
                <input
                    type="date"
                    id="chart-filter-end"
                    class="w-full h-8 px-2.5 bg-white border border-[#D0D5DD] rounded-lg text-xs text-[#344054] focus:border-[#00629B] focus:ring-1 focus:ring-[#00629B]"
                >
            </div>

            <div class="sm:col-span-2 flex items-center gap-1.5">
                <button
                    type="submit"
                    id="btn-apply-chart-filter"
                    class="w-full h-8 px-3 rounded-lg bg-[#00629B] hover:bg-[#005180] text-white text-xs font-semibold shadow-xs flex items-center justify-center gap-1.5 transition-colors"
                >
                    <i class="fa-solid fa-filter text-[10px]"></i>
                    <span>Filter</span>
                </button>
                <button
                    type="button"
                    id="btn-reset-chart-filter"
                    title="Reset Filter Grafik"
                    class="h-8 px-2.5 rounded-lg border border-[#D0D5DD] bg-white hover:bg-[#F2F4F7] text-[#667085] text-xs transition-colors flex items-center justify-center"
                >
                    <i class="fa-solid fa-rotate-left text-[11px]"></i>
                </button>
            </div>
        </form>

        {{-- Error Banner Validasi Tanggal --}}
        <div id="chart-error-banner" class="hidden mt-2 p-2.5 rounded-lg bg-[#FEF3F2] border border-[#FECDCA] text-[#B42318] text-xs flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-[#D92D20]"></i>
            <span id="chart-error-message"></span>
        </div>

        {{-- Container: Loading & Empty State --}}
        <div class="relative mt-4">
            {{-- Spinner Loading State --}}
            <div id="chart-loading" class="hidden absolute inset-0 bg-white/80 backdrop-blur-[1px] flex flex-col items-center justify-center z-10 rounded-lg min-h-[280px]">
                <i class="fa-solid fa-circle-notch fa-spin text-2xl text-[#00629B] mb-2"></i>
                <span class="text-xs font-medium text-[#475467]">Memuat data grafik...</span>
            </div>

            {{-- Empty State --}}
            <div id="chart-empty" class="hidden flex flex-col items-center justify-center py-12 text-center w-full">
                <div class="w-12 h-12 rounded-full bg-[#F2F4F7] text-[#667085] flex items-center justify-center text-lg mb-2">
                    <i class="fa-solid fa-chart-simple"></i>
                </div>
                <p class="text-xs font-semibold text-[#101828]">Tidak ada data transaksi</p>
                <p class="text-[11px] text-[#667085] mt-0.5">Tidak ditemukan pengajuan kekeringan pada rentang filter yang dipilih.</p>
            </div>

            {{-- 2 Grafik Terpisah: Volume Air & Jumlah Pengajuan --}}
            <div id="chart-grid" class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                {{-- Grafik 1: Volume Air (Liter) per Kecamatan --}}
                <div class="bg-white border border-[#EAECF0] rounded-xl p-4 shadow-xs">
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-[#F2F4F7]">
                        <div>
                            <h4 class="text-xs font-bold text-[#101828]">Volume Kebutuhan Air (Liter)</h4>
                            <p class="text-[11px] text-[#667085] mt-0.5">Estimasi kebutuhan air bersih per kecamatan</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#EBF5FB] text-[#00629B]">
                            Liter
                        </span>
                    </div>
                    {{-- Bingkai Kotak Biru Khas Diagram Batang Buku Standar --}}
                    <div class="border-2 border-[#5B9BD5] rounded-sm p-3 bg-white h-[320px]">
                        <canvas id="volumeChart"></canvas>
                    </div>
                </div>

                {{-- Grafik 2: Jumlah Pengajuan per Kecamatan --}}
                <div class="bg-white border border-[#EAECF0] rounded-xl p-4 shadow-xs">
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-[#F2F4F7]">
                        <div>
                            <h4 class="text-xs font-bold text-[#101828]">Frekuensi Pengajuan Darurat</h4>
                            <p class="text-[11px] text-[#667085] mt-0.5">Jumlah berkas permohonan masuk</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#EBF5FB] text-[#00629B]">
                            Pengajuan
                        </span>
                    </div>
                    {{-- Bingkai Kotak Biru Khas Diagram Batang Buku Standar --}}
                    <div class="border-2 border-[#5B9BD5] rounded-sm p-3 bg-white h-[320px]">
                        <canvas id="pengajuanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid Bagian Bawah: Tren & Action Panel --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        {{-- Kolom Kiri: Tren Pengajuan & Penyaluran (Line Chart + Tab Switcher) --}}
        <div class="lg:col-span-8 bg-white border border-[#EAECF0] rounded-xl p-5 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-[#F2F4F7]">
                <div>
                    <h3 class="text-sm font-bold text-[#101828]">Tren Pengajuan & Penyaluran</h3>
                    <p class="text-xs text-[#667085] mt-0.5" id="trend-subtitle">Ringkasan aktivitas harian pengajuan dan distribusi air bersih</p>
                </div>
                {{-- Tab Switcher (Mirip contoh "Transaksi | Pesanan") --}}
                <div class="inline-flex rounded-lg border border-[#D0D5DD] bg-[#F8FAFC] p-0.5">
                    <button type="button" id="tab-pengajuan"
                        class="px-3.5 py-1.5 text-xs font-semibold rounded-md bg-[#00629B] text-white shadow-sm transition-colors">
                        Pengajuan
                    </button>
                    <button type="button" id="tab-penyaluran"
                        class="px-3.5 py-1.5 text-xs font-semibold rounded-md text-[#667085] hover:text-[#344054] transition-colors">
                        Penyaluran
                    </button>
                </div>
            </div>

            {{-- Container Grafik Tren --}}
            <div class="relative w-full min-h-[280px] mt-4 flex items-center justify-center">
                {{-- Spinner Loading --}}
                <div id="trend-loading" class="hidden absolute inset-0 bg-white/80 backdrop-blur-[1px] flex flex-col items-center justify-center z-10 rounded-lg">
                    <i class="fa-solid fa-circle-notch fa-spin text-2xl text-[#00629B] mb-2"></i>
                    <span class="text-xs font-medium text-[#475467]">Memuat data tren...</span>
                </div>
                {{-- Empty State --}}
                <div id="trend-empty" class="hidden flex flex-col items-center justify-center py-12 text-center w-full">
                    <div class="w-12 h-12 rounded-full bg-[#F2F4F7] text-[#667085] flex items-center justify-center text-lg mb-2">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <p class="text-xs font-semibold text-[#101828]">Belum ada data tren</p>
                    <p class="text-[11px] text-[#667085] mt-0.5">Tidak ditemukan aktivitas pada rentang waktu ini.</p>
                </div>
                {{-- Canvas Tren --}}
                <div class="w-full h-[280px]" id="trend-canvas-wrapper">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Perlu Ditindaklanjuti & Pintasan --}}
        <div class="lg:col-span-4 space-y-4">

            {{-- Card: Perlu Ditindaklanjuti --}}
            <div class="bg-white border border-[#EAECF0] rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between pb-3 border-b border-[#F2F4F7]">
                    <h3 class="text-sm font-bold text-[#101828]">Perlu Ditindaklanjuti</h3>
                    <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full {{ $menungguVerifikasi > 0 ? 'bg-[#FEF0C7] text-[#B54708]' : 'bg-[#ECFDF3] text-[#027A48]' }}">
                        {{ $menungguVerifikasi }} pengajuan
                    </span>
                </div>

                <div class="mt-4">
                    @if ($menungguVerifikasi > 0)
                        <div class="p-3.5 rounded-lg bg-[#FEF0C7]/50 border border-[#FEDF89] text-[#7A2E0E]">
                            <div class="flex items-start gap-2.5">
                                <i class="fa-regular fa-clock text-sm text-[#D97706] mt-0.5"></i>
                                <div class="text-xs">
                                    <strong class="font-semibold block text-[#7A2E0E]">{{ $menungguVerifikasi }} pengajuan butuh verifikasi</strong>
                                    <span class="text-[#93370D] mt-0.5 block">Tinjau kelengkapan dan kelayakan desa agar armada bantuan dapat dijadwalkan.</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="p-3.5 rounded-lg bg-[#ECFDF3] border border-[#A6F4C5] text-[#027A48]">
                            <div class="flex items-start gap-2.5">
                                <i class="fa-regular fa-circle-check text-sm text-[#059669] mt-0.5"></i>
                                <div class="text-xs">
                                    <strong class="font-semibold block text-[#027A48]">Antrean Bersih</strong>
                                    <span class="text-[#05603A] mt-0.5 block">Semua pengajuan yang masuk telah diverifikasi oleh tim BPBD.</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (Route::has('verifikasi.index'))
                        <a href="{{ route('verifikasi.index') }}" class="w-full mt-4 inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-[#00629B] hover:bg-[#005180] text-white text-xs font-semibold shadow-sm transition-colors">
                            <span>Buka Verifikasi Pengajuan</span>
                            <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Card Ringkas: Pintasan Cepat --}}
            <div class="bg-white border border-[#EAECF0] rounded-xl p-4 shadow-sm">
                <h4 class="text-xs font-bold text-[#344054] mb-2 uppercase tracking-wider">Akses Cepat BPBD</h4>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    @if (Route::has('admin.pemetaan.index'))
                        <a href="{{ route('admin.pemetaan.index') }}" class="flex items-center gap-2 p-2 rounded-lg border border-[#EAECF0] hover:bg-[#F8FAFC] text-[#344054] transition-colors">
                            <i class="fa-solid fa-map text-[#00629B]"></i>
                            <span>Peta Distribusi</span>
                        </a>
                    @endif
                    @if (Route::has('admin.desa.index'))
                        <a href="{{ route('admin.desa.index') }}" class="flex items-center gap-2 p-2 rounded-lg border border-[#EAECF0] hover:bg-[#F8FAFC] text-[#344054] transition-colors">
                            <i class="fa-solid fa-database text-[#00629B]"></i>
                            <span>Master Desa</span>
                        </a>
                    @endif
                </div>
            </div>

        </div>

    </div>

    {{-- Section Pengumuman Status Kedaruratan (PBI-018) --}}
    <div class="bg-white border border-[#EAECF0] rounded-xl p-5 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-[#F2F4F7]">
            <div>
                <h3 class="text-sm font-bold text-[#101828]">Pengumuman Status Kekeringan & Kebijakan Darurat</h3>
                <p class="text-xs text-[#667085] mt-0.5">Kelola maklumat publik, status siaga, dan instruksi penanganan bencana BPBD.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.pengumuman.index') }}" class="h-8 px-3 rounded-lg border border-[#D0D5DD] bg-white hover:bg-[#F2F4F7] text-[#344054] text-xs font-semibold inline-flex items-center gap-1.5 transition-colors">
                    <span>Kelola Semua</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
                <a href="{{ route('admin.pengumuman.create') }}" class="h-8 px-3 rounded-lg bg-[#00629B] hover:bg-[#005180] text-white text-xs font-semibold shadow-xs inline-flex items-center gap-1.5 transition-colors">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Buat Pengumuman</span>
                </a>
            </div>
        </div>

        <div class="mt-4">
            @if (!isset($pengumumans) || $pengumumans->isEmpty())
                <div class="flex flex-col items-center justify-center py-8 text-center text-xs text-[#667085]">
                    <i class="fa-regular fa-bell-slash text-2xl text-[#98A2B3] mb-2"></i>
                    <p class="font-semibold text-[#101828]">Belum ada pengumuman kedaruratan</p>
                    <p class="text-[11px] text-[#667085] mt-0.5">Buat pengumuman baru untuk menyampaikan status siaga ke aparatur desa dan publik.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[#F8FAFC] border-b border-[#EAECF0] text-[#475467] font-semibold">
                            <tr>
                                <th class="py-2.5 px-3">Judul Pengumuman</th>
                                <th class="py-2.5 px-3 w-28">Kedaruratan</th>
                                <th class="py-2.5 px-3 w-32">Waktu Terbit</th>
                                <th class="py-2.5 px-3 w-28">Status</th>
                                <th class="py-2.5 px-3 w-24 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#EAECF0] text-[#344054]">
                            @foreach ($pengumumans as $item)
                                <tr class="hover:bg-[#F8FAFC]/75 transition-colors">
                                    <td class="py-3 px-3">
                                        <div class="font-semibold text-[#101828] text-xs">
                                            {{ $item->judul }}
                                        </div>
                                        <div class="text-[#667085] text-[11px] line-clamp-1 mt-0.5">
                                            {{ Str::limit(strip_tags($item->isi), 85) }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold border {{ $item->badge_class }}">
                                            @if ($item->tingkat_kelayakan === 'kritis')
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#D92D20]"></span>
                                            @elseif ($item->tingkat_kelayakan === 'waspada')
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#B54708]"></span>
                                            @else
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#027A48]"></span>
                                            @endif
                                            <span>{{ $item->tingkat_label }}</span>
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-[#667085] whitespace-nowrap">
                                        {{ $item->published_at ? $item->published_at->translatedFormat('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        <form action="{{ route('admin.pengumuman.toggle-aktif', $item) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                title="Klik untuk ubah status"
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-medium transition-colors border {{ $item->is_active ? 'bg-[#ECFDF3] border-[#A6F4C5] text-[#027A48]' : 'bg-[#F2F4F7] border-[#EAECF0] text-[#667085]' }}"
                                            >
                                                <i class="fa-solid {{ $item->is_active ? 'fa-check text-[10px]' : 'fa-pause text-[10px]' }}"></i>
                                                <span>{{ $item->is_active ? 'Aktif' : 'Draft' }}</span>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="py-3 px-3 text-right whitespace-nowrap">
                                        <a
                                            href="{{ route('admin.pengumuman.edit', $item) }}"
                                            class="h-6 px-2 rounded border border-[#D0D5DD] bg-white hover:bg-[#F2F4F7] text-[#344054] inline-flex items-center gap-1 text-[11px] transition-colors"
                                            title="Ubah"
                                        >
                                            <i class="fa-solid fa-pen-to-square text-[9px]"></i>
                                            <span>Ubah</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // =====================================================
        // 1. Dropdown Filter Periode Bulan (PBI-016)
        // =====================================================
        const btnFilter = document.getElementById('btn-filter-bulan');
        const menuFilter = document.getElementById('menu-filter-bulan');
        const iconFilter = document.getElementById('icon-filter-bulan');
        const containerFilter = document.getElementById('container-filter-bulan');

        if (btnFilter && menuFilter) {
            function toggleDropdown(show) {
                const isHidden = menuFilter.classList.contains('hidden');
                const shouldOpen = typeof show === 'boolean' ? show : isHidden;

                if (shouldOpen) {
                    menuFilter.classList.remove('hidden');
                    btnFilter.setAttribute('aria-expanded', 'true');
                    if (iconFilter) iconFilter.classList.add('rotate-180');
                } else {
                    menuFilter.classList.add('hidden');
                    btnFilter.setAttribute('aria-expanded', 'false');
                    if (iconFilter) iconFilter.classList.remove('rotate-180');
                }
            }

            btnFilter.addEventListener('click', function (e) {
                e.stopPropagation();
                toggleDropdown();
            });

            document.addEventListener('click', function (e) {
                if (containerFilter && !containerFilter.contains(e.target)) {
                    toggleDropdown(false);
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    toggleDropdown(false);
                }
            });
        }

        // =====================================================
        // 2. Grafik Tren Pengajuan & Penyaluran (Line Chart)
        // =====================================================
        let trendChartInstance = null;
        let trendDataCache = [];
        let activeTab = 'pengajuan';

        const tabPengajuan = document.getElementById('tab-pengajuan');
        const tabPenyaluran = document.getElementById('tab-penyaluran');

        const TAB_ACTIVE_CLASS = 'bg-[#00629B] text-white shadow-sm';
        const TAB_INACTIVE_CLASS = 'text-[#667085] hover:text-[#344054]';

        function setActiveTab(tab) {
            activeTab = tab;
            if (tab === 'pengajuan') {
                tabPengajuan.className = 'px-3.5 py-1.5 text-xs font-semibold rounded-md ' + TAB_ACTIVE_CLASS + ' transition-colors';
                tabPenyaluran.className = 'px-3.5 py-1.5 text-xs font-semibold rounded-md ' + TAB_INACTIVE_CLASS + ' transition-colors';
            } else {
                tabPenyaluran.className = 'px-3.5 py-1.5 text-xs font-semibold rounded-md ' + TAB_ACTIVE_CLASS + ' transition-colors';
                tabPengajuan.className = 'px-3.5 py-1.5 text-xs font-semibold rounded-md ' + TAB_INACTIVE_CLASS + ' transition-colors';
            }
            renderTrendChart();
        }

        if (tabPengajuan) {
            tabPengajuan.addEventListener('click', function () { setActiveTab('pengajuan'); });
        }
        if (tabPenyaluran) {
            tabPenyaluran.addEventListener('click', function () { setActiveTab('penyaluran'); });
        }

        async function loadTrendData(params = {}) {
            const loading = document.getElementById('trend-loading');
            const empty = document.getElementById('trend-empty');
            const canvasWrapper = document.getElementById('trend-canvas-wrapper');

            if (loading) loading.classList.remove('hidden');

            const url = new URL("{{ route('admin.dashboard.chart-trend') }}", window.location.origin);
            Object.entries(params).forEach(([key, val]) => {
                if (val) url.searchParams.append(key, val);
            });

            try {
                const res = await fetch(url.toString(), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!res.ok) throw new Error('Gagal memuat data tren.');

                const json = await res.json();
                trendDataCache = json.data || [];

                if (trendDataCache.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    if (canvasWrapper) canvasWrapper.classList.add('hidden');
                    if (trendChartInstance) {
                        trendChartInstance.destroy();
                        trendChartInstance = null;
                    }
                } else {
                    if (empty) empty.classList.add('hidden');
                    if (canvasWrapper) canvasWrapper.classList.remove('hidden');
                    renderTrendChart();
                }
            } catch (err) {
                console.error('Trend error:', err);
            } finally {
                if (loading) loading.classList.add('hidden');
            }
        }

        function renderTrendChart() {
            const ctx = document.getElementById('trendChart');
            if (!ctx || trendDataCache.length === 0) return;

            const labels = trendDataCache.map(r => {
                const d = new Date(r.tanggal + 'T00:00:00');
                return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
            });

            const isPengajuan = activeTab === 'pengajuan';
            const dataValues = trendDataCache.map(r => isPengajuan ? r.jumlah_pengajuan : r.jumlah_selesai);
            const lineColor = isPengajuan ? '#059669' : '#00629B';
            const fillColor = isPengajuan ? 'rgba(5, 150, 105, 0.08)' : 'rgba(0, 98, 155, 0.08)';
            const labelText = isPengajuan ? 'Pengajuan Masuk' : 'Penyaluran Selesai';

            if (trendChartInstance) {
                trendChartInstance.destroy();
            }

            trendChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: labelText,
                        data: dataValues,
                        borderColor: lineColor,
                        backgroundColor: fillColor,
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: lineColor,
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#101828',
                            titleFont: { size: 12, family: 'Poppins', weight: 'bold' },
                            bodyFont: { size: 11, family: 'Poppins' },
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: function (context) {
                                    return context.dataset.label + ': ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 11, family: 'Poppins' },
                                color: '#475467'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#F2F4F7' },
                            ticks: {
                                font: { size: 10, family: 'Poppins' },
                                color: '#667085',
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        // Load tren saat halaman dimuat
        loadTrendData();

        // =====================================================
        // 3. Grafik Sebaran per Kecamatan (2 Chart Terpisah)
        // =====================================================
        let volumeChartInstance = null;
        let pengajuanChartInstance = null;

        async function loadChartData(params = {}) {
            const loading = document.getElementById('chart-loading');
            const empty = document.getElementById('chart-empty');
            const chartGrid = document.getElementById('chart-grid');
            const errorBanner = document.getElementById('chart-error-banner');
            const errorMsg = document.getElementById('chart-error-message');

            if (errorBanner) errorBanner.classList.add('hidden');
            if (loading) loading.classList.remove('hidden');

            const url = new URL("{{ route('admin.dashboard.chart-data') }}", window.location.origin);
            Object.entries(params).forEach(([key, val]) => {
                if (val) url.searchParams.append(key, val);
            });

            try {
                const res = await fetch(url.toString(), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!res.ok) {
                    const errData = await res.json().catch(() => ({}));
                    let msg = 'Gagal memuat data grafik.';
                    if (errData.errors) {
                        msg = Object.values(errData.errors).flat().join(' ');
                    } else if (errData.message) {
                        msg = errData.message;
                    }
                    throw new Error(msg);
                }

                const json = await res.json();
                const rows = json.data || [];

                if (rows.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    if (chartGrid) chartGrid.classList.add('hidden');
                    destroyKecamatanCharts();
                } else {
                    if (empty) empty.classList.add('hidden');
                    if (chartGrid) chartGrid.classList.remove('hidden');
                    renderKecamatanCharts(rows);
                }
            } catch (err) {
                if (errorBanner && errorMsg) {
                    errorMsg.textContent = err.message || 'Terjadi kesalahan saat memuat grafik.';
                    errorBanner.classList.remove('hidden');
                }
            } finally {
                if (loading) loading.classList.add('hidden');
            }
        }

        function destroyKecamatanCharts() {
            if (volumeChartInstance) { volumeChartInstance.destroy(); volumeChartInstance = null; }
            if (pengajuanChartInstance) { pengajuanChartInstance.destroy(); pengajuanChartInstance = null; }
        }

        function renderKecamatanCharts(dataRows) {
            const labels = dataRows.map(r => r.kecamatan);
            const volumes = dataRows.map(r => r.volume_air_liter);
            const pengajuans = dataRows.map(r => r.jumlah_pengajuan);

            destroyKecamatanCharts();

            const sharedBarOptions = {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#101828',
                        titleFont: { size: 12, family: 'Poppins', weight: 'bold' },
                        bodyFont: { size: 11, family: 'Poppins' },
                        padding: 10,
                        cornerRadius: 4,
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Kecamatan',
                            font: { size: 12, family: 'Poppins', weight: 'bold' },
                            color: '#101828',
                            padding: { top: 8 }
                        },
                        grid: {
                            display: false,
                        },
                        border: {
                            display: true,
                            color: '#1E293B',
                            width: 2,
                        },
                        ticks: {
                            font: { size: 11, family: 'Poppins', weight: '600' },
                            color: '#101828',
                            maxRotation: 45,
                            minRotation: 0,
                            padding: 6
                        }
                    }
                }
            };

            // Grafik 1: Volume Air (Liter) per Kecamatan - Format Diagram Batang Klasik
            const ctxVolume = document.getElementById('volumeChart');
            if (ctxVolume) {
                volumeChartInstance = new Chart(ctxVolume, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Volume Air (Liter)',
                            data: volumes,
                            backgroundColor: '#2492D3',
                            hoverBackgroundColor: '#1A7BB8',
                            borderRadius: 0,
                            borderSkipped: false,
                            barPercentage: 0.55,
                            categoryPercentage: 0.75,
                            maxBarThickness: 45,
                        }]
                    },
                    options: {
                        ...sharedBarOptions,
                        scales: {
                            ...sharedBarOptions.scales,
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Volume Kebutuhan Air (Liter)',
                                    font: { size: 12, family: 'Poppins', weight: 'bold' },
                                    color: '#101828',
                                    padding: { bottom: 8 }
                                },
                                border: {
                                    display: true,
                                    color: '#1E293B',
                                    width: 2,
                                },
                                grid: {
                                    display: true,
                                    color: '#475467',
                                    lineWidth: 1.2,
                                    drawTicks: true,
                                    tickLength: 6,
                                    tickColor: '#1E293B',
                                },
                                ticks: {
                                    font: { size: 11, family: 'Poppins', weight: '600' },
                                    color: '#101828',
                                    padding: 6,
                                    callback: function (val) {
                                        return Number(val).toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        plugins: {
                            ...sharedBarOptions.plugins,
                            tooltip: {
                                ...sharedBarOptions.plugins.tooltip,
                                callbacks: {
                                    label: function (context) {
                                        return 'Volume: ' + Number(context.parsed.y).toLocaleString('id-ID') + ' Liter';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Grafik 2: Jumlah Pengajuan per Kecamatan - Format Diagram Batang Klasik
            const ctxPengajuan = document.getElementById('pengajuanChart');
            if (ctxPengajuan) {
                pengajuanChartInstance = new Chart(ctxPengajuan, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Pengajuan',
                            data: pengajuans,
                            backgroundColor: '#2492D3',
                            hoverBackgroundColor: '#1A7BB8',
                            borderRadius: 0,
                            borderSkipped: false,
                            barPercentage: 0.55,
                            categoryPercentage: 0.75,
                            maxBarThickness: 45,
                        }]
                    },
                    options: {
                        ...sharedBarOptions,
                        scales: {
                            ...sharedBarOptions.scales,
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Frekuensi Pengajuan Darurat',
                                    font: { size: 12, family: 'Poppins', weight: 'bold' },
                                    color: '#101828',
                                    padding: { bottom: 8 }
                                },
                                border: {
                                    display: true,
                                    color: '#1E293B',
                                    width: 2,
                                },
                                grid: {
                                    display: true,
                                    color: '#475467',
                                    lineWidth: 1.2,
                                    drawTicks: true,
                                    tickLength: 6,
                                    tickColor: '#1E293B',
                                },
                                ticks: {
                                    font: { size: 11, family: 'Poppins', weight: '600' },
                                    color: '#101828',
                                    padding: 6,
                                    stepSize: 1,
                                    precision: 0
                                }
                            }
                        },
                        plugins: {
                            ...sharedBarOptions.plugins,
                            tooltip: {
                                ...sharedBarOptions.plugins.tooltip,
                                callbacks: {
                                    label: function (context) {
                                        return 'Pengajuan: ' + context.parsed.y + ' Berkas';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }

        // Form Filter Event Listener
        const chartForm = document.getElementById('chart-filter-form');
        if (chartForm) {
            chartForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const kec = document.getElementById('chart-filter-kecamatan').value;
                const start = document.getElementById('chart-filter-start').value;
                const end = document.getElementById('chart-filter-end').value;

                const errorBanner = document.getElementById('chart-error-banner');
                const errorMsg = document.getElementById('chart-error-message');

                if (start && end && start > end) {
                    if (errorBanner && errorMsg) {
                        errorMsg.textContent = 'Tanggal akhir harus sama dengan atau setelah tanggal mulai.';
                        errorBanner.classList.remove('hidden');
                    }
                    return;
                }

                const filters = {
                    kecamatan: kec,
                    start_date: start,
                    end_date: end
                };
                loadChartData(filters);
                loadTrendData(filters);
            });

            const btnReset = document.getElementById('btn-reset-chart-filter');
            if (btnReset) {
                btnReset.addEventListener('click', function () {
                    document.getElementById('chart-filter-kecamatan').value = '';
                    document.getElementById('chart-filter-start').value = '';
                    document.getElementById('chart-filter-end').value = '';
                    loadChartData({});
                    loadTrendData({});
                });
            }
        }

        // Load grafik sebaran saat halaman dimuat
        loadChartData();
    });
</script>
@endpush
