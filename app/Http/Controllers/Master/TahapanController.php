<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Tahapan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TahapanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $data = Tahapan::getTotalPerTahapan();
            return DataTables::of($data)
                ->make(true);
        }

        return view('master.tahapan.index');
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
        // if($request->ajax()){
        $data = $request->all();
        $tahapan = Tahapan::create($data);
        // update for code
        $code = bcrypt($tahapan->id);
        $tahapan->update(['code' => $code]);
        return response()->json($tahapan);
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        //
        $tahapan = Tahapan::findOrFail($id);
        if ($request->ajax()) {
            $data = $tahapan->sipds;
            return DataTables::of($data)
                ->make(true);
        }

        return view('master.tahapan.show', compact('tahapan'));
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
        $tahapan = Tahapan::findOrFail($id);
        $tahapan->update($request->all());
        return response()->json($tahapan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $tahapan = Tahapan::findOrFail($id);
        if ($tahapan->sipds->count() != 0) {
            $tahapan->sipds()->delete();
        } else if ($tahapan->sipd_non_belanja->count() != 0) {
            $tahapan->sipd_non_belanja()->delete();
        } else {
            $tahapan->sipd_temps()->delete();
            $tahapan->delete();
        }
        return response()->json($tahapan);
    }
}
