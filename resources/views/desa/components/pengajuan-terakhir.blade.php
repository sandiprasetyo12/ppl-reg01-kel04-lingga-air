{{-- Kartu Pengajuan Terakhir (Mini Vertical Timeline) --}}
<div class="bg-white rounded-xl border border-[#D0D5DD] p-5 shadow-xs flex flex-col justify-between">
    <div>
        <!-- Header Kartu -->
        <div class="flex items-center justify-between pb-3 border-b border-[#EAECF0]">
            <div>
                <h2 class="text-xs sm:text-sm font-bold text-[#101828]">Pengajuan Terakhir</h2>
                <p class="text-xs text-[#667085]">Status proses permohonan bantuan air desa</p>
            </div>
            @if ($timeline)
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold font-mono bg-[#F8FAFC] text-[#344054] border border-[#D0D5DD]">
                    #{{ $timeline['nomor_tiket'] }}
                </span>
            @endif
        </div>

        <!-- Konten Timeline atau Empty State -->
        @if ($timeline)
            <div class="mt-4 space-y-4 relative before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-px before:bg-[#D0D5DD]">
                @foreach ($timeline['items'] as $index => $item)
                    @php
                        $isCompleted = ($item['state'] === 'completed');
                        $isCurrent = ($item['state'] === 'current');
                        $isWarning = ($item['state'] === 'warning');
                    @endphp

                    <div class="relative flex items-start gap-3.5">
                        <!-- Bullet Icon Biasa -->
                        <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 z-10 transition text-[10px]
                            @if ($isCompleted)
                                bg-[#00629B] text-white
                            @elseif ($isCurrent)
                                bg-white border-2 border-[#00629B] text-[#00629B] font-bold
                            @elseif ($isWarning)
                                bg-[#B54708] text-white
                            @else
                                bg-[#F8FAFC] border border-[#D0D5DD] text-[#98A2B3]
                            @endif
                        ">
                            @if ($isCompleted)
                                <i class="fa-solid fa-check text-[9px]"></i>
                            @elseif ($isCurrent)
                                <i class="fa-solid fa-circle text-[6px]"></i>
                            @elseif ($isWarning)
                                <i class="fa-solid fa-exclamation text-[9px]"></i>
                            @else
                                <span class="w-1.5 h-1.5 rounded-full bg-[#D0D5DD]"></span>
                            @endif
                        </div>

                        <!-- Konten Step -->
                        <div class="flex-1 pt-0.5">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-0.5">
                                <h3 class="text-xs font-semibold {{ $isCurrent ? 'text-[#00629B] font-bold' : 'text-[#101828]' }}">
                                    {{ $item['title'] }}
                                </h3>
                                <span class="text-[11px] text-[#667085]">{{ $item['time'] }}</span>
                            </div>
                            <p class="text-xs text-[#475467] mt-0.5 leading-relaxed">
                                {{ $item['desc'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State Jika Belum Ada Pengajuan -->
            <div class="py-8 text-center flex flex-col items-center justify-center">
                <i class="fa-regular fa-folder-open text-2xl text-[#98A2B3] mb-2"></i>
                <h3 class="text-xs sm:text-sm font-semibold text-[#101828]">Belum Ada Pengajuan Aktif</h3>
                <p class="text-xs text-[#667085] max-w-sm mt-1 leading-relaxed">
                    Desa Anda belum memiliki tiket pengajuan air bersih. Jika kondisi sumber air mengering, lakukan cek kelayakan mandiri.
                </p>
                <a 
                    href="{{ route('cek-kelayakan.index') }}" 
                    class="mt-3.5 h-8 inline-flex items-center gap-1.5 px-3 bg-white hover:bg-[#F2F4F7] text-[#344054] border border-[#D0D5DD] rounded-md text-xs font-medium transition"
                >
                    <i class="fa-solid fa-calculator text-xs text-[#667085]"></i>
                    <span>Cek Kelayakan</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Footer Card: Link Lacak Selengkapnya -->
    @if ($timeline)
        <div class="pt-3 mt-4 border-t border-[#EAECF0] flex items-center justify-between">
            <span class="text-xs text-[#667085]">Status: <strong class="text-[#344054]">{{ $timeline['status'] }}</strong></span>
            <a 
                href="{{ route('pengajuan.index') }}" 
                class="inline-flex items-center gap-1 text-xs font-medium text-[#00629B] hover:underline"
                aria-label="Lacak pengajuan selengkapnya"
            >
                <span>Lihat riwayat lengkap</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    @endif
</div>
