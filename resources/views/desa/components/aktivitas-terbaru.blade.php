{{-- Kartu Aktivitas & Notifikasi Terbaru --}}
<div class="bg-white rounded-xl border border-[#D0D5DD] p-5 shadow-xs space-y-3">
    <div class="flex items-center justify-between pb-3 border-b border-[#EAECF0]">
        <div>
            <h2 class="text-xs sm:text-sm font-bold text-[#101828]">Aktivitas &amp; Notifikasi</h2>
            <p class="text-xs text-[#667085]">Pemberitahuan terkini seputar permohonan air desa Anda</p>
        </div>
        <span class="text-[11px] text-[#667085] font-medium bg-[#F8FAFC] border border-[#D0D5DD] px-2 py-0.5 rounded">
            Terbaru
        </span>
    </div>

    <div class="divide-y divide-[#EAECF0]">
        @forelse ($aktivitas as $act)
            <div class="py-3 first:pt-0 last:pb-0 flex items-start gap-3">
                <div class="w-6 h-6 rounded bg-[#F2F4F7] border border-[#EAECF0] text-[#667085] flex items-center justify-center shrink-0 text-xs mt-0.5">
                    <i class="fa-regular fa-bell"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <h3 class="text-xs font-semibold text-[#101828] truncate">{{ $act['title'] }}</h3>
                        <span class="text-[11px] text-[#667085] shrink-0 font-medium">{{ $act['time'] }}</span>
                    </div>
                    <p class="text-xs text-[#475467] mt-0.5 leading-relaxed">
                        {{ $act['desc'] }}
                    </p>
                </div>
            </div>
        @empty
            <div class="py-5 text-center text-xs text-[#667085]">
                Belum ada aktivitas tercatat.
            </div>
        @endforelse
    </div>
</div>
