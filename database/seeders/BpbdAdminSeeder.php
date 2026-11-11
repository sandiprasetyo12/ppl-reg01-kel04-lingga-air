<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BpbdAdminSeeder extends Seeder
{
    // Seed akun admin BPBD (tidak perlu onboarding, langsung bisa login)
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin_bpbd'],
            [
                'password' => Hash::make('password123'),
                'role' => 'bpbd',
            ]
        );
    }
}