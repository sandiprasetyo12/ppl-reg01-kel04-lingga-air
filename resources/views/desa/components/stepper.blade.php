{{-- Progress Stepper: 5 Tahapan Penanganan Air Bersih --}}
<div class="bg-white rounded-xl border border-[#D0D5DD] p-5 shadow-xs space-y-4">
    <div class="flex items-center justify-between pb-3 border-b border-[#EAECF0]">
        <div>
            <h2 class="text-xs sm:text-sm font-bold text-[#101828]">Alur Penanganan Bantuan Air Bersih</h2>
            <p class="text-xs text-[#667085]">Tahapan proses dari pemeriksaan kelayakan mandiri hingga distribusi tangki</p>
        </div>
        <span class="text-[11px] font-medium text-[#475467] bg-[#F8FAFC] border border-[#D0D5DD] px-2.5 py-0.5 rounded-md hidden sm:inline-block">
            5 Tahapan Resmi BPBD
        </span>
    </div>

    <!-- Stepper Container -->
    <div class="overflow-x-auto pb-2 -mx-2 px-2 sm:mx-0 sm:px-0">
        <div class="min-w-[560px] sm:min-w-0 flex items-center justify-between relative">
            
            @foreach ($stepper as $stepNum => $step)
                @php
                    $isCompleted = ($step['status'] === 'completed');
                    $isCurrent = ($step['status'] === 'current');
                    $isFailed = ($step['status'] === 'failed');
                    $isPending = ($step['status'] === 'pending');
                @endphp

                <div class="flex flex-col items-center text-center relative z-10 flex-1">
                    <!-- Lingkaran Tahap -->
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-xs font-bold transition
                        @if ($isCompleted)
                            bg-[#00629B] text-white
                        @elseif ($isCurrent)
                            bg-white border-2 border-[#00629B] text-[#00629B]
                        @elseif ($isFailed)
                            bg-[#B42318] text-white
                        @else
                            bg-[#F8FAFC] text-[#667085] border border-[#D0D5DD]
                        @endif
                    ">
                        @if ($isCompleted)
                            <i class="fa-solid fa-check text-[10px]"></i>
                        @elseif ($isFailed)
                            <i class="fa-solid fa-xmark text-[10px]"></i>
                        @else
                            <span>{{ $stepNum }}</span>
                        @endif
                    </div>

                    <!-- Label Tahapan -->
                    <p class="text-xs font-semibold mt-1.5 
                        @if ($isCurrent)
                            text-[#00629B] font-bold
                        @elseif ($isCompleted)
                            text-[#101828]
                        @elseif ($isFailed)
                            text-[#B42318]
                        @else
                            text-[#667085]
                        @endif
                    ">
                        {{ $step['label'] }}
                    </p>

                    <!-- Keterangan Status Ringkas -->
                    <span class="text-[10px] mt-0.5 
                        @if ($isCurrent)
                            text-[#00629B] font-medium
                        @elseif ($isCompleted)
                            text-[#475467]
                        @elseif ($isFailed)
                            text-[#B42318]
                        @else
                            text-[#98A2B3]
                        @endif
                    ">
                        @if ($isCurrent)
                            Tahap Saat Ini
                        @elseif ($isCompleted)
                            Selesai
                        @elseif ($isFailed)
                            Ditolak
                        @else
                            Menunggu
                        @endif
                    </span>
                </div>

                {{-- Garis Penghubung antar step --}}
                @if ($stepNum < count($stepper))
                    <div class="flex-1 h-0.5 -mt-6 sm:-mt-6 transition
                        @if ($stepper[$stepNum]['status'] === 'completed' && ($stepper[$stepNum + 1]['status'] === 'completed' || $stepper[$stepNum + 1]['status'] === 'current'))
                            bg-[#00629B]
                        @else
                            bg-[#D0D5DD]
                        @endif
                    "></div>
                @endif
            @endforeach

        </div>
    </div>
</div>
