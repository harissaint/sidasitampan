<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Sipd;
use App\Models\SipdNonBelanja;
use App\Models\Skpd;
use App\Models\Tahapan;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Monolog\Level;

class HomeController extends Controller
{
    public $skpd = '';
    public $tahapan_id = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $data['tahap'] = $this->tahapan_id = $request->input('tahap') ?? Tahapan::latest()->first()->id;
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
                ->where('kode', 'like',  $data['code'] . '%')
                ->get()->pluck('kode')->toArray();
        }

        // merge
        $data = array_merge($data, $this->anggaran_rekening($skpd, $data['level'], $data['code']));

        return view('dashboard/rekening', $data);
    }

    public function anggaran_rekening($skpd, $level, $code)
    {
        $anggarans = [];
        // sum each code
        if ($level == 6) {
            if ($skpd == '') {
                if (Akun::getKodeBelanja() == $code) {
                    $sipds = Sipd::where('kode_akun', 'like', "$code%")
                        ->where('tahapan_id', $this->tahapan_id)
                        ->groupBy(['kode_akun', 'nama_akun'])
                        ->selectRaw('kode_akun, nama_akun, sum(nilai_rincian) as total')
                        ->get();
                } else {
                    $sipds = SipdNonBelanja::where('kode_akun', 'like', "$code%")
                        ->where('tahapan_id', $this->tahapan_id)
                        ->groupBy(['kode_akun', 'nama_akun'])
                        ->selectRaw('kode_akun, nama_akun, sum(nilai_rincian) as total')
                        ->get();
                }
            } else {
                if (Akun::getKodeBelanja() == $code) {
                    $sipds = Sipd::where('kode_akun', 'like', "$code%")
                        ->where('kode_skpd', $skpd)
                        ->where('tahapan_id', $this->tahapan_id)
                        ->groupBy(['kode_akun', 'nama_akun'])
                        ->selectRaw('kode_akun, nama_akun, sum(nilai_rincian) as total')
                        ->get();
                } else {
                    $sipds = SipdNonBelanja::where('kode_akun', 'like', "$code%")
                        ->where('kode_skpd', $skpd)
                        ->where('tahapan_id', $this->tahapan_id)
                        ->groupBy(['kode_akun', 'nama_akun'])
                        ->selectRaw('kode_akun, nama_akun, sum(nilai_rincian) as total')
                        ->get();
                }
            }

            foreach ($sipds as $key => $sipd) {
                $anggarans[$key]['code'] = $sipd->kode_akun;
                $anggarans[$key]['name'] = $sipd->nama_akun;
                $anggarans[$key]['level'] = $level;
                $anggarans[$key]['toLevel'] = $level + 1;
                $anggarans[$key]['total'] = $sipd->total;
            }
        } else {
            foreach ($code as $key => $kode) {
                $anggarans[$key]['code'] = $kode;
                $anggarans[$key]['name'] = Akun::where('kode', $kode)->first()->nama;
                $anggarans[$key]['level'] = $level;
                $anggarans[$key]['toLevel'] = $level + 1;
                if ($skpd == '') {
                    if (Akun::getKodeBelanja() == $kode[0]) {
                        $anggarans[$key]['total'] = Sipd::where('kode_akun', 'like', "$kode%")
                            ->where('tahapan_id', $this->tahapan_id)
                            ->selectRaw('sum(nilai_rincian) as total')
                            ->first()->total;
                    } else {
                        $anggarans[$key]['total'] = SipdNonBelanja::where('kode_akun', 'like', "$kode%")
                            ->where('tahapan_id', $this->tahapan_id)
                            ->selectRaw('sum(nilai_rincian) as total')
                            ->first()->total;
                    }
                } else {
                    if (Akun::getKodeBelanja() == $kode[0]) {
                        $anggarans[$key]['total'] = Sipd::where('kode_akun', 'like', "$kode%")
                            ->where('kode_skpd', $skpd)
                            ->where('tahapan_id', $this->tahapan_id)
                            ->selectRaw('sum(nilai_rincian) as total')
                            ->first()->total;
                    } else {
                        $anggarans[$key]['total'] = SipdNonBelanja::where('kode_akun', 'like', "$kode%")
                            ->where('kode_skpd', $skpd)
                            ->where('tahapan_id', $this->tahapan_id)
                            ->selectRaw('sum(nilai_rincian) as total')
                            ->first()->total;
                    }
                }
            }
        }

        $anggarans = collect($anggarans);
        $data['anggaran'] = $anggarans;
        $data['total'] = $anggarans->sum('total');

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

    public function urusan(Request $request)
    {
        $data['tahap'] = $this->tahapan_id = $request->input('tahap') ?? Tahapan::latest()->first()->id;
        $data['level'] = $request->input('level') ??  1;
        $data['code'] = $request->input('code');
        if ($request->user()->can('isSkpd', User::class)) {
            $this->skpd = Auth::user()->skpd->kode;
            $data['tahaps'] = Tahapan::orderBy('created_at', 'desc')
                ->where('is_active', true)
                ->get();
        } else {
            $this->skpd = $request->input('skpd') ?? '';
            $data['tahaps'] = Tahapan::orderBy('created_at', 'desc')->get();
        }

        $data['skpds'] = Skpd::all();

        $urusans = [];
        $breadcrumb = [];

        switch ($data['level']) {
            case 1:
                $this->fetchUrusanData($urusans, $data);
                break;
            case 2:
                $breadcrumb = $this->generateBreadcrumb($data['code'], 'nama_urusan', 1);
                $this->fetchProgramData($urusans, $data);
                break;
            case 3:
                $breadcrumb = $this->generateBreadcrumb($data['code'], 'nama_program', 2);
                $this->fetchKegiatanData($urusans, $data);
                break;
            case 4:
                $breadcrumb = $this->generateBreadcrumb($data['code'], 'nama_kegiatan', 3);
                $this->fetchSubKegiatanData($urusans, $data);
                break;
            default:
                return redirect()->back();
        }

        $data['breadcrumb'] = $breadcrumb;
        $data['urusan'] = $urusans = collect($urusans);
        $data['total'] = $urusans->sum('total');
        $data['urutan'] = [
            'Urusan',
            'Program',
            'Kegiatan',
            'Sub Kegiatan',
        ];

        return view('dashboard/urusan', $data);
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
                ->where('tahapan_id', $this->tahapan_id)
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

    private function fetchData($code, $codeField, $nameField, $level, &$urusans, $data)
    {
        if ($this->skpd == '') {
            $data['code'] = Sipd::distinct()
                ->where($codeField, 'like', $code . '%')
                ->where('tahapan_id', $this->tahapan_id)
                ->get([$codeField])
                ->pluck($codeField)
                ->toArray();
        } else {
            $data['code'] = Sipd::distinct()
                ->where($codeField, 'like', $code . '%')
                ->where('kode_skpd', $this->skpd)
                ->where('tahapan_id', $this->tahapan_id)
                ->get([$codeField])
                ->pluck($codeField)
                ->toArray();
        }

        foreach ($data['code'] as $key => $value) {
            $urusans[$key]['code'] = $value;
            $urusans[$key]['name'] = Sipd::where($codeField, $value)
                ->where('tahapan_id', $this->tahapan_id)
                ->first()->$nameField;
            $urusans[$key]['level'] = $level;
            $urusans[$key]['toLevel'] = $level + 1;
            if ($this->skpd == '') {
                $urusans[$key]['total'] = Sipd::where($codeField, $value)
                    ->where('tahapan_id', $this->tahapan_id)
                    ->selectRaw('sum(nilai_rincian) as total')
                    ->first()->total;
            } else {
                $urusans[$key]['total'] = Sipd::where($codeField, $value)
                    ->where('tahapan_id', $this->tahapan_id)
                    ->where('kode_skpd', $this->skpd)
                    ->selectRaw('sum(nilai_rincian) as total')
                    ->first()->total;
            }
        }
    }

    private function fetchUrusanData(&$urusans, $data)
    {
        $this->fetchData('', 'kode_urusan', 'nama_urusan', $data['level'], $urusans, $data);
    }

    private function fetchProgramData(&$urusans, $data)
    {
        $code = substr($data['code'], 0, 4);
        $this->fetchData($code, 'kode_program', 'nama_program', $data['level'], $urusans, $data);
    }

    private function fetchKegiatanData(&$urusans, $data)
    {
        $this->fetchData($data['code'], 'kode_kegiatan', 'nama_kegiatan', $data['level'], $urusans, $data);
    }

    private function fetchSubKegiatanData(&$urusans, $data)
    {
        $this->fetchData($data['code'], 'kode_sub_kegiatan', 'nama_sub_kegiatan', $data['level'], $urusans, $data);
    }
}
