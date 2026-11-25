<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Desa;
use App\Models\Pengajuan;
use Illuminate\View\View;
class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. Total pengajuan yang sudah resmi diajukan (bukan Draf)
        $totalPengajuan = Pengajuan::where('status', '!=', 'Draf')->count();

        // 2. Jumlah desa unik yang pernah mengajukan
        $desaTerdampak = Pengajuan::whereNot('status', 'Draf')
            ->distinct('kode_desa')
            ->count('kode_desa');
        

        // 3. Tiket yang masih menunggu tindakan BPBD
        $menungguVerifikasi = Pengajuan::whereIn('status', ['Diajukan', 'Dalam Telaah'])
            ->count();

        // 4. Distribusi yang sudah selesai disalurkan
        $distribusiSelesai = Pengajuan::where('status', 'Selesai')->count();

        // Tambahan: total desa terdaftar (untuk konteks kartu desa terdampak)
        $totalDesa = Desa::count();
        return view('admin.dashboard', compact(
            'totalPengajuan',
            'desaTerdampak',
            'menungguVerifikasi',
            'distribusiSelesai',
            'totalDesa',
        ));
    }
}