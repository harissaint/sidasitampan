<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MapPrioritas;
use App\Models\Prioritas;
use App\Models\Sipd;
use App\Models\Skpd;
use App\Models\Tahapan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PrioritasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            if($request->user()->can('isSkpd', User::class)){
                $data = Prioritas::where('type', 'kode rekening')
                ->with('maps', function($query) use ($request){
                    $query->where('kode_skpd', $request->user()->skpd->kode);
                })
                ->get();
            }else{
                $data = Prioritas::with('maps')
                ->where('type', 'kode rekening')
                ->get();
            }
            return DataTables::of($data)
                ->make(true);
        }

        return view('master.prioritas.index');
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
        $data = $request->all();
        $data['type'] = 'kode rekening';
        $prioritas = Prioritas::create($data);

        return response()->json($prioritas);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        //
        $data['prioritas'] = Prioritas::findOrFail($id);

        // get skpd
        if($request->user()->can('isSkpd', User::class))
            $skpds = Skpd::where('kode', $request->user()->skpd->kode)->get();
        else
            $skpds = Skpd::all();

        $data['trees'] = [];
        // foreach skpd
        foreach ($skpds as $keys => $skpd) {
            // add skpd
            $data['trees'][$keys]['text'] = $skpd->nama;

            // find programs
            $kode_skpd = substr($skpd->kode, 0, 4);
            $programs = Sipd::where('kode_program', 'like', $kode_skpd . '%')
                ->where('kode_skpd', $skpd->kode)
                ->whereHas('tahapan', function ($query) use ($data) {
                    $query->where('tahapans.tahun', $data['prioritas']->tahun);
                })
                ->distinct()
                ->get(['kode_program', 'nama_program']);
            $jprogram = [];
            foreach ($programs as $keyp => $program) {
                $jprogram[$keyp]['text'] = $program->nama_program;

                // find kegiatan
                $kode_program = $program->kode_program;
                $kegiatans = Sipd::where('kode_kegiatan', 'like', $kode_program . '%')
                    ->where('kode_skpd', $skpd->kode)
                    ->whereHas('tahapan', function ($query) use ($data) {
                        $query->where('tahapans.tahun', $data['prioritas']->tahun);
                    })
                    ->distinct()
                    ->get(['kode_kegiatan', 'nama_kegiatan']);
                $jkegiatan = [];
                foreach ($kegiatans as $keyk => $kegiatan) {
                    $jkegiatan[$keyk]['text'] = $kegiatan->nama_kegiatan;

                    // find sub kegiatan
                    $kode_kegiatan = $kegiatan->kode_kegiatan;
                    $sub_kegiatans = Sipd::where('kode_sub_kegiatan', 'like', $kode_kegiatan . '%')
                        ->where('kode_skpd', $skpd->kode)
                        ->whereHas('tahapan', function ($query) use ($data) {
                            $query->where('tahapans.tahun', $data['prioritas']->tahun);
                        })
                        ->distinct()
                        ->get(['kode_sub_kegiatan', 'nama_sub_kegiatan']);                    
                    $jskegiatan = [];
                    foreach ($sub_kegiatans as $keysk => $sub_kegiatan) {
                        $jskegiatan[$keysk]['text'] = $sub_kegiatan->nama_sub_kegiatan;

                        // find akun
                        $kode_sub_kegiatan = $sub_kegiatan->kode_sub_kegiatan;
                        $akuns = Sipd::where('kode_sub_kegiatan', $kode_sub_kegiatan)
                            ->where('kode_skpd', $skpd->kode)
                            ->where('kode_skpd', $skpd->kode)
                            ->whereHas('tahapan', function ($query) use ($data) {
                                $query->where('tahapans.tahun', $data['prioritas']->tahun);
                            })
                            ->distinct()
                            ->get(['kode_sub_kegiatan', 'nama_sub_kegiatan', 'kode_akun', 'nama_akun', 'kode_skpd', 'nama_skpd']);
                        $jakun = [];
                        foreach ($akuns as $keya => $akun) {
                            $jakun[$keya]['text'] = $akun->nama_akun;
                            $jakun[$keya]['icon'] = 'bx bx-file-blank';
                            $jakun[$keya]['id'] = $akun->kode_skpd . '-' . $akun->kode_sub_kegiatan . '-' . $akun->kode_akun;

                            // check map
                            $is_checked = MapPrioritas::where('prioritas_id', $id)
                                ->where('kode_skpd', $akun->kode_skpd)
                                ->where('kode_sub_kegiatan', $akun->kode_sub_kegiatan)
                                ->where('kode_rekening', $akun->kode_akun)
                                ->count();
                            if ($is_checked > 0) {
                                $jakun[$keya]['state'] = [
                                    'selected' => true
                                ];
                            }
                        }

                        // children akun
                        $jskegiatan[$keysk]['children'] = $jakun;
                    }

                    // children sub kegiatan
                    $jkegiatan[$keyk]['children'] = $jskegiatan;
                }

                // children kegiatan
                $jprogram[$keyp]['children'] = $jkegiatan;
            }

            // children program
            $data['trees'][$keys]['children'] = $jprogram;
        }
        $data['jtree'] = json_encode($data['trees']);
        // dd(ini_get_all());

        return view('master.prioritas.show', $data);
    }

    public function report(Request $request, string $id)
    {
        $data['prioritas'] = Prioritas::findOrFail($id);
        if ($request->ajax()) {
            if ($request->user()->can('isSkpd', User::class)) {
                $maps = MapPrioritas::where('prioritas_id', $id)
                    ->where('kode_skpd', $request->user()->skpd->kode)
                    ->get();
            } else {
                $maps = MapPrioritas::where('prioritas_id', $id)->get();
            }
            $data['mp'] = [];
            foreach ($maps as $map) {
                $sipd = Sipd::where('kode_skpd', $map->kode_skpd)
                    ->where('kode_sub_kegiatan', $map->kode_sub_kegiatan)
                    ->where('kode_akun', $map->kode_rekening)
                    ->where('tahapan_id', $request->tahapan_id)
                    ->first();
                if ($sipd != null)
                    $data['mp'][] = $sipd;
            }
            return DataTables::of($data['mp'])
                ->make(true);
        }

        return view('master.prioritas.report', $data);
    }

    public function recap(Request $request, string $id)
    {
        $data['prioritas'] = Prioritas::findOrFail($id);
        if ($request->ajax()) {
            if ($request->user()->can('isSkpd', User::class)) {
                $maps = MapPrioritas::where('prioritas_id', $id)
                    ->where('kode_skpd', $request->user()->skpd->kode)
                    ->get();
            } else {
                $maps = MapPrioritas::where('prioritas_id', $id)->get();
            }
            $data['mp'] = [];
            foreach ($maps as $map) {
                $sipd = Sipd::where('kode_skpd', $map->kode_skpd)
                    ->where('kode_sub_kegiatan', $map->kode_sub_kegiatan)
                    ->where('kode_akun', $map->kode_rekening)
                    ->where('tahapan_id', $request->tahapan_id)
                    ->groupBy('kode_skpd')
                    ->first();
                if ($sipd != null) {
                    $data['mp'][] = $sipd;
                }
            }
            // to collection
            $data['mp'] = collect($data['mp']);
            // group by skpd and sum nilai_rincian
            $data['mp'] = $data['mp']->groupBy('kode_skpd')->map(function ($item, $key) {
                return [
                    'kode_skpd' => $key,
                    'nama_skpd' => $item[0]->nama_skpd,
                    'nilai_rincian' => $item->sum('nilai_rincian')
                ];
            });
            return DataTables::of($data['mp'])
                ->make(true);
        }

        return view('master.prioritas.recap', $data);
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
        $prioritas = Prioritas::findOrFail($id);
        $prioritas->update($request->all());
        return response()->json($prioritas);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $prioritas = Prioritas::findOrFail($id);
        if ($prioritas->maps()->count() > 0) {
            $prioritas->maps()->delete();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Berhasil menghapus data maping!'
                ]
            );
        } else {
            $prioritas->delete();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Berhasil menghapus data!'
                ]
            );
        }
    }

    public function upsertMap(Request $request, string $id)
    {
        //
        $prioritas = Prioritas::findOrFail($id);

        DB::beginTransaction();

        try {
            // delete all map
            if($request->user()->can('isSkpd', User::class)){
                $prioritas->maps()->where('kode_skpd', $request->user()->skpd->kode)->delete();
            }else{
                $prioritas->maps()->delete();
            }
            $ids = $request->data['ids'];
            foreach ($ids as $id) {
                $map = new MapPrioritas();
                $map->prioritas_id = $prioritas->id;
                $strformat = explode('-', $id);
                if (count($strformat) == 3) {
                    $map->kode_skpd = $strformat[0];
                    $map->kode_sub_kegiatan = $strformat[1];
                    $map->kode_rekening = $strformat[2];
                    $map->save();
                }
            }

            DB::commit();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Berhasil menyimpan data!'
                ]
            );
        } catch (\Throwable $th) {
            DB::rollback();

            return response()->json(
                [
                    'status' => 500,
                    'message' => 'Gagal menyimpan data!'
                ]
            );
        }
    }
}
