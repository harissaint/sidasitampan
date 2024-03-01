<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'kode',
        'nama',
        'tahun',
        'level',
    ];

    public static function getKodePendapatan()
    {
        return Akun::where('nama', 'like', '%Pendapatan%')->where('level', 1)->first()?->kode;
    }

    public static function getKodePembiayaan()
    {
        return Akun::where('nama', 'like', '%Pembiayaan%')->where('level', 1)->first()?->kode;
    }

    public static function getKodeBelanja()
    {
        return Akun::where('nama', 'like', '%Belanja%')->where('level', 1)->first()?->kode;
    }
}
