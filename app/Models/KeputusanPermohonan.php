<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeputusanPermohonan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
