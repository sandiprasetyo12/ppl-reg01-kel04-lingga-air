<?php

namespace App\Http\Controllers;

use App\Services\BmkgWeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class DesaWeatherController extends Controller
{
    public function __invoke(Request $request, BmkgWeatherService $weather): JsonResponse
    {
        $desa = $request->user()?->desa;

        if (! $desa) {
            return response()->json(['message' => 'Profil desa belum terhubung.'], 404);
        }

        try {
            $forecast = $weather->forecast($desa->kode_desa);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json(['message' => 'Prakiraan cuaca BMKG belum dapat dimuat.'], 503);
        }

        if (! $forecast) {
            return response()->json(['message' => 'Data prakiraan cuaca belum tersedia.'], 503);
        }

        return response()->json($forecast);
    }
}
