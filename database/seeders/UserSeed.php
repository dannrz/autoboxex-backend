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
            'role_id' => 1,
        ]);
        User::create([
            'id' => null,
            'name' => 'Alexander Ramirez',
            'email' => 'almacen@admin.com',
            'username' => 'lxndr',
            'password' => Hash::make('asdfG2'),
            'role_id' => 2,
        ]);
        User::create([
            'id' => null,
            'name' => 'dios johnson',
            'email' => 'diosj@admin.com',
            'username' => 'dj',
            'password' => Hash::make('qwerT5'),
            'role_id' => 1,
        ]);
    }
}
