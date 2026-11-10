<?php

namespace App\Services;

use LogicException;

class CekKelayakanService
{
    /**
     * Ambang batas status kelayakan berdasarkan skor 0–100.
     *
     * - Aman    :  0 – 39,99  → kondisi terkendali
     * - Waspada : 40 – 69,99  → mulai terdampak, siapkan pengajuan
     * - Kritis  : 70 – 100    → darurat, segera ajukan bantuan
     */
    public const AMBANG_KRITIS = 70.0;

    public const AMBANG_WASPADA = 40.0;

    /**
     * @return array{
     *   c1: float,
     *   c2: float,
     *   c3: float,
     *   c4: float,
     *   weights: array{c1: float, c2: float, c3: float, c4: float},
     *   consistency_ratio: float,
     *   score: float,
     *   status: string
     * }
     */
    public function calculate(
        int $kkTerdampak,
        int $jumlahKkTotal,
        int $jumlahBalita,
        int $jumlahLansia,
        int $jumlahDisabilitas,
        int $jumlahJiwaTotal,
        int $hariTanpaAir,
        int $jarakSumberMeter,
    ): array {
        $c1 = min($kkTerdampak / $jumlahKkTotal, 1.0);
        $rasioRentan = ($jumlahBalita + $jumlahLansia + $jumlahDisabilitas) / $jumlahJiwaTotal;
        $c2 = min($rasioRentan / 0.15, 1.0);
        $c3 = min($hariTanpaAir / 7, 1.0);
        $c4 = min($jarakSumberMeter / 2000, 1.0);
        [$weights, $consistencyRatio] = $this->calculateAhpWeights();
        $score = (
            $weights['c1'] * $c1
            + $weights['c2'] * $c2
            + $weights['c3'] * $c3
            + $weights['c4'] * $c4
        ) * 100;

        return [
            'c1' => $c1,
            'c2' => $c2,
            'c3' => $c3,
            'c4' => $c4,
            'weights' => $weights,
            'consistency_ratio' => $consistencyRatio,
            'score' => round($score, 2),
            'status' => $this->getStatus($score),
        ];
    }

    /**
     * @return array{array{c1: float, c2: float, c3: float, c4: float}, float}
     */
    private function calculateAhpWeights(): array
    {
        /** @var array<string, array<string, int|float>> $matrix */
        $matrix = config('cek_kelayakan.pairwise_matrix');
        $criteria = ['c1', 'c2', 'c3', 'c4'];
        $weights = array_fill_keys($criteria, 1 / count($criteria));

        for ($iteration = 0; $iteration < 100; $iteration++) {
            $nextWeights = [];
            foreach ($criteria as $row) {
                $sum = 0.0;
                foreach ($criteria as $column) {
                    $value = $matrix[$row][$column] ?? null;
                    if (! is_numeric($value) || (float) $value <= 0) {
                        throw new LogicException('AHP comparison matrix must contain positive numeric values for every criterion pair.');
                    }

                    $sum += (float) $value * $weights[$column];
                }

                $nextWeights[$row] = $sum;
            }

            $total = array_sum($nextWeights);
            if ($total <= 0) {
                throw new LogicException('AHP comparison matrix produced an invalid weight total.');
            }

            foreach ($nextWeights as $criterion => $weight) {
                $nextWeights[$criterion] = $weight / $total;
            }

            $difference = max(array_map(
                static fn (string $criterion): float => abs($nextWeights[$criterion] - $weights[$criterion]),
                $criteria,
            ));
            $weights = $nextWeights;

            if ($difference < 1e-12) {
                break;
            }
        }

        $lambdaMax = 0.0;
        foreach ($criteria as $row) {
            $weightedSum = 0.0;
            foreach ($criteria as $column) {
                $weightedSum += (float) $matrix[$row][$column] * $weights[$column];
            }
            $lambdaMax += $weightedSum / $weights[$row];
        }
        $lambdaMax /= count($criteria);

        $consistencyIndex = ($lambdaMax - count($criteria)) / (count($criteria) - 1);
        $randomIndex = (float) config('cek_kelayakan.random_index');
        $consistencyRatio = $randomIndex > 0 ? $consistencyIndex / $randomIndex : 0.0;

        if ($consistencyRatio > (float) config('cek_kelayakan.maximum_consistency_ratio')) {
            throw new LogicException(sprintf(
                'AHP comparison matrix is inconsistent (CR %.4f; maximum %.4f).',
                $consistencyRatio,
                (float) config('cek_kelayakan.maximum_consistency_ratio'),
            ));
        }

        return [$weights, $consistencyRatio];
    }

    /**
     * Menentukan status kelayakan berdasarkan skor akhir (0–100).
     *
     * @return 'aman'|'waspada'|'kritis'
     */
    public function getStatus(float $score): string
    {
        if ($score >= self::AMBANG_KRITIS) {
            return 'kritis';
        }

        if ($score >= self::AMBANG_WASPADA) {
            return 'waspada';
        }

        return 'aman';
    }
}
