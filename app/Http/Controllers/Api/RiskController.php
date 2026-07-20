<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class RiskController extends Controller
{
    /**
     * 1. Ambil Data Cuaca Real-Time dari Open-Meteo API
     */
    public function getWeather()
    {
        $latitude = -6.2088;
        $longitude = 106.8456;

        $response = Http::get("https://api.open-meteo.com/v1/forecast", [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'current_weather' => true
        ]);

        if ($response->successful()) {
            $weatherData = $response->json();
            $current = $weatherData['current_weather'];

            return response()->json([
                'status' => 'success',
                'location' => 'Jakarta, Indonesia',
                'temperature' => $current['temperature'] . '°C',
                'windspeed' => $current['windspeed'] . ' km/h',
                'weather_code' => $current['weathercode']
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Gagal mengambil data cuaca'
        ], 500);
    }

    /**
     * 2. Analisis Sentimen Sederhana (Lexicon-Based)
     */
    public function analyzeSentiment(Request $request)
    {
        // Ambil input teks berita dari user
        $text = $request->input('text', 'Inflation increases while exports decrease due to war.');

        // Bersihkan teks: ubah ke huruf kecil dan hapus tanda baca
        $cleanText = strtolower(preg_replace('/[^a-z\s]/', '', $text));
        
        // Pecah teks menjadi kumpulan kata individu
        $words = explode(' ', $cleanText);

        // Ambil daftar kata positif dan negatif dari database MySQL
        $positiveWords = DB::table('positive_words')->pluck('word')->toArray();
        $negativeWords = DB::table('negative_words')->pluck('word')->toArray();

        $positiveScore = 0;
        $negativeScore = 0;
        $matchedPositive = [];
        $matchedNegative = [];

        // Hitung kecocokan kata (Lexicon-Based)
        foreach ($words as $word) {
            if (in_array($word, $positiveWords)) {
                $positiveScore++;
                $matchedPositive[] = $word;
            }
            if (in_array($word, $negativeWords)) {
                $negativeScore++;
                $matchedNegative[] = $word;
            }
        }

        // Tentukan hasil akhir sentimen
        if ($positiveScore > $negativeScore) {
            $sentiment = 'Positive';
        } elseif ($negativeScore > $positiveScore) {
            $sentiment = 'Negative';
        } else {
            $sentiment = 'Neutral';
        }

        return response()->json([
            'status' => 'success',
            'original_text' => $text,
            'detected_positive_words' => $matchedPositive,
            'detected_negative_words' => $matchedNegative,
            'positive_score' => $positiveScore,
            'negative_score' => $negativeScore,
            'final_sentiment' => $sentiment
        ]);
    }

    /**
     * 3. Risk Scoring Engine (Weighted Risk Model Terintegrasi Sentimen Berita)
     */
    public function calculateRisk(Request $request)
    {
        $country = $request->input('country', 'Indonesia');
        
        // Ambil input berita dari user. Jika kosong, pakai kalimat bawaan ini
        $text = $request->input('text', 'Inflation increases while exports decrease due to war.');

        // --- A. PROSES ANALISIS SENTIMEN REAL-TIME DARI INPUT ---
        $cleanText = strtolower(preg_replace('/[^a-z\s]/', '', $text));
        $words = explode(' ', $cleanText);

        $positiveWords = DB::table('positive_words')->pluck('word')->toArray();
        $negativeWords = DB::table('negative_words')->pluck('word')->toArray();

        $positiveScore = 0;
        $negativeScore = 0;

        foreach ($words as $word) {
            if (in_array($word, $positiveWords)) { $positiveScore++; }
            if (in_array($word, $negativeWords)) { $negativeScore++; }
        }

        // Hitung skor risiko berita (0 - 100) berdasarkan rasio kata negatif terhadap total kecocokan kata
        $totalWords = $positiveScore + $negativeScore;
        if ($totalWords > 0) {
            $newsSentimentRisk = ($negativeScore / $totalWords) * 100;
        } else {
            $newsSentimentRisk = 50; // Netral (50%) jika tidak ada kata yang terdeteksi cocok
        }

        // --- B. AMBIL DATA INDIKATOR RISIKO LAINNYA ---
        $weatherRisk = 40;   // Weather Risk (Bobot 30%)
        $inflationRisk = 50; // Inflation Risk (Bobot 20%)
        $currencyRisk = 30;  // Currency Risk (Bobot 10%)

        // --- C. PERHITUNGAN WEIGHTED RISK MODEL (Rumus: Skor * Bobot) ---
        $weightedWeather = $weatherRisk * 0.30;
        $weightedInflation = $inflationRisk * 0.20;
        $weightedNews = $newsSentimentRisk * 0.40; // News Sentiment Risk memiliki bobot terbesar (40%)
        $weightedCurrency = $currencyRisk * 0.10;

        // Total Risk Score
        $totalRiskScore = round($weightedWeather + $weightedInflation + $weightedNews + $weightedCurrency, 1);

        // Tentukan Kategori Risiko berdasarkan skor akhir
        if ($totalRiskScore < 35) {
            $status = 'Low Risk (Risiko Rendah)';
            $recommendation = 'Aman untuk melakukan pengiriman barang dan transaksi.';
        } elseif ($totalRiskScore >= 35 && $totalRiskScore <= 65) {
            $status = 'Medium Risk (Risiko Sedang)';
            $recommendation = 'Lakukan pemantauan berkala pada cuaca dan kondisi geopolitik daerah transit.';
        } else {
            $status = 'High Risk (Risiko Tinggi)';
            $recommendation = '⚠️ Sangat direkomendasikan untuk menunda pengiriman atau mencari rute alternatif!';
        }

        // Return JSON terstruktur
        return response()->json([
            'status' => 'success',
            'country' => $country,
            'calculation_method' => 'Weighted Risk Model (30% Weather, 20% Inflation, 40% News Sentiment, 10% Currency)',
            'news_analyzed' => $text,
            'individual_scores' => [
                'weather_risk' => $weatherRisk . '%',
                'inflation_risk' => $inflationRisk . '%',
                'news_sentiment_risk' => round($newsSentimentRisk, 1) . '%',
                'currency_risk' => $currencyRisk . '%',
            ],
            'weighted_contributions' => [
                'weather_contribution' => $weightedWeather . '%',
                'inflation_contribution' => $weightedInflation . '%',
                'news_contribution' => round($weightedNews, 1) . '%',
                'currency_contribution' => $weightedCurrency . '%',
            ],
            'total_risk_score' => $totalRiskScore . '%',
            'risk_status' => $status,
            'recommendation' => $recommendation
        ]);
    }
}