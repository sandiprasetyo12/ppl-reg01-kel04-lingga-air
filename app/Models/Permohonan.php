<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permohonan extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'rincian_kriteria' => 'array',
        'skor_ahp_saw' => 'float',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'kode_desa', 'kode_desa');
    }

    public function keputusanPermohonans()
    {
        return $this->hasMany(KeputusanPermohonan::class)->latest();
    }
}
