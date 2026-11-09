<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lingga Air - Sistem Bantuan Air Bersih Darurat BPBD Kab. Purbalingga</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- NAVIGATION (Mockup Halaman 43) -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-sky-600 flex items-center justify-center text-white shadow-md shadow-sky-600/30">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                        <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                    </svg>
                </div>
                <div>
                    <span class="font-extrabold text-lg text-slate-900 tracking-tight block leading-none">Lingga Air</span>
                    <span class="text-[11px] text-sky-700 font-bold uppercase tracking-wider">BPBD Kab. Purbalingga</span>
                </div>
            </div>

            <nav class="hidden md:flex items-center space-x-6 text-sm font-semibold text-slate-600">
                <a href="{{ url('/') }}" class="text-sky-600 font-bold">Beranda</a>
                <a href="{{ route('pengajuan.create') }}" class="hover:text-sky-600 transition">Cek Kelayakan</a>
                <a href="{{ route('pengajuan.index') }}" class="hover:text-sky-600 transition">Informasi Bantuan</a>
            </nav>

            <div class="flex items-center space-x-3">
                @auth
                    <a href="{{ route('pengajuan.index') }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                        Portal Layanan Desa →
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                        Masuk / Login →
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- HERO SECTION (Mockup Halaman 43) -->
    <section class="py-12 lg:py-16 bg-gradient-to-b from-sky-50/80 to-slate-50 border-b border-slate-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <div class="lg:col-span-7 space-y-5">
                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-800 border border-sky-200 uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-sky-600 animate-ping"></span>
                    <span>PORTAL TANGGAP BENCANA KEKERINGAN</span>
                </span>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                    Layanan Penyaluran Bantuan Air Bersih <br>
                    <span class="text-sky-600">Kabupaten Purbalingga</span>
                </h1>

                <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl">
                    Portal tanggap darurat resmi BPBD untuk koordinasi cepat, cek kelayakan desa mandiri (AHP-SAW), penyusunan draf pengajuan, dan distribusi armada tangki air bersih ke tandon komunal warga.
                </p>

                <!-- 3 Tombol Aksi Hero (Mockup Hal 43) -->
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <a href="{{ route('pengajuan.index') }}" class="px-6 py-3 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md shadow-sky-600/30 transition flex items-center space-x-2">
                        <span>Masuk ke Akun Desa</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="{{ route('pengajuan.create') }}" class="px-5 py-3 rounded-xl border border-sky-600 text-sky-700 bg-white hover:bg-sky-50 font-bold text-sm shadow-xs transition">
                        Cek Kelayakan & Pengajuan Cepat
                    </a>
                </div>

                <!-- 3 Badges Info (Mockup Hal 43) -->
                <div class="grid grid-cols-3 gap-3 pt-4 max-w-md">
                    <div class="bg-white p-3 rounded-xl border border-slate-200 text-center shadow-2xs">
                        <span class="block text-lg font-black text-slate-800">8 Unit</span>
                        <span class="text-[11px] text-slate-500 font-semibold">Armada Siaga</span>
                    </div>
                    <div class="bg-white p-3 rounded-xl border border-slate-200 text-center shadow-2xs">
                        <span class="block text-lg font-black text-slate-800">24 Jam</span>
                        <span class="text-[11px] text-slate-500 font-semibold">Respons Cepat</span>
                    </div>
                    <div class="bg-white p-3 rounded-xl border border-slate-200 text-center shadow-2xs">
                        <span class="block text-lg font-black text-emerald-600">Gratis</span>
                        <span class="text-[11px] text-slate-500 font-semibold">Bebas Retribusi</span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-xl relative overflow-hidden group">
                    <div class="h-64 sm:h-72 bg-gradient-to-tr from-sky-800 to-cyan-600 rounded-2xl flex flex-col justify-end p-6 text-white relative overflow-hidden">
                        <div class="absolute inset-0 bg-black/20"></div>
                        <div class="relative z-10">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/20 backdrop-blur border border-white/30 text-white uppercase tracking-wider inline-block mb-2">
                                Siaga Bencana Kekeringan
                            </span>
                            <h3 class="text-xl font-bold leading-snug">Distribusi Air Tangki Cepat Ke 86 Desa Terdampak</h3>
                            <p class="text-xs text-sky-100 mt-1">Kabupaten Purbalingga • Jawa Tengah</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- 4 LANGKAH DISTRIBUSI (Mockup Halaman 45) -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-10">
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">4 Langkah Alur Penyaluran Bantuan</h2>
                <p class="text-xs text-slate-500 mt-1">Prosedur terpadu dan ringkas bagi aparatur desa untuk mengajukan bantuan air bersih</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- 1 -->
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 relative">
                    <div class="w-8 h-8 rounded-lg bg-sky-600 text-white font-black text-sm flex items-center justify-center mb-3">1</div>
                    <h3 class="font-bold text-sm text-slate-800">Daftar / Masuk Akun Desa</h3>
                    <p class="text-xs text-slate-500 mt-1">Aparatur desa masuk portal pelayanan untuk mengakses koordinasi resmi.</p>
                </div>
                <!-- 2 -->
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 relative">
                    <div class="w-8 h-8 rounded-lg bg-sky-600 text-white font-black text-sm flex items-center justify-center mb-3">2</div>
                    <h3 class="font-bold text-sm text-slate-800">Cek Kelayakan Mandiri</h3>
                    <p class="text-xs text-slate-500 mt-1">Periksa kondisi kekeringan desa untuk melihat skor prioritas kelayakan AHP-SAW.</p>
                </div>
                <!-- 3 -->
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 relative">
                    <div class="w-8 h-8 rounded-lg bg-sky-600 text-white font-black text-sm flex items-center justify-center mb-3">3</div>
                    <h3 class="font-bold text-sm text-slate-800">Ajukan Kebutuhan & Foto</h3>
                    <p class="text-xs text-slate-500 mt-1">Susun draf pengajuan, unggah bukti foto lapangan dan tentukan titik drop tandon.</p>
                </div>
                <!-- 4 -->
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 relative">
                    <div class="w-8 h-8 rounded-lg bg-sky-600 text-white font-black text-sm flex items-center justify-center mb-3">4</div>
                    <h3 class="font-bold text-sm text-slate-800">Penyaluran Tangki Langsung</h3>
                    <p class="text-xs text-slate-500 mt-1">Armada BPBD & mitra penyalur mengirim air bersih langsung ke tandon desa.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="mt-auto bg-slate-900 text-slate-400 py-8 text-xs text-center border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4">
            <p class="text-white font-bold">Lingga Air • BPBD & Pemerintah Kabupaten Purbalingga</p>
            <p class="mt-1">Call Center BPBD: 112 / (0281) 891011 • Layanan Tanggap Darurat Air Bersih</p>
        </div>
    </footer>

</body>
</html>
