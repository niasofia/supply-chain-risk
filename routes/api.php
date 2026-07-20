<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RiskController; // 1. Pastikan baris ini ada!

// Route Cuaca
Route::get('/weather', [RiskController::class, 'getWeather']);

// Route Analisis Sentimen
Route::get('/sentiment', [RiskController::class, 'analyzeSentiment']);

// Route Perhitungan Risiko (BARU)
Route::get('/risk', [RiskController::class, 'calculateRisk']); // 2. Pastikan baris ini ada!