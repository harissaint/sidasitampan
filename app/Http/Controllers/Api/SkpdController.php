<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skpd;
use Illuminate\Http\Request;

class SkpdController extends Controller
{
    //
    public function index()
    {
        $skpds = Skpd::all();

        return response()->json([
            'message' => 'success',
            'data' => $skpds,
        ]);
    }
}
