<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class BerkasPengajuan extends Model
{
    use HasFactory;

    protected $table = 'berkas_pengajuan';

    protected $fillable = [
        'pengajuan_id',
        'jenis_berkas',
        'nama_file',
        'path_file',
        'mime_type',
        'ukuran_file',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'ukuran_file' => 'integer',
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path_file);
    }

    public function humanFileSize(): string
    {
        $bytes = $this->ukuran_file;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }
}

