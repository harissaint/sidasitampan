<?php

namespace App\Imports;

use App\Models\RealisasiTemp;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RealisasiTempImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $request = request()->all();

        if(Auth::user()->group->nama == 'SKPD'){
            $row['kode_skpd'] = Auth::user()->skpd->kode;
            $row['nama_skpd'] = Auth::user()->skpd->nama;

            $row['kode_sub_skpd'] = Auth::user()->skpd->kode;
            $row['nama_sub_skpd'] = Auth::user()->skpd->nama;

            $row['nilai_realisasi_lra'] = $row['nilai_lra_sebelum_koreksi'];
        }

        return new RealisasiTemp([
            //
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'kode_urusan' => $row['kode_urusan'] ?? '-',
            'nama_urusan' => $row['nama_urusan'] ?? '-',
            'kode_bidang_urusan' => $row['kode_bidang_urusan'] ?? '-',
            'nama_bidang_urusan' => $row['nama_bidang_urusan'] ?? '-',
            'kode_skpd' => $row['kode_skpd'] ?? '-',
            'nama_skpd' => $row['nama_skpd'] ?? '-',
            'kode_sub_skpd' => $row['kode_sub_skpd'] ?? '-',
            'nama_sub_skpd' => $row['nama_sub_skpd'] ?? '-',
            'kode_program' => $row['kode_program'] ?? '-',
            'nama_program' => $row['nama_program'] ?? '-',
            'kode_kegiatan' => $row['kode_kegiatan'] ?? '-',
            'nama_kegiatan' => $row['nama_kegiatan'] ?? '-',
            'kode_sub_kegiatan' => $row['kode_sub_kegiatan'] ?? '-',
            'nama_sub_kegiatan' => $row['nama_sub_kegiatan'] ?? '-',
            'kode_akun' => $row['kode_rekening'] ?? '-',
            'nama_akun' => $row['nama_rekening'] ?? '-',
            'nilai_rincian' => $row['nilai_realisasi_lra'] ?? $row['nilai_lra_sebelum_koreksi'] ?? 0,
            'tahun' => $request['tahun'],
        ]);
    }

    public function chunkSize(): int
    {
        return 15000;
    }

    public function batchSize(): int
    {
        return 100;
    }
}
