<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Tahapan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // WithoutModelEvents::disable();
        $this->call([
            GroupSeeder::class,
            AkunSeeder::class,
            SkpdSeeder::class,
            TahapanSeeder::class,
        ]);
        // WithoutModelEvents::enable();
    }
}
