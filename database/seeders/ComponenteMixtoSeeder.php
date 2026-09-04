<?php

namespace Database\Seeders;

use App\Models\ComponenteMixto;
use Illuminate\Database\Seeder;

class ComponenteMixtoSeeder extends Seeder
{
    public function run(): void
    {
        $componentes = [
            'Arroz',
            'Tollito',
            'Tallarines',
            'Ceviche',
            'Cancha',
            'Camote',
            'Mondongo',
            'Chanfaina',
        ];

        foreach ($componentes as $componente) {
            ComponenteMixto::create([
                'nombre' => $componente,
                'activo' => true,
            ]);
        }
    }
}