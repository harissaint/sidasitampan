<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if($request->ajax()) {
            $data = Group::all();
            return DataTables::of($data)
                ->make(true);
        }
        
        return view('setting.group.index');
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
        Group::create([
            'nama' => $request->nama
        ]);

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
        $group = Group::findOrFail($id);
        $group->nama = $request->nama;
        $group->save();

        return response()->json([
            'message' => 'Data berhasil diubah!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $group = Group::findOrFail($id);
        $group->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus!'
        ]);
    }
}
