<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun admin awal. Segera ganti kata sandinya setelah login pertama.
        if (!User::where('email', 'admin@bawaslu.test')->exists()) {
            User::forceCreate([
                'name' => 'Administrator',
                'email' => 'admin@bawaslu.test',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'area' => 'Bawaslu Kabupaten/Kota',
                'role' => 'admin',
            ]);
        }
    }
}
