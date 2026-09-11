<?php

use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Mesero\PedidoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| Perfil
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Pedidos
        |--------------------------------------------------------------------------
        */

        Route::get('/pedidos', [\App\Http\Controllers\Admin\PedidoController::class, 'index'])
            ->name('pedidos.index');

        Route::get('/pedidos/{pedido}', [\App\Http\Controllers\Admin\PedidoController::class, 'show'])
            ->name('pedidos.show');

        Route::patch('/pedidos/{pedido}/estado', [\App\Http\Controllers\Admin\PedidoController::class, 'cambiarEstado'])
            ->name('pedidos.estado');

        Route::post('/pedidos/{pedido}/pago', [\App\Http\Controllers\Admin\PedidoController::class, 'finalizarPago'])
            ->name('pedidos.pago');

        Route::patch('/pedidos/{pedido}/cancelar', [\App\Http\Controllers\Admin\PedidoController::class, 'cancelar'])
            ->name('pedidos.cancelar');


        /*
        |--------------------------------------------------------------------------
        | Productos
        |--------------------------------------------------------------------------
        */

        Route::get('/productos', [ProductoController::class, 'index'])
            ->name('productos.index');

        Route::get('/productos/crear', [ProductoController::class, 'create'])
            ->name('productos.create');

        Route::post('/productos', [ProductoController::class, 'store'])
            ->name('productos.store');

        Route::get('/productos/{producto}/editar', [ProductoController::class, 'edit'])
            ->name('productos.edit');

        Route::put('/productos/{producto}', [ProductoController::class, 'update'])
            ->name('productos.update');


        /*
        |--------------------------------------------------------------------------
        | Categorías
        |--------------------------------------------------------------------------
        */

        Route::get('/categorias', [CategoriaController::class, 'index'])
            ->name('categorias.index');

        Route::get('/categorias/crear', [CategoriaController::class, 'create'])
            ->name('categorias.create');

        Route::post('/categorias', [CategoriaController::class, 'store'])
            ->name('categorias.store');

        Route::get('/categorias/{categoria}/editar', [CategoriaController::class, 'edit'])
            ->name('categorias.edit');

        Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])
            ->name('categorias.update');

    });


/*
|--------------------------------------------------------------------------
| MESERO
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:mesero'])
    ->prefix('admin/mesero')
    ->name('admin.mesero.')
    ->group(function () {

        Route::get('/pedidos', [PedidoController::class, 'index'])
            ->name('pedidos.index');

        Route::get('/pedidos/{mesa}/crear', [PedidoController::class, 'create'])
            ->name('pedidos.create');

        Route::get('/pedidos/{mesa}/agregar', [PedidoController::class, 'agregar'])
            ->name('pedidos.agregar');

        Route::post('/pedidos/{mesa}', [PedidoController::class, 'store'])
            ->name('pedidos.store');

    });


require __DIR__.'/auth.php';