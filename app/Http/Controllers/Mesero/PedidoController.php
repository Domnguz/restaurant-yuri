<?php

namespace App\Http\Controllers\Mesero;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\ComponenteMixto;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
            }
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

    public function create(Mesa $mesa): View
    {
        return $this->mostrarFormulario($mesa);
    }


    /*
    |--------------------------------------------------------------------------
    | Agregar productos a un pedido existente
    |--------------------------------------------------------------------------
    */

    public function agregar(Mesa $mesa): View
    {
        $pedido = $mesa->pedidos()
            ->whereNotIn('estado', ['pagado', 'cancelado'])
            ->latest()
            ->first();

        if (!$pedido) {
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
            ->with('categoria')
            ->orderBy('nombre')
            ->get();

        $categorias = Categoria::where('activo', true)
            ->orderBy('nombre')
            ->get();

        $componentesMixto = ComponenteMixto::where('activo', true)
            ->orderBy('id')
            ->get();

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
            'items' => ['required', 'array', 'min:1'],

            'items.*.producto_id' => [
                'required',
                'integer',
                'exists:productos,id'
            ],

            'items.*.precio' => [
                'required',
                'numeric',
                'min:0'
            ],

            'items.*.componente_ids' => [
                'nullable',
                'array'
            ],

            'items.*.componente_ids.*' => [
                'integer',
                'exists:componentes_mixtos,id'
            ],

            'items.*.observaciones' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'observaciones' => [
                'nullable',
                'string',
                'max:1000'
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

            /*
            |--------------------------------------------------------------------------
            | Buscar pedido activo
            |--------------------------------------------------------------------------
            */

            $pedido = $mesa->pedidos()
                ->whereNotIn('estado', ['pagado', 'cancelado'])
                ->latest()
                ->first();


            /*
            |--------------------------------------------------------------------------
            | Si NO existe pedido activo
            |--------------------------------------------------------------------------
            */

            if (!$pedido) {

                if ($mesa->estado !== 'disponible') {
                    throw new \Exception(
                        'La mesa está ocupada pero no tiene un pedido activo.'
                    );
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


                $detalle = $pedido->detalles()->create([
                    'precio' => $item['precio'],
                    'observaciones' => $item['observaciones'] ?? null,
                ]);


                $detalle->productos()->attach(
                    $producto->id
                );


                if (!empty($item['componente_ids'])) {

                    $detalle->componentesMixto()->attach(
                        $item['componente_ids']
                    );
                }


                $totalNuevo += (float) $item['precio'];
            }


            /*
            |--------------------------------------------------------------------------
            | Actualizar total acumulado
            |--------------------------------------------------------------------------
            */

            $pedido->update([
                'total' => (float) $pedido->total + $totalNuevo,
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

       try {

    logger()->info('PASO 1: Laravel va a avisar a Node', [
        'pedido_id' => $resultado->id,
        'mesa' => $resultado->mesa->numero,
    ]);

    $respuestaNode = Http::timeout(5)->post(
        'http://127.0.0.1:3001/api/pedidos/actualizado',
        [
            'pedido' => [
                'id' => $resultado->id,
                'mesa_id' => $resultado->mesa_id,
                'mesa_numero' => $resultado->mesa->numero,
                'zona' => $resultado->mesa->zona,
                'estado' => $resultado->estado,
                'total' => (float) $resultado->total,
                'mesero' => $resultado->usuario->name,
                'productos' => $resultado->detalles->count(),
                'hora' => $resultado->updated_at->format('H:i'),
            ]
        ]
    );

    logger()->info('PASO 2: Node respondió', [
        'status' => $respuestaNode->status(),
        'body' => $respuestaNode->body(),
    ]);

} catch (\Exception $e) {

    logger()->error('ERROR AL AVISAR A NODE', [
        'mensaje' => $e->getMessage(),
    ]);
}


        /*
        |--------------------------------------------------------------------------
        | Respuesta al mesero
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'message' => 'Pedido guardado correctamente.',
            'pedido_id' => $resultado->id,
        ]);
    }
}