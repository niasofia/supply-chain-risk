<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RiskController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Diakses saat pengguna belum login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Diakses saat pengguna sudah login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Global Country Intelligence & Weather Monitoring (Open-Meteo & REST Countries)
    Route::get('/country-monitoring', [CountryController::class, 'index'])->name('country.monitoring');

    // Logout (Mendukung POST & GET Fallback)
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes (Khusus Role Admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // Management Users
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::patch('/users/{id}/role', [UserController::class, 'updateRole'])->name('users.updateRole');

        // Management Risks (CRUD Lengkap)
        Route::get('/risks', [RiskController::class, 'index'])->name('risks.index');
        Route::post('/risks', [RiskController::class, 'store'])->name('risks.store');
        Route::put('/risks/{id}', [RiskController::class, 'update'])->name('risks.update');
        Route::delete('/risks/{id}', [RiskController::class, 'destroy'])->name('risks.destroy');

    });

});