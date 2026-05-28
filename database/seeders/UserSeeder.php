<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'sikocak334@gmail.com',
            'password' => Hash::make('akuadmin'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Pegawai 1
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'pegawai@example.com',
            'password' => Hash::make('password'),
            'role' => 'pegawai',
            'unit_kerja' => 'Bendahara',
            'is_active' => true,
        ]);

        // Pegawai 2
        User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'),
            'role' => 'pegawai',
            'unit_kerja' => 'Kesra',
            'is_active' => true,
        ]);
    }
}
