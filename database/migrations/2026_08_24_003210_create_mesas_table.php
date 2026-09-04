<?php

namespace Database\Seeders;

use App\Models\Mesa;
use Illuminate\Database\Seeder;

class MesaSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 6; $i++) {
            Mesa::create([
                'numero' => $i,
                'zona' => 'interior',
                'estado' => 'disponible',
            ]);
        }

        for ($i = 7; $i <= 8; $i++) {
            Mesa::create([
                'numero' => $i,
                'zona' => 'exterior',
                'estado' => 'disponible',
            ]);
        }
    }
}