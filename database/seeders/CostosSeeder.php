<?php

namespace Database\Seeders;

use App\Models\Costo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CostosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Costo::factory()->count(50)->create();
    }
}
