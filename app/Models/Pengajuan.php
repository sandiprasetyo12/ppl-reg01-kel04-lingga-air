<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';

    protected $fillable = [
        'nomor_tiket',
        'kode_desa',
        'desa_id',
        'riwayat_cek_kelayakan_id',
        'simulasi_id',
        'status',
        'jumlah_kk',
        'hari_tanpa_air',
        'jarak_sumber_air',
        'alasan_pengajuan',
        'lokasi_pengantaran',
        'latitude',
        'longitude',
        'catatan_tambahan',
        'tanggal_pengajuan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'datetime',
            'latitude' => 'float',
            'longitude' => 'float',
            'jumlah_kk' => 'integer',
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

    public function riwayatKelayakan(): BelongsTo
    {
        return $this->belongsTo(RiwayatCekKelayakan::class, 'riwayat_cek_kelayakan_id');
    }

    public function simulasi(): BelongsTo
    {
        return $this->riwayatKelayakan();
    }

    public function getSimulasiIdAttribute(): ?int
    {
        return $this->riwayat_cek_kelayakan_id;
    }

    public function setSimulasiIdAttribute($value): void
    {
        $this->attributes['riwayat_cek_kelayakan_id'] = $value;
    }

    public function berkas(): HasMany
    {
        return $this->hasMany(BerkasPengajuan::class, 'pengajuan_id');
    }

    public function fotoKondisi(): HasMany
    {
        return $this->hasMany(BerkasPengajuan::class, 'pengajuan_id')->where('jenis_berkas', 'foto_kondisi');
    }

    public function suratKepalaDesa(): HasOne
    {
        return $this->hasOne(BerkasPengajuan::class, 'pengajuan_id')->where('jenis_berkas', 'surat_kepala_desa');
    }

    public function isDraft(): bool
    {
        return $this->status === 'Draf';
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['Diajukan', 'Dalam Telaah', 'Disetujui', 'Dijadwalkan', 'Revisi']);
    }

    /**
     * Estimasi kebutuhan air (liter), estimasi 100 liter per KK (minimum 5.000 L / 1 armada tangki)
     */
    public function estimasiLiter(): int
    {
        return max(5000, ($this->jumlah_kk ?? 0) * 100);
    }

    /**
     * Estimasi jumlah armada tangki (kapasitas standar 5.000 L / tangki)
     */
    public function estimasiTangki(): int
    {
        return (int) ceil($this->estimasiLiter() / 5000);
    }

    /**
     * Generate nomor tiket unik format: LA-YYYYMMDD-XXX
     */
    public static function generateNomorTiket(?Carbon $date = null): string
    {
        $date = $date ?: Carbon::now();
        $datePrefix = 'LA-' . $date->format('Ymd') . '-';

        $lastTicket = static::where('nomor_tiket', 'like', $datePrefix . '%')
            ->orderBy('nomor_tiket', 'desc')
            ->first();

        $sequence = 1;
        if ($lastTicket && preg_match('/-(\d+)$/', $lastTicket->nomor_tiket, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }

        return $datePrefix . str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
    }
}

