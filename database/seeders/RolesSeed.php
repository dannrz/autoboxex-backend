<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['role_name' => 'admin', 'description' => 'Administrator'],
            ['role_name' => 'almacen', 'description' => 'Almacenista'],
            ['role_name' => 'user', 'description' => 'Usuario'],
        ]);
    }
}
