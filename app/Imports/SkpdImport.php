<?php

namespace App\Imports;

use App\Models\Skpd;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SkpdImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Skpd([
            //
            'kode' => $row['kode'],
            'nama' => $row['nama'],
            'nama_kepala' => $row['nama_kepala']??null,
            'nip_kepala' => $row['nip_kepala']??null,
        ]);
    }
}
