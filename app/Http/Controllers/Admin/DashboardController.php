<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use App\Models\Pedido;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $inicioHoy = today();
        $finHoy = $inicioHoy->copy()->addDay();
        $ventasPorDia = Pedido::where('pagado_at', '>=', $inicioHoy->copy()->subDays(6))
            ->where('pagado_at', '<', $finHoy)
            ->where('estado', 'pagado')
            ->selectRaw('DATE(pagado_at) as fecha, SUM(total) as ventas')
            ->groupByRaw('DATE(pagado_at)')
            ->pluck('ventas', 'fecha');

        $ventasHoy = (float) ($ventasPorDia[$inicioHoy->format('Y-m-d')] ?? 0);

        $pedidosHoy = Pedido::where('created_at', '>=', $inicioHoy)
            ->where('created_at', '<', $finHoy)
            ->count();

        $mesasOcupadas = Mesa::where('estado', 'ocupada')
            ->count();

        $totalMesas = Mesa::count();

        $pedidosActivos = Pedido::whereNotIn('estado', ['pagado', 'cancelado'])->count();

        $ultimosPedidos = Pedido::with(['mesa', 'usuario', 'detalles.productos', 'detalles.componentesMixto'])
            ->latest()
            ->take(5)
            ->get();

        // Ventas de los últimos 7 días
        $ventasSemana = collect();

        for ($i = 6; $i >= 0; $i--) {
            $fecha = today()->subDays($i);

            $ventasSemana->push([
                'fecha' => $fecha->format('d/m'),
                'ventas' => (float) ($ventasPorDia[$fecha->format('Y-m-d')] ?? 0),
            ]);
        }

        return view('admin.dashboard', compact(
            'ventasHoy',
            'pedidosHoy',
            'mesasOcupadas',
            'totalMesas',
            'pedidosActivos',
            'ultimosPedidos',
            'ventasSemana'
        ));
    }
}
