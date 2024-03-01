<?php

namespace App\Imports;

use App\Models\Akun;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AkunImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Akun([
            //
            'kode' => $row['kode_akun'],
            'nama' => $row['nama_akun'],
            'tahun' => date('Y'),
            'level' => $row['level'],
        ]);
    }
}
