<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Risk; // Pastikan Model Risk disesuaikan dengan nama model kamu
use Illuminate\Http\Request;

class RiskController extends Controller
{
    // Menampilkan daftar risiko
    public function index()
    {
        $risks = Risk::latest()->get();
        return view('admin.risks', compact('risks'));
    }

    // Menyimpan data risiko baru (METODE INI YANG SEBELUMNYA HILANG/BELUM ADA)
    public function store(Request $request)
    {
        $request->validate([
            'location'   => 'required|string|max:255',
            'category'   => 'required|string',
            'indicator'  => 'required|string|max:255',
            'risk_level' => 'required|in:LOW,MEDIUM,HIGH',
        ]);

        Risk::create([
            'location'   => $request->location,
            'category'   => $request->category,
            'indicator'  => $request->indicator,
            'risk_level' => $request->risk_level,
        ]);

        return redirect()->back()->with('success', 'Data risiko berhasil ditambahkan!');
    }

    // Memperbarui data risiko (untuk fitur Edit)
    public function update(Request $request, $id)
    {
        $request->validate([
            'location'   => 'required|string|max:255',
            'category'   => 'required|string',
            'indicator'  => 'required|string|max:255',
            'risk_level' => 'required|in:LOW,MEDIUM,HIGH',
        ]);

        $risk = Risk::findOrFail($id);
        $risk->update([
            'location'   => $request->location,
            'category'   => $request->category,
            'indicator'  => $request->indicator,
            'risk_level' => $request->risk_level,
        ]);

        return redirect()->back()->with('success', 'Data risiko berhasil diperbarui!');
    }

    // Menghapus data risiko (untuk fitur Hapus)
    public function destroy($id)
    {
        $risk = Risk::findOrFail($id);
        $risk->delete();

        return redirect()->back()->with('success', 'Data risiko berhasil dihapus!');
    }
}