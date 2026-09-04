<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_detalle_componentes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pedido_detalle_id')
                ->constrained('pedido_detalles')
                ->cascadeOnDelete();

            $table->foreignId('componente_mixto_id')
                ->constrained('componentes_mixtos')
                ->restrictOnDelete();

            $table->timestamps();

            $table->unique(
                ['pedido_detalle_id', 'componente_mixto_id'],
                'detalle_componente_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_detalle_componentes');
    }
};