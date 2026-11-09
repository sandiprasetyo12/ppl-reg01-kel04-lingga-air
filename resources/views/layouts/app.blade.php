<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Lingga Air - Sistem Pengajuan Bantuan Air Bersih')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0284c7',
                            600: '#0369a1',
                            700: '#075985',
                            800: '#0c4a6e',
                            900: '#082f49',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- TOP BAR -->
    <header class="bg-gradient-to-r from-sky-900 via-sky-800 to-cyan-900 text-white sticky top-0 z-40 shadow-sm border-b border-sky-700/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('pengajuan.index') }}" class="flex items-center space-x-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur border border-white/20 flex items-center justify-center text-cyan-300 shadow-inner group-hover:bg-white/20 transition">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="font-extrabold text-lg tracking-tight text-white">Lingga Air</span>
                                <span class="text-[10px] font-semibold uppercase tracking-wider bg-sky-600/60 px-2 py-0.5 rounded-full border border-sky-400/30 text-sky-200">Kab. Purbalingga</span>
                            </div>
                            <p class="text-xs text-sky-200/80 -mt-0.5">Portal Pelayanan Tanggap Darurat Air Bersih BPBD</p>
                        </div>
                    </a>
                </div>

                <!-- Right Nav / User Info & Quick Switcher -->
                <div class="flex items-center space-x-3">
                    @auth
                        <!-- Village / User badge -->
                        <div class="hidden sm:flex flex-col text-right">
                            <span class="text-sm font-semibold text-white">
                                {{ Auth::user()->desa ? Auth::user()->desa->nama_desa : Auth::user()->name }}
                            </span>
                            <span class="text-xs text-sky-200">
                                {{ Auth::user()->desa ? 'Kec. ' . Auth::user()->desa->kecamatan : ucfirst(Auth::user()->role) }}
                            </span>
                        </div>

                        <!-- Quick Role Switcher (Sangat berguna untuk pengujian fitur) -->
                        <div class="relative inline-block text-left" id="userSwitcherDropdown">
                            <button type="button" onclick="document.getElementById('switchMenu').classList.toggle('hidden')" class="flex items-center space-x-1.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                <svg class="w-4 h-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Ganti Akun Demo</span>
                                <svg class="w-3.5 h-3.5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="switchMenu" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-slate-200 py-2 z-50 text-slate-700">
                                <div class="px-3 py-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                    Pilih Akun Simulasi
                                </div>
                                @php
                                    $demoUsers = \App\Models\User::with('desa')->get();
                                @endphp
                                @foreach($demoUsers as $dUser)
                                    <a href="{{ route('auth.switch', $dUser->id) }}" class="flex items-center justify-between px-3 py-2 text-xs hover:bg-sky-50 transition {{ Auth::id() === $dUser->id ? 'bg-sky-50 font-bold text-sky-800' : '' }}">
                                        <div>
                                            <p class="font-medium text-slate-800">{{ $dUser->desa ? $dUser->desa->nama_desa : $dUser->name }}</p>
                                            <p class="text-[11px] text-slate-500">{{ $dUser->role === 'pemohon' ? 'Pemohon (' . ($dUser->desa?->kecamatan ?? '-') . ')' : 'Admin BPBD' }}</p>
                                        </div>
                                        @if(Auth::id() === $dUser->id)
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Logout -->
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" title="Keluar" class="p-2 text-sky-200 hover:text-white hover:bg-white/10 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition">
                            Masuk ke Akun Desa
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN WRAPPER WITH SIDEBAR -->
    <div class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- LEFT SIDEBAR (Layanan Desa) -->
            <aside class="lg:col-span-3 space-y-4">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4">
                    <div class="text-[11px] font-bold tracking-wider text-slate-400 uppercase px-3 mb-2">
                        Layanan Desa
                    </div>
                    <nav class="space-y-1">
                        <a href="{{ route('pengajuan.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('pengajuan.index') ? 'bg-sky-600 text-white shadow-sm shadow-sky-600/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Beranda & Draf</span>
                        </a>

                        <a href="{{ route('pengajuan.create') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('pengajuan.create') ? 'bg-sky-600 text-white shadow-sm shadow-sky-600/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Buat Pengajuan Baru</span>
                        </a>

                        <div class="pt-2 pb-1">
                            <div class="h-px bg-slate-200"></div>
                        </div>

                        <div class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span>Cek Kelayakan (F-02)</span>
                        </div>

                        <div class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Status Pengajuan (F-06)</span>
                        </div>

                        <div class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            <span>Feedback Penyaluran (F-10)</span>
                        </div>
                    </nav>
                </div>

                <!-- Card Bantuan / Call Center -->
                <div class="bg-gradient-to-br from-sky-50 to-cyan-50 border border-sky-100 rounded-2xl p-4 text-sky-900">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-sky-600 text-white flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-sky-950">Butuh Bantuan Cepat?</h4>
                            <p class="text-xs text-sky-800 mt-0.5">Call Center BPBD: <strong>112</strong> / <strong>(0281) 891011</strong></p>
                            <p class="text-[11px] text-sky-600 mt-1">Siaga 24 Jam Non-Stop Posko Darurat Kekeringan Purbalingga</p>
                        </div>
                    </div>
                </div>

                <!-- Info Desa Singkat -->
                @auth
                    @if(Auth::user()->desa)
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 text-xs space-y-2">
                            <div class="flex items-center justify-between font-semibold text-slate-800">
                                <span>Profil Desa</span>
                                <span class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded text-[10px] font-bold">Terdaftar</span>
                            </div>
                            <div class="text-slate-600 space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Kode Desa:</span>
                                    <span class="font-mono font-medium">{{ Auth::user()->desa->kode_desa }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Total KK:</span>
                                    <span class="font-medium">{{ Auth::user()->desa->jumlah_kk }} KK</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">PJ Darurat:</span>
                                    <span class="font-medium">{{ Auth::user()->desa->nama_pj }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">WhatsApp:</span>
                                    <span class="font-medium">{{ Auth::user()->desa->no_wa }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endauth
            </aside>

            <!-- MAIN CONTENT AREA -->
            <main class="lg:col-span-9 space-y-6">

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-xl p-4 flex items-start space-x-3 shadow-sm">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm font-medium">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if(session('info'))
                    <div class="bg-sky-50 border border-sky-200 text-sky-900 rounded-xl p-4 flex items-start space-x-3 shadow-sm">
                        <svg class="w-5 h-5 text-sky-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm font-medium">
                            {{ session('info') }}
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-900 rounded-xl p-4 shadow-sm">
                        <div class="flex items-center space-x-2 text-rose-800 font-semibold text-sm mb-1">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Mohon periksa kembali isian formulir:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs space-y-1 text-rose-700 ml-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')

            </main>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="mt-auto bg-white border-t border-slate-200/80 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            <p>© 2026 Lingga Air • BPBD & Pemerintah Kabupaten Purbalingga. Hak Cipta Dilindungi.</p>
            <p class="mt-1 text-[11px] text-slate-400">Sistem Pendukung Keputusan Penyaluran Bantuan Air Bersih Darurat berbasis AHP-SAW</p>
        </div>
    </footer>

    <!-- MODAL POP-UP KELAYAKAN STATUS AMAN (TIDAK DAPAT AKSES PENGAJUAN) -->
    @if(session('modal_kelayakan_aman'))
        @php
            $modalAman = session('modal_kelayakan_aman');
        @endphp
        <div id="modalKelayakanAman" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden animate-in fade-in zoom-in-95 duration-200">
                <!-- Header Gambar / Icon -->
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-6 text-center text-white relative">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center mx-auto mb-3 shadow-inner">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-white/25 text-white border border-white/30 mb-1">
                        Status Cek Kelayakan: AMAN
                    </span>
                    <h3 class="text-xl font-extrabold">Pengajuan Tidak Dapat Diproses</h3>
                    <p class="text-xs text-emerald-100 mt-1">Desa {{ $modalAman['desa'] ?? (Auth::user()?->desa?->nama_desa ?? '') }}</p>
                </div>

                <!-- Body Konten -->
                <div class="p-6 space-y-4">
                    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider block">Skor Prioritas SPK (AHP-SAW)</span>
                            <span class="text-2xl font-extrabold text-emerald-700">{{ number_format($modalAman['skor'] ?? 48.1, 1) }}</span>
                            <span class="text-xs font-semibold text-emerald-600">/ 100 (Kategori Aman)</span>
                        </div>
                        <span class="px-3 py-1.5 rounded-xl bg-emerald-600 text-white font-extrabold text-xs shadow-sm">
                            Kondisi Terkendali
                        </span>
                    </div>

                    <div class="text-xs text-slate-600 space-y-2 leading-relaxed">
                        <p class="font-medium text-slate-800">
                            {{ $modalAman['pesan'] ?? 'Berdasarkan hasil Cek Kelayakan Mandiri (F-02), kondisi ketersediaan air di desa Anda dinyatakan dalam kondisi AMAN.' }}
                        </p>
                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3 text-[11px] text-slate-500">
                            <span class="font-bold text-slate-700 block mb-0.5">Ketentuan SOP Bantuan Air BPBD:</span>
                            Armada tangki distribusi air bersih hanya diprioritaskan bagi desa yang berada pada status <strong class="text-rose-600">KRITIS</strong> atau <strong class="text-amber-600">WASPADA</strong>. Desa dengan status <strong>AMAN</strong> tidak dapat mengakses formulir pengajuan bantuan.
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center space-x-3 pt-2">
                        <button type="button" onclick="document.getElementById('modalKelayakanAman').classList.add('hidden')" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/30 transition text-center">
                            Mengerti & Kembali
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Close dropdown on click outside -->
    <script>
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('userSwitcherDropdown');
            const menu = document.getElementById('switchMenu');
            if (dropdown && menu && !dropdown.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

