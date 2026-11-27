<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * Label tingkat kedaruratan dalam teks terformat
     */
    public function getTingkatLabelAttribute(): string
    {
        return match (strtolower($this->tingkat_kelayakan)) {
            'kritis' => 'Kritis',
            'waspada' => 'Waspada',
            'aman' => 'Aman',
            default => ucfirst($this->tingkat_kelayakan),
        };
    }

    /**
     * CSS kelas badge tingkat kedaruratan
     */
    public function getBadgeClassAttribute(): string
    {
        return match (strtolower($this->tingkat_kelayakan)) {
            'kritis' => 'bg-[#FEF3F2] text-[#B42318] border-[#FECDCA]',
            'waspada' => 'bg-[#FEF0C7] text-[#B54708] border-[#FEDF89]',
            'aman' => 'bg-[#ECFDF3] text-[#027A48] border-[#A6F4C5]',
            default => 'bg-[#F2F4F7] text-[#344054] border-[#EAECF0]',
        };
    }
}
