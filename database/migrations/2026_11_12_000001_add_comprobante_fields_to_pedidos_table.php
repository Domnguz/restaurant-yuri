<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('comprobante_tipo', 20)->nullable()->after('pagado_at');
            $table->string('cliente_nombre', 160)->nullable()->after('comprobante_tipo');
            $table->string('cliente_documento', 20)->nullable()->after('cliente_nombre');
            $table->string('cliente_direccion', 255)->nullable()->after('cliente_documento');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['comprobante_tipo', 'cliente_nombre', 'cliente_documento', 'cliente_direccion']);
        });
    }
};
