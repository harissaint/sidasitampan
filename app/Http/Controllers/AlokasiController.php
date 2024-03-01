<?php

namespace App\Http\Controllers;

use App\Models\Tahapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlokasiController extends Controller
{
    //
    public function index(Request $request)
    {
        $data['tahaps'] = Tahapan::latest()->get();
        $tahapan = $request->input('tahapan') ?? Tahapan::latest()->first()->id;
        $data['results'] = DB::select('CALL getSP("' . $tahapan . '")');        
        // $data['pendidikan'] = $data['results'][0];
        // $data['kesehatan'] = $data['results'][1];
        // $data['infrastruktur'] = $data['results'][2];
        // $data['pengawasan'] = $data['results'][3];
        // $data['pelatihan'] = $data['results'][4];

        return view('alokasi.index', $data);
    }
}
