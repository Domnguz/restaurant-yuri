<?php

use App\Models\Categoria;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\Http;

it('keeps the catalog internal and shows only available products to waiters', function () {
    $this->get('/carta')->assertNotFound();
    $waiter = User::factory()->create(['role' => 'mesero']);
    $table = Mesa::create(['numero' => 1, 'zona' => 'interior', 'estado' => 'disponible']);
    $this->get(route('admin.mesero.pedidos.create', $table))->assertRedirect(route('login'));

    $category = Categoria::create(['nombre' => 'Bebidas', 'activo' => true]);
    $hiddenCategory = Categoria::create(['nombre' => 'Oculta', 'activo' => false]);
    Producto::create(['categoria_id' => $category->id, 'nombre' => 'Agua visible', 'precio' => 2, 'activo' => true]);
    Producto::create(['categoria_id' => $category->id, 'nombre' => 'Bebida desactivada', 'precio' => 3, 'activo' => false]);
    Producto::create(['categoria_id' => $hiddenCategory->id, 'nombre' => 'Producto oculto', 'precio' => 4, 'activo' => true]);

    $this->actingAs($waiter)->get(route('admin.mesero.pedidos.create', $table))->assertOk()->assertSee('Agua visible')
        ->assertDontSee('Bebida desactivada')->assertDontSee('Producto oculto')
        ->assertSee('Enviar pedido al administrador');
});

it('renders the restaurant management and waiter pages for their roles', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $waiter = User::factory()->create(['role' => 'mesero']);
    $category = Categoria::create(['nombre' => 'Bebidas', 'activo' => true]);
    $product = Producto::create(['categoria_id' => $category->id, 'nombre' => 'Agua', 'precio' => 2, 'activo' => false]);
    $table = Mesa::create(['numero' => 1, 'zona' => 'interior', 'estado' => 'disponible']);

    $this->actingAs($admin);
    foreach (['admin.dashboard', 'admin.pedidos.index', 'admin.productos.index', 'admin.productos.create', 'admin.categorias.index', 'admin.categorias.create'] as $route) {
        $this->get(route($route))->assertOk();
    }
    $this->get(route('admin.productos.index'))->assertSee('Agua')->assertSee('Inactivo');
    $this->get(route('admin.productos.edit', $product))->assertOk();
    $this->get(route('admin.categorias.edit', $category))->assertOk();

    $this->actingAs($waiter)->get(route('admin.mesero.pedidos.index'))->assertOk();
    $this->get(route('admin.mesero.pedidos.create', $table))->assertOk();
    $this->get(route('admin.mesero.pedidos.agregar', $table))->assertRedirect(route('admin.mesero.pedidos.index'));
    $this->get(route('admin.productos.index'))->assertForbidden();
});

it('lets the administrator manage fixed and customizable prices', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Categoria::create(['nombre' => 'Mixtos', 'activo' => true]);
    $this->actingAs($admin)->post(route('admin.productos.store'), [
        'nombre' => 'Mixto de la casa', 'categoria_id' => $category->id,
        'precio_personalizado' => 1, 'activo' => 1,
    ])->assertSessionHasNoErrors()->assertRedirect(route('admin.productos.index'));
    $product = Producto::first();
    expect($product->precio)->toBeNull();
    $this->put(route('admin.productos.update', $product), [
        'nombre' => 'Mixto de la casa', 'categoria_id' => $category->id,
        'precio' => 10.50, 'activo' => 0,
    ])->assertSessionHasNoErrors();
    expect($product->fresh()->precio)->toBe('10.50')->and($product->fresh()->activo)->toBeFalse();
});

it('registers a waiter order using the current menu price and preserves notes', function () {
    Http::fake();
    $waiter = User::factory()->create(['role' => 'mesero']);
    $category = Categoria::create(['nombre' => 'Bebidas', 'activo' => true]);
    $product = Producto::create(['categoria_id' => $category->id, 'nombre' => 'Agua', 'precio' => 3, 'activo' => true]);
    $table = Mesa::create(['numero' => 1, 'zona' => 'interior', 'estado' => 'disponible']);
    $this->actingAs($waiter)->postJson(route('admin.mesero.pedidos.store', $table), [
        'items' => [['producto_id' => $product->id, 'precio' => 0.01, 'observaciones' => 'Sin hielo']],
        'observaciones' => 'Servir primero',
    ])->assertOk();
    $order = Pedido::first();
    expect($order->total)->toBe('3.00')->and($table->fresh()->estado)->toBe('ocupada')
        ->and($order->detalles->first()->observaciones)->toBe('Sin hielo · Servir primero');
    $this->get(route('admin.mesero.pedidos.agregar', $table))->assertOk()->assertSee('Sin hielo', false);

    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin)->get(route('admin.pedidos.show', $order))->assertOk()->assertSee('Registrar cobro');
    $this->post(route('admin.pedidos.pago', $order), ['metodo_pago' => 'efectivo', 'monto_recibido' => 5])->assertSessionHasNoErrors();
    expect($order->fresh()->estado)->toBe('pagado')->and($order->fresh()->vuelto)->toBe('2.00')->and($table->fresh()->estado)->toBe('disponible');
});

it('rejects unavailable products without creating an order', function () {
    Http::fake();
    $waiter = User::factory()->create(['role' => 'mesero']);
    $category = Categoria::create(['nombre' => 'Bebidas', 'activo' => false]);
    $product = Producto::create(['categoria_id' => $category->id, 'nombre' => 'Agua', 'precio' => 3, 'activo' => true]);
    $table = Mesa::create(['numero' => 1, 'zona' => 'interior', 'estado' => 'disponible']);
    $this->actingAs($waiter)->postJson(route('admin.mesero.pedidos.store', $table), [
        'items' => [['producto_id' => $product->id, 'precio' => 3]],
    ])->assertUnprocessable();
    expect(Pedido::count())->toBe(0)->and($table->fresh()->estado)->toBe('disponible');
});

it('delivers the waiter form to the administrator and keeps management restricted', function () {
    Http::fake();
    $waiter = User::factory()->create(['name' => 'Mesero de prueba', 'role' => 'mesero']);
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Categoria::create(['nombre' => 'Bebidas', 'activo' => true]);
    $product = Producto::create(['categoria_id' => $category->id, 'nombre' => 'Agua', 'precio' => 3, 'activo' => true]);
    $table = Mesa::create(['numero' => 2, 'zona' => 'interior', 'estado' => 'disponible']);

    $this->actingAs($waiter)->get(route('admin.mesero.pedidos.create', $table))
        ->assertOk()->assertSee('Enviar pedido al administrador')->assertDontSee('Registrar cobro');
    $this->postJson(route('admin.mesero.pedidos.store', $table), [
        'items' => [['producto_id' => $product->id, 'precio' => 3, 'observaciones' => 'Sin hielo']],
    ])->assertOk()->assertJsonPath('message', 'Pedido enviado al administrador correctamente.');
    $order = Pedido::first();
    expect($order->estado)->toBe('pendiente')->and($order->user_id)->toBe($waiter->id);
    Http::assertSent(fn ($request) => $request['pedido']['id'] === $order->id);

    $this->getJson(route('admin.pedidos.actualizaciones'))->assertForbidden();
    $this->get(route('admin.pedidos.show', $order))->assertForbidden();
    $this->patch(route('admin.pedidos.estado', $order), ['estado' => 'listo'])->assertForbidden();
    $this->patch(route('admin.pedidos.cancelar', $order))->assertForbidden();
    $this->post(route('admin.pedidos.pago', $order), ['metodo_pago' => 'yape'])->assertForbidden();

    $this->actingAs($admin)->getJson(route('admin.pedidos.actualizaciones'))->assertOk()
        ->assertJsonPath('pedidos.0.id', $order->id)
        ->assertJsonPath('pedidos.0.mesero', 'Mesero de prueba')
        ->assertJsonPath('pedidos.0.estado', 'pendiente')
        ->assertJsonPath('pedidos.0.productos', 1)
        ->assertJsonPath('mesas.0.estado', 'ocupada');
    $this->get(route('admin.pedidos.show', $order))->assertOk()->assertSee('Sin hielo');
    $this->patch(route('admin.pedidos.estado', $order), ['estado' => 'listo'])->assertSessionHasNoErrors();
    $this->getJson(route('admin.pedidos.actualizaciones'))->assertJsonPath('pedidos.0.estado', 'listo');
    $this->post(route('admin.pedidos.pago', $order), ['metodo_pago' => 'yape'])->assertSessionHasNoErrors();
    $this->getJson(route('admin.pedidos.actualizaciones'))
        ->assertJsonCount(0, 'pedidos')->assertJsonPath('mesas.0.estado', 'disponible');
});
