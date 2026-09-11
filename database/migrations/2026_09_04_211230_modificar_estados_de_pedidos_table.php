<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->enum('estado', [
                'pendiente',
                'listo',
                'entregado',
                'pagado',
                'cancelado'
            ])->default('pendiente')->change();
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->enum('estado', [
                'pendiente',
                'preparando',
                'listo',
                'entregado',
                'pagado',
                'cancelado'
            ])->default('pendiente')->change();
        });
    }
};