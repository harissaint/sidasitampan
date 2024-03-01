<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Imports\RealisasiTempImport;
use App\Models\Realisasi;
use App\Models\RealisasiTemp;
use App\Models\Skpd;
use App\Models\Tahapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class RealisasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            if ($request->user()->can('isSkpd', User::class)) {
                $skpd = Auth::user()->skpd->kode;
                $data = Realisasi::where('kode_skpd', $skpd)
                    ->groupBy(['kode_skpd', 'nama_skpd', 'tahun'])
                    ->selectRaw('kode_skpd, nama_skpd, tahun, updated_at, sum(nilai_rincian) as jumlah')
                    ->orderBy('updated_at', 'desc')
                    ->get();
            } else {
                $data = Realisasi::groupBy(['kode_skpd', 'tahun'])
                    ->orderBy('updated_at', 'asc')
                    ->selectRaw('kode_skpd, nama_skpd, tahun, updated_at, sum(nilai_rincian) as jumlah')
                    ->get();
            }
            return DataTables::of($data)
                ->make(true);
        }

        return view('master.realisasi.index');
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
        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|mimes:xls,xlsx,csv,txt',
                'tahun' => 'required',
            ]);
            try {
                // start transaction
                DB::beginTransaction();

                if ($request->user()->can('isSkpd', User::class)) {
                    $skpd = Auth::user()->skpd->kode;
                    // delete realisasi data by tahun and skpd
                    Realisasi::where('tahun', $request->tahun)->where('kode_skpd', $skpd)->delete();
                } else {
                    // delete realisasi data by tahun
                    Realisasi::where('tahun', $request->tahun)->delete();
                }

                // delete realisasi temp data by tahun
                RealisasiTemp::where('tahun', $request->tahun)->delete();

                // import realisasi temp
                Excel::import(new RealisasiTempImport, $request->file('file'));

                // sum realisasi
                $realisasis = RealisasiTemp::groupBy(
                    'kode_urusan',
                    'nama_urusan',
                    'kode_bidang_urusan',
                    'nama_bidang_urusan',
                    'kode_skpd',
                    'nama_skpd',
                    'kode_sub_skpd',
                    'nama_sub_skpd',
                    'kode_program',
                    'nama_program',
                    'kode_kegiatan',
                    'nama_kegiatan',
                    'kode_sub_kegiatan',
                    'nama_sub_kegiatan',
                    'kode_akun',
                    'nama_akun',
                    'tahun',
                )
                    ->selectRaw('*, sum(nilai_rincian) as jumlah')->get();

                // insert realisasi
                foreach ($realisasis as $realisasi) {
                    $data = [
                        'kode_urusan' => $realisasi->kode_urusan,
                        'nama_urusan' => $realisasi->nama_urusan,
                        'kode_bidang_urusan' => $realisasi->kode_bidang_urusan,
                        'nama_bidang_urusan' => $realisasi->nama_bidang_urusan,
                        'kode_skpd' => $realisasi->kode_skpd,
                        'nama_skpd' => $realisasi->nama_skpd,
                        'kode_sub_skpd' => $realisasi->kode_sub_skpd,
                        'nama_sub_skpd' => $realisasi->nama_sub_skpd,
                        'kode_program' => $realisasi->kode_program,
                        'nama_program' => $realisasi->nama_program,
                        'kode_kegiatan' => $realisasi->kode_kegiatan,
                        'nama_kegiatan' => $realisasi->nama_kegiatan,
                        'kode_sub_kegiatan' => $realisasi->kode_sub_kegiatan,
                        'nama_sub_kegiatan' => $realisasi->nama_sub_kegiatan,
                        'kode_akun' => $realisasi->kode_akun,
                        'nama_akun' => $realisasi->nama_akun,
                        'nilai_rincian' => $realisasi->jumlah,
                        'tahun' => $request->tahun,
                    ];
                    Realisasi::create($data);
                }

                // distinct kode_skpd
                $skpds = Realisasi::where('tahun', $request->tahun)->distinct('kode_skpd')->get(['kode_skpd', 'nama_skpd']);
                // check realisasi has same kode_skpd with Skpd
                foreach ($skpds as $skpd) {
                    $check = Skpd::where('nama', $skpd->nama_skpd)->first();
                    if ($check) {
                        // update realisasi kode_skpd to same with Skpd
                        Realisasi::where('tahun', $request->tahun)
                            ->where('kode_skpd', $skpd->kode_skpd)
                            ->update(['kode_skpd' => $check->kode]);
                    }
                }

                // commit transaction
                DB::commit();

                return response()->json([
                    'message' => 'Data berhasil ditambahkan!',
                ]);
            } catch (Throwable $e) {
                // rollback transaction
                DB::rollback();

                return response()->json([
                    'message' => 'Data gagal ditambahkan!',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }

        return response()->json([
            'message' => 'Data gagal ditambahkan!',
        ], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function bandingkan(Request $request)
    {
        //        
        if ($request->ajax()) {
            $tahapan = Tahapan::findOrfail($request->tahapan_id);
            if ($request->user()->can('isSkpd', User::class)) {
                $skpd = Auth::user()->skpd->kode;
                $data = Realisasi::getDataByTahapanSkpd($tahapan->tahun, $request->tahapan_id, $skpd);
            } else if ($request->skpd != '') {
                $data = Realisasi::getDataByTahapanSkpd($tahapan->tahun, $request->tahapan_id, $request->skpd);
            } else {
                $data = Realisasi::getDataByTahapan($tahapan->tahun, $request->tahapan_id);
            }
            return DataTables::of($data)
                ->make(true);
        }

        return view('master.realisasi.bandingkan');
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
