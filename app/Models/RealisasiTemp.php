<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealisasiTemp extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
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
        // 'nomor_spd',
        // 'periode_spd',
        // 'nilai_detail_spd',
        // 'sisa_detail_spd',
        // 'tahap_spd',
        // 'sub_tahap_spd',
        // 'status_tahan_spd',
        'nilai_rincian',
        'tahun',
    ];
}
