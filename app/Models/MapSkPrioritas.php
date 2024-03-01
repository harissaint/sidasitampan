<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapSkPrioritas extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        "prioritas_id",
        "kode_skpd",
        "kode_sub_kegiatan",
    ];

    public function prioritas()
    {
        return $this->belongsTo(Prioritas::class);
    }
}
