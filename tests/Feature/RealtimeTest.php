<?php

use App\Models\Categoria;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use App\Services\RestaurantRealtime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake();
    config(['services.realtime.secret' => 'test-realtime-secret']);
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->waiter = User::factory()->create(['role' => 'mesero']);
    $category = Categoria::create(['nombre' => 'Bebidas', 'activo' => true]);
    $this->product = Producto::create(['nombre' => 'Agua', 'precio' => 3, 'activo' => true, 'categoria_id' => $category->id]);
    $this->table = Mesa::create(['numero' => 1, 'zona' => 'interior', 'estado' => 'disponible']);
    $this->items = ['items' => [['producto_id' => $this->product->id, 'precio' => 3, 'observaciones' => 'Sin hielo']]];
});

it('publishes committed creation additions service states and cancellation', function () {
    $this->actingAs($this->waiter)->postJson(route('admin.mesero.pedidos.store', $this->table), $this->items)->assertOk();
    $order = Pedido::first();
    Http::assertSent(fn ($request) => $request['action'] === 'created' && $request['pedido']['id'] === $order->id && $request->hasHeader('Authorization', 'Bearer test-realtime-secret'));
    $this->actingAs($this->admin)->patchJson(route('admin.pedidos.estado', $order), ['estado' => 'listo'])->assertOk();
    Http::assertSent(fn ($request) => $request['action'] === 'state_changed' && $request['pedido']['estado'] === 'listo');
    $this->actingAs($this->waiter)->postJson(route('admin.mesero.pedidos.store', $this->table), [...$this->items, 'pedido_id' => $order->id])->assertOk();
    expect($order->fresh()->estado)->toBe('pendiente')->and($order->fresh()->total)->toBe('6.00');
    Http::assertSent(fn ($request) => $request['action'] === 'items_added');
    $this->actingAs($this->admin)->patchJson(route('admin.pedidos.cancelar', $order))->assertOk()->assertJsonPath('pedido.estado', 'cancelado');
    Http::assertSent(fn ($request) => $request['action'] === 'cancelled');
    $this->actingAs($this->waiter)->getJson(route('realtime.snapshot', ['pedido' => $order->id]))->assertOk()
        ->assertJsonCount(0, 'pedidos')->assertJsonPath('pedido.estado', 'cancelado')->assertJsonPath('mesas.0.estado', 'disponible')
        ->assertJsonMissingPath('pedido.pago')->assertJsonMissingPath('resumen');
});

it('publishes payment and updates the administrator summary', function () {
    $this->actingAs($this->waiter)->postJson(route('admin.mesero.pedidos.store', $this->table), $this->items);
    $order = Pedido::first();
    $this->actingAs($this->admin)->postJson(route('admin.pedidos.pago', $order), ['metodo_pago' => 'efectivo', 'monto_recibido' => 5])
        ->assertOk()->assertJsonPath('pedido.estado', 'pagado')->assertJsonPath('pedido.pago.vuelto', '2.00');
    Http::assertSent(fn ($request) => $request['action'] === 'paid');
    $this->getJson(route('realtime.snapshot', ['pedido' => $order->id]))->assertOk()->assertJsonCount(0, 'pedidos')
        ->assertJsonPath('resumen.ventasHoy', 3)->assertJsonPath('resumen.pedidosActivos', 0)
        ->assertJsonPath('resumen.ultimosPedidos.0.estado', 'pagado')->assertJsonPath('mesas.0.estado', 'disponible');
    $this->patchJson(route('admin.pedidos.cancelar', $order))->assertUnprocessable();
    $this->patchJson(route('admin.pedidos.estado', $order), ['estado' => 'listo'])->assertUnprocessable();
    $this->postJson(route('admin.pedidos.pago', $order), ['metodo_pago' => 'yape'])->assertUnprocessable();
    Http::assertSentCount(2);
});

it('prevents a stale waiter form from recreating a cancelled order', function () {
    $this->actingAs($this->waiter)->postJson(route('admin.mesero.pedidos.store', $this->table), $this->items);
    $order = Pedido::first();
    $this->actingAs($this->admin)->patchJson(route('admin.pedidos.cancelar', $order))->assertOk();
    $this->actingAs($this->waiter)->postJson(route('admin.mesero.pedidos.store', $this->table), [...$this->items, 'pedido_id' => $order->id])->assertUnprocessable();
    expect(Pedido::count())->toBe(1)->and($order->fresh()->estado)->toBe('cancelado')->and($this->table->fresh()->estado)->toBe('disponible');
    Http::assertSentCount(2);
});

it('keeps saved orders when node is unavailable and emits nothing for rolled back transactions', function () {
    Http::fake(fn () => Http::response(['message' => 'Unavailable'], 503));
    $this->actingAs($this->waiter)->postJson(route('admin.mesero.pedidos.store', $this->table), $this->items)->assertOk();
    expect(Pedido::first()->total)->toBe('3.00');
    Http::fake();
    try {
        DB::transaction(function () {
            app(RestaurantRealtime::class)->publish(Pedido::first(), 'created');
            throw new RuntimeException('Rollback');
        });
    } catch (RuntimeException $exception) {
        expect($exception->getMessage())->toBe('Rollback');
    }
    Http::assertNothingSent();
});

it('protects staff snapshots and issues short-lived signed connection tokens', function () {
    $this->getJson(route('realtime.snapshot'))->assertUnauthorized();
    $this->getJson(route('realtime.connection'))->assertUnauthorized();
    $response = $this->actingAs($this->waiter)->getJson(route('realtime.connection'))->assertOk();
    [$payload, $signature] = explode('.', $response->json('token'));
    expect($signature)->toBe(hash_hmac('sha256', $payload, 'test-realtime-secret'));
    $claims = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
    expect($claims['role'])->toBe('mesero')->and($claims['exp'])->toBeGreaterThan(now()->timestamp)->toBeLessThanOrEqual(now()->addMinutes(5)->timestamp);
    $this->getJson(route('realtime.snapshot', ['pedido' => 'invalid']))->assertUnprocessable();
});

it('rejects serving or charging a total reviewed before the waiter added products', function () {
    $this->actingAs($this->waiter)->postJson(route('admin.mesero.pedidos.store', $this->table), $this->items)->assertOk();
    $order = Pedido::first();
    $this->postJson(route('admin.mesero.pedidos.store', $this->table), [...$this->items, 'pedido_id' => $order->id])->assertOk();
    $this->actingAs($this->admin)->patchJson(route('admin.pedidos.estado', $order), ['estado' => 'listo', 'total_revisado' => 3])
        ->assertUnprocessable();
    $this->postJson(route('admin.pedidos.pago', $order), ['metodo_pago' => 'yape', 'total_revisado' => 3])->assertUnprocessable();
    $this->postJson(route('admin.pedidos.pago', $order), ['metodo_pago' => 'efectivo', 'monto_recibido' => 4])->assertUnprocessable();
    expect($order->fresh()->estado)->toBe('pendiente')->and($order->fresh()->total)->toBe('6.00');
    Http::assertSentCount(2);
    $this->postJson(route('admin.pedidos.pago', $order), ['metodo_pago' => 'yape', 'total_revisado' => 6])->assertOk();
});
