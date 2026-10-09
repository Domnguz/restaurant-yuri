<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Services\RestaurantRealtime;
use App\Services\RestaurantSnapshot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function actualizaciones(Request $request, RestaurantSnapshot $snapshot): JsonResponse
    {
        $data = $request->validate(['pedido' => ['nullable', 'integer', 'min:1']]);

        return response()->json($snapshot->get(true, $data['pedido'] ?? null))->header('Cache-Control', 'no-store, private');
    }

    public function index(): View
    {
        $pedidos = Pedido::with(['mesa', 'usuario', 'detalles.productos', 'detalles.componentesMixto'])->whereNotIn('estado', ['pagado', 'cancelado'])->latest()->get();
        $mesas = Mesa::orderBy('numero')->get();

        return view('admin.pedidos.index', compact('pedidos', 'mesas'));
    }

    public function show(Pedido $pedido): View
    {
        $pedido->load(['mesa', 'usuario', 'detalles.productos', 'detalles.componentesMixto']);

        return view('admin.pedidos.show', compact('pedido'));
    }

    public function cambiarEstado(Request $request, Pedido $pedido): JsonResponse|RedirectResponse
    {
        $data = $request->validate(['estado' => ['required', 'in:pendiente,listo,entregado'], 'total_revisado' => ['nullable', 'numeric', 'min:0']]);
        $pedido = $this->change($pedido, function ($order) use ($data) {
            $this->checkReviewedTotal($order, $data);
            $order->update(['estado' => $data['estado']]);
        }, 'state_changed');

        return $this->respond($request, $pedido, 'Estado del pedido actualizado correctamente.');
    }

    public function finalizarPago(Request $request, Pedido $pedido): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'metodo_pago' => ['required', 'in:efectivo,yape,plin,tarjeta'],
            'monto_recibido' => ['nullable', 'numeric', 'min:0', 'required_if:metodo_pago,efectivo'],
            'total_revisado' => ['nullable', 'numeric', 'min:0'],
        ]);
        $pedido = $this->change($pedido, function ($order) use ($data) {
            $this->checkReviewedTotal($order, $data);
            $received = $data['metodo_pago'] === 'efectivo' ? (float) $data['monto_recibido'] : null;
            if ($received !== null && $received < (float) $order->total) {
                throw ValidationException::withMessages(['monto_recibido' => 'El monto recibido es insuficiente. Faltan S/ '.number_format((float) $order->total - $received, 2)]);
            }
            $order->update([
                'estado' => 'pagado', 'metodo_pago' => $data['metodo_pago'], 'monto_recibido' => $received,
                'vuelto' => $received !== null ? round($received - (float) $order->total, 2) : null, 'pagado_at' => now(),
            ]);
            $order->mesa->update(['estado' => 'disponible']);
        }, 'paid');

        return $this->respond($request, $pedido, 'Pedido pagado correctamente.');
    }

    public function cancelar(Request $request, Pedido $pedido): JsonResponse|RedirectResponse
    {
        $pedido = $this->change($pedido, function ($order) {
            $order->update(['estado' => 'cancelado']);
            $order->mesa->update(['estado' => 'disponible']);
        }, 'cancelled');

        return $this->respond($request, $pedido, 'Pedido cancelado correctamente. La mesa está disponible nuevamente.', true);
    }

    private function change(Pedido $pedido, callable $update, string $action): Pedido
    {
        // Lock table before order, in the same order as a waiter submission.
        $result = DB::transaction(function () use ($pedido, $update) {
            Mesa::whereKey($pedido->mesa_id)->lockForUpdate()->firstOrFail();
            $order = Pedido::whereKey($pedido->id)->lockForUpdate()->firstOrFail();
            if (in_array($order->estado, ['pagado', 'cancelado'])) {
                throw ValidationException::withMessages(['pedido' => 'Este pedido ya fue pagado o cancelado.']);
            }
            $update($order);

            return $order;
        });
        app(RestaurantRealtime::class)->publish($result, $action);

        return $result;
    }

    private function checkReviewedTotal(Pedido $pedido, array $data): void
    {
        if (isset($data['total_revisado']) && round((float) $data['total_revisado'], 2) !== round((float) $pedido->total, 2)) {
            throw ValidationException::withMessages(['pedido' => 'El mesero modificó el pedido. Revisa el nuevo total antes de continuar.']);
        }
    }

    private function respond(Request $request, Pedido $pedido, string $message, bool $toBoard = false): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message, 'pedido' => app(RestaurantSnapshot::class)->order($pedido, true)]);
        }

        return redirect()->route($toBoard ? 'admin.pedidos.index' : 'admin.pedidos.show', $toBoard ? [] : $pedido)->with('success', $message);
    }
}
