<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SimulasiKelayakan extends Model
{
    use HasFactory;

    protected $table = 'simulasi_kelayakan';

    protected $fillable = [
        'kode_desa',
        'desa_id',
        'kk_terdampak',
        'jiwa_rentan',
        'hari_tanpa_air',
        'jarak_sumber_air',
        'skor_ahp_saw',
        'status_kelayakan',
        'rekomendasi',
    ];

    protected function casts(): array
    {
        return [
            'skor_ahp_saw' => 'float',
            'kk_terdampak' => 'integer',
            'jiwa_rentan' => 'integer',
            'hari_tanpa_air' => 'integer',
            'jarak_sumber_air' => 'integer',
        ];
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class, 'kode_desa', 'kode_desa');
    }

    public function getDesaIdAttribute(): ?string
    {
        return $this->kode_desa;
    }

    public function setDesaIdAttribute($value): void
    {
        $this->attributes['kode_desa'] = $value;
    }

    public function pengajuan(): HasMany
    {
        return $this->hasMany(Pengajuan::class, 'simulasi_id');
    }

    /**
     * Memeriksa apakah hasil kelayakan memenuhi syarat pengajuan (Kritis atau Waspada)
     */
    public function isEligibleForSubmission(): bool
    {
        return in_array($this->status_kelayakan, ['Kritis', 'Waspada']);
    }
}
