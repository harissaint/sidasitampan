<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $group = \App\Models\Group::create([
            'nama' => 'admin',
        ]);
        \App\Models\Group::create([
            'nama' => 'TAPD',
        ]);
        \App\Models\Group::create([
            'nama' => 'SKPD',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@bpkad.com',
            'password' => bcrypt('admin'),
            'group_id' => $group->id,
            'status' => true,
        ]);
    }
}
