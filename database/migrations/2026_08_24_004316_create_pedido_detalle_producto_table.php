<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_detalle_producto', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pedido_detalle_id')
                ->constrained('pedido_detalles')
                ->cascadeOnDelete();

            $table->foreignId('producto_id')
                ->constrained('productos')
                ->restrictOnDelete();

            $table->timestamps();

            $table->unique([
                'pedido_detalle_id',
                'producto_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_detalle_producto');
    }
};