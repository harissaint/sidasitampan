<?php

namespace App\Http\Controllers;

use App\Models\Sosmed;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    //
    public function index()
    {
        $data['sosmeds'] = Sosmed::all();

        return view('welcome', $data);
    }
}
