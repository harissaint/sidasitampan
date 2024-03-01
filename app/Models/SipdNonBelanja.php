<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SipdNonBelanja extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'kode_skpd',
        'nama_skpd',
        'kode_akun',
        'nama_akun',
        'nilai_rincian',
        'tahapan_id',
    ];

    public static function getDataByTahapan($tahapan_id_1, $tahapan_id_2)
    {
        $data = DB::select("concat(kode_skpd, ' ', nama_skpd) skpd,                
                concat(kode_akun, ' ', nama_akun) akun,
                sum(anggaran1) anggaran1,
                sum(anggaran2) anggaran2,
                sum(anggaran2) - sum(anggaran1) as selisih
            from (
                    select 
                        a.kode_skpd,
                        a.nama_skpd,
                        a.kode_akun,
                        a.nama_akun,
                        sum(nilai_rincian) anggaran1,
                        0 as anggaran2
                    from sipd_non_belanjas a
                    where tahapan_id = ?
                    group by
                        a.kode_skpd,
                        a.nama_skpd,
                        a.kode_akun,
                        a.nama_akun
                    union all
                    select 
                        a.kode_skpd,
                        a.nama_skpd,
                        a.kode_akun,
                        a.nama_akun,
                        0,
                        sum(nilai_rincian)
                    from sipd_non_belanjas a
                    where tahapan_id = ?
                    group by
                        a.kode_skpd,
                        a.nama_skpd,
                        a.kode_akun,
                        a.nama_akun
                ) x
            group by
                kode_skpd,
                nama_skpd,
                kode_akun,
                nama_akun
            order by
                kode_skpd,
                kode_akun", [$tahapan_id_1, $tahapan_id_2]);
        return $data;
    }

    public static function getDataByTahapanSkpd($tahapan_id_1, $tahapan_id_2, $skpd)
    {
        $data = DB::select("concat(kode_skpd, ' ', nama_skpd) skpd,                
                concat(kode_akun, ' ', nama_akun) akun,
                sum(anggaran1) anggaran1,
                sum(anggaran2) anggaran2,
                sum(anggaran2) - sum(anggaran1) as selisih
            from (
                    select
                        a.kode_skpd,
                        a.nama_skpd,
                        a.kode_akun,
                        a.nama_akun,
                        sum(nilai_rincian) anggaran1,
                        0 as anggaran2
                    from sipd_non_belanjas a
                    where tahapan_id = ? AND kode_skpd = ?
                    group by
                        a.kode_skpd,
                        a.nama_skpd,
                        a.kode_akun,
                        a.nama_akun
                    union all
                    select
                        a.kode_skpd,
                        a.nama_skpd,
                        a.kode_akun,
                        a.nama_akun,
                        0,
                        sum(nilai_rincian)
                    from sipd_non_belanjas a
                    where tahapan_id = ? AND kode_skpd = ?
                    group by
                        a.kode_skpd,
                        a.nama_skpd,
                        a.kode_akun,
                        a.nama_akun
                ) x
            group by
                kode_skpd,
                nama_skpd,
                kode_akun,
                nama_akun
            order by
                kode_skpd,
                kode_akun", [$tahapan_id_1, $skpd, $tahapan_id_2, $skpd]);
        return $data;
    }

    public static function getDataByRekening($tahap_1, $tahap_2, $code)
    {
        $sipd_non_belanjas =  DB::select("select kode_akun, nama_akun,
                sum(anggaran1) anggaran1,
                sum(anggaran2) anggaran2
                from (
                        select
                            a.kode_skpd,
                            a.nama_skpd,
                            a.kode_akun,
                            a.nama_akun,
                            sum(nilai_rincian) anggaran1,
                            0 as anggaran2
                        from sipd_non_belanjas a
                        where tahapan_id = ? AND kode_akun like ?
                        group by
                            a.kode_skpd,
                            a.nama_skpd,
                            a.kode_akun,
                            a.nama_akun
                        union all
                        select
                            a.kode_skpd,
                            a.nama_skpd,
                            a.kode_akun,
                            a.nama_akun,
                            0,
                            sum(nilai_rincian)
                        from sipd_non_belanjas a
                        where tahapan_id = ? AND kode_akun like ?
                        group by
                            a.kode_skpd,
                            a.nama_skpd,
                            a.kode_akun,
                            a.nama_akun
                    ) x
                group by
                    kode_skpd,
                    nama_skpd,
                    kode_akun,
                    nama_akun
                order by
                    kode_skpd,
                    kode_akun", [$tahap_1, $code.'%', $tahap_2, $code.'%']);
        return $sipd_non_belanjas;
    }

    public static function getDataByRekeningSkpd($tahap_1, $tahap_2, $code, $skpd)
    {
        $sipd_non_belanjas =  DB::select("select kode_akun, nama_akun,
                sum(anggaran1) anggaran1,
                sum(anggaran2) anggaran2
                from (
                        select
                            a.kode_skpd,
                            a.nama_skpd,
                            a.kode_akun,
                            a.nama_akun,
                            sum(nilai_rincian) anggaran1,
                            0 as anggaran2
                        from sipd_non_belanjas a
                        where tahapan_id = ? AND kode_akun like ? AND kode_skpd = ?
                        group by
                            a.kode_skpd,
                            a.nama_skpd,
                            a.kode_akun,
                            a.nama_akun
                        union all
                        select
                            a.kode_skpd,
                            a.nama_skpd,
                            a.kode_akun,
                            a.nama_akun,
                            0,
                            sum(nilai_rincian)
                        from sipd_non_belanjas a
                        where tahapan_id = ? AND kode_akun like ? AND kode_skpd = ?
                        group by
                            a.kode_skpd,
                            a.nama_skpd,
                            a.kode_akun,
                            a.nama_akun
                    ) x
                group by
                    kode_skpd,
                    nama_skpd,
                    kode_akun,
                    nama_akun
                order by
                    kode_skpd,
                    kode_akun", [$tahap_1, $code.'%', $skpd, $tahap_2, $code.'%', $skpd]);
        return $sipd_non_belanjas;
    }

    public static function getPerbandinganDataBy($namaField, $code, $tahap_1, $tahap_2, $skpd)
    {
        if($skpd == ''){
            $whereSkpd = 'is not null';
        }else{
            $whereSkpd = "= '".$skpd."'";
        }
        $sipd_non_belanjas =  DB::select("select kode_".$namaField." as code, nama_".$namaField." as name,
                sum(anggaran1) anggaran1,
                sum(anggaran2) anggaran2
                from (
                        select
                            a.kode_skpd,
                            a.nama_skpd,
                            a.kode_akun,
                            a.nama_akun,
                            sum(nilai_rincian) anggaran1,
                            0 as anggaran2
                        from sipd_non_belanjas a
                        where tahapan_id = ? AND kode_".$namaField." like ? AND kode_skpd ".$whereSkpd."
                        group by a.kode_".$namaField.",
                            a.nama_".$namaField."
                        union all
                        select
                            a.kode_skpd,
                            a.nama_skpd,
                            a.kode_akun,
                            a.nama_akun,
                            0,
                            sum(nilai_rincian)
                        from sipd_non_belanjas a
                        where tahapan_id = ? AND kode_".$namaField." like ? AND kode_skpd ".$whereSkpd."
                        group by a.kode_".$namaField.",
                            a.nama_".$namaField."
                    ) x
                group by kode_".$namaField.",
                    nama_".$namaField."
                order by
                    kode_skpd,
                    kode_akun", [$tahap_1, $code.'%', $tahap_2, $code.'%']);
        return $sipd_non_belanjas;
    }

    public function tahapan()
    {
        return $this->belongsTo(Tahapan::class);
    }
}
