<?php

namespace App\Imports;

use App\Models\Sipd;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Http\Request;

class SipdImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $request = request()->all();
        $data['kode_urusan'] = substr($row['urusan'], 0, 1);
        $data['kode_urusan'] = str_replace("\xFFFD", "", $data['kode_urusan']);
        $data['nama_urusan'] = trim(substr($row['urusan'], 2, strlen($row['urusan'])));

        $data['kode_bidang_urusan'] = substr($row['sub_bidang'], 0, 4);
        $data['kode_bidang_urusan'] = str_replace("\xFFFD", "", $data['kode_bidang_urusan']);
        $data['nama_bidang_urusan'] = trim(substr($row['sub_bidang'], 5, strlen($row['sub_bidang'])));

        $data['kode_skpd'] = substr($row['skpd'], 0, 22);
        $data['kode_skpd'] = str_replace("\xFFFD", "", $data['kode_skpd']);
        $data['nama_skpd'] = trim(substr($row['skpd'], 23, strlen($row['skpd'])));

        $data['kode_sub_skpd'] = substr($row['sub_unit'], 0, 22);
        $data['kode_sub_skpd'] = str_replace("\xFFFD", "", $data['kode_sub_skpd']);
        $data['nama_sub_skpd'] = trim(substr($row['sub_unit'], 23, strlen($row['sub_unit'])));

        $data['kode_program'] = substr($row['program'], 0, 7);
        $data['kode_program'] = str_replace("\xFFFD", "", $data['kode_program']);
        $data['nama_program'] = trim(substr($row['program'], 8, strlen($row['program'])));

        $data['kode_kegiatan'] = substr($row['kegiatan'], 0, 12);
        $data['kode_kegiatan'] = str_replace("\xFFFD", "", $data['kode_kegiatan']);
        $data['nama_kegiatan'] = trim(substr($row['kegiatan'], 13, strlen($row['kegiatan'])));

        $sub_keg = substr($row['sub_kegiatan'], 0, 15);
        $sub_keg = str_replace("\xFFFD", "", $sub_keg);
        $data['kode_sub_kegiatan'] = $sub_keg;
        //$data['kode_sub_kegiatan']=substr($row['sub_kegiatan'],0,15);
        $data['nama_sub_kegiatan'] = trim(substr($row['sub_kegiatan'], 16, strlen($row['sub_kegiatan'])));

        $data['kode_akun'] = substr($row['rekening'], 0, 17);
        $data['kode_akun'] = str_replace("\xFFFD", "", $data['kode_akun']);
        $data['nama_akun'] = trim(substr($row['rekening'], 18, strlen($row['rekening'])));
        
        $data['nilai_rincian'] = $row['rincian'];
        
        $data['sub_unit'] = $row['sub_unit'];

        return new Sipd([
            //
            'kode_urusan' => $data['kode_urusan'],
            'nama_urusan' => $data['nama_urusan'],
            'kode_bidang_urusan' => $data['kode_bidang_urusan'],
            'nama_bidang_urusan' => $data['nama_bidang_urusan'],
            'kode_skpd' => $data['kode_skpd'],
            'nama_skpd' => $data['nama_skpd'],
            'kode_sub_skpd' => $data['kode_sub_skpd'],
            'nama_sub_skpd' => $data['nama_sub_skpd'],
            'kode_program' => $data['kode_program'],
            'nama_program' => $data['nama_program'],
            'kode_kegiatan' => $data['kode_kegiatan'],
            'nama_kegiatan' => $data['nama_kegiatan'],
            'kode_sub_kegiatan' => $data['kode_sub_kegiatan'],
            'nama_sub_kegiatan' => $data['nama_sub_kegiatan'],
            'kode_akun' => $data['kode_akun'],
            'nama_akun' => $data['nama_akun'],
            'nilai_rincian' => $data['nilai_rincian'],
            // 'jenis' => $row['jenis'],
            'sub_unit' => $data['sub_unit'],
            'tahapan_id' => $request['tahapan_id'],
        ]);
    }
}
