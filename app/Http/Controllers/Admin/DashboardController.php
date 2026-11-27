<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use App\Models\Pengajuan;
use App\Models\Pengumuman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $bulanFilter = $request->query('bulan');
        $selectedBulan = null;
        $selectedBulanLabel = 'Semua Periode';

        // Query dasar pengajuan resmi (non-Draf)
        $query = Pengajuan::where('status', '!=', 'Draf');

        // Validasi dan terapkan filter bulan jika disediakan (format: YYYY-MM)
        if ($bulanFilter && preg_match('/^\d{4}-\d{2}$/', $bulanFilter)) {
            $selectedBulan = $bulanFilter;
            [$tahun, $bulan] = explode('-', $bulanFilter);

            $query->whereYear('tanggal_pengajuan', (int) $tahun)
                  ->whereMonth('tanggal_pengajuan', (int) $bulan);

            $selectedBulanLabel = Carbon::createFromFormat('Y-m', $bulanFilter)
                ->locale('id')
                ->translatedFormat('F Y');
        }

        // 1. Total pengajuan yang sudah resmi diajukan (bukan Draf)
        $totalPengajuan = (clone $query)->count();

        // 2. Jumlah desa unik yang mengajukan pada periode tersebut
        $desaTerdampak = (clone $query)
            ->distinct('kode_desa')
            ->count('kode_desa');

        // 3. Tiket yang masih menunggu tindakan BPBD
        $menungguVerifikasi = (clone $query)
            ->whereIn('status', ['Diajukan', 'Dalam Telaah'])
            ->count();

        // 4. Distribusi yang sudah selesai disalurkan
        $distribusiSelesai = (clone $query)
            ->where('status', 'Selesai')
            ->count();

        // Total desa terdaftar di seluruh Purbalingga
        $totalDesa = Desa::count();

        // Daftar kecamatan untuk filter grafik (PBI-017)
        $kecamatans = Desa::distinct()->whereNotNull('kecamatan')->orderBy('kecamatan')->pluck('kecamatan');

        // Daftar 6 bulan terakhir untuk menu pilihan cepat di dropdown
        $recentMonths = collect(range(0, 5))->map(function ($i) {
            $date = Carbon::now()->subMonths($i);
            return [
                'value' => $date->format('Y-m'),
                'label' => $date->locale('id')->translatedFormat('F Y'),
            ];
        });

        // 5 Pengumuman terbaru untuk widget dashboard (PBI-018)
        $pengumumans = Pengumuman::with('creator')
            ->orderByDesc('published_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPengajuan',
            'desaTerdampak',
            'menungguVerifikasi',
            'distribusiSelesai',
            'totalDesa',
            'selectedBulan',
            'selectedBulanLabel',
            'recentMonths',
            'kecamatans',
            'pengumumans'
        ));
    }
}
