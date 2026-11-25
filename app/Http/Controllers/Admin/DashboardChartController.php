<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DashboardChartRequest;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardChartController extends Controller 
{
    public function index(DashboardChartRequest $request): JsonResponse
    {
        $filters = $request->validated();

        $data = DB::table('pengajuan as p')
            ->join('desas as d', 'p.kode_desa', '=', 'd.kode_desa')
            ->where('p.status', '!=', 'Draf')
            ->when(
                $filters['start_date'] ?? null, 
                fn (Builder $query, string $date) => $query->whereDate('p.tanggal_pengajuan', '>=', $date)
            )
            ->when(
                $filters['end_date'] ?? null, 
                fn (Builder $query, string $date) => $query->whereDate('p.tanggal_pengajuan', '<=', $date)
            )
            ->when(
                $filters['kecamatan'] ?? null,
                fn (Builder $query, string $kecamatan) => $query->where('d.kecamatan', $kecamatan)
            )
            ->select('d.kecamatan')
            ->selectRaw('COUNT(p.id) AS jumlah_pengajuan')
            ->selectRaw(
                'SUM(CASE WHEN p.jumlah_kk * 100 < 4000
                    THEN 4000
                    ELSE p.jumlah_kk * 100
                END) AS volume_air_liter'
            )
            ->groupBy('d.kecamatan')
            ->orderBy('d.kecamatan')
            ->get()
            ->map(fn (object $row): array => [
                'kecamatan' => $row->kecamatan,
                'jumlah_pengajuan' => (int) $row->jumlah_pengajuan,
                'volume_air_liter' => (int) $row->volume_air_liter,
            ])
            ->values();
        
        return response()->json(['data' => $data]);
    }
}