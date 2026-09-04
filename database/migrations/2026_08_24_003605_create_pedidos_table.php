<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();

            // Mesa donde se realiza el pedido
            $table->foreignId('mesa_id')
                ->constrained('mesas')
                ->restrictOnDelete();

            // Trabajador que tomó el pedido
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Estado actual del pedido
            $table->enum('estado', [
                'pendiente',
                'preparando',
                'listo',
                'entregado',
                'pagado',
                'cancelado'
            ])->default('pendiente');

            // Total del pedido
            $table->decimal('total', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};