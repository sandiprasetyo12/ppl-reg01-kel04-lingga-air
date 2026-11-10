<?php

namespace Database\Seeders;

use App\Models\Desa;
use Illuminate\Database\Seeder;

class DesaDemografiDummySeeder extends Seeder
{
    public function run(): void
    {
        $fields = [
            'jumlah_kk_total',
            'jumlah_jiwa_total',
            'jumlah_balita',
            'jumlah_lansia',
            'jumlah_disabilitas',
        ];
        $filled = 0;
        $skipped = 0;

        Desa::query()->orderBy('kode_desa')->each(function (Desa $desa) use ($fields, &$filled, &$skipped): void {
            if (collect($fields)->contains(fn (string $field): bool => $desa->{$field} !== null)) {
                $skipped++;

                return;
            }

            $code = $desa->kode_desa;
            $households = 100 + (crc32($code) % 901);
            $peoplePerHousehold = 3 + (crc32($code.'-people') % 3);
            $population = $households * $peoplePerHousehold;

            $desa->forceFill([
                'jumlah_kk_total' => $households,
                'jumlah_jiwa_total' => $population,
                'jumlah_balita' => (int) round($population * (3 + crc32($code.'-toddlers') % 5) / 100),
                'jumlah_lansia' => (int) round($population * (6 + crc32($code.'-elderly') % 8) / 100),
                'jumlah_disabilitas' => (int) round($population * (1 + crc32($code.'-disability') % 4) / 100),
                'data_kependudukan_dummy' => true,
            ])->save();
            $filled++;
        });

        $this->command?->info("Data demografi dummy diisi untuk {$filled} desa; {$skipped} desa dilewati karena sudah memiliki data.");
    }
}
