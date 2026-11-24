<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateKoordinatDesaRequest;
use App\Models\Desa;
use App\Services\GisStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PetaGisController extends Controller
{
    public function __construct(
        private readonly GisStatusService $gisStatusService,
    ) {
    }

    /**
     * Halaman utama Pemetaan Desa GIS (F-09).
     */
    public function index(): View
    {
        $kecamatans = Desa::select('kecamatan')
            ->distinct()
            ->orderBy('kecamatan')
            ->pluck('kecamatan');

        $summary = $this->gisStatusService->getInitialSummary();

        return view('admin.pemetaan.index', compact('kecamatans', 'summary'));
    }

    /**
     * Data marker desa siap peta + daftar belum berkoordinat + ringkasan (PBI-025 & PBI-026).
     */
    public function data(Request $request): JsonResponse
    {
        $kecamatan = $request->query('kecamatan');
        $search = $request->query('search');
        $statusFilter = $request->query('status');

        if (!in_array($statusFilter, ['merah', 'kuning', 'hijau', 'abu_abu'], true)) {
            $statusFilter = null;
        }

        $mappedDesas = Desa::query()
            ->siapPeta()
            ->filterKecamatan($kecamatan)
            ->search($search)
            ->orderBy('kecamatan')
            ->orderBy('nama_desa')
            ->get();

        $unmappedDesas = Desa::query()
            ->belumSiapPeta()
            ->filterKecamatan($kecamatan)
            ->search($search)
            ->orderBy('kecamatan')
            ->orderBy('nama_desa')
            ->get();

        $payload = $this->gisStatusService->buildMapData($mappedDesas, $unmappedDesas, $statusFilter);

        return response()->json($payload);
    }

    /**
     * Detail lengkap satu desa ketika penanda/poligon diklik (PBI-026).
     */
    public function show(string $kode_desa): JsonResponse
    {
        $desa = Desa::where('kode_desa', $kode_desa)->firstOrFail();

        return response()->json(
            $this->gisStatusService->buildDesaDetail($desa)
        );
    }

    /**
     * Simpan koordinat dan opsional titik drop air desa (PBI-027).
     */
    public function updateKoordinat(UpdateKoordinatDesaRequest $request, string $kode_desa): JsonResponse
    {
        $desa = Desa::where('kode_desa', $kode_desa)->firstOrFail();
        $validated = $request->validated();

        $updateTitikDrop = $request->exists('titik_drop_air');
        $desa->updateKoordinat(
            (float) $validated['latitude'],
            (float) $validated['longitude'],
            $validated['titik_drop_air'] ?? null,
            $updateTitikDrop
        );

        $desa->refresh();

        return response()->json([
            'message' => "Koordinat Desa {$desa->nama_desa} berhasil diperbarui.",
            'data' => $this->gisStatusService->buildDesaDetail($desa),
            'marker' => $this->gisStatusService->buildDesaMarkerSummary($desa),
        ]);
    }
}

