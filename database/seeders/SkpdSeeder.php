<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkpdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $file = fopen(base_path("database/seeders/data/skpds.csv"), 'r');
        $idx = 0;
        while (($row = fgetcsv($file, 0, ';')) !== false) {
            // skip first row
            if ($idx++ == 0) {
                continue;
            }
            // create skpd
            \App\Models\Skpd::create([
                'kode' => $row[0],
                'nama' => $row[1],
            ]);
        }
    }
}
