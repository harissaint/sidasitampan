<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapPrioritas extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        "prioritas_id",
        "kode_skpd",
        "kode_sub_kegiatan",
        "kode_rekening",
    ];

    public function prioritas()
    {
        return $this->belongsTo(Prioritas::class);
    }
}
