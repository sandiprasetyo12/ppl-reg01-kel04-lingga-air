<?php

namespace App\Http\Controllers;

use App\Http\Requests\SimulasiKelayakanRequest;
use App\Models\Desa;
use App\Models\RiwayatCekKelayakan;
use App\Services\CekKelayakanService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class CekKelayakanController extends Controller
{
    public function index(): View
    {
        return view('cek-kelayakan.index', $this->formData());
    }

    public function calculate(
        SimulasiKelayakanRequest $request,
        CekKelayakanService $service,
    ): View {
        $input = $request->validated();
        $desa = Desa::query()->findOrFail($input['kode_desa']);
        $result = $service->calculate(
            (int) $input['kk_terdampak'],
            (int) $desa->jumlah_kk_total,
            (int) $desa->jumlah_balita,
            (int) $desa->jumlah_lansia,
            (int) $desa->jumlah_disabilitas,
            (int) $desa->jumlah_jiwa_total,
            (int) $input['hari_tanpa_air'],
            (int) $input['jarak_sumber_meter'],
        );

        RiwayatCekKelayakan::create([
            'kode_desa' => $desa->kode_desa,
            'jumlah_kk_total' => $desa->jumlah_kk_total,
            'jumlah_jiwa_total' => $desa->jumlah_jiwa_total,
            'jumlah_balita' => $desa->jumlah_balita,
            'jumlah_lansia' => $desa->jumlah_lansia,
            'jumlah_disabilitas' => $desa->jumlah_disabilitas,
            'kk_terdampak' => $input['kk_terdampak'],
            'hari_tanpa_air' => $input['hari_tanpa_air'],
            'jarak_sumber_meter' => $input['jarak_sumber_meter'],
            'c1' => $result['c1'],
            'c2' => $result['c2'],
            'c3' => $result['c3'],
            'c4' => $result['c4'],
            'bobot_c1' => $result['weights']['c1'],
            'bobot_c2' => $result['weights']['c2'],
            'bobot_c3' => $result['weights']['c3'],
            'bobot_c4' => $result['weights']['c4'],
            'rasio_konsistensi' => $result['consistency_ratio'],
            'skor' => $result['score'],
            'status' => $result['status'],
        ]);

        return view('cek-kelayakan.index', array_merge($this->formData(), [
            'result' => $result,
            'input' => array_merge($input, [
                'jumlah_kk_total' => $desa->jumlah_kk_total,
                'jumlah_jiwa_total' => $desa->jumlah_jiwa_total,
                'jumlah_balita' => $desa->jumlah_balita,
                'jumlah_lansia' => $desa->jumlah_lansia,
                'jumlah_disabilitas' => $desa->jumlah_disabilitas,
            ]),
        ]));
    }

    /**
     * @return array{villages: \Illuminate\Database\Eloquent\Collection<int, Desa>, districts: Collection<int, string>}
     */
    private function formData(): array
    {
        $villages = Desa::query()
            ->orderBy('kecamatan')
            ->orderBy('nama_desa')
            ->get([
                'kode_desa',
                'nama_desa',
                'kecamatan',
                'jumlah_kk_total',
                'jumlah_jiwa_total',
                'jumlah_balita',
                'jumlah_lansia',
                'jumlah_disabilitas',
            ]);

        return [
            'villages' => $villages,
            'districts' => $villages->pluck('kecamatan')->unique()->values(),
        ];
    }
}
