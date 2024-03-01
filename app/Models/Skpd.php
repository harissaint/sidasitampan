<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skpd extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'kode',
        'nama',
        'nama_kepala',
        'nip_kepala',
    ];
}
