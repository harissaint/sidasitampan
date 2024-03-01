<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Sosmed;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SosmedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if($request->ajax()) {
            $data = Sosmed::all();
            return DataTables::of($data)
                ->make(true);
        }
        
        return view('setting.sosmed.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'link' => 'required|url',
            'category' => 'required|in:SIDASI TAMPAN,SIPD PENGANGGARAN,SIPD-RI PENGANGGARAN,SIPD-RI PENATAUSAHAAN,Lain-lain',
        ]);

        Sosmed::create($request->all());

        return response()->json([
            'message' => 'Data berhasil ditambahkan!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $sosmed = Sosmed::findOrFail($id);
        $sosmed->update($request->all());

        return response()->json([
            'message' => 'Data berhasil ditambahkan!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $sosmed = Sosmed::findOrFail($id);
        $sosmed->delete();

        return response()->json([
            'message' => 'Data berhasil ditambahkan!'
        ]);
    }
}
