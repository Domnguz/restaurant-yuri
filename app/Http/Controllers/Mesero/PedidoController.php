<?php

namespace App\Http\Controllers\Mesero;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\ComponenteMixto;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use App\Services\RestaurantRealtime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function index(): View
    {
        $mesas = Mesa::with([
            'pedidos' => function ($query) {
                $query->whereNotIn('estado', ['pagado', 'cancelado'])
                    ->with([
                        'detalles.productos',
                        'detalles.componentesMixto',
                    ])
                    ->latest();
            },
        ])
            ->orderBy('numero')
            ->get();

        return view('mesero.pedidos.index', compact('mesas'));
    }

    /*
    |--------------------------------------------------------------------------
    | Crear primer pedido
    |--------------------------------------------------------------------------
    */

    public function create(Mesa $mesa): View|RedirectResponse
    {
        if ($mesa->pedidos()->whereNotIn('estado', ['pagado', 'cancelado'])->exists()) {
            return redirect()->route('admin.mesero.pedidos.agregar', $mesa);
        }

        return $this->mostrarFormulario($mesa);
    }

    /*
    |--------------------------------------------------------------------------
    | Agregar productos a un pedido existente
    |--------------------------------------------------------------------------
    */

    public function agregar(Mesa $mesa): View|RedirectResponse
    {
        $pedido = $mesa->pedidos()
            ->whereNotIn('estado', ['pagado', 'cancelado'])
            ->latest()
            ->first();

        if (! $pedido) {
            return redirect()
                ->route('admin.mesero.pedidos.index')
                ->with('error', 'Esta mesa no tiene un pedido activo.');
        }

        return $this->mostrarFormulario($mesa, $pedido);
    }

    /*
    |--------------------------------------------------------------------------
    | Mostrar formulario de productos
    |--------------------------------------------------------------------------
    */

    private function mostrarFormulario(
        Mesa $mesa,
        ?Pedido $pedido = null
    ): View {

        $productos = Producto::where('activo', true)
            ->whereHas('categoria', fn ($query) => $query->where('activo', true))
            ->with('categoria')
            ->orderBy('nombre')
            ->get();

        $categorias = Categoria::where('activo', true)
            ->orderBy('nombre')
            ->get();

        $componentesMixto = ComponenteMixto::where('activo', true)
            ->orderBy('id')
            ->get();

        $pedido?->loadMissing(['detalles.productos', 'detalles.componentesMixto']);

        return view('mesero.pedidos.create', compact(
            'mesa',
            'productos',
            'categorias',
            'componentesMixto',
            'pedido'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Guardar pedido / agregar productos
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, Mesa $mesa): JsonResponse
    {
        $datos = $request->validate([
            'pedido_id' => ['nullable', 'integer', 'min:1'],
            'items' => ['required', 'array', 'min:1', 'max:100'],

            'items.*.producto_id' => [
                'required',
                'integer',
                'exists:productos,id',
            ],

            'items.*.precio' => [
                'required',
                'numeric',
                'min:0',
            ],

            'items.*.componente_ids' => [
                'nullable',
                'array',
            ],

            'items.*.componente_ids.*' => [
                'integer',
                'exists:componentes_mixtos,id',
            ],

            'items.*.observaciones' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'observaciones' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Guardar todo en la base de datos
        |--------------------------------------------------------------------------
        */

        $resultado = DB::transaction(function () use (
            $datos,
            $mesa,
            $request
        ) {

            $mesa = Mesa::whereKey($mesa->id)->lockForUpdate()->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Buscar pedido activo
            |--------------------------------------------------------------------------
            */

            $pedido = $mesa->pedidos()
                ->whereNotIn('estado', ['pagado', 'cancelado'])
                ->latest()
                ->first();

            if (isset($datos['pedido_id']) && (! $pedido || $pedido->id !== (int) $datos['pedido_id'])) {
                throw ValidationException::withMessages(['pedido' => 'Este pedido ya fue cerrado o cancelado. Vuelve a mesas para iniciar una nueva atención.']);
            }

            /*
            |--------------------------------------------------------------------------
            | Si NO existe pedido activo
            |--------------------------------------------------------------------------
            */

            if (! $pedido) {

                if ($mesa->estado !== 'disponible') {
                    throw ValidationException::withMessages(['mesa' => 'La mesa no está disponible. Actualiza la lista de mesas.']);
                }

                $pedido = Pedido::create([
                    'mesa_id' => $mesa->id,
                    'user_id' => $request->user()->id,
                    'estado' => 'pendiente',
                    'total' => 0,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Agregar productos
            |--------------------------------------------------------------------------
            */

            $totalNuevo = 0;

            foreach ($datos['items'] as $item) {

                $producto = Producto::findOrFail(
                    $item['producto_id']
                );

                if (! $producto->activo || ! $producto->categoria->activo) {
                    throw ValidationException::withMessages(['items' => 'Un producto ya no está disponible. Actualiza la carta.']);
                }

                if ($producto->personalizable) {
                    $precio = (float) $item['precio'];
                    $precios = array_map('floatval', $producto->precios_personalizacion);
                    if (! in_array($precio, $precios, true)) {
                        throw ValidationException::withMessages(['items' => 'Selecciona un precio válido para el mixto.']);
                    }

                    $componentes = array_values(array_unique($item['componente_ids'] ?? []));
                    if (empty($componentes) || ComponenteMixto::where('activo', true)->whereIn('id', $componentes)->count() !== count($componentes)) {
                        throw ValidationException::withMessages(['items' => 'Selecciona al menos un componente disponible para el mixto.']);
                    }
                } else {
                    $precio = (float) $producto->precio;
                    if (! empty($item['componente_ids'])) {
                        throw ValidationException::withMessages(['items' => 'Los componentes solo se pueden agregar a un mixto.']);
                    }
                }

                $notas = array_filter([$item['observaciones'] ?? null, $datos['observaciones'] ?? null]);

                $detalle = $pedido->detalles()->create([
                    'precio' => $precio,
                    'observaciones' => $notas ? implode(' · ', $notas) : null,
                ]);

                $detalle->productos()->attach(
                    $producto->id
                );

                if (! empty($item['componente_ids'])) {

                    $detalle->componentesMixto()->attach(
                        $item['componente_ids']
                    );
                }

                $totalNuevo += $precio;
            }

            /*
            |--------------------------------------------------------------------------
            | Actualizar total acumulado
            |--------------------------------------------------------------------------
            */

            $pedido->update([
                'total' => (float) $pedido->total + $totalNuevo,
                'estado' => 'pendiente',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Marcar mesa como ocupada
            |--------------------------------------------------------------------------
            */

            $mesa->update([
                'estado' => 'ocupada',
            ]);

            return $pedido;
        });

        /*
        |--------------------------------------------------------------------------
        | Cargar datos completos del pedido
        |--------------------------------------------------------------------------
        */

        $resultado->load([
            'mesa',
            'usuario',
            'detalles.productos',
            'detalles.componentesMixto',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Avisar a Node.js que el pedido cambió
        |--------------------------------------------------------------------------
        */

        app(RestaurantRealtime::class)->publish($resultado, $resultado->wasRecentlyCreated ? 'created' : 'items_added');

        /*
        |--------------------------------------------------------------------------
        | Respuesta al mesero
        |--------------------------------------------------------------------------
        */

        $request->session()->flash('success', 'Pedido #'.$resultado->id.' enviado al administrador correctamente.');

        return response()->json([
            'message' => 'Pedido enviado al administrador correctamente.',
            'pedido_id' => $resultado->id,
        ]);
    }
}
