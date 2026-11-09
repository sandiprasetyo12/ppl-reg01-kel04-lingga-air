<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relasi ke permohonan bantuan
     */
    public function permohonans()
    {
        return $this->hasMany(Permohonan::class);
    }

    /**
     * Accessor untuk total kelompok rentan desa (Balita + Lansia + Disabilitas)
     */
    public function getTotalJiwaRentanAttribute(): int
    {
        return (int) ($this->jumlah_balita + $this->jumlah_lansia + $this->jumlah_disabilitas);
    }
}
