<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WargaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('warga')->insert([
            'nama_lengkap' => 'Ahmad Akrom Kamaluddin',
            'kontak' => '08123456789',
            'alamat' => 'Gresik, Jawa Timur',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

