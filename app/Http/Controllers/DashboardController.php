<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Risk;

class DashboardController extends Controller
{
    public function index()
    {
        // Data Risiko dari Database
        $risks = Risk::latest()->get();

        // Data Pelabuhan Global untuk Peta (Leaflet.js)
        $ports = [
            [
                'name' => 'Port of Shanghai',
                'country' => 'China',
                'lat' => 31.2304,
                'lng' => 121.4737,
                'status' => 'HIGH RISK (Kongesti Heavy)',
            ],
            [
                'name' => 'Pelabuhan Tanjung Priok',
                'country' => 'Indonesia',
                'lat' => -6.1032,
                'lng' => 106.8837,
                'status' => 'MEDIUM RISK (Cuaca / Rob)',
            ],
            [
                'name' => 'Port of Hamburg',
                'country' => 'Germany',
                'lat' => 53.5511,
                'lng' => 9.9937,
                'status' => 'HIGH RISK (Mogok Kerja)',
            ],
            [
                'name' => 'Port of Rotterdam',
                'country' => 'Netherlands',
                'lat' => 51.9244,
                'lng' => 4.4777,
                'status' => 'LOW RISK (Operasional Normal)',
            ],
        ];

        return view('dashboard', compact('risks', 'ports'));
    }
}