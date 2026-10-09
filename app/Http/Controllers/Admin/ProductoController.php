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

        $categorias = Categoria::where('activo', true)
            ->with([
                'productos' => function ($query) {
                    $query->where('activo', true)
                        ->orderBy('nombre');
                },
            ])
            ->orderBy('nombre')
            ->get();

        return view('admin.productos.index', compact(
            'productos',
            'categorias'
        ));
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
        if ($request->boolean('precio_personalizado')) {
            $request->merge(['precio' => null]);
        }
        $datos = $request->validate([
            'categoria_id' => ['required', 'exists:categorias,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'precio' => ['nullable', 'required_unless:precio_personalizado,1', 'numeric', 'min:0', 'max:99999999.99'],
            'precio_personalizado' => ['nullable', 'boolean'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $datos['activo'] = $request->boolean('activo');
        unset($datos['precio_personalizado']);

        Producto::create($datos);

        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto): View
    {
        $categorias = Categoria::where('activo', true)
            ->orWhere('id', $producto->categoria_id)
            ->orderBy('nombre')
            ->get();

        return view('admin.productos.edit', compact(
            'producto',
            'categorias'
        ));
    }

    public function update(
        Request $request,
        Producto $producto
    ): RedirectResponse {
        if ($request->boolean('precio_personalizado')) {
            $request->merge(['precio' => null]);
        }
        $datos = $request->validate([
            'categoria_id' => ['required', 'exists:categorias,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'precio' => ['nullable', 'required_unless:precio_personalizado,1', 'numeric', 'min:0', 'max:99999999.99'],
            'precio_personalizado' => ['nullable', 'boolean'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $datos['activo'] = $request->boolean('activo');
        unset($datos['precio_personalizado']);

        $producto->update($datos);

        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }
}
