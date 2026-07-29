<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User Admin Baru
        User::updateOrCreate(
            ['email' => 'adminbaru@gmail.com'], 
            [
                'name'     => 'Administrator',
                'password' => Hash::make('admin12345'),
                'role'     => 'admin',
            ]
        );

        // User Biasa Baru
        User::updateOrCreate(
            ['email' => 'userbaru@gmail.com'], 
            [
                'name'     => 'User Biasa',
                'password' => Hash::make('user12345'),
                'role'     => 'user',
            ]
        );

        // Seed Data Negara & Kata Sentimen
        $this->call([
            CountrySeeder::class,
            SentimentWordsSeeder::class,
        ]);
    }
}