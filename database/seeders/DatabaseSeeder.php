<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Risk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. User Dummy (Admin & Operator/User)
        User::updateOrCreate(
            ['email' => 'admin@supplyrisk.com'],
            [
                'name'     => 'System Admin',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'operator@supplyrisk.com'],
            [
                'name'     => 'Supply Chain Operator',
                'password' => Hash::make('operator123'),
                'role'     => 'user',
            ]
        );

        // 2. Data Dummy Risk Indikator Pelabuhan
        $dummyRisks = [
            [
                'location'   => 'Port of Shanghai (China)',
                'category'   => 'Kongesti Logistik',
                'indicator'  => 'Waktu Antre Kapal > 48 Jam akibat penumpukan kontainer',
                'risk_level' => 'HIGH',
            ],
            [
                'location'   => 'Pelabuhan Tanjung Priok (Indonesia)',
                'category'   => 'Cuaca Ekstrem',
                'indicator'  => 'Potensi Banjir Rob memicu hambatan akses truk kontainer',
                'risk_level' => 'MEDIUM',
            ],
            [
                'location'   => 'Port of Hamburg (Germany)',
                'category'   => 'Operasional Alat',
                'indicator'  => 'Mogok kerja serikat buruh pelabuhan berkala',
                'risk_level' => 'HIGH',
            ],
            [
                'location'   => 'Port of Rotterdam (Netherlands)',
                'category'   => 'Kongesti Logistik',
                'indicator'  => 'Kapasitas lapangan penumpukan (yard occupancy) mencapai 85%',
                'risk_level' => 'LOW',
            ],
        ];

        foreach ($dummyRisks as $riskData) {
            Risk::create($riskData);
        }
    }
}