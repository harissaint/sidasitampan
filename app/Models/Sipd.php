<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sipd extends Model
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
        // 'jenis',
        'sub_unit',
        'tahapan_id',
    ];

    public function tahapan()
    {
        return $this->belongsTo(Tahapan::class);
    }

    public static function getDataByTahapan($tahapan_id_1, $tahapan_id_2)
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
                kode_akun", [$tahapan_id_1, $tahapan_id_2]);
        return $data;
    }

    public static function getDataByTahapanSkpd($tahapan_id_1, $tahapan_id_2, $skpd)
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
                kode_akun", [$tahapan_id_1, $skpd, $tahapan_id_2, $skpd]);
        return $data;
    }

    public static function getDataByRekening($tahap_1, $tahap_2, $code)
    {
        $sipds =  DB::select("select kode_akun, nama_akun,
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
                        from sipds a
                        where tahapan_id = ? AND kode_akun like ?
                        group by
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
                        where tahapan_id = ? AND kode_akun like ?
                        group by
                            a.kode_akun,
                            a.nama_akun
                    ) x
                group by
                    kode_akun,
                    nama_akun
                order by kode_urusan,
                    kode_skpd,
                    kode_bidang_urusan,
                    kode_sub_skpd,
                    kode_program,
                    kode_kegiatan,
                    kode_sub_kegiatan,
                    kode_akun", [$tahap_1, $code.'%', $tahap_2, $code.'%']);
        return $sipds;
    }

    public static function getDataByRekeningSkpd($tahap_1, $tahap_2, $code, $skpd)
    {
        $sipds =  DB::select("select kode_akun, nama_akun,
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
                        from sipds a
                        where tahapan_id = ? AND kode_akun like ? AND kode_skpd = ?
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
                        where tahapan_id = ? AND kode_akun like ? AND kode_skpd = ?
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
                    kode_akun", [$tahap_1, $code.'%', $skpd, $tahap_2, $code.'%', $skpd]);
        return $sipds;
    }

    public static function getPerbandinganDataBy($namaField, $code, $tahap_1, $tahap_2, $skpd)
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
                        from sipds a
                        where tahapan_id = ? AND kode_".$namaField." like ? AND kode_skpd ".$whereSkpd."
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
                    kode_akun", [$tahap_1, $code.'%', $tahap_2, $code.'%']);
        return $sipds;
    }
}
