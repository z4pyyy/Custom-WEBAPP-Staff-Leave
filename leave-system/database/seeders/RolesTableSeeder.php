<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'superadmin'],
            ['id' => 2, 'name' => 'management'],
            ['id' => 3, 'name' => 'employee'],
        ]);
    }
}
