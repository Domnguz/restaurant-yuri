<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        Categoria::create([
            'nombre' => 'Mixto',
            'activo' => true,
        ]);

        Categoria::create([
            'nombre' => 'Bebidas',
            'activo' => true,
        ]);

        Categoria::create([
            'nombre' => 'Postres',
            'activo' => true,
        ]);
    }
}