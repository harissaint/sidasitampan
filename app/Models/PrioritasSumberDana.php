<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrioritasSumberDana extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        "nama",
        "tahapan_id",
        "type",
    ];

    public function maps()
    {
        return $this->hasMany(MapPrioritasSumberDana::class);
    }

    public function sk_maps()
    {
        return $this->hasMany(MapPrioritasSumberDanaSk::class);
    }

    public function tahapan()
    {
        return $this->belongsTo(Tahapan::class);
    }
}
