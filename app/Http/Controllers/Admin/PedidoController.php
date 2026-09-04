<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PedidoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Lista de pedidos
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $pedidos = Pedido::with([
            'mesa',
            'usuario',
            'detalles.productos',
            'detalles.componentesMixto',
        ])
        ->whereNotIn('estado', ['pagado', 'cancelado'])
        ->latest()
        ->get();

        $mesas = \App\Models\Mesa::orderBy('numero')->get();

        return view('admin.pedidos.index', compact('pedidos', 'mesas'));
    }


    /*
    |--------------------------------------------------------------------------
    | Ver pedido
    |--------------------------------------------------------------------------
    */

    public function show(Pedido $pedido): View
    {
        $pedido->load([
            'mesa',
            'usuario',
            'detalles.productos',
            'detalles.componentesMixto',
        ]);

        return view('admin.pedidos.show', compact('pedido'));
    }


    /*
    |--------------------------------------------------------------------------
    | Cambiar estado
    |--------------------------------------------------------------------------
    */

public function cambiarEstado(
    Request $request,
    Pedido $pedido
): RedirectResponse {

    $datos = $request->validate([
        'estado' => [
            'required',
            'in:pendiente,preparando,listo,entregado,pagado,cancelado',
        ],
    ]);

    $pedido->update([
        'estado' => $datos['estado'],
    ]);

    return back()->with('success', 'Estado del pedido actualizado correctamente.');
}
    /*
    |--------------------------------------------------------------------------
    | Finalizar pago
    |--------------------------------------------------------------------------
    */
public function finalizarPago(
    Request $request,
    Pedido $pedido
): RedirectResponse {

    $datos = $request->validate([
        'metodo_pago' => [
            'required',
            'in:efectivo,yape,plin,tarjeta',
        ],

        'monto_recibido' => [
            'nullable',
            'numeric',
            'min:0',
        ],
    ]);

    DB::transaction(function () use ($datos, $pedido) {

        $total = (float) $pedido->total;
        $montoRecibido = isset($datos['monto_recibido'])
            ? (float) $datos['monto_recibido']
            : null;

        // Si paga en efectivo, debe entregar suficiente dinero
        if ($datos['metodo_pago'] === 'efectivo') {

            if ($montoRecibido === null || $montoRecibido < $total) {
                throw new \Exception(
                    'El monto recibido es insuficiente para pagar el pedido.'
                );
            }

            $vuelto = $montoRecibido - $total;

        } else {

            // Para Yape, Plin o tarjeta no necesitamos vuelto
            $montoRecibido = null;
            $vuelto = null;
        }

        $pedido->update([
            'estado' => 'pagado',
            'metodo_pago' => $datos['metodo_pago'],
            'monto_recibido' => $montoRecibido,
            'vuelto' => $vuelto,
            'pagado_at' => now(),
        ]);

        // Liberamos la mesa
        $pedido->mesa->update([
            'estado' => 'disponible',
        ]);
    });

    return redirect()
        ->route('admin.pedidos.show', $pedido)
        ->with('success', 'Pedido pagado correctamente.');
}
}