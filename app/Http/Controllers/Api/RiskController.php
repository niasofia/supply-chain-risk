<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Risk;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiskController extends Controller
{
    // GET /api/risk
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => Risk::latest()->get()
        ]);
    }

    // GET /api/countries
    public function getCountries()
    {
        $countries = Country::all();
        return response()->json([
            'status' => 'success',
            'total' => $countries->count(),
            'data' => $countries
        ]);
    }

    // GET /api/ports
    public function getPorts()
    {
        $ports = Country::all()->map(function($country) {
            return [
                'port_name' => $country->port_name,
                'country' => $country->name,
                'country_code' => strtoupper($country->code),
                'lat' => (float) $country->lat,
                'lng' => (float) $country->lng,
                'status' => 'ACTIVE'
            ];
        });

        return response()->json([
            'status' => 'success',
            'total' => $ports->count(),
            'data' => $ports
        ]);
    }

    // GET /api/news
    public function getNews(Request $request)
    {
        $newsText = $request->get('text', 'Rising inflation rates and sudden currency depreciation are causing mild delays in customs clearance');
        
        $positiveWords = DB::table('positive_words')->pluck('word')->toArray();
        $negativeWords = DB::table('negative_words')->pluck('word')->toArray();

        $words = preg_split('/\s+/', strtolower(preg_replace('/[^a-zA-Z\s]/', '', $newsText)));
        
        $positiveScore = 0;
        $negativeScore = 0;

        foreach ($words as $word) {
            if (in_array($word, $positiveWords)) $positiveScore++;
            if (in_array($word, $negativeWords)) $negativeScore++;
        }

        $sentiment = $positiveScore > $negativeScore ? 'Positive' : ($negativeScore > $positiveScore ? 'Negative' : 'Neutral');

        return response()->json([
            'status' => 'success',
            'analyzed_text' => $newsText,
            'positive_count' => $positiveScore,
            'negative_count' => $negativeScore,
            'sentiment' => $sentiment
        ]);
    }

    // GET /api/currency
    public function getCurrency()
    {
        return response()->json([
            'status' => 'success',
            'base' => 'USD',
            'rates' => [
                'IDR' => 15850.00,
                'EUR' => 0.92,
                'GBP' => 0.79,
                'JPY' => 154.30,
                'CNY' => 7.23,
                'SGD' => 1.34,
                'MYR' => 4.72,
                'AUD' => 1.52,
            ]
        ]);
    }

    // GET /api/weather
    public function getWeather(Request $request)
    {
        $lat = $request->get('lat', -6.1018);
        $lng = $request->get('lng', 106.8824);

        return response()->json([
            'status' => 'success',
            'coordinates' => ['lat' => $lat, 'lng' => $lng],
            'weather' => [
                'temperature' => 28.5,
                'windspeed' => 12.4,
                'condition' => 'Clear / Moderate Wind'
            ]
        ]);
    }

    // GET /api/sentiment
    public function analyzeSentiment(Request $request)
    {
        return $this->getNews($request);
    }
}