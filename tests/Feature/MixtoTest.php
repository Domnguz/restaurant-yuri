<?php

use App\Models\Categoria;
use App\Models\ComponenteMixto;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake();
    $this->waiter = User::factory()->create(['role' => 'mesero']);
    $this->category = Categoria::create(['nombre' => 'Platos', 'activo' => true]);
    $this->mixto = Producto::create(['nombre' => 'Mixto', 'categoria_id' => $this->category->id, 'precio' => 0, 'activo' => true]);
    $this->table = Mesa::create(['numero' => 1, 'zona' => 'interior', 'estado' => 'disponible']);
    $this->mondongo = ComponenteMixto::create(['nombre' => 'Mondongo', 'activo' => true]);
    $this->chanfaina = ComponenteMixto::create(['nombre' => 'Chanfaina', 'activo' => true]);
});

it('shows the mixto ingredients inline even when its stored price is zero', function () {
    expect($this->mixto->personalizable)->toBeTrue()
        ->and($this->mixto->precios_personalizacion)->toBe([7, 8, 9, 10, 11, 12]);

    $this->actingAs($this->waiter)->get(route('admin.mesero.pedidos.create', $this->table))->assertOk()
        ->assertSee('¿Qué llevará el mixto?')->assertSee('Mondongo')->assertSee('Chanfaina')
        ->assertSee('type="checkbox"', false)->assertDontSee('x-ref="customize"', false);
});

it('saves the selected mixto price and ingredients rather than the zero catalog price', function () {
    $this->actingAs($this->waiter)->postJson(route('admin.mesero.pedidos.store', $this->table), [
        'items' => [['producto_id' => $this->mixto->id, 'precio' => 10, 'componente_ids' => [$this->mondongo->id]]],
    ])->assertOk();

    $order = Pedido::first();
    expect($order->total)->toBe('10.00')->and($order->detalles->first()->precio)->toBe('10.00')
        ->and($order->detalles->first()->componentesMixto->pluck('id')->all())->toBe([$this->mondongo->id]);
});

it('rejects a mixto without ingredients or with zero price', function (float $price, array $ids) {
    $this->actingAs($this->waiter)->postJson(route('admin.mesero.pedidos.store', $this->table), [
        'items' => [['producto_id' => $this->mixto->id, 'precio' => $price, 'componente_ids' => $ids]],
    ])->assertUnprocessable();
    expect(Pedido::count())->toBe(0)->and($this->table->fresh()->estado)->toBe('disponible');
})->with([[10.0, []], [0.0, [1]]]);

it('rejects ingredients that are no longer available', function () {
    $this->mondongo->update(['activo' => false]);
    $this->actingAs($this->waiter)->postJson(route('admin.mesero.pedidos.store', $this->table), [
        'items' => [['producto_id' => $this->mixto->id, 'precio' => 10, 'componente_ids' => [$this->mondongo->id]]],
    ])->assertUnprocessable();
    expect(Pedido::count())->toBe(0);
});

it('still lets a mixto with a fixed positive price select ingredients', function () {
    $this->mixto->update(['precio' => 9.50]);
    expect($this->mixto->personalizable)->toBeTrue()->and($this->mixto->precios_personalizacion)->toBe([9.5]);
    $this->actingAs($this->waiter)->postJson(route('admin.mesero.pedidos.store', $this->table), [
        'items' => [['producto_id' => $this->mixto->id, 'precio' => 9.50, 'componente_ids' => [$this->chanfaina->id]]],
    ])->assertOk();
    expect(Pedido::first()->total)->toBe('9.50');
});
