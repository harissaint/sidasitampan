<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MapPrioritasSumberDanaSk;
use App\Models\PrioritasSumberDana;
use App\Models\Sipd;
use App\Models\Skpd;
use App\Models\Tahapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PrioritasSdSkController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $tahapans = Tahapan::all();

            if ($request->user()->can('isSkpd', User::class)) {
                $prioritas = PrioritasSumberDana::with('tahapan')
                    ->with('sk_maps', function ($query) use ($request) {
                        $query->where('kode_skpd', $request->user()->skpd->kode);
                    })
                    ->where('type', 'sub kegiatan')
                    ->get();
            } else {
                $prioritas = PrioritasSumberDana::with('tahapan')
                    ->with('sk_maps')
                    ->where('type', 'sub kegiatan')
                    ->get();
            }
            // dd($prioritas);

            $data = [];
            foreach ($prioritas as $key => $value) {
                $data[$key]['id'] = $value->id;
                $data[$key]['nama'] = $value->nama;
                $data[$key]['tahapan'] = $value->tahapan;
                $data[$key]['sk_maps'] = $value->sk_maps;
                $data[$key]['tahapans'] = $tahapans;
            }
            $data = collect($data);
            return DataTables::of($data)
                ->make(true);
        }

        return view('master.prioritas-sd-sk.index');
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
        $prioritas = PrioritasSumberDana::create([
            'nama' => $data['nama'],
            'type' => 'sub kegiatan',
            'tahapan_id' => $data['tahapan_id'],
        ]);

        return response()->json($prioritas);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        //
        $data['prioritas'] = PrioritasSumberDana::findOrFail($id);

        // get skpd
        if ($request->user()->can('isSkpd', User::class))
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
                    $query->where('tahapans.id', $data['prioritas']->tahapan_id);
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
                        $query->where('tahapans.id', $data['prioritas']->tahapan_id);
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
                            $query->where('tahapans.id', $data['prioritas']->tahapan_id);
                        })
                        ->distinct()
                        ->get(['kode_skpd', 'kode_sub_kegiatan', 'nama_sub_kegiatan']);
                    $jskegiatan = [];
                    foreach ($sub_kegiatans as $keysk => $sub_kegiatan) {
                        $jskegiatan[$keysk]['text'] = $sub_kegiatan->nama_sub_kegiatan;
                        $jskegiatan[$keysk]['icon'] = 'bx bx-file-blank';
                        $jskegiatan[$keysk]['id'] = $sub_kegiatan->kode_skpd . '-' . $sub_kegiatan->kode_sub_kegiatan;

                        // check map
                        $is_checked = MapPrioritasSumberDanaSk::where('prioritas_sumber_dana_id', $id)
                            ->where('kode_skpd', $sub_kegiatan->kode_skpd)
                            ->where('kode_sub_kegiatan', $sub_kegiatan->kode_sub_kegiatan)
                            ->count();
                        if ($is_checked > 0) {
                            $jskegiatan[$keysk]['state'] = [
                                'selected' => true
                            ];
                        }
                    }

                    // children sub kegiatan
                    $jkegiatan[$keyk]['children'] = $jskegiatan;

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

        return view('master.prioritas-sd-sk.show', $data);
    }

    public function report(Request $request, string $id)
    {
        $data['prioritas'] = PrioritasSumberDana::findOrFail($id);
        if ($request->ajax()) {
            if ($request->user()->can('isSkpd', User::class)) {
                $maps = MapPrioritasSumberDanaSk::where('prioritas_sumber_dana_id', $id)
                    ->where('kode_skpd', $request->user()->skpd->kode)
                    ->get();
            } else {
                $maps = MapPrioritasSumberDanaSk::where('prioritas_sumber_dana_id', $id)->get();
            }

            $data['mp'] = [];
            // dd($maps);
            foreach ($maps as $key => $map) {
                $sipd = Sipd::where('kode_skpd', $map->kode_skpd)
                    ->where('kode_sub_kegiatan', $map->kode_sub_kegiatan)
                    ->where('tahapan_id', $data['prioritas']->tahapan_id)
                    ->groupBy('kode_sub_kegiatan')
                    ->get(['*', DB::raw('sum(nilai_rincian) as nilai_rincian')])
                    ->first();
                if ($sipd != null) {
                    $data['mp'][$key]['id'] = $map->id;
                    $data['mp'][$key]['nilai'] = $map->nilai;
                    $data['mp'][$key]['sipd'] = $sipd;
                }
            }
            return DataTables::of($data['mp'])
                ->make(true);
        }

        return view('master.prioritas-sd-sk.report', $data);
    }

    public function recap(Request $request, string $id)
    {
        $data['prioritas'] = PrioritasSumberDana::findOrFail($id);
        if ($request->ajax()) {
            $maps = MapPrioritasSumberDanaSk::where('prioritas_sumber_dana_id', $id)->get();
            $data['mp'] = [];
            foreach ($maps as $map) {
                $sipd = Sipd::where('kode_skpd', $map->kode_skpd)
                    ->where('kode_sub_kegiatan', $map->kode_sub_kegiatan)
                    ->where('tahapan_id', $data['prioritas']->tahapan_id)
                    ->groupBy('kode_skpd')
                    ->get(['*', DB::raw('sum(nilai_rincian) as nilai_rincian')])
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

        return view('master.prioritas-sd-sk.recap', $data);
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
        $prioritas = PrioritasSumberDana::findOrFail($id);
        $prioritas->update($request->all());
        return response()->json($prioritas);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $prioritas = PrioritasSumberDana::findOrFail($id);
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
        $prioritas = PrioritasSumberDana::findOrFail($id);

        DB::beginTransaction();

        try {
            // delete all map
            if ($request->user()->can('isSkpd', User::class))
                $prioritas->sk_maps()->where('kode_skpd', $request->user()->skpd->kode)->delete();
            else
                $prioritas->sk_maps()->delete();

            $ids = $request->data['ids'];
            foreach ($ids as $id) {
                $map = new MapPrioritasSumberDanaSk();
                $map->prioritas_sumber_dana_id = $prioritas->id;
                $strformat = explode('-', $id);
                if (count($strformat) == 2) {
                    $map->kode_skpd = $strformat[0];
                    $map->kode_sub_kegiatan = $strformat[1];
                    $map->save();
                }else{
                    throw new \Exception("Format data tidak sesuai!");
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

    public function updateNilai(Request $request, string $id)
    {
        //
        $map = MapPrioritasSumberDanaSk::findOrFail($id);
        $map->nilai = $request->nilai;
        $map->save();

        return response()->json(
            [
                'status' => 200,
                'message' => 'Berhasil menyimpan data!'
            ]
        );
    }
}
