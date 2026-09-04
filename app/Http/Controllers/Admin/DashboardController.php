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
        $ventasHoy = Pedido::whereDate('created_at', today())
            ->where('estado', 'pagado')
            ->sum('total');

        $pedidosHoy = Pedido::whereDate('created_at', today())
            ->count();

        $mesasOcupadas = Mesa::where('estado', 'ocupada')
            ->count();

        $totalMesas = Mesa::count();

        $ultimosPedidos = Pedido::with(['mesa', 'usuario'])
            ->latest()
            ->take(5)
            ->get();

        // Ventas de los últimos 7 días
        $ventasSemana = collect();

        for ($i = 6; $i >= 0; $i--) {
            $fecha = today()->subDays($i);

            $ventas = Pedido::whereDate('pagado_at', $fecha)
                ->where('estado', 'pagado')
                ->sum('total');

            $ventasSemana->push([
                'fecha' => $fecha->format('d/m'),
                'ventas' => (float) $ventas,
            ]);
        }

        return view('admin.dashboard', compact(
            'ventasHoy',
            'pedidosHoy',
            'mesasOcupadas',
            'totalMesas',
            'ultimosPedidos',
            'ventasSemana'
        ));
    }
}