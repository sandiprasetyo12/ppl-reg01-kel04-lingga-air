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
        'desa_id',
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
        return $this->belongsTo(Desa::class);
    }

    public function simulasi(): BelongsTo
    {
        return $this->belongsTo(SimulasiKelayakan::class, 'simulasi_id');
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
     * Estimasi kebutuhan air (liter), estimasi 100 liter per KK (sesuai mockup: 120 KK = 12.000 Liter)
     */
    public function estimasiLiter(): int
    {
        return max(4000, ($this->jumlah_kk ?? 0) * 100);
    }

    /**
     * Estimasi jumlah armada tangki (kapasitas standar 4.000 L / tangki)
     */
    public function estimasiTangki(): int
    {
        return (int) ceil($this->estimasiLiter() / 4000);
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

