<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tahapan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'nama',
        'tahun',
        'keterangan',
        'is_active'
    ];

    public function sipds()
    {
        return $this->hasMany(Sipd::class);
    }

    public function sipd_temps()
    {
        return $this->hasMany(SipdTemp::class);
    }

    public function sipd_non_belanja()
    {
        return $this->hasMany(SipdNonBelanja::class);
    }

    public function sipd_pendapatan()
    {
        $kode = Akun::where('nama', 'like', '%Pendapatan%')->where('level', 1)->first()?->kode;
        return $this->sipd_non_belanja()->where('kode_akun', 'like', $kode . '%');
    }

    public function sipd_pembiayaan()
    {
        $kode = Akun::where('nama', 'like', '%Pembiayaan%')->where('level', 1)->first()?->kode;        
        return $this->sipd_non_belanja()->where('kode_akun', 'like', $kode . '%');
    }

    public function getTotalTahapan()
    {
        return $this->sipds()->sum('nilai_rincian');
    }

    public function getTotalPendapatanTahapan()
    {
        return $this->sipd_pendapatan()->sum('nilai_rincian');
    }

    public function getTotalPembiayaanTahapan()
    {
        return $this->sipd_pembiayaan()->sum('nilai_rincian');
    }

    public static function getTotalPerTahapan()
    {
        $data = Tahapan::select('id', 'nama', 'tahun', 'keterangan', 'is_active')->orderBy('created_at', 'desc')->get();
        foreach ($data as $key => $value) {
            $data[$key]['total_belanja'] = $value->getTotalTahapan();
            $data[$key]['total_pendapatan'] = $value->getTotalPendapatanTahapan();
            $data[$key]['total_pembiayaan'] = $value->getTotalPembiayaanTahapan();
        }
        return $data;
    }
    
}
