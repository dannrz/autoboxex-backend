<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => null,
            'name' => 'Daniel Rodríguez',
            'email' => 'admin@admin.com',
            'username' => 'dann',
            'password' => Hash::make('qwerT5'),
            'permission' => 'admin',
        ]);
        User::create([
            'id' => null,
            'name' => 'Alexander Ramirez',
            'email' => 'almacen@admin.com',
            'username' => 'lxndr',
            'password' => Hash::make('asdfG2'),
            'permission' => 'almacen',
        ]);
    }
}
