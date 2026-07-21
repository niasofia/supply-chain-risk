<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $countryName = $request->get('country', 'Indonesia');

        // Database Profil Negara Lengkap (Lokal Fallback)
        $countriesDatabase = [
            'Indonesia' => [
                'name' => ['common' => 'Indonesia'],
                'capital' => ['Jakarta'],
                'region' => 'Asia',
                'subregion' => 'South-Eastern Asia',
                'population' => 273753191,
                'flags' => ['png' => 'https://flagcdn.com/w160/id.png'],
                'currencies' => ['IDR' => ['name' => 'Indonesian rupiah', 'symbol' => 'Rp']],
            ],
            'Germany' => [
                'name' => ['common' => 'Germany'],
                'capital' => ['Berlin'],
                'region' => 'Europe',
                'subregion' => 'Western Europe',
                'population' => 83240525,
                'flags' => ['png' => 'https://flagcdn.com/w160/de.png'],
                'currencies' => ['EUR' => ['name' => 'Euro', 'symbol' => '€']],
            ],
            'China' => [
                'name' => ['common' => 'China'],
                'capital' => ['Beijing'],
                'region' => 'Asia',
                'subregion' => 'Eastern Asia',
                'population' => 1411778724,
                'flags' => ['png' => 'https://flagcdn.com/w160/cn.png'],
                'currencies' => ['CNY' => ['name' => 'Chinese yuan', 'symbol' => '¥']],
            ],
            'Australia' => [
                'name' => ['common' => 'Australia'],
                'capital' => ['Canberra'],
                'region' => 'Oceania',
                'subregion' => 'Australia and New Zealand',
                'population' => 25687041,
                'flags' => ['png' => 'https://flagcdn.com/w160/au.png'],
                'currencies' => ['AUD' => ['name' => 'Australian dollar', 'symbol' => '$']],
            ],
            'Japan' => [
                'name' => ['common' => 'Japan'],
                'capital' => ['Tokyo'],
                'region' => 'Asia',
                'subregion' => 'Eastern Asia',
                'population' => 125836021,
                'flags' => ['png' => 'https://flagcdn.com/w160/jp.png'],
                'currencies' => ['JPY' => ['name' => 'Japanese yen', 'symbol' => '¥']],
            ],
            'United States' => [
                'name' => ['common' => 'United States'],
                'capital' => ['Washington, D.C.'],
                'region' => 'Americas',
                'subregion' => 'North America',
                'population' => 331002651,
                'flags' => ['png' => 'https://flagcdn.com/w160/us.png'],
                'currencies' => ['USD' => ['name' => 'United States dollar', 'symbol' => '$']],
            ],
        ];

        // 1. Ambil data negara dari lokal database
        $countryData = $countriesDatabase[$countryName] ?? $countriesDatabase['Indonesia'];

        // 2. Mapping koordinat lat & lng untuk Open-Meteo Weather API
        $coordinates = [
            'Indonesia'     => ['lat' => -6.2088, 'lng' => 106.8456],
            'Germany'       => ['lat' => 52.5200, 'lng' => 13.4050],
            'China'         => ['lat' => 39.9042, 'lng' => 116.4074],
            'Australia'     => ['lat' => -35.2809, 'lng' => 149.1300],
            'Japan'         => ['lat' => 35.6762, 'lng' => 139.6503],
            'United States' => ['lat' => 38.8951, 'lng' => -77.0364],
        ];

        $lat = $coordinates[$countryName]['lat'] ?? -6.2088;
        $lng = $coordinates[$countryName]['lng'] ?? 106.8456;

        // 3. Fetch Cuaca Real-Time dari Open-Meteo API
        $weatherData = null;
        try {
            $weatherResponse = Http::withoutVerifying()
                ->timeout(5)
                ->get("https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lng}&current_weather=true");

            if ($weatherResponse->successful()) {
                $weatherData = $weatherResponse->json()['current_weather'] ?? null;
            }
        } catch (\Exception $e) {
            $weatherData = null;
        }

        return view('country', compact('countryName', 'countryData', 'weatherData', 'lat', 'lng'));
    }
}