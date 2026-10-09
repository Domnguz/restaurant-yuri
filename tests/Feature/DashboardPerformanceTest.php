<?php

use App\Models\Categoria;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\DB;

it('renders recent order details with bounded queries and preserves weekly sales', function () {
    $this->freezeTime();
    $admin = User::factory()->create(['role' => 'admin']);
    $table = Mesa::create(['numero' => 1, 'zona' => 'interior', 'estado' => 'disponible']);
    $category = Categoria::create(['nombre' => 'Bebidas', 'activo' => true]);
    $product = Producto::create(['categoria_id' => $category->id, 'nombre' => 'Agua', 'precio' => 3, 'activo' => true]);

    foreach (range(1, 5) as $index) {
        $order = Pedido::create([
            'mesa_id' => $table->id, 'user_id' => $admin->id,
            'estado' => 'pagado', 'total' => 3, 'pagado_at' => now(),
        ]);
        $order->detalles()->create(['precio' => 3])->productos()->attach($product);
    }

    Pedido::create([
        'mesa_id' => $table->id, 'user_id' => $admin->id,
        'estado' => 'pagado', 'total' => 7, 'pagado_at' => today()->subDays(6),
    ]);
    Pedido::create([
        'mesa_id' => $table->id, 'user_id' => $admin->id,
        'estado' => 'pagado', 'total' => 100, 'pagado_at' => today()->subDays(7),
    ]);

    $this->actingAs($admin);
    DB::enableQueryLog();
    DB::flushQueryLog();
    try {
        $response = $this->get(route('admin.dashboard'))->assertOk();
        $queryCount = count(DB::getQueryLog());
    } finally {
        DB::disableQueryLog();
    }

    $response->assertViewHas('ventasHoy', 15.0)
        ->assertViewHas('ventasSemana', fn ($week) => $week->count() === 7
            && $week->first()['ventas'] === 7.0
            && $week->last()['ventas'] === 15.0
            && $week->sum('ventas') === 22.0);
    expect($queryCount)->toBeLessThanOrEqual(12);
});
