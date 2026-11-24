{{-- Hero Status Card (F-02 Realtime Status) --}}
@php
    $status = strtolower($statusKelayakan ?? '');
    
    // Konfigurasi Badge Status
    $badgeConfig = match($status) {
        'kritis' => [
            'bg' => 'bg-[#FEF3F2]',
            'text' => 'text-[#B42318]',
            'border' => 'border-[#FECDCA]',
            'icon' => 'fa-triangle-exclamation',
            'label' => 'KRITIS',
            'desc' => 'Desa Anda mengalami kekeringan tingkat kritis dan sangat membutuhkan pasokan air darurat.',
        ],
        'waspada' => [
            'bg' => 'bg-[#FFFAEB]',
            'text' => 'text-[#B54708]',
            'border' => 'border-[#FEDF89]',
            'icon' => 'fa-clock-rotate-left',
            'label' => 'WASPADA',
            'desc' => 'Kondisi air desa dalam tingkat waspada. Segera siapkan titik tandon dan pengajuan ke BPBD.',
        ],
        'aman' => [
            'bg' => 'bg-[#ECFDF3]',
            'text' => 'text-[#027A48]',
            'border' => 'border-[#A6F4C5]',
            'icon' => 'fa-circle-check',
            'label' => 'AMAN',
            'desc' => 'Pasokan air di desa Anda terpantau masih mencukupi kebutuhan warga saat ini.',
        ],
        default => [
            'bg' => 'bg-[#F8FAFC]',
            'text' => 'text-[#475467]',
            'border' => 'border-[#D0D5DD]',
            'icon' => 'fa-circle-question',
            'label' => 'BELUM DICEK',
            'desc' => 'Kondisi kelayakan air desa Anda belum pernah diperiksa. Lakukan cek mandiri sekarang.',
        ],
    };
@endphp

<div class="rounded-xl bg-white border border-[#D0D5DD] p-5 sm:p-6 shadow-xs space-y-4">
    <!-- Header Kartu: Label & Status Badge -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <span class="text-[11px] font-bold text-[#667085] uppercase tracking-wider block">
                Status Kebutuhan Air Bersih (SPK AHP-SAW)
            </span>
            <p class="text-xs text-[#667085] mt-1 flex items-center gap-1.5">
                <i class="fa-regular fa-clock text-[#667085]"></i>
                <span>Pemeriksaan terakhir: <strong class="text-[#344054]">{{ $tanggalCek ?: 'Belum pernah dicek' }}</strong></span>
            </p>
        </div>

        <!-- Badge Status Resmi -->
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md {{ $badgeConfig['bg'] }} {{ $badgeConfig['text'] }} border {{ $badgeConfig['border'] }} font-bold text-xs self-start sm:self-auto">
            <i class="fa-solid {{ $badgeConfig['icon'] }}"></i>
            <span>{{ $badgeConfig['label'] }}</span>
        </div>
    </div>

    <!-- Penjelasan Status -->
    <p class="text-xs sm:text-sm text-[#344054] leading-relaxed max-w-3xl">
        {{ $badgeConfig['desc'] }}
    </p>

    <!-- Tombol Aksi Kontekstual -->
    <div class="pt-1 flex flex-wrap items-center gap-2.5">
        <a 
            href="{{ $cta['url'] }}" 
            class="h-9 inline-flex items-center justify-center gap-2 px-4 bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white text-xs font-semibold rounded-lg shadow-xs transition"
            aria-label="{{ $cta['label'] }}"
        >
            <i class="fa-solid {{ $cta['icon'] }} text-xs"></i>
            <span>{{ $cta['label'] }}</span>
        </a>

        @if (!empty($cta['show_recheck']))
            <a 
                href="{{ route('cek-kelayakan.index') }}" 
                class="h-9 inline-flex items-center justify-center gap-1.5 px-3.5 bg-white border border-[#D0D5DD] hover:bg-[#F2F4F7] text-[#344054] text-xs font-semibold rounded-lg transition"
            >
                <i class="fa-solid fa-rotate-right text-xs text-[#667085]"></i>
                <span>Cek Ulang Kelayakan</span>
            </a>
        @endif
    </div>
</div>
