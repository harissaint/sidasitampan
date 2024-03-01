<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prioritas extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        "nama",
        "tahun",
        "keterangan",
        "type"
    ];

    public function maps()
    {
        return $this->hasMany(MapPrioritas::class);
    }

    public function sk_maps()
    {
        return $this->hasMany(MapSkPrioritas::class);
    }
}
