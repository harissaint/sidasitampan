<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Realisasi;
use App\Models\Sipd;
use App\Models\Skpd;
use App\Models\Tahapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class RealisasiController extends Controller
{
    //
    public function perbandinganByRekening(Request $request)
    {
        // $data['tahap'] = $this->tahapan_id = $request->input('tahap') ?? Tahapan::latest()->first()->id;        
        $tahun = $request->input('tahun') ?? date('Y');
        $tahapan_id = $request->input('tahapan_id') ?? Tahapan::latest()->first()->id;
        $data['level'] = $request->input('level') ?? 1;
        $data['code'] = $request->input('code');
        if ($request->user()->can('isSkpd', User::class)) {
            $skpd = Auth::user()->skpd->kode;
            $data['tahaps'] = Tahapan::orderBy('created_at', 'desc')
                ->where('is_active', true)
                ->get();
        } else {
            $skpd = $request->input('skpd') ?? '';
            $data['tahaps'] = Tahapan::orderBy('created_at', 'desc')->get();
        }

        $data['skpds'] = Skpd::all();

        if ($data['level'] >= 7)
            return redirect()->back();

        if ($data['level'] == 1) {
            $data['code'] = Akun::where('level', $data['level'])->get()->pluck('kode')->toArray();
        } else if ($data['level'] != 6) {
            $data['code'] = Akun::where('level', $data['level'])
                ->where('kode', 'like', '%' . $data['code'] . '%')
                ->get()->pluck('kode')->toArray();
        }

        // merge
        $data = array_merge($data, $this->perbandingan_anggaran_rekening($tahun, $tahapan_id, $skpd, $data['level'], $data['code']));

        return view('dashboard/realisasi/rekening', $data);
    }

    public function perbandingan_anggaran_rekening($tahun, $tahapan_id, $skpd, $level, $code)
    {
        $anggarans = [];
        // sum each code
        if ($level == 6) {
            $sipds =  Realisasi::getPerbandinganDataBy('akun', $code, $tahun, $tahapan_id, $skpd);

            foreach ($sipds as $key => $sipd) {
                $anggarans[$key]['code'] = $sipd->code;
                $anggarans[$key]['name'] = $sipd->name;
                $anggarans[$key]['level'] = $level;
                $anggarans[$key]['toLevel'] = $level + 1;
                $anggarans[$key]['total_1'] = $sipd->anggaran1;
                $anggarans[$key]['total_2'] = $sipd->anggaran2;
            }
        } else {
            foreach ($code as $key => $kode) {
                $anggarans[$key]['code'] = $kode;
                $anggarans[$key]['name'] = Akun::where('kode', $kode)->first()->nama;
                $anggarans[$key]['level'] = $level;
                $anggarans[$key]['toLevel'] = $level + 1;
                if ($skpd == '') {
                    $anggarans[$key]['total_1'] = Realisasi::where('kode_akun', 'like', "$kode%")
                        ->where('tahun', $tahun)
                        ->selectRaw('sum(nilai_rincian) as total')
                        ->first()->total;
                    $anggarans[$key]['total_2'] = Sipd::where('kode_akun', 'like', "$kode%")
                        ->where('tahapan_id', $tahapan_id)
                        ->selectRaw('sum(nilai_rincian) as total')
                        ->first()->total;
                } else {
                    $anggarans[$key]['total_1'] = Realisasi::where('kode_akun', 'like', "$kode%")
                        ->where('kode_skpd', $skpd)
                        ->where('tahun', $tahun)
                        ->selectRaw('sum(nilai_rincian) as total')
                        ->first()->total;
                    $anggarans[$key]['total_2'] = Sipd::where('kode_akun', 'like', "$kode%")
                        ->where('kode_skpd', $skpd)
                        ->where('tahapan_id', $tahapan_id)
                        ->selectRaw('sum(nilai_rincian) as total')
                        ->first()->total;
                }
            }
        }

        $anggarans = collect($anggarans);
        $data['anggaran'] = $anggarans;
        $data['total_1'] = $anggarans->sum('total_1');
        $data['total_2'] = $anggarans->sum('total_2');

        if ($code && $level > 1) {
            $codeParts = $level == 6 ? explode('.', $code) : explode('.', $code[0]);
            $breadcrumb = [];

            $currentCode = '';
            foreach ($codeParts as $part) {
                $currentCode = ($currentCode === '') ? $part : "$currentCode.$part";
                $breadcrumbItem = Akun::where('kode', $currentCode)->first();

                // and not the last item
                if ($breadcrumbItem && $breadcrumbItem->level != $level) {
                    $breadcrumb[] = $breadcrumbItem;
                }
            }
            $data['breadcumb'] = $breadcrumb;
        }

        return $data;
    }

    public function perbandinganUrusan(Request $request)
    {
        $tahun = $request->input('tahun') ?? date('Y');
        $tahapan_id = $request->input('tahapan_id') ?? Tahapan::latest()->first()->id;
        $data['level'] = $request->input('level') ??  1;
        $data['code'] = $request->input('code');
        if ($request->user()->can('isSkpd', User::class)) {
            $skpd = Auth::user()->skpd->kode;
            $data['tahaps'] = Tahapan::orderBy('created_at', 'desc')
                ->where('is_active', true)
                ->get();
        } else {
            $skpd = $request->input('skpd') ?? '';
            $data['tahaps'] = Tahapan::orderBy('created_at', 'desc')->get();
        }

        $data['skpds'] = Skpd::all();

        $urusans = [];
        $breadcrumb = [];

        switch ($data['level']) {
            case 1:
                $urusans = Realisasi::getPerbandinganDataBy('urusan', '', $tahun, $tahapan_id, $skpd);
                break;
            case 2:
                $breadcrumb = $this->generateBreadcrumb($data['code'], 'nama_urusan', 1);
                $urusans = Realisasi::getPerbandinganDataBy('program', $data['code'], $tahun, $tahapan_id, $skpd);
                break;
            case 3:
                $breadcrumb = $this->generateBreadcrumb($data['code'], 'nama_program', 2);
                $urusans = Realisasi::getPerbandinganDataBy('kegiatan', $data['code'], $tahun, $tahapan_id, $skpd);
                break;
            case 4:
                $breadcrumb = $this->generateBreadcrumb($data['code'], 'nama_kegiatan', 3);
                $urusans = Realisasi::getPerbandinganDataBy('sub_kegiatan', $data['code'], $tahun, $tahapan_id, $skpd);
                break;
            default:
                return redirect()->back();
        }

        $data['breadcrumb'] = $breadcrumb;
        $data['anggaran'] = $urusans = collect($urusans);
        // dd($data['anggaran']);
        $data['total'] = $urusans->sum('total');
        $data['urutan'] = [
            'Urusan',
            'Program',
            'Kegiatan',
            'Sub Kegiatan',
        ];

        return view('dashboard/realisasi/urusan', $data);
    }

    private function generateBreadcrumb($code, $nameField, $levelOffset)
    {
        $list_order_code = [
            'kode_urusan',
            'kode_program',
            'kode_kegiatan',
            'kode_sub_kegiatan',
        ];

        $list_order_name = [
            'nama_urusan',
            'nama_program',
            'nama_kegiatan',
            'nama_sub_kegiatan',
        ];

        // Check if the levelOffset is within a valid range
        if ($levelOffset < 1 || $levelOffset > count($list_order_code)) {
            throw new InvalidArgumentException('Invalid levelOffset value');
        }

        $breadcrumb = [];
        $parts = explode('.', $code);

        for ($i = 0; $i < $levelOffset; $i++) {
            if ($i != 1) {
                $codeTarget =  implode('.', array_slice($parts, 0, $i + 1));
            } else {
                $codeTarget = substr($code, 0, 3);
            }
            // if($i == 3){
            //     dd($codeTarget);
            // }
            $breadcrumbItem = Sipd::where($list_order_code[$i], 'like', $codeTarget . '%')
                ->first();
            // $breadcrumbItem = Sipd::where($list_order_code[$i], $codeTarget)->first();
            // dd($breadcrumbItem);
            $codeField = $list_order_code[$i];
            $nameField = $list_order_name[$i];
            if ($breadcrumbItem) {
                $breadcrumb[] = [
                    'code' => $breadcrumbItem->$codeField,
                    'name' => $breadcrumbItem->$nameField,
                    'level' => $i + 1,
                ];
            }
        }
        // dd($breadcrumb);
        return $breadcrumb;
    }
}
