<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tahapan;
use App\Models\User;
use Illuminate\Http\Request;

class TahapanController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->user()->can('isSkpd', User::class)) {
            $tahapans = Tahapan::where('is_active', true)->get();
        } else {
            $tahapans = Tahapan::all();
        }

        return response()->json([
            'message' => 'success',
            'data' => $tahapans,
        ]);
    }
}
