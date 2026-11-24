<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Penyalur;
use App\Models\Penyaluran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    /**
     * PBI-022: Menampilkan Rekap Penyaluran dengan Filter Multi-Kriteria (Aktor: BPBD Purbalingga)
     */
    public function indexBPBD(Request $request)
    {
        Penyaluran::ensureTableExists();

        $filters = $request->only(['start_date', 'end_date', 'kecamatan', 'penyalur_id', 'status']);

        // Query dasar dengan relasi desa & mitra penyalur
        $baseQuery = Penyaluran::with(['desa', 'penyalur', 'permohonan'])->filter($filters);

        // Ringkasan KPI Evaluasi Pemerataan Penyaluran BPBD
        $kpiQuery = clone $baseQuery;
        $allFiltered = $kpiQuery->get();

        $terkirimItems = $allFiltered->where('status', 'Terkirim');
        $ringkasan = [
            'total_volume_liter' => (int) $terkirimItems->sum('volume_air_liter'),
            'total_tangki' => (int) $terkirimItems->sum('jumlah_tangki'),
            'total_desa' => $terkirimItems->pluck('kode_desa')->unique()->count(),
            'total_selesai' => $terkirimItems->count(),
            'total_proses' => $allFiltered->whereIn('status', ['Dalam Pengiriman', 'Dijadwalkan'])->count(),
            'total_kendala' => $allFiltered->where('status', 'Kendala')->count(),
            'total_semua' => $allFiltered->count(),
        ];

        // Pagination data tabel rekap (10 per halaman sesuai AC)
        $penyaluran = (clone $baseQuery)
            ->orderByDesc('tanggal_penyaluran')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // Data dropdown filter
        $kecamatanList = Desa::select('kecamatan')
            ->whereNotNull('kecamatan')
            ->distinct()
            ->orderBy('kecamatan')
            ->pluck('kecamatan');

        $penyalurList = Penyalur::orderBy('nama_penyalur')->get();

        $statusList = ['Terkirim', 'Dalam Pengiriman', 'Dijadwalkan', 'Kendala'];

        return view('laporan.bpbd', compact(
            'penyaluran',
            'ringkasan',
            'kecamatanList',
            'penyalurList',
            'statusList',
            'filters'
        ));
    }

    /**
     * PBI-023: Menampilkan Rekap Penyaluran Milik Penyalur (Aktor: PMI / PAM / Armada Mitra)
     * Strict Tenant Data Isolation: Data penyalur lain TIDAK BISA diakses, termasuk manipulasi URL/param
     */
    public function indexPenyalur(Request $request)
    {
        Penyaluran::ensureTableExists();

        $user = auth()->user();
        $penyalur = $user?->penyalur;

        if (!$penyalur) {
            return redirect()->route('penyalur.dashboard')
                ->with('error', 'Profil Mitra Penyalur belum terhubung dengan akun Anda.');
        }

        // AMAN: Parameter penyalur_id dari request DITOLAK / DIABAIKAN secara paksa
        $filters = $request->only(['start_date', 'end_date', 'kecamatan', 'status']);
        $filters['penyalur_id'] = $penyalur->id;

        // Query khusus penyalur yang sedang login
        $baseQuery = Penyaluran::with(['desa', 'penyalur', 'permohonan'])
            ->where('penyalur_id', $penyalur->id)
            ->filter($filters);

        // Ringkasan KPI kinerja armada penyalur yang login
        $kpiQuery = clone $baseQuery;
        $allFiltered = $kpiQuery->get();

        $terkirimItems = $allFiltered->where('status', 'Terkirim');
        $ringkasan = [
            'total_volume_liter' => (int) $terkirimItems->sum('volume_air_liter'),
            'total_tangki' => (int) $terkirimItems->sum('jumlah_tangki'),
            'total_desa' => $terkirimItems->pluck('kode_desa')->unique()->count(),
            'total_selesai' => $terkirimItems->count(),
            'total_proses' => $allFiltered->whereIn('status', ['Dalam Pengiriman', 'Dijadwalkan'])->count(),
            'total_kendala' => $allFiltered->where('status', 'Kendala')->count(),
            'total_semua' => $allFiltered->count(),
        ];

        // Pagination data tabel (10 per halaman)
        $penyaluran = (clone $baseQuery)
            ->orderByDesc('tanggal_penyaluran')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // Data filter kecamatan
        $kecamatanList = Desa::select('kecamatan')
            ->whereNotNull('kecamatan')
            ->distinct()
            ->orderBy('kecamatan')
            ->pluck('kecamatan');

        $statusList = ['Terkirim', 'Dalam Pengiriman', 'Dijadwalkan', 'Kendala'];

        return view('laporan.penyalur', compact(
            'penyalur',
            'penyaluran',
            'ringkasan',
            'kecamatanList',
            'statusList',
            'filters'
        ));
    }

    /**
     * PBI-024: Ekspor Laporan Penyaluran ke Format Excel (.xls) atau PDF Print-Ready
     * Menerapkan filter aktif yang sama persis dan isolasi data ketat untuk penyalur
     */
    public function export(Request $request)
    {
        Penyaluran::ensureTableExists();

        $user = auth()->user();
        $isBpbd = $user && $user->isBpbd();
        $isPenyalur = $user && $user->isPenyalur();

        if (!$isBpbd && !$isPenyalur) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $filters = $request->only(['start_date', 'end_date', 'kecamatan', 'status']);

        // Proteksi Isolasi Data untuk Penyalur
        if ($isPenyalur) {
            $penyalur = $user->penyalur;
            if (!$penyalur) {
                abort(403, 'Profil penyalur tidak ditemukan.');
            }
            // Kunci hanya data miliknya sendiri
            $filters['penyalur_id'] = $penyalur->id;
        } else {
            // BPBD boleh memfilter penyalur_id jika memilih di dropdown
            if ($request->filled('penyalur_id')) {
                $filters['penyalur_id'] = $request->penyalur_id;
            }
            $penyalur = !empty($filters['penyalur_id']) ? Penyalur::find($filters['penyalur_id']) : null;
        }

        // Ambil seluruh data hasil filter (tanpa limit pagination)
        $data = Penyaluran::with(['desa', 'penyalur', 'permohonan'])
            ->filter($filters)
            ->orderByDesc('tanggal_penyaluran')
            ->orderByDesc('id')
            ->get();

        $format = strtolower($request->get('format', 'excel'));

        // =========================================================================
        // EKSPOR FORMAT EXCEL (.xls spreadsheet murni berbasis tabel HTML)
        // =========================================================================
        if ($format === 'excel' || $format === 'csv') {
            $filename = 'Laporan-Penyaluran-Air-' . ($isPenyalur ? preg_replace('/[^A-Za-z0-9_-]/', '_', $user->penyalur->nama_penyalur) : 'BPBD-Purbalingga') . '-' . date('Ymd_His') . '.xls';

            $totalVolume = $data->where('status', 'Terkirim')->sum('volume_air_liter');
            $totalTangki = $data->where('status', 'Terkirim')->sum('jumlah_tangki');

            $html = view('laporan.export-excel', [
                'data' => $data,
                'filters' => $filters,
                'isPenyalur' => $isPenyalur,
                'penyalurAktif' => $isPenyalur ? $user->penyalur : $penyalur,
                'totalVolume' => $totalVolume,
                'totalTangki' => $totalTangki,
                'tanggalCetak' => Carbon::now()->isoFormat('D MMMM Y, HH:mm') . ' WIB',
            ])->render();

            return response($html, 200, [
                'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'max-age=0',
                'Pragma' => 'public',
            ]);
        }

        // =========================================================================
        // EKSPOR FORMAT PDF (Print-Ready Layout Resmi Standar Pemerintahan)
        // =========================================================================
        $totalVolume = $data->where('status', 'Terkirim')->sum('volume_air_liter');
        $totalTangki = $data->where('status', 'Terkirim')->sum('jumlah_tangki');

        return view('laporan.export-pdf', [
            'data' => $data,
            'filters' => $filters,
            'isPenyalur' => $isPenyalur,
            'penyalurAktif' => $isPenyalur ? $user->penyalur : $penyalur,
            'totalVolume' => $totalVolume,
            'totalTangki' => $totalTangki,
            'tanggalCetak' => Carbon::now()->isoFormat('D MMMM Y, HH:mm') . ' WIB',
            'user' => $user,
        ]);
    }
}
