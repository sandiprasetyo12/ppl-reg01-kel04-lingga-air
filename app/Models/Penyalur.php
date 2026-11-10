<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyalur extends Model
{
    protected $table = 'penyalurs';

    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [
        'kapasitas_tangki_liter' => 'integer',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
    
}
