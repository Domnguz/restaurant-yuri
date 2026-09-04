<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@restaurantyuri.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Mesero 1',
            'email' => 'mesero1@restaurantyuri.com',
            'password' => 'password',
            'role' => 'mesero',
        ]);

        User::create([
            'name' => 'Mesero 2',
            'email' => 'mesero2@restaurantyuri.com',
            'password' => 'password',
            'role' => 'mesero',
        ]);
    }
}