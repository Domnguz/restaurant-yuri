<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $bebidas = Categoria::where('nombre', 'Bebidas')->first();

        $productos = [
            [
                'nombre' => 'Cevada',
                'precio' => 2.00,
            ],
            [
                'nombre' => 'Clarito',
                'precio' => 2.00,
            ],
            [
                'nombre' => 'Gaseosa',
                'precio' => 3.00,
            ],
            [
                'nombre' => 'Agua',
                'precio' => 2.00,
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create([
                'categoria_id' => $bebidas->id,
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'activo' => true,
            ]);
        }
    }
}