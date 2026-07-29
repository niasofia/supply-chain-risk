<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Risk;
use App\Models\Country; // Wajib memanggil Model Country

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data Risiko dari Database
        $risks = Risk::latest()->get();

        // 2. Mengambil seluruh data 100+ negara dari database (untuk dropdown)
        $countries = Country::all();
        if ($countries->isEmpty()) {
            try {
                \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'CountrySeeder', '--force' => true]);
                $countries = Country::all();
            } catch (\Throwable $e) {
                // Keep empty collection if seeding fails
            }
        }

        // 3. Mapping data port/pelabuhan untuk peta Leaflet
        $ports = $countries->map(function($country) {
            return [
                'name' => $country->port_name,
                'country' => $country->name,
                'lat' => (float) $country->lat,
                'lng' => (float) $country->lng,
                'status' => 'NORMAL / AKTIF',
            ];
        });

        // Kirim semua variabel ke view dashboard.blade.php
        return view('dashboard', compact('risks', 'countries', 'ports'));
    }
}