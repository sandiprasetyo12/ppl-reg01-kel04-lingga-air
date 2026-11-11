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

    // Role helper methods
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
            return $this->desa()->exists();
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
