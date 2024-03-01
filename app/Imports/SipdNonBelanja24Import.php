<?php

namespace App\Imports;

use App\Models\SipdNonBelanja;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SipdNonBelanja24Import implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $request = request()->all();

        $data['kode_skpd'] = $row['kode_opd'];
        $data['nama_skpd'] = $row['nama_opd'];

        $data['kode_akun'] = $row['kode_akun'];
        $data['nama_akun'] = $row['nama_akun'];
        $data['nilai_rincian'] = $row['pagu'];

        return new SipdNonBelanja([
            //
            'kode_skpd' => $data['kode_skpd'],
            'nama_skpd' => $data['nama_skpd'],
            'kode_akun' => $data['kode_akun'],
            'nama_akun' => $data['nama_akun'],
            'nilai_rincian' => $data['nilai_rincian'],
            'tahapan_id' => $request['tahapan_id'],
        ]);
    }
}
