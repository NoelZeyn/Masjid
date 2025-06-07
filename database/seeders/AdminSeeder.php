<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admin')->insert([
            'nama_lengkap' => 'Admin Masjid',
            'posisi' => 'Ketua',
            'email' => 'admin@masjid.id',
            'password' => Hash::make('password'),
            'tugas' => 'Mengelola semua data',
            'timestamp' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
