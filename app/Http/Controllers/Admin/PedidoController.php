<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PedidoController extends Controller
{
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

        $mesas = Mesa::orderBy('numero')->get();

        return view('admin.pedidos.index', compact(
            'pedidos',
            'mesas'
        ));
    }

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

    public function cambiarEstado(
        Request $request,
        Pedido $pedido
    ): RedirectResponse {

        $datos = $request->validate([
            'estado' => [
                'required',
                'in:pendiente,listo,entregado',
            ],
        ]);

        // No permitir cambiar estados de un pedido
        // que ya fue pagado o cancelado.
        if (in_array($pedido->estado, ['pagado', 'cancelado'])) {
            return back()->with(
                'error',
                'No se puede cambiar el estado de un pedido pagado o cancelado.'
            );
        }

        $pedido->update([
            'estado' => $datos['estado'],
        ]);

        return back()->with(
            'success',
            'Estado del pedido actualizado correctamente.'
        );
    }

    public function finalizarPago(
        Request $request,
        Pedido $pedido
    ): RedirectResponse {

        // No permitir pagar un pedido ya pagado o cancelado.
        if (in_array($pedido->estado, ['pagado', 'cancelado'])) {
            return back()->with(
                'error',
                'Este pedido ya fue pagado o cancelado.'
            );
        }

        $datos = $request->validate([
            'metodo_pago' => [
                'required',
                'in:efectivo,yape,plin,tarjeta',
            ],

            'monto_recibido' => [
                'nullable',
                'numeric',
                'min:0',
                'required_if:metodo_pago,efectivo',
            ],
        ]);

        $total = (float) $pedido->total;

        if ($datos['metodo_pago'] === 'efectivo') {

            $montoRecibido = (float) $datos['monto_recibido'];

            if ($montoRecibido < $total) {
                return back()
                    ->withErrors([
                        'monto_recibido' =>
                            'El monto recibido es insuficiente. Faltan S/ ' .
                            number_format(
                                $total - $montoRecibido,
                                2
                            )
                    ])
                    ->withInput();
            }

            $vuelto = round(
                $montoRecibido - $total,
                2
            );

        } else {

            $montoRecibido = null;
            $vuelto = null;
        }

        DB::transaction(function () use (
            $pedido,
            $datos,
            $montoRecibido,
            $vuelto
        ) {

            $pedido->update([
                'estado' => 'pagado',
                'metodo_pago' => $datos['metodo_pago'],
                'monto_recibido' => $montoRecibido,
                'vuelto' => $vuelto,
                'pagado_at' => now(),
            ]);

            $pedido->mesa->update([
                'estado' => 'disponible',
            ]);
        });

        return redirect()
            ->route('admin.pedidos.show', $pedido)
            ->with(
                'success',
                'Pedido pagado correctamente.'
            );
    }

    public function cancelar(Pedido $pedido): RedirectResponse
    {
        // No permitir cancelar un pedido
        // que ya fue pagado o cancelado.
        if (in_array($pedido->estado, ['pagado', 'cancelado'])) {
            return back()->with(
                'error',
                'Este pedido ya fue pagado o cancelado.'
            );
        }

        DB::transaction(function () use ($pedido) {

            $pedido->update([
                'estado' => 'cancelado',
            ]);

            $pedido->mesa->update([
                'estado' => 'disponible',
            ]);
        });

        return redirect()
            ->route('admin.pedidos.index')
            ->with(
                'success',
                'Pedido cancelado correctamente. La mesa está disponible nuevamente.'
            );
    }
}