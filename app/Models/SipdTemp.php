<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SipdTemp extends Model
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
}
