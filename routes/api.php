<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RiskController;

/*
|--------------------------------------------------------------------------
| REST API Routes (Sesuai Spesifikasi Project Final)
|--------------------------------------------------------------------------
*/

Route::get('/countries', [RiskController::class, 'getCountries']);
Route::get('/risk', [RiskController::class, 'index']);
Route::get('/ports', [RiskController::class, 'getPorts']);
Route::get('/news', [RiskController::class, 'getNews']);
Route::get('/currency', [RiskController::class, 'getCurrency']);

// Endpoint Tambahan
Route::get('/weather', [RiskController::class, 'getWeather']);
Route::get('/sentiment', [RiskController::class, 'analyzeSentiment']);