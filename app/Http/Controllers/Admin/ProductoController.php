<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductoController extends Controller
{
    public function index(): View
    {
        $productos = Producto::with('categoria')
            ->orderBy('nombre')
            ->get();

        return view('admin.productos.index', compact('productos'));
    }

    public function create(): View
    {
        $categorias = Categoria::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('admin.productos.create', compact('categorias'));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'categoria_id' => ['required', 'exists:categorias,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'precio' => ['required', 'numeric', 'min:0'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $datos['activo'] = $request->boolean('activo');

        Producto::create($datos);

        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto): View
    {
        $categorias = Categoria::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('admin.productos.edit', compact('producto', 'categorias'));
    }
    public function update(Request $request, Producto $producto): RedirectResponse
{
    $datos = $request->validate([
        'categoria_id' => ['required', 'exists:categorias,id'],
        'nombre' => ['required', 'string', 'max:255'],
        'precio' => ['required', 'numeric', 'min:0'],
        'activo' => ['nullable', 'boolean'],
    ]);

    $datos['activo'] = $request->boolean('activo');

    $producto->update($datos);

    return redirect()
        ->route('admin.productos.index')
        ->with('success', 'Producto actualizado correctamente.');
}
}