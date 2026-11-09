<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Desa extends Model
{
    use HasFactory;

    protected $table = 'desa';

    protected $fillable = [
        'user_id',
        'kode_desa',
        'nama_desa',
        'kecamatan',
        'jumlah_kk',
        'jumlah_jiwa',
        'jumlah_balita',
        'jumlah_lansia',
        'jumlah_disabilitas',
        'nama_pj',
        'no_wa',
        'lokasi_drop_point',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'jumlah_kk' => 'integer',
            'jumlah_jiwa' => 'integer',
            'jumlah_balita' => 'integer',
            'jumlah_lansia' => 'integer',
            'jumlah_disabilitas' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function simulasiKelayakan(): HasMany
    {
        return $this->hasMany(SimulasiKelayakan::class)->latest();
    }

    public function latestSimulasi(): HasOne
    {
        return $this->hasOne(SimulasiKelayakan::class)->latestOfMany();
    }

    public function pengajuan(): HasMany
    {
        return $this->hasMany(Pengajuan::class)->latest();
    }

    /**
     * Cek apakah desa sedang memiliki tiket aktif
     * Tiket aktif adalah pengajuan yang sedang diproses (bukan Draf, bukan Selesai, bukan Ditolak).
     */
    public function hasActiveTicket(): bool
    {
        return $this->pengajuan()
            ->whereIn('status', ['Diajukan', 'Dalam Telaah', 'Disetujui', 'Dijadwalkan', 'Revisi'])
            ->exists();
    }

    /**
     * Dapatkan tiket aktif milik desa jika ada
     */
    public function activeTicket(): ?Pengajuan
    {
        return $this->pengajuan()
            ->whereIn('status', ['Diajukan', 'Dalam Telaah', 'Disetujui', 'Dijadwalkan', 'Revisi'])
            ->first();
    }
}

