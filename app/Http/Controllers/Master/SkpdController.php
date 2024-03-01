<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Imports\SkpdImport;
use App\Models\Skpd;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SkpdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $data = Skpd::all();
            return DataTables::of($data)
                ->make(true);
        }

        return view('master.skpd.index');
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
        Skpd::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'nip_kepala' => $request->nip_kepala,
            'nama_kepala' => $request->nama_kepala,
        ]);

        return response()->json([
            'message' => 'Data berhasil ditambahkan!',
        ]);
    }

    /**
     * Store data from excel.
     */
    public function import(Request $request)
    {
        //
        if($request->hasFile('file')) {
            try{
                $request->validate([
                    'file' => 'required|mimes:xls,xlsx',
                ]);
                
                Excel::import(new SkpdImport, $request->file('file'));

                return response()->json([
                    'message' => 'Data berhasil ditambahkan!',
                ]);
            }
            catch(\Exception $e){
                return response()->json([
                    'message' => $e->getMessage(),
                ]);
            }
        }
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
        $skpd = Skpd::findOrFail($id);
        $skpd->kode = $request->kode;
        $skpd->nama = $request->nama;
        $skpd->nip_kepala = $request->nip_kepala;
        $skpd->nama_kepala = $request->nama_kepala;
        $skpd->save();

        return response()->json([
            'message' => 'Data berhasil diubah!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $skpd = Skpd::findOrFail($id);
        $skpd->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus!',
        ]);
    }
}
