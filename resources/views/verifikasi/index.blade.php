<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrean Verifikasi Pengajuan - Lingga Air</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">

    <!-- Top Navigation -->
    <nav class="bg-blue-900 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center font-bold text-xl shadow">
                        <i class="fa-solid fa-droplet text-cyan-300"></i>
                    </div>
                    <div>
                        <span class="font-bold text-lg tracking-wide">Lingga Air</span>
                        <span class="text-xs text-blue-200 block">Sistem Bantuan Air Bersih Darurat BPBD Purbalingga</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4 text-sm">
                    <span class="bg-blue-800 text-blue-100 px-3 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5 border border-blue-700">
                        <i class="fa-solid fa-shield-halved text-cyan-300"></i> Verifikator BPBD
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Flash Message Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-600 text-xl mt-0.5"></i>
                <div class="flex-1">
                    <h4 class="font-bold text-sm">Berhasil!</h4>
                    <p class="text-xs text-emerald-800 mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Page Header -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-blue-600 mb-1">
                    <span>Fitur 4 (F-04)</span>
                    <span>•</span>
                    <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded">PBI-010 • PBI-011 • PBI-012</span>
                </div>
                <h1 class="text-2xl font-bold text-slate-900">Antrean Verifikasi Pengajuan Bantuan Air</h1>
                <p class="text-sm text-slate-600 mt-1">Antrean permohonan bantuan darurat yang otomatis terurut berdasarkan skor prioritas <strong>AHP-SAW</strong> tertinggi ke terendah.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('verifikasi.index') }}" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 flex items-center gap-2 shadow-sm transition">
                    <i class="fa-solid fa-rotate-right text-slate-500"></i> Refresh Antrean
                </a>
            </div>
        </div>

        <!-- Metric Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase">Total Pengajuan Masuk</p>
                    <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $ringkasan['total_pengajuan'] }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">Seluruh berkas terdaftar</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-amber-200 bg-amber-50/30 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-amber-700 uppercase">Menunggu Verifikasi</p>
                    <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $ringkasan['menunggu_verifikasi'] }}</p>
                    <p class="text-xs text-amber-700/70 mt-0.5">Memerlukan telaah Admin</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-emerald-200 bg-emerald-50/30 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-emerald-700 uppercase">Disetujui</p>
                    <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $ringkasan['disetujui'] }}</p>
                    <p class="text-xs text-emerald-700/70 mt-0.5">Siap penugasan armada</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-rose-200 bg-rose-50/30 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-rose-700 uppercase">Perlu Tindak Lanjut</p>
                    <p class="text-2xl font-extrabold text-rose-600 mt-1">{{ $ringkasan['perlu_tindak_lanjut'] }}</p>
                    <p class="text-xs text-rose-700/70 mt-0.5">Revisi dokumen / Ditolak</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6">
            <form method="GET" action="{{ route('verifikasi.index') }}" class="flex flex-col md:flex-row items-center gap-3">
                <div class="relative flex-1 w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama desa, kecamatan, atau nomor tiket..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <div class="flex items-center gap-2 w-full md:w-auto">
                    <select name="status" class="w-full md:w-auto px-3.5 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Semua Status Antrean</option>
                        <option value="Menunggu Verifikasi" {{ request('status') == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="Revisi" {{ request('status') == 'Revisi' ? 'selected' : '' }}>Revisi</option>
                        <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition shrink-0">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('verifikasi.index') }}" class="px-3 py-2 text-slate-500 hover:text-slate-700 text-sm">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Standar Ambang Batas Kategori (Sinkron F-02 & F-04) -->
        <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded-r-xl mb-6 text-sm text-blue-900 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-shield-halved text-blue-600 text-lg mt-0.5"></i>
                <div>
                    <div class="flex items-center gap-2">
                        <p class="font-bold">Standar Ambang Batas Kelayakan (AHP-SAW F-02 &bull; Skrining Masuk F-04):</p>
                        <span class="text-[11px] bg-blue-200/70 text-blue-900 font-semibold px-2 py-0.5 rounded-full">Filter SDG 10</span>
                    </div>
                    <p class="text-blue-900/90 text-xs mt-1">
                        <span class="inline-block mr-3"><strong class="text-rose-700">&#9679; 70.00 – 100.00: KRITIS</strong> (Prioritas Utama tangki &lt; 2 jam &bull; Lolos ke F-04)</span>
                        <span class="inline-block mr-3"><strong class="text-amber-700">&#9679; 40.00 – 69.99: WASPADA</strong> (Prioritas Distribusi Tahap 2 &bull; Lolos ke F-04)</span>
                        <span class="inline-block"><strong class="text-emerald-700">&#9679; 0.00 – 39.99: AMAN</strong> <em class="text-slate-600">(Dicegat di F-02 &bull; Pengajuan F-03 Dinonaktifkan &bull; Tidak Masuk Antrean BPBD)</em></span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Ranked Queue Table (PBI-010 Acceptance Criteria 1) -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-200 bg-slate-50/60 flex items-center justify-between">
                <h2 class="font-semibold text-slate-800 text-sm">Daftar Antrean Permohonan Bantuan Air</h2>
                <span class="text-xs text-slate-500 font-medium">Menampilkan <strong>{{ $antrean->count() }}</strong> permohonan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-100 text-xs uppercase font-semibold text-slate-600 tracking-wider">
                        <tr>
                            <th class="px-4 py-3.5 text-center w-16">Peringkat</th>
                            <th class="px-4 py-3.5">Nomor Tiket & Waktu</th>
                            <th class="px-4 py-3.5">Desa & Kecamatan</th>
                            <th class="px-4 py-3.5 text-center">Parameter Kekeringan</th>
                            <th class="px-4 py-3.5 text-center">Skor AHP-SAW</th>
                            <th class="px-4 py-3.5 text-center">Kategori Kelayakan</th>
                            <th class="px-4 py-3.5 text-center">Status</th>
                            <th class="px-4 py-3.5 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($antrean as $index => $item)
                            <tr class="hover:bg-slate-50 transition duration-150 {{ $index === 0 ? 'bg-amber-50/20' : '' }}">
                                <!-- Peringkat -->
                                <td class="px-4 py-4 text-center">
                                    @if($index === 0)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-500 text-white font-bold text-xs shadow-sm ring-2 ring-amber-200" title="Prioritas Tertinggi #1">
                                            #1
                                        </span>
                                    @elseif($index === 1)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-400 text-white font-bold text-xs">
                                            #2
                                        </span>
                                    @elseif($index === 2)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-700 text-white font-bold text-xs">
                                            #3
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-100 text-slate-600 font-semibold text-xs border border-slate-300">
                                            #{{ $index + 1 }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Nomor Tiket & Waktu -->
                                <td class="px-4 py-4">
                                    <span class="font-bold text-blue-900 block font-mono text-xs">{{ $item->nomor_tiket }}</span>
                                    <span class="text-xs text-slate-400 block mt-0.5">
                                        <i class="fa-regular fa-calendar-days mr-1"></i>{{ $item->created_at->format('d M Y, H:i') }}
                                    </span>
                                </td>

                                <!-- Desa & Kecamatan -->
                                <td class="px-4 py-4">
                                    <span class="font-semibold text-slate-900 block">Desa {{ $item->desa->nama_desa }}</span>
                                    <span class="text-xs text-slate-500 block">Kec. {{ $item->desa->kecamatan }}</span>
                                </td>

                                <!-- Parameter Kekeringan -->
                                <td class="px-4 py-4 text-center">
                                    <div class="inline-flex items-center gap-3 text-xs bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-200">
                                        <span title="Kepala Keluarga Terdampak"><i class="fa-solid fa-users text-blue-600 mr-1"></i><strong>{{ $item->kk_terdampak }}</strong> KK</span>
                                        <span class="text-slate-300">|</span>
                                        <span title="Hari Tanpa Pasokan Air"><i class="fa-solid fa-sun text-amber-500 mr-1"></i><strong>{{ $item->hari_tanpa_air }}</strong> hari</span>
                                    </div>
                                </td>

                                <!-- Skor AHP-SAW (KUNCI PBI-010) -->
                                <td class="px-4 py-4 text-center">
                                    <div class="inline-block">
                                        <span class="text-base font-extrabold {{ $item->skor_ahp_saw >= 70 ? 'text-rose-600' : ($item->skor_ahp_saw >= 40 ? 'text-amber-600' : 'text-emerald-600') }}">
                                            {{ number_format($item->skor_ahp_saw, 2) }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 block">/ 100</span>
                                    </div>
                                </td>

                                <!-- Kategori Kelayakan (Ambang batas 0-39.99 Aman, 40-69.99 Waspada, 70-100 Kritis) -->
                                <td class="px-4 py-4 text-center">
                                    @if($item->skor_ahp_saw >= 70)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600 mr-1.5 animate-pulse"></span> Kritis
                                        </span>
                                    @elseif($item->skor_ahp_saw >= 40)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600 mr-1.5"></span> Waspada
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 mr-1.5"></span> Aman
                                        </span>
                                    @endif
                                </td>

                                <!-- Status Verifikasi -->
                                <td class="px-4 py-4 text-center">
                                    @if($item->status == 'Menunggu Verifikasi')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                            Menunggu
                                        </span>
                                    @elseif($item->status == 'Disetujui')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Disetujui
                                        </span>
                                    @elseif($item->status == 'Revisi')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                            Revisi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>

                                <!-- Tombol Aksi Detail (PBI-010 AC 2) -->
                                <td class="px-4 py-4 text-center">
                                    <a href="{{ route('verifikasi.show', $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white rounded-lg text-xs font-semibold border border-blue-200 hover:border-blue-600 transition shadow-sm">
                                        <span>Detail</span>
                                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-slate-400">
                                    <i class="fa-solid fa-inbox text-4xl mb-3 block"></i>
                                    <p class="font-medium">Tidak ada permohonan dalam antrean saat ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <footer class="mt-12 py-6 border-t border-slate-200 text-center text-xs text-slate-500">
        &copy; 2026 Lingga Air - Proyek Perangkat Lunak (Kelompok 4). BPBD Kabupaten Purbalingga.
    </footer>

</body>
</html>
