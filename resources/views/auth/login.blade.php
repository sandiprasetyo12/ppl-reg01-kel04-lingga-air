<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk ke Portal Layanan - Lingga Air Kab. Purbalingga</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl border border-slate-200/80 overflow-hidden grid grid-cols-1 md:grid-cols-12">
        
        <!-- Panel Kiri: Identitas Visual Lingga Air -->
        <div class="md:col-span-5 bg-gradient-to-br from-sky-900 via-sky-800 to-cyan-900 p-8 text-white flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-cyan-500/20 rounded-full blur-2xl pointer-events-none"></div>

            <div>
                <div class="flex items-center space-x-2.5 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur border border-white/20 flex items-center justify-center text-cyan-300">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-extrabold text-xl tracking-tight leading-none text-white">Lingga Air</h1>
                        <p class="text-[11px] text-cyan-200 uppercase tracking-wider font-semibold">BPBD Kab. Purbalingga</p>
                    </div>
                </div>

                <h2 class="text-2xl font-bold leading-tight mt-6">
                    Distribusi Air Bersih Cepat & Terkoordinasi.
                </h2>
                <p class="text-sm text-sky-200/90 mt-3 leading-relaxed">
                    Sistem standarisasi keadilan penyaluran bantuan darurat air bersih untuk masyarakat perdesaan terdampak kekeringan.
                </p>
            </div>

            <div class="pt-8 border-t border-white/10 mt-8 space-y-2 text-xs text-sky-200">
                <div class="flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-medium text-white">Posko Siaga Darurat 24 Jam</span>
                </div>
                <p class="text-[11px] text-sky-300/80">Call Center: 112 / (0281) 891011</p>
            </div>
        </div>

        <!-- Panel Kanan: Form Masuk -->
        <div class="md:col-span-7 p-8 sm:p-10 flex flex-col justify-center">
            
            <div class="mb-6">
                <h3 class="text-xl font-bold text-slate-800">Masuk ke Akun Anda</h3>
                <p class="text-xs text-slate-500 mt-1">Masukkan kredensial resmi untuk mengakses koordinasi penyaluran air.</p>
            </div>

            @if($errors->any())
                <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-3 text-xs">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('info'))
                <div class="mb-4 bg-sky-50 border border-sky-200 text-sky-800 rounded-xl p-3 text-xs">
                    {{ session('info') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Email Resmi / Kode Desa
                    </label>
                    <input 
                        type="text" 
                        name="email" 
                        id="email" 
                        value="{{ old('email', 'desa.tanalum@purbalinggakab.go.id') }}" 
                        required 
                        placeholder="Contoh: aparatur@desa.id atau 3303132005"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 focus:bg-white transition"
                    >
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                            Kata Sandi
                        </label>
                        <a href="#" class="text-xs text-sky-600 hover:text-sky-700 font-medium">Lupa kata sandi?</a>
                    </div>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        value="password"
                        required 
                        placeholder="Masukkan kata sandi terverifikasi"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 focus:bg-white transition"
                    >
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center space-x-2 text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                        <span>Ingat sesi di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 text-white font-semibold py-2.5 px-4 rounded-xl shadow-md shadow-sky-600/30 transition flex items-center justify-center space-x-2 text-sm mt-2">
                    <span>Masuk ke Portal Layanan</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            <!-- Quick Demo Login Switcher Box -->
            <div class="mt-8 pt-6 border-t border-slate-100">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">
                    Pintasan Demo Akun (PPL Sprint Review)
                </p>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    @php
                        $quickUsers = \App\Models\User::with('desa')->get();
                    @endphp
                    @foreach($quickUsers as $qu)
                        <a href="{{ route('auth.switch', $qu->id) }}" class="p-2 rounded-lg bg-slate-50 hover:bg-sky-50 border border-slate-200 hover:border-sky-300 text-slate-700 transition flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full {{ $qu->role === 'admin' ? 'bg-purple-500' : 'bg-sky-500' }}"></span>
                            <span class="truncate font-medium">{{ $qu->desa ? $qu->desa->nama_desa : $qu->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

</body>
</html>

