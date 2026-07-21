<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Risk;
use Illuminate\Http\Request;

class RiskController extends Controller
{
    public function index()
    {
        return response()->json(Risk::all());
    }
}