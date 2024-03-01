<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $file = fopen(base_path("database/seeders/data/akuns.csv"), 'r');
        $idx = 0;
        while (($row = fgetcsv($file, 0, ';')) !== false) {
            // skip first row
            if ($idx++ == 0) {
                continue;
            }
            // create akun
            \App\Models\Akun::create([
                'kode' => $row[0],
                'nama' => $row[1],
                'level' => $row[2],
                'tahun' => date('Y'),
            ]);
        }
    }
}
