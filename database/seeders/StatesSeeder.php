<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('states_catalog')->insert([
            ['name' => 'Emitida', 'code' => 'EM'],
            ['name' => 'Retenida', 'code' => 'RE'],
            ['name' => 'Cancelada', 'code' => 'CA'],
            ['name' => 'En pausa', 'code' => 'PA'],
            ['name' => 'Ausente', 'code' => 'AU'],
        ]);
    }
}
