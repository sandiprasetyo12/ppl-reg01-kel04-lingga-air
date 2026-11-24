{{-- Mobile Bottom Navigation Bar (Khusus Tampilan HP / Layar Kecil) --}}
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-[#EAECF0] shadow-sm px-1 py-1" aria-label="Navigasi bawah ponsel">
    <div class="grid grid-cols-5 items-center">
        <!-- 1. Beranda -->
        <a 
            href="{{ route('desa.dashboard') }}" 
            class="min-h-[44px] flex flex-col items-center justify-center gap-1 py-1 rounded-lg transition text-center {{ request()->routeIs('desa.dashboard') ? 'text-[#00629B] font-bold' : 'text-[#667085] hover:text-[#101828]' }}"
            @if(request()->routeIs('desa.dashboard')) aria-current="page" @endif
        >
            <svg class="w-4 h-4 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
            </svg>
            <span class="text-[9.5px] leading-none">Beranda</span>
        </a>

        <!-- 2. Cek Kelayakan -->
        <a 
            href="{{ route('cek-kelayakan.index') }}" 
            class="min-h-[44px] flex flex-col items-center justify-center gap-1 py-1 rounded-lg transition text-center {{ request()->routeIs('cek-kelayakan.*') ? 'text-[#00629B] font-bold' : 'text-[#667085] hover:text-[#101828]' }}"
            @if(request()->routeIs('cek-kelayakan.*')) aria-current="page" @endif
        >
            <svg class="w-4 h-4 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm0 3h.008v.008H8.25v-.008zm3.75-6h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm3.75-6h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
            </svg>
            <span class="text-[9.5px] leading-none">Kelayakan</span>
        </a>

        <!-- 3. Pengajuan Bantuan -->
        <a 
            href="{{ route('pengajuan.create') }}" 
            class="min-h-[44px] flex flex-col items-center justify-center gap-1 py-1 rounded-lg transition text-center {{ request()->routeIs('pengajuan.create') ? 'text-[#00629B] font-bold' : 'text-[#667085] hover:text-[#101828]' }}"
            @if(request()->routeIs('pengajuan.create')) aria-current="page" @endif
        >
            <svg class="w-4 h-4 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            <span class="text-[9.5px] leading-none">Ajukan</span>
        </a>

        <!-- 4. Riwayat Pengajuan -->
        <a 
            href="{{ route('pengajuan.index') }}" 
            class="min-h-[44px] flex flex-col items-center justify-center gap-1 py-1 rounded-lg transition text-center {{ request()->routeIs('pengajuan.index') || request()->routeIs('pengajuan.show') ? 'text-[#00629B] font-bold' : 'text-[#667085] hover:text-[#101828]' }}"
            @if(request()->routeIs('pengajuan.index') || request()->routeIs('pengajuan.show')) aria-current="page" @endif
        >
            <svg class="w-4 h-4 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="text-[9.5px] leading-none">Riwayat</span>
        </a>

        <!-- 5. Lacak Pengajuan (Warna & font sama, tidak bisa dibuka) -->
        <a 
            href="javascript:void(0)" 
            class="min-h-[44px] flex flex-col items-center justify-center gap-1 py-1 rounded-lg text-center text-[#667085] hover:text-[#101828] transition cursor-pointer"
            role="button"
            title="Lacak Pengajuan"
        >
            <svg class="w-4 h-4 text-current shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
            </svg>
            <span class="text-[9.5px] leading-none">Lacak</span>
        </a>
    </div>
</nav>
