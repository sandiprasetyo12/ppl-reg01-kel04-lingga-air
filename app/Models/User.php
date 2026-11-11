<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'username',
        'password',
        'role',
        'phone',
    ];

    protected $hidden = [
        'password',
    ];

    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function desa(): HasOne
    {
        return $this->hasOne(Desa::class);
    }

    public function penyalur(): HasOne
    {
        return $this->hasOne(Penyalur::class);
    }

    // Role helper methods (case-insensitive in PHP, covers both 'desa'/'pemohon' and 'bpbd'/'admin')
    public function isDesa(): bool
    {
        return in_array($this->role, ['desa', 'pemohon'], true);
    }

    public function isBpbd(): bool
    {
        return in_array($this->role, ['bpbd', 'admin'], true);
    }

    public function isPenyalur(): bool
    {
        return $this->role === 'penyalur';
    }

    public function isPemohon(): bool
    {
        return $this->isDesa();
    }

    public function isAdmin(): bool
    {
        return $this->isBpbd();
    }

    // Helper untuk mengecek apakah user sudah selesai onboarding
    public function hasCompletedOnboarding(): bool
    {
        if (is_null($this->role)) {
            return false;
        }

        if ($this->isBpbd()) {
            return true;
        }

        if ($this->isDesa()) {
            return $this->desa()
                ->whereNotNull('jumlah_kk_total')
                ->where('jumlah_kk_total', '>', 0)
                ->whereNotNull('jumlah_jiwa_total')
                ->where('jumlah_jiwa_total', '>', 0)
                ->whereNotNull('jumlah_balita')
                ->where('jumlah_balita', '>=', 0)
                ->whereNotNull('jumlah_lansia')
                ->where('jumlah_lansia', '>=', 0)
                ->whereNotNull('jumlah_disabilitas')
                ->where('jumlah_disabilitas', '>=', 0)
                ->whereRaw('(jumlah_balita + jumlah_lansia + jumlah_disabilitas) <= jumlah_jiwa_total')
                ->exists();
        }

        if ($this->isPenyalur()) {
            return $this->penyalur()->exists();
        }

        return false;
    }

    // Cek status onboarding
    public function currentOnboardingStatus(): string
    {
        if (is_null($this->role)) {
            return 'role';
        }

        return 'profile';
    }

    // Alias agar sinkron dengan pemanggilan di AuthController & OnboardingMiddleware
    public function currentOnboardingStep(): string
    {
        return $this->currentOnboardingStatus();
    }
}
