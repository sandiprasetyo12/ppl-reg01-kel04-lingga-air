<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Desa extends Model
{
    protected $table = 'desas';
    protected $primaryKey = 'kode_desa';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    //Fitur F-09-pemetaan-desa
    // Scope untuk PBI-025: Hanya ambil desa yang punya koordinat (tidak NULL dan tidak 0)
    public function scopeSiapPeta(Builder $query): Builder
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude')->where('latitude', '!=', 0)->where('longitude', '!=', 0);
    }

    // Scope untuk PBI-026: Filter per kecamatan
    public function scopeFilterKecamatan(Builder $query, ?string $kecamatan): Builder
    {
        return (!empty($kecamatan) && $kecamatan !== 'Semua') ? $query->where('kecamatan', $kecamatan) : $query;
    }

    // Accessor untuk PBI-025: Status dan warna penanda peta
    public function getStatusGisAttribute(): array
    {
        return ['status' => 'Tanpa Tiket', 'kode_warna' => '#9CA3AF', 'label' => 'Belum Ada Laporan Aktif'];
    }

    // Method untuk PBI-027: Update titik koordinat drop air
    public function updateKoordinat(float $lat, float $lng, ?string $titikDrop = null): bool
    {
        return $this->update(['latitude' => $lat, 'longitude' => $lng, 'titik_drop_air' => $titikDrop ?? $this->titik_drop_air]);
    }


    

    // Silahkan tambahkan function kalian masing" sesuai fitur dan berikan command per PBI agar jelas dibawah ini dan tambahkan relasinya juga

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function riwayatCekKelayakan()
    {
        return $this->hasMany(RiwayatCekKelayakan::class, 'kode_desa', 'kode_desa');
    }
}