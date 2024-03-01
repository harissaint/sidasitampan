<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Realisasi extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
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
        'nilai_rincian',
        'tahun',
    ];

    public static function getDataByTahapan($tahun, $tahapan_id)
    {
        $data = DB::select("select concat(kode_urusan, ' ', nama_urusan) urusan,
                concat(kode_skpd, ' ', nama_skpd) skpd,
                concat(kode_bidang_urusan, ' ', nama_bidang_urusan) bidang_urusan,
                concat(kode_sub_skpd, ' ', nama_sub_skpd) sub_skpd,
                concat(kode_program, ' ', nama_program) program,
                concat(kode_kegiatan, ' ', nama_kegiatan) kegiatan,
                concat(kode_sub_kegiatan, ' ', nama_sub_kegiatan) sub_kegiatan,
                concat(kode_akun, ' ', nama_akun) akun,
                sum(anggaran1) anggaran1,
                sum(anggaran2) anggaran2,
                sum(anggaran2) - sum(anggaran1) as selisih
            from (
                    select a.kode_urusan,
                        a.nama_urusan,
                        a.kode_skpd,
                        a.nama_skpd,
                        a.kode_bidang_urusan,
                        a.nama_bidang_urusan,
                        a.kode_sub_skpd,
                        a.nama_sub_skpd,
                        a.kode_program,
                        a.nama_program,
                        a.kode_kegiatan,
                        a.nama_kegiatan,
                        a.kode_sub_kegiatan,
                        a.nama_sub_kegiatan,
                        a.kode_akun,
                        a.nama_akun,
                        sum(nilai_rincian) anggaran1,
                        0 as anggaran2
                    from realisasis a
                    where tahun = ?
                    group by a.kode_urusan,
                        a.nama_urusan,
                        a.kode_skpd,
                        a.nama_skpd,
                        a.kode_bidang_urusan,
                        a.nama_bidang_urusan,
                        a.kode_sub_skpd,
                        a.nama_sub_skpd,
                        a.kode_program,
                        a.nama_program,
                        a.kode_kegiatan,
                        a.nama_kegiatan,
                        a.kode_sub_kegiatan,
                        a.nama_sub_kegiatan,
                        a.kode_akun,
                        a.nama_akun
                    union all
                    select a.kode_urusan,
                        a.nama_urusan,
                        a.kode_skpd,
                        a.nama_skpd,
                        a.kode_bidang_urusan,
                        a.nama_bidang_urusan,
                        a.kode_sub_skpd,
                        a.nama_sub_skpd,
                        a.kode_program,
                        a.nama_program,
                        a.kode_kegiatan,
                        a.nama_kegiatan,
                        a.kode_sub_kegiatan,
                        a.nama_sub_kegiatan,
                        a.kode_akun,
                        a.nama_akun,
                        0,
                        sum(nilai_rincian)
                    from sipds a
                    where tahapan_id = ?
                    group by a.kode_urusan,
                        a.nama_urusan,
                        a.kode_skpd,
                        a.nama_skpd,
                        a.kode_bidang_urusan,
                        a.nama_bidang_urusan,
                        a.kode_sub_skpd,
                        a.nama_sub_skpd,
                        a.kode_program,
                        a.nama_program,
                        a.kode_kegiatan,
                        a.nama_kegiatan,
                        a.kode_sub_kegiatan,
                        a.nama_sub_kegiatan,
                        a.kode_akun,
                        a.nama_akun
                ) x
            group by kode_urusan,
                nama_urusan,
                kode_skpd,
                nama_skpd,
                kode_bidang_urusan,
                nama_bidang_urusan,
                kode_sub_skpd,
                nama_sub_skpd,
                kode_program,
                nama_program,
                kode_kegiatan,
                nama_kegiatan,
                kode_sub_kegiatan,
                nama_sub_kegiatan,
                kode_akun,
                nama_akun
            order by kode_urusan,
                kode_skpd,
                kode_bidang_urusan,
                kode_sub_skpd,
                kode_program,
                kode_kegiatan,
                kode_sub_kegiatan,
                kode_akun", [$tahun, $tahapan_id]);
        return $data;
    }

    public static function getDataByTahapanSkpd($tahun, $tahapan_id, $skpd)
    {
        $data = DB::select("select concat(kode_urusan, ' ', nama_urusan) urusan,
                concat(kode_skpd, ' ', nama_skpd) skpd,
                concat(kode_bidang_urusan, ' ', nama_bidang_urusan) bidang_urusan,
                concat(kode_sub_skpd, ' ', nama_sub_skpd) sub_skpd,
                concat(kode_program, ' ', nama_program) program,
                concat(kode_kegiatan, ' ', nama_kegiatan) kegiatan,
                concat(kode_sub_kegiatan, ' ', nama_sub_kegiatan) sub_kegiatan,
                concat(kode_akun, ' ', nama_akun) akun,
                sum(anggaran1) anggaran1,
                sum(anggaran2) anggaran2,
                sum(anggaran2) - sum(anggaran1) as selisih
            from (
                    select a.kode_urusan,
                        a.nama_urusan,
                        a.kode_skpd,
                        a.nama_skpd,
                        a.kode_bidang_urusan,
                        a.nama_bidang_urusan,
                        a.kode_sub_skpd,
                        a.nama_sub_skpd,
                        a.kode_program,
                        a.nama_program,
                        a.kode_kegiatan,
                        a.nama_kegiatan,
                        a.kode_sub_kegiatan,
                        a.nama_sub_kegiatan,
                        a.kode_akun,
                        a.nama_akun,
                        sum(nilai_rincian) anggaran1,
                        0 as anggaran2
                    from realisasis a
                    where tahun = ? AND kode_skpd = ?
                    group by a.kode_urusan,
                        a.nama_urusan,
                        a.kode_skpd,
                        a.nama_skpd,
                        a.kode_bidang_urusan,
                        a.nama_bidang_urusan,
                        a.kode_sub_skpd,
                        a.nama_sub_skpd,
                        a.kode_program,
                        a.nama_program,
                        a.kode_kegiatan,
                        a.nama_kegiatan,
                        a.kode_sub_kegiatan,
                        a.nama_sub_kegiatan,
                        a.kode_akun,
                        a.nama_akun
                    union all
                    select a.kode_urusan,
                        a.nama_urusan,
                        a.kode_skpd,
                        a.nama_skpd,
                        a.kode_bidang_urusan,
                        a.nama_bidang_urusan,
                        a.kode_sub_skpd,
                        a.nama_sub_skpd,
                        a.kode_program,
                        a.nama_program,
                        a.kode_kegiatan,
                        a.nama_kegiatan,
                        a.kode_sub_kegiatan,
                        a.nama_sub_kegiatan,
                        a.kode_akun,
                        a.nama_akun,
                        0,
                        sum(nilai_rincian)
                    from sipds a
                    where tahapan_id = ? AND kode_skpd = ?
                    group by a.kode_urusan,
                        a.nama_urusan,
                        a.kode_skpd,
                        a.nama_skpd,
                        a.kode_bidang_urusan,
                        a.nama_bidang_urusan,
                        a.kode_sub_skpd,
                        a.nama_sub_skpd,
                        a.kode_program,
                        a.nama_program,
                        a.kode_kegiatan,
                        a.nama_kegiatan,
                        a.kode_sub_kegiatan,
                        a.nama_sub_kegiatan,
                        a.kode_akun,
                        a.nama_akun
                ) x
            group by kode_urusan,
                nama_urusan,
                kode_skpd,
                nama_skpd,
                kode_bidang_urusan,
                nama_bidang_urusan,
                kode_sub_skpd,
                nama_sub_skpd,
                kode_program,
                nama_program,
                kode_kegiatan,
                nama_kegiatan,
                kode_sub_kegiatan,
                nama_sub_kegiatan,
                kode_akun,
                nama_akun
            order by kode_urusan,
                kode_skpd,
                kode_bidang_urusan,
                kode_sub_skpd,
                kode_program,
                kode_kegiatan,
                kode_sub_kegiatan,
                kode_akun", [$tahun, $skpd, $tahapan_id, $skpd]);
        return $data;
    }

    public static function getPerbandinganDataBy($namaField, $code, $tahun, $tahapan_id, $skpd)
    {
        if($skpd == ''){
            $whereSkpd = 'is not null';
        }else{
            $whereSkpd = "= '".$skpd."'";
        }
        $sipds =  DB::select("select kode_".$namaField." as code, nama_".$namaField." as name,
                sum(anggaran1) anggaran1,
                sum(anggaran2) anggaran2
                from (
                        select a.kode_urusan,
                            a.nama_urusan,
                            a.kode_skpd,
                            a.nama_skpd,
                            a.kode_bidang_urusan,
                            a.nama_bidang_urusan,
                            a.kode_sub_skpd,
                            a.nama_sub_skpd,
                            a.kode_program,
                            a.nama_program,
                            a.kode_kegiatan,
                            a.nama_kegiatan,
                            a.kode_sub_kegiatan,
                            a.nama_sub_kegiatan,
                            a.kode_akun,
                            a.nama_akun,
                            sum(nilai_rincian) anggaran1,
                            0 as anggaran2
                        from realisasis a
                        where tahun = ? AND kode_".$namaField." like ? AND kode_skpd ".$whereSkpd."
                        group by a.kode_".$namaField.",
                            a.nama_".$namaField."
                        union all
                        select a.kode_urusan,
                            a.nama_urusan,
                            a.kode_skpd,
                            a.nama_skpd,
                            a.kode_bidang_urusan,
                            a.nama_bidang_urusan,
                            a.kode_sub_skpd,
                            a.nama_sub_skpd,
                            a.kode_program,
                            a.nama_program,
                            a.kode_kegiatan,
                            a.nama_kegiatan,
                            a.kode_sub_kegiatan,
                            a.nama_sub_kegiatan,
                            a.kode_akun,
                            a.nama_akun,
                            0,
                            sum(nilai_rincian)
                        from sipds a
                        where tahapan_id = ? AND kode_".$namaField." like ? AND kode_skpd ".$whereSkpd."
                        group by a.kode_".$namaField.",
                            a.nama_".$namaField."
                    ) x
                group by kode_".$namaField.",
                    nama_".$namaField."
                order by kode_urusan,
                    kode_skpd,
                    kode_bidang_urusan,
                    kode_sub_skpd,
                    kode_program,
                    kode_kegiatan,
                    kode_sub_kegiatan,
                    kode_akun", [$tahun, $code.'%', $tahapan_id, $code.'%']);
        return $sipds;
    }
}
