<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services_catalog')->insert([
            ['name' => 'Servicio', 'code' => 'NY'],
            ['name' => 'Mantenimiento', 'code' => 'RM'],
            ['name' => 'Refacciones', 'code' => 'LDN'],
            ['name' => 'Pintura', 'code' => 'IST'],
            ['name' => 'Talacha', 'code' => 'PRS'],
        ]);
    }
}
