<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class BmkgWeatherService
{
    /**
     * Return the nearest forecast for an administrative village code.
     * BMKG refreshes this dataset twice daily, so cache successful responses.
     */
    public function forecast(string $villageCode): ?array
    {
        return Cache::remember("bmkg-weather:{$villageCode}", now()->addHour(), function () use ($villageCode) {
            $response = Http::acceptJson()
                ->connectTimeout(3)
                ->timeout(5)
                ->get('https://api.bmkg.go.id/publik/prakiraan-cuaca', [
                    'adm4' => $villageCode,
                ]);

            if (! $response->successful()) {
                throw new RuntimeException('BMKG weather request failed.');
            }

            $payload = $response->json();
            $forecast = $this->firstForecast($payload['data'] ?? []);

            if (! $forecast) {
                return null;
            }

            return [
                'weather' => $forecast['weather_desc'] ?? null,
                'temperature' => isset($forecast['t']) ? (int) $forecast['t'] : null,
                'humidity' => isset($forecast['hu']) ? (int) $forecast['hu'] : null,
                'wind_speed' => isset($forecast['ws']) ? (float) $forecast['ws'] : null,
                'local_datetime' => $forecast['local_datetime'] ?? null,
                'location' => $payload['lokasi']['desa'] ?? null,
                'analysis_date' => $forecast['analysis_date'] ?? null,
            ];
        });
    }

    private function firstForecast(array $data): ?array
    {
        return $this->findForecast($data);
    }

    private function findForecast(array $items): ?array
    {
        if (isset($items['weather_desc'])) {
            return $items;
        }

        foreach ($items as $item) {
            if (is_array($item) && ($forecast = $this->findForecast($item))) {
                return $forecast;
            }
        }

        return null;
    }
}
