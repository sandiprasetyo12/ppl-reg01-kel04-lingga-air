@extends('layouts.desa')

@section('title', 'Lacak Status Pengajuan | LinggaAir')

@section('content')
<div class="space-y-6 pb-16 md:pb-6">

    <!-- HEADER TITLE & INFO -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-3 border-b border-[#EAECF0]">
        <div>
            <div class="eyebrow">Layanan Desa &middot; Monitoring Real-Time</div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#101828] tracking-tight mt-0.5">
                Lacak Status Pengajuan Bantuan Air
            </h1>
            <p class="text-xs sm:text-sm text-[#475467] mt-1 max-w-2xl leading-relaxed">
                Pantau perkembangan verifikasi berkas oleh BPBD Kabupaten Purbalingga dan proses distribusi armada tangki air bersih ke desa Anda secara langsung.
            </p>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-auto shrink-0">
            <a href="{{ route('pengajuan.index') }}" class="h-9 px-3.5 bg-white border border-[#D0D5DD] hover:bg-[#F2F4F7] text-[#344054] text-xs font-semibold rounded-xl transition inline-flex items-center gap-2 shadow-xs">
                <svg class="w-4 h-4 text-[#667085] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Lihat Semua Riwayat &amp; Draf</span>
            </a>
            <a href="{{ route('pengajuan.create') }}" class="h-9 px-4 bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white text-xs font-bold rounded-xl transition inline-flex items-center gap-1.5 shadow-xs">
                <svg class="w-3.5 h-3.5 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span>Buat Pengajuan Baru</span>
            </a>
        </div>
    </div>

    @if ($selectedPengajuan)
        @php
            $status = $selectedPengajuan->status;
            // Tentukan step aktif saat ini (1 sampai 5)
            $step = match($status) {
                'Diajukan' => 2,
                'Disetujui' => 3,
                'Selesai' => 5,
                'Ditolak' => 2,
                default => 1,
            };

            $isRejected = ($status === 'Ditolak');
        @endphp

        <!-- SWITCHER TIKET (JIKA ADA LEBIH DARI 1 TIKET) -->
        @if ($daftarTiket->count() > 1)
            <div class="bg-white border border-[#EAECF0] rounded-2xl p-3.5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-[#344054]">Pilih Tiket untuk Dilacak:</span>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    @foreach ($daftarTiket as $t)
                        <a 
                            href="{{ route('pengajuan.lacak', ['id' => $t->id]) }}" 
                            class="px-3 py-1.5 rounded-lg text-xs font-mono font-bold transition flex items-center gap-1.5 {{ $selectedPengajuan->id === $t->id ? 'bg-[#00629B] text-white shadow-xs' : 'bg-[#F2F4F7] text-[#475467] hover:bg-[#E5E7EB]' }}"
                        >
                            <span>{{ $t->nomor_tiket }}</span>
                            <span class="text-[10px] opacity-80">({{ $t->status }})</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- KARTU STATUS UTAMA & TIMELINE TRACKING -->
        <div class="bg-white border border-[#EAECF0] rounded-2xl p-6 sm:p-8 shadow-xs space-y-8">
            
            <!-- HEADER KARTU TIKET -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-[#EAECF0]">
                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-[#101828] font-mono tracking-tight">
                            {{ $selectedPengajuan->nomor_tiket }}
                        </h2>
                        @php
                            $badge = match($status) {
                                'Diajukan' => 'status-waspada',
                                'Disetujui' => 'status-aman',
                                'Selesai' => 'status-aman',
                                'Ditolak' => 'status-kritis',
                                default => 'status-draf',
                            };
                        @endphp
                        <span class="status-badge {{ $badge }} text-xs py-1 px-3">
                            {{ $status === 'Diajukan' ? 'Menunggu Verifikasi BPBD' : $status }}
                        </span>
                    </div>
                    <p class="text-xs text-[#667085]">
                        Desa {{ $desa->nama_desa }}, Kec. {{ $desa->kecamatan }} &middot; 
                        Diajukan pada: <span class="font-medium text-[#344054]">{{ $selectedPengajuan->tanggal_pengajuan ? $selectedPengajuan->tanggal_pengajuan->translatedFormat('d F Y, H:i') : $selectedPengajuan->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('pengajuan.show', $selectedPengajuan->id) }}" class="h-9 px-4 bg-white border border-[#EAECF0] hover:bg-[#F8FAFC] active:bg-[#EAECF0] text-[#344054] text-xs font-semibold rounded-xl transition inline-flex items-center gap-2 shadow-xs">
                        <svg class="w-4 h-4 text-[#667085]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Buka Detail Berkas</span>
                    </a>
                </div>
            </div>

            <!-- VISUAL 5-STEP TRACKER -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#667085]">
                    Progres Pelacakan Penyaluran Air Bersih
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 relative">
                    
                    <!-- STEP 1: Pengajuan Masuk -->
                    <div class="p-4 rounded-xl border {{ $step >= 1 ? 'border-[#A6F4C5] bg-[#F6FEF9]' : 'border-[#EAECF0] bg-[#F8FAFC]' }} space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="w-7 h-7 rounded-full {{ $step >= 1 ? 'bg-[#027A48] text-white' : 'bg-[#E4E7EC] text-[#667085]' }} font-bold text-xs flex items-center justify-center">
                                ✓
                            </span>
                            <span class="text-[10px] font-bold text-[#027A48] uppercase tracking-wider">Tahap 1</span>
                        </div>
                        <h4 class="text-xs font-bold text-[#101828]">Pengajuan Terkirim</h4>
                        <p class="text-[11px] text-[#475467] leading-relaxed">
                            Berkas permohonan dan foto kondisi kekeringan tercatat di sistem.
                        </p>
                    </div>

                    <!-- STEP 2: Verifikasi BPBD -->
                    <div class="p-4 rounded-xl border {{ $isRejected ? 'border-[#FECDCA] bg-[#FEF3F2]' : ($step >= 2 ? ($step > 2 ? 'border-[#A6F4C5] bg-[#F6FEF9]' : 'border-[#BAE3F5] bg-[#EBF5FB]') : 'border-[#EAECF0] bg-[#F8FAFC]') }} space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="w-7 h-7 rounded-full {{ $isRejected ? 'bg-[#B42318] text-white' : ($step > 2 ? 'bg-[#027A48] text-white' : ($step === 2 ? 'bg-[#00629B] text-white ring-4 ring-[#BAE3F5]' : 'bg-[#E4E7EC] text-[#667085]')) }} font-bold text-xs flex items-center justify-center">
                                {{ $isRejected ? '✕' : ($step > 2 ? '✓' : '2') }}
                            </span>
                            <span class="text-[10px] font-bold {{ $isRejected ? 'text-[#B42318]' : ($step >= 2 ? 'text-[#00629B]' : 'text-[#667085]') }} uppercase tracking-wider">Tahap 2</span>
                        </div>
                        <h4 class="text-xs font-bold text-[#101828]">
                            {{ $isRejected ? 'Verifikasi Ditolak' : 'Verifikasi BPBD' }}
                        </h4>
                        <p class="text-[11px] text-[#475467] leading-relaxed">
                            @if ($isRejected)
                                Permohonan ditolak oleh BPBD. Silakan periksa catatan telaah pada berkas.
                            @elseif ($step === 2)
                                Sedang ditelaah dan diverifikasi oleh petugas verifikator BPBD.
                            @else
                                Berkas telah lolos telaah dan disetujui untuk penjadwalan.
                            @endif
                        </p>
                    </div>

                    <!-- STEP 3: Penugasan Armada -->
                    <div class="p-4 rounded-xl border {{ $step >= 3 ? ($step > 3 ? 'border-[#A6F4C5] bg-[#F6FEF9]' : 'border-[#BAE3F5] bg-[#EBF5FB]') : 'border-[#EAECF0] bg-[#F8FAFC]' }} space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="w-7 h-7 rounded-full {{ $step > 3 ? 'bg-[#027A48] text-white' : ($step === 3 ? 'bg-[#00629B] text-white ring-4 ring-[#BAE3F5]' : 'bg-[#E4E7EC] text-[#667085]') }} font-bold text-xs flex items-center justify-center">
                                {{ $step > 3 ? '✓' : '3' }}
                            </span>
                            <span class="text-[10px] font-bold {{ $step >= 3 ? 'text-[#00629B]' : 'text-[#667085]' }} uppercase tracking-wider">Tahap 3</span>
                        </div>
                        <h4 class="text-xs font-bold text-[#101828]">Penugasan Penyalur</h4>
                        <p class="text-[11px] text-[#475467] leading-relaxed">
                            @if ($step >= 3)
                                BPBD menugaskan mitra penyalur tangki air untuk alokasi pengiriman.
                            @else
                                Menunggu persetujuan verifikasi BPBD.
                            @endif
                        </p>
                    </div>

                    <!-- STEP 4: Distribusi Air -->
                    <div class="p-4 rounded-xl border {{ $step >= 4 ? ($step > 4 ? 'border-[#A6F4C5] bg-[#F6FEF9]' : 'border-[#BAE3F5] bg-[#EBF5FB]') : 'border-[#EAECF0] bg-[#F8FAFC]' }} space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="w-7 h-7 rounded-full {{ $step > 4 ? 'bg-[#027A48] text-white' : ($step === 4 ? 'bg-[#00629B] text-white ring-4 ring-[#BAE3F5]' : 'bg-[#E4E7EC] text-[#667085]') }} font-bold text-xs flex items-center justify-center">
                                {{ $step > 4 ? '✓' : '4' }}
                            </span>
                            <span class="text-[10px] font-bold {{ $step >= 4 ? 'text-[#00629B]' : 'text-[#667085]' }} uppercase tracking-wider">Tahap 4</span>
                        </div>
                        <h4 class="text-xs font-bold text-[#101828]">Distribusi ke Titik Drop</h4>
                        <p class="text-[11px] text-[#475467] leading-relaxed">
                            @if ($step >= 4)
                                Truk tangki bergerak menuju titik drop tandon air warga desa.
                            @else
                                Tahapan logistik pengiriman air darurat.
                            @endif
                        </p>
                    </div>

                    <!-- STEP 5: Selesai -->
                    <div class="p-4 rounded-xl border {{ $step === 5 ? 'border-[#A6F4C5] bg-[#F6FEF9]' : 'border-[#EAECF0] bg-[#F8FAFC]' }} space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="w-7 h-7 rounded-full {{ $step === 5 ? 'bg-[#027A48] text-white' : 'bg-[#E4E7EC] text-[#667085]' }} font-bold text-xs flex items-center justify-center">
                                {{ $step === 5 ? '✓' : '5' }}
                            </span>
                            <span class="text-[10px] font-bold {{ $step === 5 ? 'text-[#027A48]' : 'text-[#667085]' }} uppercase tracking-wider">Tahap 5</span>
                        </div>
                        <h4 class="text-xs font-bold text-[#101828]">Penyaluran Selesai</h4>
                        <p class="text-[11px] text-[#475467] leading-relaxed">
                            @if ($step === 5)
                                Pasokan air telah diterima warga dan berita acara tuntas ditandatangani.
                            @else
                                Penutupan tiket bantuan air darurat.
                            @endif
                        </p>
                    </div>

                </div>
            </div>

            <!-- DETAIL INFORMASI DROP AIR & LOGISTIK -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-[#EAECF0]">
                
                <div class="p-4 rounded-xl bg-[#F8FAFC] border border-[#EAECF0] space-y-1">
                    <span class="text-[11px] font-bold text-[#667085] uppercase tracking-wider block">Kebutuhan &amp; Alokasi Air</span>
                    <p class="text-base font-bold text-[#101828] font-mono">
                        {{ number_format($selectedPengajuan->estimasiLiter(), 0, ',', '.') }} Liter
                    </p>
                    <span class="text-xs text-[#00629B] font-semibold block">
                        &plusmn; {{ $selectedPengajuan->estimasiTangki() }} Truk Tangki (Kapasitas 5.000 L)
                    </span>
                    <span class="text-[11px] text-[#667085] block">
                        Untuk {{ $selectedPengajuan->jumlah_kk }} KK ({{ $selectedPengajuan->hari_tanpa_air }} hari tanpa air)
                    </span>
                </div>

                <div class="p-4 rounded-xl bg-[#F8FAFC] border border-[#EAECF0] space-y-1">
                    <span class="text-[11px] font-bold text-[#667085] uppercase tracking-wider block">Titik Drop &amp; Koordinat</span>
                    <p class="text-sm font-semibold text-[#101828] line-clamp-2">
                        {{ $selectedPengajuan->lokasi_spesifik ?: ($desa->titik_drop_air ?: 'Tandon Penampungan Utama Desa') }}
                    </p>
                    <span class="text-[11px] text-[#667085] block">
                        Jarak dari sumber air normal: {{ number_format($selectedPengajuan->jarak_sumber_air, 0, ',', '.') }} meter
                    </span>
                </div>

                <div class="p-4 rounded-xl bg-[#F8FAFC] border border-[#EAECF0] space-y-1">
                    <span class="text-[11px] font-bold text-[#667085] uppercase tracking-wider block">Kontak Penanggung Jawab</span>
                    <p class="text-sm font-semibold text-[#101828]">
                        {{ $desa->nama_pj_darurat ?: 'Perwakilan Desa ' . $desa->nama_desa }}
                    </p>
                    @if ($desa->no_wa_darurat)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $desa->no_wa_darurat) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-[#027A48] font-bold hover:underline">
                            <span>WhatsApp: {{ $desa->no_wa_darurat }}</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                        </a>
                    @else
                        <span class="text-xs text-[#667085]">Nomor kontak belum dicantumkan</span>
                    @endif
                </div>

            </div>

        </div>

    @else
        <!-- EMPTY STATE: JIKA BELUM ADA PENGAJUAN -->
        <div class="bg-white border border-[#EAECF0] rounded-2xl p-8 sm:p-12 text-center max-w-xl mx-auto space-y-4 shadow-xs">
            <div class="w-16 h-16 rounded-2xl bg-[#EBF5FB] text-[#00629B] border border-[#BAE3F5] flex items-center justify-center mx-auto text-2xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                </svg>
            </div>
            
            <div class="space-y-1">
                <h2 class="text-lg font-bold text-[#101828]">Belum Ada Pengajuan Bantuan yang Berjalan</h2>
                <p class="text-xs sm:text-sm text-[#475467] leading-relaxed">
                    Desa Anda saat ini belum memiliki tiket permohonan bantuan air bersih yang sedang diproses oleh BPBD Kabupaten Purbalingga.
                </p>
            </div>

            <div class="pt-3 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('cek-kelayakan.index') }}" class="h-9 px-4 bg-white border border-[#D0D5DD] hover:bg-[#F2F4F7] text-[#344054] text-xs font-semibold rounded-xl transition inline-flex items-center gap-2 shadow-xs">
                    <svg class="w-3.5 h-3.5 text-current" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm3.75-6h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm3.75-6h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                    </svg>
                    <span>Cek Kelayakan Mandiri</span>
                </a>
                <a href="{{ route('pengajuan.create') }}" class="h-9 px-4 bg-[#00629B] hover:bg-[#004F7D] active:bg-[#003B5C] text-white text-xs font-bold rounded-xl transition inline-flex items-center gap-1.5 shadow-xs">
                    <svg class="w-3.5 h-3.5 text-current" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <span>Ajukan Bantuan Air Sekarang</span>
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
