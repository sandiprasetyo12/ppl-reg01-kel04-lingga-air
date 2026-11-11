<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RiwayatCekKelayakan extends Model
{
    protected $table = 'riwayat_cek_kelayakan';

    protected $fillable = [
        'kode_desa',
        'jumlah_kk_total',
        'jumlah_jiwa_total',
        'jumlah_balita',
        'jumlah_lansia',
        'jumlah_disabilitas',
        'kk_terdampak',
        'hari_tanpa_air',
        'jarak_sumber_meter',
        'c1',
        'c2',
        'c3',
        'c4',
        'bobot_c1',
        'bobot_c2',
        'bobot_c3',
        'bobot_c4',
        'rasio_konsistensi',
        'skor',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_kk_total' => 'integer',
            'jumlah_jiwa_total' => 'integer',
            'jumlah_balita' => 'integer',
            'jumlah_lansia' => 'integer',
            'jumlah_disabilitas' => 'integer',
            'kk_terdampak' => 'integer',
            'hari_tanpa_air' => 'integer',
            'jarak_sumber_meter' => 'integer',
            'c1' => 'float',
            'c2' => 'float',
            'c3' => 'float',
            'c4' => 'float',
            'bobot_c1' => 'float',
            'bobot_c2' => 'float',
            'bobot_c3' => 'float',
            'bobot_c4' => 'float',
            'rasio_konsistensi' => 'float',
            'skor' => 'float',
        ];
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class, 'kode_desa', 'kode_desa');
    }

    public function pengajuan(): HasMany
    {
        return $this->hasMany(Pengajuan::class, 'riwayat_cek_kelayakan_id');
    }

    public function getSkorAhpSawAttribute(): float
    {
        return (float) $this->skor;
    }

    public function getStatusKelayakanAttribute(): string
    {
        return ucfirst(strtolower($this->status));
    }

    public function getJarakSumberAirAttribute(): int
    {
        return (int) $this->jarak_sumber_meter;
    }

    public function getRekomendasiAttribute(): string
    {
        return match (strtolower($this->status)) {
            'kritis' => 'Kondisi darurat krisis air bersih. Segera ajukan permohonan bantuan pasokan armada tangki ke BPBD.',
            'waspada' => 'Penuhi syarat administrasi dan siapkan titik tandon untuk pengajuan bantuan pasokan.',
            default => 'Kondisi pasokan air masih terkendali secara mandiri di tingkat lingkungan.',
        };
    }

    public function isEligibleForSubmission(): bool
    {
        return in_array(strtolower($this->status), ['waspada', 'kritis'], true);
    }
}
