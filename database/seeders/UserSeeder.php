<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Hapus data user lama jika ada agar tidak duplikat
        DB::table('users')->truncate();

        // Masukkan data admin baru
        DB::table('users')->insert([
            'name' => 'Admin Supply Chain',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'), // Password di-enkripsi dengan aman
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}