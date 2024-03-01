<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TahapanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        \App\Models\Tahapan::create([
            'nama' => 'RKPD',
            'tahun' => 2023,
            'keterangan' => 'lorem lorem',
        ]);
    }
}
