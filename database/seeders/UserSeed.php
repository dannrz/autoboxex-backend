<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Usuario;
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
            'name' => 'Daniel Rodríguez',
            'email' => 'admin@admin.com',
            'username' => 'dann',
            'password' => Hash::make('qwerT5'),
            'role_id' => 1,
        ]);
        User::create([
            'name' => 'Alexander Ramirez',
            'email' => 'almacen@admin.com',
            'username' => 'lxndr',
            'password' => Hash::make('asdfG2'),
            'role_id' => 2,
        ]);

        $oldUsers = Usuario::all();

        $oldUsers->each(function ($user) {
            switch ($user->IDPERFIL) {
                case 'adm':
                    $user->role_id = 1;
                    break;
                case 'Uad':
                    $user->role_id = 5;
                    break;
                case 'Ual':
                    $user->role_id = 6;
                    break;
                case 'Jta':
                    $user->role_id = 7;
                    break;
                default:
                    $user->role_id = 8;
                    break;
            }
            User::create([
                'name' => "{$user->NOMBREUSUARIO} {$user->APELLIDOUSUARIO}",
                'email' => fake()->unique()->safeEmail(),
                'username' => $user->IDUSUARIO,
                'password' => Hash::make($user->CONTRASENAUSUARIO),
                'role_id' => $user->role_id,
            ]);
        });
    }
}
