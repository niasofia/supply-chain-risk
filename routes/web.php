<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. ROUTE PUBLIC (GUEST)
// ==========================================
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login'])->name('login.post');


// ==========================================
// 2. ROUTE WAJIB LOGIN (USER & ADMIN)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Utama (Diatur via DashboardController)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Process Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


    // ==========================================
    // 3. ROUTE KHUSUS ADMIN
    // ==========================================
    Route::middleware(['admin'])->group(function () {
        
        // Kelola Pengguna
        Route::get('/admin/users', function () {
            return "Halaman Kelola User (Khusus Admin)";
        })->name('admin.users');
        
        // Tambahkan route khusus admin lainnya di sini...
        
    });

});