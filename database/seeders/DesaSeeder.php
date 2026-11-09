<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Desa;
use Illuminate\Support\Facades\File;

class DesaSeeder extends Seeder
{
    public function run(): void
    {
        // Menentukan direktori file berada
        $jsonPath = database_path('data/desa_master.json');

        // Memvalidasi apakah file ada di dalam direktori
        if (!File::exists($jsonPath)) {
            $this->command->error("File data/desa_master.json tidak ditemukan!");
            return;
        }

        // Mengubah json menjadi array
        $dataDesa = json_decode(File::get($jsonPath), true);

        // Masukkan data desa ke dalam database menggunakan updateOrCreate untuk menghindari duplikasi
        foreach ($dataDesa as $item) {
            Desa::updateOrCreate(
                ['kode_desa' => $item['kode_desa']],
                [
                    'nama_desa' => $item['nama_desa'],
                    'kecamatan' => $item['kecamatan'],
                    'latitude'  => $item['latitude'] ?? null,
                    'longitude' => $item['longitude'] ?? null,
                ]
            );
        }

        // 5. Tampilkan pesan sukses di console
        $this->command->info("Berhasil! Sebanyak " . Desa::count() . " desa tersimpan di database.");
    }
}