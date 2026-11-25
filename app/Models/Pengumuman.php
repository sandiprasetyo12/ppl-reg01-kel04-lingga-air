<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumumen';

    protected $fillable = [
        'judul',
        'isi',
        'tingkat_kelayakan',
        'is_active',
        'created_by',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActivePublished(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at');
    }
}

