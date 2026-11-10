<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Desa extends Model
{
    use HasFactory;

    protected $table = 'desas';
    protected $primaryKey = 'kode_desa';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'jumlah_kk_total' => 'integer',
            'jumlah_jiwa_total' => 'integer',
            'jumlah_balita' => 'integer',
            'jumlah_lansia' => 'integer',
            'jumlah_disabilitas' => 'integer',
        ];
    }

    // Accessors & Mutators untuk kompatibilitas field
    public function getIdAttribute(): string
    {
        return $this->kode_desa;
    }

    public function getJumlahKkAttribute(): int
    {
        return (int) ($this->jumlah_kk_total ?? 0);
    }

    public function setJumlahKkAttribute($value): void
    {
        $this->attributes['jumlah_kk_total'] = $value;
    }

    public function getJumlahJiwaAttribute(): int
    {
        return (int) ($this->jumlah_jiwa_total ?? 0);
    }

    public function setJumlahJiwaAttribute($value): void
    {
        $this->attributes['jumlah_jiwa_total'] = $value;
    }

    public function getLokasiDropPointAttribute(): ?string
    {
        return $this->titik_drop_air;
    }

    public function setLokasiDropPointAttribute($value): void
    {
        $this->attributes['titik_drop_air'] = $value;
    }

    public function getNamaPjAttribute(): ?string
    {
        return $this->nama_pj_darurat;
    }

    public function setNamaPjAttribute($value): void
    {
        $this->attributes['nama_pj_darurat'] = $value;
    }

    public function getNoWaAttribute(): ?string
    {
        return $this->no_wa_darurat;
    }

    public function setNoWaAttribute($value): void
    {
        $this->attributes['no_wa_darurat'] = $value;
    }

    // Fitur F-09: Pemetaan Desa (dari branch main)
    public function scopeSiapPeta(Builder $query): Builder
    {
        return $query->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', 0)
            ->where('longitude', '!=', 0);
    }

    public function scopeFilterKecamatan(Builder $query, ?string $kecamatan): Builder
    {
        return (!empty($kecamatan) && $kecamatan !== 'Semua') ? $query->where('kecamatan', $kecamatan) : $query;
    }

    public function getStatusGisAttribute(): array
    {
        return ['status' => 'Tanpa Tiket', 'kode_warna' => '#9CA3AF', 'label' => 'Belum Ada Laporan Aktif'];
    }

    public function updateKoordinat(float $lat, float $lng, ?string $titikDrop = null): bool
    {
        return $this->update([
            'latitude' => $lat,
            'longitude' => $lng,
            'titik_drop_air' => $titikDrop ?? $this->titik_drop_air,
        ]);
    }

    // Relasi User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi Cek Kelayakan (F-02) & Pengajuan Air (F-03)
    public function riwayatCekKelayakan(): HasMany
    {
        return $this->hasMany(RiwayatCekKelayakan::class, 'kode_desa', 'kode_desa')->latest();
    }

    public function latestRiwayatKelayakan(): HasOne
    {
        return $this->hasOne(RiwayatCekKelayakan::class, 'kode_desa', 'kode_desa')->latestOfMany();
    }

    public function pengajuan(): HasMany
    {
        return $this->hasMany(Pengajuan::class, 'kode_desa', 'kode_desa')->latest();
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
