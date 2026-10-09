<?php

namespace App\Services;

use App\Models\Mesa;
use App\Models\Pedido;

class RestaurantSnapshot
{
    public function order(Pedido $pedido, bool $admin = false): array
    {
        $pedido->loadMissing(['mesa', 'usuario', 'detalles.productos', 'detalles.componentesMixto']);

        $data = [
            'id' => $pedido->id, 'mesa_id' => $pedido->mesa_id,
            'mesa_numero' => $pedido->mesa->numero, 'zona' => $pedido->mesa->zona,
            'estado' => $pedido->estado, 'total' => (float) $pedido->total,
            'mesero' => $pedido->usuario->name, 'productos' => $pedido->detalles->count(),
            'hora' => $pedido->updated_at->format('H:i'),
            'fecha' => $pedido->created_at->format('d/m · H:i'),
            'detalles' => $pedido->detalles->map(fn ($detalle) => [
                'id' => $detalle->id, 'nombre' => $detalle->productos->pluck('nombre')->join(' + '),
                'componentes' => $detalle->componentesMixto->pluck('nombre')->join(', '),
                'observaciones' => $detalle->observaciones, 'precio' => (float) $detalle->precio,
            ])->values()->all(),
        ];

        if ($admin) {
            $data['pago'] = [
                'metodo' => $pedido->metodo_pago, 'recibido' => $pedido->monto_recibido,
                'vuelto' => $pedido->vuelto, 'fecha' => $pedido->pagado_at?->format('d/m/Y · H:i'),
            ];
        }

        return $data;
    }

    public function get(bool $admin, ?int $focusedOrder = null): array
    {
        $orders = Pedido::with(['mesa', 'usuario', 'detalles.productos', 'detalles.componentesMixto'])
            ->whereNotIn('estado', ['pagado', 'cancelado'])->latest()->get();
        $focused = $focusedOrder ? ($orders->firstWhere('id', $focusedOrder) ?? Pedido::find($focusedOrder)) : null;
        $data = [
            'pedidos' => $orders->map(fn ($order) => $this->order($order, $admin))->values()->all(),
            'mesas' => Mesa::orderBy('numero')->get(['id', 'numero', 'zona', 'estado'])->toArray(),
            'pedido' => $focused ? $this->order($focused, $admin) : null,
        ];

        if ($admin) {
            $sales = Pedido::where('estado', 'pagado')->whereBetween('pagado_at', [today()->subDays(6), today()->endOfDay()])
                ->selectRaw('DATE(pagado_at) as fecha, SUM(total) as ventas')->groupByRaw('DATE(pagado_at)')->pluck('ventas', 'fecha');
            $week = collect(range(6, 0))->map(function ($offset) use ($sales) {
                $day = today()->subDays($offset);

                return ['fecha' => $day->format('d/m'), 'ventas' => (float) ($sales[$day->format('Y-m-d')] ?? 0)];
            });
            $data['resumen'] = [
                'ventasHoy' => (float) ($sales[today()->format('Y-m-d')] ?? 0),
                'pedidosHoy' => Pedido::whereDate('created_at', today())->count(),
                'mesasOcupadas' => collect($data['mesas'])->where('estado', 'ocupada')->count(),
                'totalMesas' => count($data['mesas']), 'pedidosActivos' => $orders->count(),
                'ventasSemana' => $week->all(),
                'ultimosPedidos' => Pedido::with(['mesa', 'usuario', 'detalles.productos', 'detalles.componentesMixto'])->latest()->take(5)->get()->map(fn ($order) => $this->order($order, true))->all(),
            ];
        }

        return $data;
    }
}
