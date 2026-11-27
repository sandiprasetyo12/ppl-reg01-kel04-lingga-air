@extends('layouts.desa')

@section('title', 'Cek Kelayakan Mandiri - LinggaAir')

@push('styles')
<style>
    *, input, button, select, textarea {
        font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif !important;
    }
    .layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 18px;
        margin-top: 12px;
        align-items: stretch;
    }
    .layout.has-result {
        grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);
        align-items: start;
    }
    .fields-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px 14px;
    }
    .fields-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px 14px;
    }
    .field-span-2 {
        grid-column: span 2;
    }
    .input-wrapper {
        position: relative;
    }
    .input-unit {
        position: absolute;
        top: 50%;
        right: 12px;
        transform: translateY(-50%);
        font-size: 12px;
        font-weight: 500;
        color: #667085;
        pointer-events: none;
    }
    .form-input-compact {
        width: 100%;
        height: 38px;
        padding: 0 44px 0 12px;
        border: 1px solid #D0D5DD;
        border-radius: 8px;
        background: #fff;
        color: #101828;
        font-size: 13px;
        font-weight: 500;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .form-input-compact:focus {
        outline: none;
        border-color: #00629B;
        box-shadow: 0 0 0 3px rgba(0, 98, 155, 0.15);
    }
    .form-input-disabled {
        background: #F8FAFC !important;
        border-color: #EAECF0 !important;
        color: #475467 !important;
        cursor: not-allowed;
    }
    .form-input-compact[type="number"] {
        appearance: textfield;
        -moz-appearance: textfield;
    }
    .form-input-compact[type="number"]::-webkit-inner-spin-button,
    .form-input-compact[type="number"]::-webkit-outer-spin-button {
        margin: 0;
        -webkit-appearance: none;
    }

    @media (max-width: 900px) {
        .layout.has-result {
            grid-template-columns: minmax(0, 1fr);
        }
    }
    @media (max-width: 600px) {
        .fields-grid, .fields-grid-3 {
            grid-template-columns: minmax(0, 1fr);
            gap: 10px;
        }
        .field-span-2 {
            grid-column: auto;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-4">

    {{-- Header Halaman Ramping & IDDS Standard --}}
    <div class="pb-3 border-b border-[#EAECF0]">
        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-[#00629B] uppercase tracking-wider">
            <i class="fa-solid fa-calculator text-[10px]"></i>
            Layanan Desa
        </span>
        <h1 class="text-lg sm:text-xl font-bold text-[#101828] mt-1 tracking-tight">
            Cek Kelayakan Bantuan Air Bersih
        </h1>
        <p class="text-xs sm:text-sm text-[#667085] mt-0.5 max-w-2xl leading-relaxed">
            Periksa tingkat kedaruratan kekeringan desa secara mandiri berdasarkan kriteria kependudukan dan kondisi lapangan sebelum mengajukan permohonan.
        </p>
    </div>

    <div class="layout {{ isset($result) ? 'has-result' : 'no-result' }}">

        {{-- FORM SIMULASI --}}
        <section class="bg-white border border-[#D0D5DD] rounded-xl p-5 sm:p-6 shadow-xs space-y-4" aria-labelledby="form-simulasi-title">
            <div class="flex items-center justify-between pb-3 border-b border-[#EAECF0]">
                <div>
                    <h2 id="form-simulasi-title" class="text-sm sm:text-base font-bold text-[#101828]">
                        Data Simulasi Kelayakan
                    </h2>
                    <p class="text-xs text-[#667085] mt-0.5">
                        Isi kondisi lapangan aktual untuk menghitung skor prioritas bantuan.
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('cek-kelayakan.calculate') }}" class="space-y-4">
                @csrf

                {{-- Bagian 1: Data Profil Desa (Terkunci dari Database Profil) --}}
                <div class="bg-[#F8FAFC] border border-[#EAECF0] rounded-xl p-3.5 sm:p-4 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 pb-2 border-b border-[#EAECF0]">
                        <span class="text-xs font-bold text-[#344054] uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-lock text-[10px] text-[#00629B]"></i>
                            Profil Desa Terdaftar
                        </span>
                        <span class="text-xs text-[#00629B] font-semibold">
                            Desa {{ $desa->nama_desa }} &bull; Kec. {{ $desa->kecamatan }}
                        </span>
                    </div>

                    <div class="fields-grid">
                        <div>
                            <label class="block text-xs font-semibold text-[#344054] mb-1">
                                Jumlah KK Total
                            </label>
                            <div class="input-wrapper">
                                <input type="number" class="form-input-compact form-input-disabled" value="{{ $desa->jumlah_kk_total ?? '' }}" disabled aria-disabled="true">
                                <span class="input-unit">KK</span>
                            </div>
                            <p class="text-[11px] text-[#667085] mt-0.5">Total kepala keluarga terdata</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#344054] mb-1">
                                Jumlah Jiwa Total
                            </label>
                            <div class="input-wrapper">
                                <input type="number" class="form-input-compact form-input-disabled" value="{{ $desa->jumlah_jiwa_total ?? '' }}" disabled aria-disabled="true">
                                <span class="input-unit">jiwa</span>
                            </div>
                            <p class="text-[11px] text-[#667085] mt-0.5">Total warga desa</p>
                        </div>
                    </div>

                    <div class="fields-grid-3 pt-1">
                        <div>
                            <label class="block text-xs font-medium text-[#475467] mb-1">
                                Balita (Rentan)
                            </label>
                            <div class="input-wrapper">
                                <input type="number" class="form-input-compact form-input-disabled" value="{{ $desa->jumlah_balita ?? 0 }}" disabled aria-disabled="true">
                                <span class="input-unit">jiwa</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-[#475467] mb-1">
                                Lansia (Rentan)
                            </label>
                            <div class="input-wrapper">
                                <input type="number" class="form-input-compact form-input-disabled" value="{{ $desa->jumlah_lansia ?? 0 }}" disabled aria-disabled="true">
                                <span class="input-unit">jiwa</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-[#475467] mb-1">
                                Disabilitas
                            </label>
                            <div class="input-wrapper">
                                <input type="number" class="form-input-compact form-input-disabled" value="{{ $desa->jumlah_disabilitas ?? 0 }}" disabled aria-disabled="true">
                                <span class="input-unit">jiwa</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-[#667085] flex items-center gap-1.5 pt-0.5">
                        <i class="fa-solid fa-circle-info text-xs text-[#667085]"></i>
                        Data demografi terkunci dari profil desa. Ubah melalui menu profil bila terdapat pembaruan.
                    </p>
                </div>

                {{-- Bagian 2: Parameter Kondisi Lapangan (Aktual) --}}
                <div class="space-y-3 pt-1">
                    <span class="block text-xs font-bold text-[#101828] uppercase tracking-wider">
                        Parameter Kondisi Lapangan Terkini
                    </span>

                    <div class="fields-grid">
                        <div>
                            <label for="kk_terdampak" class="block text-xs font-semibold text-[#344054] mb-1">
                                Jumlah KK Terdampak <span class="text-[#B42318]">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input 
                                    id="kk_terdampak" 
                                    name="kk_terdampak" 
                                    type="number" 
                                    min="1" 
                                    max="{{ $desa->jumlah_kk_total ?? 99999 }}" 
                                    step="1" 
                                    value="{{ old('kk_terdampak', $input['kk_terdampak'] ?? '') }}" 
                                    placeholder="Contoh: 120"
                                    class="form-input-compact @error('kk_terdampak') border-[#FDA29B] ring-1 ring-[#FDA29B] @enderror" 
                                    required
                                >
                                <span class="input-unit">KK</span>
                            </div>
                            <p class="text-[11px] text-[#667085] mt-0.5">Maksimal: {{ number_format($desa->jumlah_kk_total ?? 0) }} KK</p>
                            @error('kk_terdampak')
                                <p role="alert" class="text-xs text-[#B42318] font-medium mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="hari_tanpa_air" class="block text-xs font-semibold text-[#344054] mb-1">
                                Durasi Hari Tanpa Air <span class="text-[#B42318]">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input 
                                    id="hari_tanpa_air" 
                                    name="hari_tanpa_air" 
                                    type="number" 
                                    min="0" 
                                    step="1" 
                                    value="{{ old('hari_tanpa_air', $input['hari_tanpa_air'] ?? '') }}" 
                                    placeholder="Contoh: 5"
                                    class="form-input-compact @error('hari_tanpa_air') border-[#FDA29B] ring-1 ring-[#FDA29B] @enderror" 
                                    required
                                >
                                <span class="input-unit">hari</span>
                            </div>
                            <p class="text-[11px] text-[#667085] mt-0.5">Bobot normalisasi maksimal pada 7 hari</p>
                            @error('hari_tanpa_air')
                                <p role="alert" class="text-xs text-[#B42318] font-medium mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field-span-2">
                            <label for="jarak_sumber_meter" class="block text-xs font-semibold text-[#344054] mb-1">
                                Jarak ke Sumber Air Alternatif Terdekat <span class="text-[#B42318]">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input 
                                    id="jarak_sumber_meter" 
                                    name="jarak_sumber_meter" 
                                    type="number" 
                                    min="0" 
                                    step="1" 
                                    value="{{ old('jarak_sumber_meter', $input['jarak_sumber_meter'] ?? '') }}" 
                                    placeholder="Contoh: 1500"
                                    class="form-input-compact @error('jarak_sumber_meter') border-[#FDA29B] ring-1 ring-[#FDA29B] @enderror" 
                                    required
                                >
                                <span class="input-unit">meter</span>
                            </div>
                            <p class="text-[11px] text-[#667085] mt-0.5">Jarak tempuh warga mengambil air terdekat (maks normalisasi 2.000 m)</p>
                            @error('jarak_sumber_meter')
                                <p role="alert" class="text-xs text-[#B42318] font-medium mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi Form --}}
                @php
                    $isAllFilled = !empty(old('kk_terdampak', $input['kk_terdampak'] ?? '')) &&
                                   !empty(old('hari_tanpa_air', $input['hari_tanpa_air'] ?? '')) &&
                                   !empty(old('jarak_sumber_meter', $input['jarak_sumber_meter'] ?? ''));
                @endphp
                <div class="pt-3 border-t border-[#EAECF0] flex items-center justify-between gap-2.5">
                    <a 
                        href="{{ route('cek-kelayakan.index') }}" 
                        id="btn-reset-form"
                        class="h-9 px-3.5 bg-white border border-[#D0D5DD] hover:bg-[#F2F4F7] active:bg-[#EAECF0] text-[#344054] text-xs font-semibold rounded-lg transition {{ $isAllFilled ? 'inline-flex' : 'hidden' }} items-center gap-1.5 shadow-xs"
                    >
                        <svg class="w-3.5 h-3.5 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                        </svg>
                        <span>Reset Form</span>
                    </a>

                    <button 
                        type="submit" 
                        class="h-9 px-5 bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white text-xs font-bold rounded-lg transition inline-flex items-center gap-2 shadow-xs cursor-pointer ml-auto"
                    >
                        <svg class="w-3.5 h-3.5 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm3.75-6h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm3.75-6h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                        </svg>
                        <span>Cek Kelayakan</span>
                    </button>
                </div>
            </form>
        </section>

        {{-- HASIL SIMULASI SPK AHP-SAW --}}
        @if (isset($result))
            @php
                $statusConfig = match ($result['status']) {
                    'kritis' => [
                        'label' => 'Kritis',
                        'icon' => 'fa-circle-exclamation',
                        'badge' => 'bg-[#FEF3F2] border-[#FECDCA] text-[#B42318]',
                        'cardBorder' => 'border-[#FECDCA]',
                        'accent' => 'text-[#B42318]',
                        'meaning' => 'Desa Anda berada dalam kondisi darurat tinggi dan membutuhkan distribusi air bersih segera.',
                        'action' => 'Segera buat pengajuan resmi bantuan air bersih. Siapkan surat permohonan Kepala Desa bertanda tangan dan foto dokumentasi tandon.',
                    ],
                    'waspada' => [
                        'label' => 'Waspada',
                        'icon' => 'fa-triangle-exclamation',
                        'badge' => 'bg-[#FFFAEB] border-[#FEDF89] text-[#B54708]',
                        'cardBorder' => 'border-[#FEDF89]',
                        'accent' => 'text-[#B54708]',
                        'meaning' => 'Kondisi air di desa Anda mulai menipis dan perlu dipantau secara berkala.',
                        'action' => 'Anda dapat mengajukan bantuan sekarang atau memantau kondisi dalam 1-2 hari ke depan bersama BPBD.',
                    ],
                    default => [
                        'label' => 'Aman',
                        'icon' => 'fa-circle-check',
                        'badge' => 'bg-[#ECFDF3] border-[#A6F4C5] text-[#027A48]',
                        'cardBorder' => 'border-[#A6F4C5]',
                        'accent' => 'text-[#027A48]',
                        'meaning' => 'Kondisi ketersediaan air bersih di desa Anda saat ini masih berada dalam batas aman terkendali.',
                        'action' => 'Belum memenuhi syarat distribusi air darurat BPBD. Tetap pantau dan lakukan simulasi ulang bila krisis air meningkat.',
                    ],
                };

                $reasonText = 'Sumber air alternatif berjarak '
                    . number_format((int) $input['jarak_sumber_meter'], 0, ',', '.') . ' m, durasi '
                    . number_format((int) $input['hari_tanpa_air'], 0, ',', '.') . ' hari tanpa air, dan '
                    . number_format((int) $input['kk_terdampak'], 0, ',', '.') . ' dari '
                    . number_format((int) $input['jumlah_kk_total'], 0, ',', '.') . ' KK ('
                    . number_format(((int) $input['kk_terdampak'] / max(1, (int) $input['jumlah_kk_total'])) * 100, 0, ',', '.') . '%) terdampak.';
            @endphp

            <aside id="hasil-cek" class="bg-white border {{ $statusConfig['cardBorder'] }} rounded-2xl p-5 sm:p-6 shadow-sm space-y-4 scroll-mt-20" aria-live="polite">
                {{-- Header Hasil Cek --}}
                <div class="flex items-center justify-between pb-3 border-b border-[#EAECF0]">
                    <span class="text-xs sm:text-sm font-bold text-[#101828] flex items-center gap-1.5">
                        <i class="fa-solid fa-chart-simple text-[#00629B]"></i>
                        Hasil Evaluasi
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full border text-xs font-bold {{ $statusConfig['badge'] }}">
                        <i class="fa-solid {{ $statusConfig['icon'] }} text-xs"></i>
                        Status: {{ $statusConfig['label'] }}
                    </span>
                </div>

                {{-- Box Skor Prioritas --}}
                <div class="p-4 rounded-xl bg-[#F8FAFC] border border-[#EAECF0] flex items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold text-[#667085] uppercase tracking-wider block">
                            Skor Prioritas Kebutuhan
                        </span>
                        <div class="flex items-baseline gap-1.5 mt-0.5">
                            <span class="text-3xl sm:text-4xl font-extrabold text-[#00629B] tracking-tight">
                                {{ number_format($result['score'], 2, ',', '.') }}
                            </span>
                            <span class="text-sm font-semibold text-[#667085]">/ 100</span>
                        </div>
                    </div>

                    <div class="text-right">
                        <span class="text-xs font-semibold text-[#667085] block">Klasifikasi Darurat</span>
                        <span class="text-base font-extrabold {{ $statusConfig['accent'] }}">
                            {{ $statusConfig['label'] }}
                        </span>
                    </div>
                </div>

                {{-- Uraian Hasil --}}
                <div class="space-y-2.5 text-xs sm:text-sm text-[#344054] leading-relaxed">
                    <p>
                        <strong>Analisis:</strong> {{ $statusConfig['meaning'] }}
                    </p>
                    <p class="text-xs sm:text-sm text-[#667085] bg-[#F8FAFC] p-3 rounded-lg border border-[#EAECF0]">
                        <i class="fa-solid fa-info-circle text-[#00629B] mr-1"></i>
                        {{ $reasonText }}
                    </p>
                </div>

                {{-- Rekomendasi Tindak Lanjut --}}
                <div class="p-3.5 rounded-xl bg-[#EBF5FB] border border-[#BAE3F5] text-xs sm:text-sm text-[#004F7D] space-y-1">
                    <span class="font-bold block text-xs sm:text-sm text-[#003B5C] flex items-center gap-1.5">
                        <i class="fa-solid fa-lightbulb text-[#00629B]"></i>
                        Rekomendasi Tindak Lanjut
                    </span>
                    <p class="text-xs sm:text-sm leading-relaxed">
                        {{ $statusConfig['action'] }}
                    </p>
                </div>

                {{-- Tombol Lanjut ke Pengajuan (Hanya jika Kritis / Waspada) --}}
                @if (in_array($result['status'], ['waspada', 'kritis'], true))
                    <div class="pt-2">
                        <a 
                            href="{{ route('pengajuan.create', ['riwayat_id' => $riwayat->id ?? null]) }}" 
                            class="w-full h-10 px-4 rounded-xl bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white text-xs sm:text-sm font-bold inline-flex items-center justify-center gap-2 shadow-xs transition cursor-pointer" 
                            id="btn-ajukan-pengajuan"
                        >
                            <span>Lanjutkan ke Pengajuan Bantuan Air</span>
                            <svg class="w-3.5 h-3.5 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                        <p class="text-xs text-[#667085] text-center mt-1.5">
                            Data simulasi ini akan otomatis diisi ke formulir permohonan bantuan.
                        </p>
                    </div>
                @else
                    <div class="p-3.5 rounded-lg bg-[#F2F4F7] text-center text-xs sm:text-sm text-[#475467]">
                        <i class="fa-solid fa-circle-check text-[#027A48] mr-1"></i>
                        Permohonan bantuan ditujukan bagi desa berstatus Kritis & Waspada.
                    </div>
                @endif
            </aside>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const kkInput = document.getElementById('kk_terdampak');
        const hariInput = document.getElementById('hari_tanpa_air');
        const jarakInput = document.getElementById('jarak_sumber_meter');
        const btnReset = document.getElementById('btn-reset-form');

        function updateResetButtonVisibility() {
            if (!btnReset || !kkInput || !hariInput || !jarakInput) return;
            const isAllFilled = kkInput.value.trim() !== '' && 
                                hariInput.value.trim() !== '' && 
                                jarakInput.value.trim() !== '';
            if (isAllFilled) {
                btnReset.classList.remove('hidden');
                btnReset.classList.add('inline-flex');
            } else {
                btnReset.classList.add('hidden');
                btnReset.classList.remove('inline-flex');
            }
        }

        if (btnReset) {
            btnReset.addEventListener('click', (e) => {
                @if(isset($result))
                    return;
                @else
                    e.preventDefault();
                    if (kkInput) kkInput.value = '';
                    if (hariInput) hariInput.value = '';
                    if (jarakInput) jarakInput.value = '';
                    updateResetButtonVisibility();
                @endif
            });
        }

        [kkInput, hariInput, jarakInput].forEach(input => {
            if (input) {
                input.addEventListener('input', updateResetButtonVisibility);
                input.addEventListener('change', updateResetButtonVisibility);
            }
        });

        updateResetButtonVisibility();

        @if(isset($result))
            const el = document.getElementById('hasil-cek');
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        @endif
    });
</script>
@endpush
