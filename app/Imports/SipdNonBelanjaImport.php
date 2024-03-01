<?php

namespace App\Imports;

use App\Models\SipdNonBelanja;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SipdNonBelanjaImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $request = request()->all();

        $data['kode_skpd'] = substr($row[0], 0, 22);
        $data['kode_skpd'] = str_replace("\xFFFD", "", $data['kode_skpd']);
        $data['nama_skpd'] = trim(substr($row[0], 23, strlen($row[0])));

        $data['kode_akun'] = substr($row[1], 0, 17);
        $data['kode_akun'] = str_replace("\xFFFD", "", $data['kode_akun']);
        $data['nama_akun'] = trim(substr($row[1], 18, strlen($row[1])));

        $data['nilai_rincian'] = $row[2];  

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

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}
