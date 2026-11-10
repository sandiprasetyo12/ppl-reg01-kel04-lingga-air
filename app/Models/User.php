<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'username',
        'password',
        'role',
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

    public function desa()
    {
        return $this->hasOne(Desa::class);
    }

    public function penyalur()
    {
        return $this->hasOne(Penyalur::class);
    }

    //helper untuk cek role
    public function IsDesa(): bool
    {
        return $this->role === 'desa';
    }

    public function IsBpbd(): bool
    {
        return $this->role === 'bpbd';
    }

    public function IsPenyalur(): bool
    {
        return $this->role === 'penyalur';
    }

    // Helper untuk mengecek apakah user sudah selesai onboarding
    public function hasCompletedOnboarding(): bool
    {
        //jika belum ada role
        if (is_null($this->role)) {
            return false;
        }

        //bpbd selalu dianggap sudah selesai onboarding
        if ($this->IsBpbd()) {
            return true;
        }

        //jika role desa, cek apakah sudah ada data desa
        if ($this->IsDesa()) {
            return $this->desa()->exists();
        }

        //jika role penyalur, cek apakah sudah ada data penyalur
        if ($this->IsPenyalur()) {
            return $this->penyalur()->exists(); 
        }

        return false;
    }

    //cek status onboarding
    public function currentOnboardingStatus(): string
    {
        //jika belum ada role, redirect ke halaman pilih role
        if (is_null($this->role)) {
            return 'role';
        }

        //sudah pilih role tapi belum isi profile, redirect ke halaman profile
        return 'profile';
    }

    // Alias agar sinkron dengan pemanggilan di AuthController & OnboardingMiddleware
    public function currentOnboardingStep(): string
    {
        return $this->currentOnboardingStatus();
    }
}
