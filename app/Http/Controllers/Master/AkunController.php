<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Imports\AkunImport;
use App\Models\Akun;
use App\Models\Sipd;
use App\Models\SipdNonBelanja;
use App\Models\Skpd;
use App\Models\Tahapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class AkunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $data = Akun::all();
            return DataTables::of($data)
                ->make(true);
        }

        return view('master.akun.index');
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
        // level indicate how many dots in the code
        $level = substr_count($request->kode, '.');
        $level += 1;
        Akun::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'tahun' => $request->tahun,
            'level' => $level,
        ]);

        return response()->json([
            'message' => 'Data berhasil ditambahkan!',
        ]);
    }

    public function import(Request $request)
    {
        //
        if ($request->hasFile('file')) {
            try {
                $request->validate([
                    'file' => 'required|mimes:xls,xlsx',
                ]);

                Excel::import(new AkunImport, $request->file('file'));

                return response()->json([
                    'message' => 'Data berhasil ditambahkan!',
                ]);
            } catch (\Exception $e) {
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
        $akun = Akun::find($id);
        $level = substr_count($request->kode, '.');
        $level += 1;
        $akun->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'tahun' => $request->tahun,
            'level' => $level,
        ]);

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
        $akun = Akun::find($id);
        $akun->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus!',
        ]);
    }

    public function nilai(Request $request)
    {
        //
        $data['tahapans'] = Tahapan::all();

        if ($request->ajax()) {
            // $akun_lv6 = [];
            if ($request->search['value'] == null) {
                $akuns = DB::select("
                SELECT * FROM ( 
                    SELECT a.kode, a.nama, 0 as nilai 
                        FROM akuns a 
                    UNION ALL 
                    SELECT b.kode_akun, b.nama_akun, sum(b.nilai_rincian) as nilai 
                        FROM sipds b 
                            WHERE b.tahapan_id=?
                        group BY b.kode_akun, b.nama_akun
                    UNION ALL
                    SELECT c.kode_akun, c.nama_akun, sum(c.nilai_rincian) as nilai 
                        FROM sipd_non_belanjas c
                        WHERE c.tahapan_id=?
                            group BY c.kode_akun, c.nama_akun )
                    as t 
                    ORDER BY kode ASC", [$request->tahapan_id, $request->tahapan_id]);
            } else {
                $akuns = DB::select("
                SELECT * FROM ( 
                    SELECT a.kode, a.nama, 0 as nilai 
                        FROM akuns a                         
                    UNION ALL 
                    SELECT b.kode_akun, b.nama_akun, sum(b.nilai_rincian) as nilai 
                        FROM sipds b 
                            WHERE b.tahapan_id=?
                        group BY b.kode_akun, b.nama_akun
                    UNION ALL
                    SELECT c.kode_akun, c.nama_akun, sum(c.nilai_rincian) as nilai 
                        FROM sipd_non_belanjas c
                        WHERE c.tahapan_id=?
                            group BY c.kode_akun, c.nama_akun )
                    as t 
                    WHERE t.kode LIKE ? OR t.nama LIKE ?
                    ORDER BY kode ASC", [
                    $request->tahapan_id,
                    $request->tahapan_id,
                    '%' . $request->search['value'] . '%',
                    '%' . $request->search['value'] . '%'
                ]);
            }

            // get nilai from nilai_rincian sipd
            foreach ($akuns as $key => $akun) {
                if ($key > $request->start + $request->length)
                    break;

                if ($key >= $request->start && $key < $request->start + $request->length) {
                    // check if this akun belanja
                    if ($akun->kode[0] == '5') {
                        $akun->nilai = Sipd::where('tahapan_id', $request->tahapan_id)
                            ->where('kode_akun', 'like', $akun->kode . '%')
                            ->sum('nilai_rincian');
                    } else {
                        $akun->nilai = SipdNonBelanja::where('tahapan_id', $request->tahapan_id)
                            ->where('kode_akun', 'like', $akun->kode . '%')
                            ->sum('nilai_rincian');
                    }
                    $akun->tahapan_id = $request->tahapan_id;
                }
            }

            return DataTables::of($akuns)
                ->make(true);
        }

        return view('master.akun.nilai.index', $data);
    }

    public function skpd(Request $request, string $kode)
    {
        $data['akun'] = Akun::where('kode', $kode)->first();
        if ($data['akun'] == null) {
            if ($kode[0] == '5') {
                $data['akun'] = Sipd::where('kode_akun', $kode)
                    ->where('tahapan_id', $request->tahapan_id)
                    ->first();
            } else {
                $data['akun'] = SipdNonBelanja::where('kode_akun', $kode)
                    ->where('tahapan_id', $request->tahapan_id)
                    ->first();
            }
        }
        // dd($data['akun']);

        if ($request->ajax()) {
            if ($kode[0] == '5') {
                $skpds = Sipd::where('kode_akun', 'like', $kode . '%')
                    ->where('tahapan_id', $request->tahapan_id)
                    ->groupBy('kode_skpd')
                    ->get([
                        '*',
                        DB::raw(
                            'sum(nilai_rincian) as nilai, "' . $kode . '" as rekening, "' . $request->tahapan_id . '" as tahapan_id'
                        )
                    ]);
            } else {
                $skpds = SipdNonBelanja::where('kode_akun', 'like', $kode . '%')
                    ->where('tahapan_id', $request->tahapan_id)
                    ->groupBy('kode_skpd')
                    ->get([
                        '*',
                        DB::raw(
                            'sum(nilai_rincian) as nilai, "' . $kode . '" as rekening, "' . $request->tahapan_id . '" as tahapan_id'
                        )
                    ]);
            }

            return DataTables::of($skpds)
                ->make(true);
        }

        return view('master.akun.nilai.skpd', $data);
    }

    public function subkeg(Request $request, string $kode_rekening, string $kode_skpd)
    {
        $data['akun'] = Akun::where('kode', $kode_rekening)->first();        
        if ($data['akun'] == null) {
            if ($kode_rekening[0] == '5') {
                $data['akun'] = Sipd::where('kode_akun', $kode_rekening)
                    ->where('tahapan_id', $request->tahapan_id)
                    ->first();
                    
            } else {
                $data['akun'] = SipdNonBelanja::where('kode_akun', $kode_rekening)
                    ->where('tahapan_id', $request->tahapan_id)
                    ->first();
            }
        }

        $data['skpd'] = Skpd::where('kode', $kode_skpd)->first();        

        if ($request->ajax()) {
            if ($kode_rekening[0] == '5') {
                $sipds = Sipd::where('kode_akun', 'like', $kode_rekening . '%')
                    ->where('tahapan_id', $request->tahapan_id)
                    ->where('kode_skpd', $kode_skpd)
                    ->groupBy('kode_sub_kegiatan')
                    ->get(['*', DB::raw('sum(nilai_rincian) as nilai, "' . $kode_rekening . '" as rekening')]);
            } else {
                $sipds = SipdNonBelanja::where('kode_akun', 'like', $kode_rekening . '%')
                    ->where('tahapan_id', $request->tahapan_id)
                    ->where('kode_skpd', $kode_skpd)
                    ->groupBy('kode_sub_kegiatan')
                    ->get(['*', DB::raw('sum(nilai_rincian) as nilai, "' . $kode_rekening . '" as rekening')]);
            }

            return DataTables::of($sipds)
                ->make(true);
        }

        return view('master.akun.nilai.subkeg', $data);
    }
}
