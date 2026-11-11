<?php

namespace Tests\Unit;

use App\Services\CekKelayakanService;
use Tests\TestCase;

class CekKelayakanServiceTest extends TestCase
{
    public function test_it_calculates_the_documented_example_and_caps_normalized_values(): void
    {
        $result = (new CekKelayakanService)->calculate(
            kkTerdampak: 450,
            jumlahKkTotal: 600,
            jumlahBalita: 95,
            jumlahLansia: 80,
            jumlahDisabilitas: 12,
            jumlahJiwaTotal: 2400,
            hariTanpaAir: 5,
            jarakSumberMeter: 2500,
        );

        $this->assertSame(0.75, $result['c1']);
        $this->assertEqualsWithDelta(0.519444, $result['c2'], 0.000001);
        $this->assertEqualsWithDelta(5 / 7, $result['c3'], 0.000001);
        $this->assertSame(1.0, $result['c4']);
        $this->assertSame(74.76, $result['score']);
        $this->assertEqualsWithDelta(0.504736, $result['weights']['c1'], 0.000001);
        $this->assertEqualsWithDelta(0.082644, $result['weights']['c2'], 0.000001);
        $this->assertEqualsWithDelta(0.302604, $result['weights']['c3'], 0.000001);
        $this->assertEqualsWithDelta(0.110017, $result['weights']['c4'], 0.000001);
        $this->assertLessThan(0.10, $result['consistency_ratio']);
        // 74.76 >= 60 → status kritis
        $this->assertSame('kritis', $result['status']);
    }

    public function test_identical_input_always_produces_the_same_ahp_saw_result(): void
    {
        $service = new CekKelayakanService;
        $arguments = [
            'kkTerdampak' => 120,
            'jumlahKkTotal' => 600,
            'jumlahBalita' => 95,
            'jumlahLansia' => 80,
            'jumlahDisabilitas' => 12,
            'jumlahJiwaTotal' => 2400,
            'hariTanpaAir' => 5,
            'jarakSumberMeter' => 1500,
        ];

        $first = $service->calculate(...$arguments);
        $second = $service->calculate(...$arguments);

        $this->assertSame($first, $second);
    }
}
