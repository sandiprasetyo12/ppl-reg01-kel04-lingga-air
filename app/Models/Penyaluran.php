<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Penyaluran extends Model
{
    use HasFactory;

    protected $table = 'penyalurans';

    protected $guarded = [];

    protected $casts = [
        'tanggal_penyaluran' => 'date',
        'volume_air_liter' => 'integer',
        'jumlah_tangki' => 'integer',
    ];

    /**
     * Memastikan tabel penyalurans tersedia secara otomatis dan terisi data awal jika kosong
     */
    public static function ensureTableExists(): void
    {
        if (!Schema::hasTable('penyalurans')) {
            Schema::create('penyalurans', function (Blueprint $table) {
                $table->id();
                $table->string('kode_penyaluran', 30)->unique();
                $table->string('nomor_tiket', 50)->nullable()->index();
                $table->string('kode_desa', 20)->index();
                $table->foreignId('penyalur_id')->nullable()->constrained('penyalurs')->nullOnDelete();
                $table->date('tanggal_penyaluran');
                $table->integer('volume_air_liter')->default(5000);
                $table->integer('jumlah_tangki')->default(1);
                $table->enum('status', ['Terkirim', 'Dalam Pengiriman', 'Dijadwalkan', 'Kendala'])->default('Terkirim');
                $table->string('titik_serah_terima')->nullable();
                $table->string('nama_penerima')->nullable();
                $table->string('no_telepon_penerima', 25)->nullable();
                $table->string('armada_nopol', 20)->nullable();
                $table->string('nama_driver')->nullable();
                $table->text('catatan')->nullable();
                $table->string('foto_bukti')->nullable();
                $table->timestamps();
            });
        }

        try {
            if (self::count() === 0) {
                (new \Database\Seeders\PenyaluranSeeder())->run();
            }
        } catch (\Throwable $e) {
            // Silently fail if dependencies are not yet ready
        }
    }

    /**
     * Relasi ke Mitra Penyalur (PMI, PDAM, BPBD)
     */
    public function penyalur(): BelongsTo
    {
        return $this->belongsTo(Penyalur::class, 'penyalur_id');
    }

    /**
     * Relasi ke Desa
     */
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class, 'kode_desa', 'kode_desa');
    }

    /**
     * Relasi opsional ke Permohonan
     */
    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(Permohonan::class, 'nomor_tiket', 'nomor_tiket');
    }

    /**
     * Scope Filter Multi-Kriteria untuk PBI-022 & PBI-023
     */
    public function scopeFilter($query, array $filters)
    {
        // Filter Periode Tanggal Mulai
        if (!empty($filters['start_date'])) {
            $query->whereDate('tanggal_penyaluran', '>=', $filters['start_date']);
        }

        // Filter Periode Tanggal Selesai
        if (!empty($filters['end_date'])) {
            $query->whereDate('tanggal_penyaluran', '<=', $filters['end_date']);
        }

        // Filter Kecamatan
        if (!empty($filters['kecamatan'])) {
            $query->whereHas('desa', function ($q) use ($filters) {
                $q->where('kecamatan', $filters['kecamatan']);
            });
        }

        // Filter Mitra Penyalur
        if (!empty($filters['penyalur_id'])) {
            $query->where('penyalur_id', $filters['penyalur_id']);
        }

        // Filter Status Penyaluran
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query;
    }
}
