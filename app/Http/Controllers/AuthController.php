<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }

    // Proses login admin
    public function login(Request $request)
    {
        // Validasi inputan
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba lakukan login (pencocokan ke database)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Buat session baru agar aman
            return redirect()->intended('/dashboard');
        }

        // Jika gagal, kembalikan ke login dengan pesan error
        return back()->withErrors([
            'loginError' => 'Email atau password yang Anda masukkan salah!',
        ])->onlyInput('email');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}