<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {

            $table->enum('metodo_pago', [
                'efectivo',
                'yape',
                'plin',
                'tarjeta',
            ])->nullable()->after('total');

            $table->decimal('monto_recibido', 10, 2)
                ->nullable()
                ->after('metodo_pago');

            $table->decimal('vuelto', 10, 2)
                ->nullable()
                ->after('monto_recibido');

            $table->timestamp('pagado_at')
                ->nullable()
                ->after('vuelto');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {

            $table->dropColumn([
                'metodo_pago',
                'monto_recibido',
                'vuelto',
                'pagado_at',
            ]);

        });
    }
};