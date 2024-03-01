<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Imports\SipdImport;
use App\Imports\SipdNonBelanja24Import;
use App\Imports\SipdNonBelanjaImport;
use App\Imports\SipdTempImport;
use App\Models\Sipd;
use App\Models\SipdNonBelanja;
use App\Models\SipdTemp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SipdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $data = Sipd::with('tahapan')->get();
            return DataTables::of($data)
                ->make(true);
        }

        return view('master.sipd.index');
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
            'file' => 'required|mimes:xls,xlsx,csv,txt',
            'tahapan_id' => 'required',
            'jenis' => 'required|in:belanja,pendapatan/pembiayaan',
            'format' => 'required_if:jenis,belanja',
        ]);

        switch ($request->input('jenis')) {
            case 'belanja':
                $this->store_belanja($request);
                return response()->json([
                    'message' => 'Data berhasil ditambahkan!',
                ]);
                break;
            case 'pendapatan/pembiayaan':
                $this->store_non_belanja($request);
                return response()->json([
                    'message' => 'Data berhasil ditambahkan!',
                ]);
                break;
        }

        return response()->json([
            'message' => 'Data gagal ditambahkan!',
        ], 400);
    }

    private function store_belanja(Request $request)
    {
        if ($request->hasFile('file')) {
            try {
                DB::beginTransaction();
                // delete sipd data by tahapan_id
                Sipd::where('tahapan_id', $request->tahapan_id)->delete();

                if ($request->format == 'v2023') {
                    // import sipd
                    Excel::import(new SipdImport, $request->file('file'));
                } else {
                    // import sipd temp
                    Excel::import(new SipdTempImport, $request->file('file'));

                    // group by kode_skpd, kode_program, kode_kegiatan, kode_sub_kegiatan, kode_akun
                    $data = SipdTemp::groupBy(
                        'kode_urusan',
                        'kode_bidang_urusan',
                        'kode_skpd',
                        'kode_sub_skpd',
                        'kode_program',
                        'kode_kegiatan',
                        'kode_sub_kegiatan',
                        'kode_akun',
                        'sub_unit',
                    )
                        ->selectRaw('
                            kode_urusan, nama_urusan,
                            kode_bidang_urusan, nama_bidang_urusan,
                            kode_skpd, nama_skpd,
                            kode_sub_skpd, nama_sub_skpd,
                            kode_program, nama_program,
                            kode_kegiatan, nama_kegiatan,
                            kode_sub_kegiatan, nama_sub_kegiatan,
                            kode_akun, nama_akun,
                            sub_unit,
                            sum(nilai_rincian) as nilai_rincian
                        ')
                        ->get();

                    // insert sipd
                    foreach ($data as $sipd) {
                        $data = [
                            'kode_urusan' => $sipd->kode_urusan,
                            'nama_urusan' => $sipd->nama_urusan,
                            'kode_bidang_urusan' => $sipd->kode_bidang_urusan,
                            'nama_bidang_urusan' => $sipd->nama_bidang_urusan,
                            'kode_skpd' => $sipd->kode_skpd,
                            'nama_skpd' => $sipd->nama_skpd,
                            'kode_sub_skpd' => $sipd->kode_sub_skpd,
                            'nama_sub_skpd' => $sipd->nama_sub_skpd,
                            'kode_program' => $sipd->kode_program,
                            'nama_program' => $sipd->nama_program,
                            'kode_kegiatan' => $sipd->kode_kegiatan,
                            'nama_kegiatan' => $sipd->nama_kegiatan,
                            'kode_sub_kegiatan' => $sipd->kode_sub_kegiatan,
                            'nama_sub_kegiatan' => $sipd->nama_sub_kegiatan,
                            'kode_akun' => $sipd->kode_akun,
                            'nama_akun' => $sipd->nama_akun,
                            'sub_unit' => $sipd->sub_unit,
                            'nilai_rincian' => $sipd->nilai_rincian,
                            'tahapan_id' => $request->tahapan_id,
                        ];
                        Sipd::create($data);
                    }

                    // delete all sipd temp
                    DB::statement('DELETE FROM sipd_temps');
                }

                DB::commit();

                return response()->json([
                    'message' => 'Data berhasil ditambahkan!',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }

    private function store_non_belanja(Request $request)
    {
        if ($request->hasFile('file')) {
            try {

                // delete sipd data by tahapan_id
                SipdNonBelanja::where('tahapan_id', $request->tahapan_id)->delete();

                if ($request->format == 'v2023') {
                    // import sipd
                    Excel::import(new SipdNonBelanjaImport, $request->file('file'));
                } else {
                    // import sipd
                    Excel::import(new SipdNonBelanja24Import, $request->file('file'));
                }

                return response()->json([
                    'message' => 'Data berhasil ditambahkan!',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => "ada kesalahan pada baris ke-" . $e->getMessage() . "!",
                ], 500);
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

    public function bandingkan(Request $request)
    {
        //
        if ($request->ajax()) {
            if ($request->user()->can('isSkpd', User::class)) {
                $skpd = Auth::user()->skpd->kode;
                $data = Sipd::getDataByTahapanSkpd($request->tahap_id_1, $request->tahap_id_2, $skpd);
            } else if ($request->skpd != '') {
                $data = Sipd::getDataByTahapanSkpd($request->tahap_id_1, $request->tahap_id_2, $request->skpd);
            } else {
                $data = Sipd::getDataByTahapan($request->tahap_id_1, $request->tahap_id_2);
            }
            return DataTables::of($data)
                ->make(true);
        }

        return view('master.sipd.bandingkan');
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
