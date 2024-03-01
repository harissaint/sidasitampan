<?php

namespace App\Imports;

use App\Models\SipdTemp;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SipdTempImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $request = request()->all();
        
        return new SipdTemp([
            //
            'kode_urusan' => $row['kode_urusan'],
            'nama_urusan' => $row['nama_urusan'],
            'kode_bidang_urusan' => $row['kode_bidang_urusan'],
            'nama_bidang_urusan' => $row['nama_bidang_urusan'],
            'kode_skpd' => $row['kode_skpd'],
            'nama_skpd' => $row['nama_skpd'],
            'kode_sub_skpd' => $row['kode_sub_unit'],
            'nama_sub_skpd' => $row['nama_sub_unit'],
            'kode_program' => $row['kode_program'],
            'nama_program' => $row['nama_program'],
            'kode_kegiatan' => $row['kode_kegiatan'],
            'nama_kegiatan' => $row['nama_kegiatan'],
            'kode_sub_kegiatan' => $row['kode_sub_kegiatan'],
            'nama_sub_kegiatan' => $row['nama_sub_kegiatan'],
            'kode_akun' => $row['kode_rekening'],
            'nama_akun' => $row['nama_rekening'],
            'nilai_rincian' => $row['pagu'],
            'sub_unit' => $row['kode_sub_unit'] . ' ' . $row['nama_sub_unit'],
            'tahapan_id' => $request['tahapan_id'],
        ]);
    }
}
