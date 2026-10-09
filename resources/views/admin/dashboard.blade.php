<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div><p>Tu restaurante, al día</p><h2>Resumen del día</h2><p class="text-sm mt-2">Así va la atención en Mr. Yuri.</p></div>
            <span class="flex items-center gap-2 text-sm text-gray-500 bg-white border border-gray-200 rounded-lg px-4 py-2"><x-icon name="clock" />{{ now('America/Lima')->format('d/m/Y') }}</span>
        </div>
    </x-slot>
    @php $initialSummary = ['ventasHoy' => (float)$ventasHoy, 'pedidosHoy' => $pedidosHoy, 'mesasOcupadas' => $mesasOcupadas, 'totalMesas' => $totalMesas, 'pedidosActivos' => $pedidosActivos, 'ventasSemana' => $ventasSemana, 'ultimosPedidos' => $ultimosPedidos->map(fn($order) => app(\App\Services\RestaurantSnapshot::class)->order($order, true))->values()]; @endphp
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-6" x-data="restaurantDashboard(@js($initialSummary))">
        <section class="dashboard-hero">
            <div><p class="eyebrow !text-orange-800">CADA MESA CUENTA</p><h2>Hola, {{ explode(' ', auth()->user()->name)[0] }}.<br>Que sea un gran día.</h2><p class="text-sm text-gray-600">Tu equipo, tus pedidos y tu carta. Todo listo para atender.</p><a href="{{ route('admin.pedidos.index') }}" class="ui-button mt-5">Gestionar pedidos <x-icon name="arrow" /></a></div>
            <svg class="hero-illustration" viewBox="0 0 180 180" fill="none" aria-hidden="true"><circle cx="90" cy="90" r="70" fill="#f8f4ed"/><circle cx="90" cy="90" r="49" stroke="currentColor" stroke-width="2"/><circle cx="90" cy="90" r="37" stroke="currentColor" stroke-width="1"/><path d="M23 45v27a8 8 0 0 0 8 8v55M23 45v20M31 45v20M39 45v27a8 8 0 0 1-8 8M151 45c-16 0-16 35 0 35v55" stroke="currentColor" stroke-width="3" stroke-linecap="round"/><path d="m73 93 12 12 25-28" stroke="#c85120" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </section>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            @foreach([
                ['live'=>'money(summary.ventasHoy)', 'label'=>'Ventas de hoy','value'=>'S/ '.number_format($ventasHoy,2),'note'=>'Pedidos pagados hoy','icon'=>'money'],
                ['live'=>'summary.pedidosHoy', 'label'=>'Pedidos de hoy','value'=>$pedidosHoy,'note'=>'Registrados durante el día','icon'=>'orders'],
                ['live'=>'summary.mesasOcupadas + ` / ` + summary.totalMesas', 'label'=>'Mesas ocupadas','value'=>$mesasOcupadas.' / '.$totalMesas,'note'=>($totalMesas - $mesasOcupadas).' mesas disponibles','icon'=>'table'],
                ['live'=>'summary.pedidosActivos', 'label'=>'Pedidos activos','value'=>$pedidosActivos,'note'=>'Pendientes, listos o entregados','icon'=>'clock'],
            ] as $stat)
                <div class="stat-card"><div class="flex justify-between items-center gap-3"><p class="text-sm text-gray-500">{{ $stat['label'] }}</p><span class="stat-icon"><x-icon :name="$stat['icon']" /></span></div><p class="stat-value" x-text="{{ $stat['live'] }}">{{ $stat['value'] }}</p><p class="text-xs text-gray-400" @if($stat['label'] === 'Mesas ocupadas') x-text="(summary.totalMesas - summary.mesasOcupadas) + ' mesas libres'" @endif>{{ $stat['note'] }}</p></div>
            @endforeach
        </div>
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <section class="panel xl:col-span-2">
                <div class="flex flex-wrap justify-between gap-3"><div><h3 class="text-lg font-bold">Ventas de la semana</h3><p class="text-xs text-gray-500 mt-1">Ingresos de pedidos pagados · Últimos 7 días</p></div><span class="text-lg font-bold" x-text="money(weekTotal)">S/ {{ number_format($ventasSemana->sum('ventas'),2) }}</span></div>
                <div class="sales-chart" role="img" :aria-label="'Ventas de los últimos siete días. Total: ' + money(weekTotal)">
                    <template x-for="(day, index) in summary.ventasSemana" :key="day.fecha"><div class="sales-column"><span class="sales-amount" x-text="money(day.ventas)"></span><div class="sales-track"><div class="sales-bar" :class="{ today: index === 6 }" :style="{ height: Math.max(2, day.ventas / weekMax * 100) + '%' }" :title="day.fecha + ': ' + money(day.ventas)"></div></div><span class="text-xs text-gray-500 mt-3" x-text="index === 6 ? 'Hoy' : day.fecha"></span></div></template>
                </div>
            </section>
            <section class="panel"><p class="eyebrow mb-3">A UN CLIC</p><h3 class="text-lg font-bold mb-5">Prepara tu servicio</h3>
                <a href="{{ route('admin.productos.index') }}" class="quick-link"><span class="stat-icon"><x-icon name="plate" /></span><span class="flex-1"><strong>Gestionar la carta</strong><small>Productos, precios y disponibilidad</small></span><x-icon name="arrow" /></a>
                <a href="{{ route('admin.categorias.index') }}" class="quick-link"><span class="stat-icon !bg-green-50 !text-green-700"><x-icon name="categories" /></span><span class="flex-1"><strong>Organizar categorías</strong><small>Una carta fácil de explorar</small></span><x-icon name="arrow" /></a>
            </section>
        </div>
        <section class="panel !p-0 overflow-hidden"><div class="p-5 flex justify-between items-center gap-4"><div><h3 class="text-lg font-bold">Últimos pedidos</h3><p class="text-xs text-gray-500 mt-1">La actividad más reciente de tu restaurante</p></div><a href="{{ route('admin.pedidos.index') }}" class="text-xs font-semibold text-orange-700">Ver activos →</a></div>
            <div class="empty-state" x-show="summary.ultimosPedidos.length === 0"><x-icon name="orders" /><strong>Todo listo para el primer pedido</strong><p class="text-sm">Los pedidos que registre tu equipo aparecerán aquí.</p></div>
            <div class="overflow-x-auto" x-show="summary.ultimosPedidos.length > 0"><table class="data-table"><thead><tr><th>PEDIDO</th><th>MESA</th><th>ATENDIDO POR</th><th>ESTADO</th><th>TOTAL</th><th></th></tr></thead><tbody><template x-for="order in summary.ultimosPedidos" :key="order.id"><tr><td class="font-semibold">#<span x-text="String(order.id).padStart(4,'0')"></span><small class="block text-gray-400 font-normal" x-text="order.fecha"></small></td><td>Mesa <span x-text="order.mesa_numero"></span></td><td x-text="order.mesero"></td><td><span class="status-badge" :class="'status-' + order.estado" x-text="$store.restaurant.stateLabel(order.estado)"></span></td><td class="font-semibold whitespace-nowrap" x-text="money(order.total)"></td><td><a :href="@js(url('/admin/pedidos')) + '/' + order.id" class="text-orange-700 text-xs font-semibold whitespace-nowrap">Ver detalle →</a></td></tr></template></tbody></table></div>
        </section>
        <p class="text-center text-xs text-gray-400 pt-3">Una buena atención, de principio a fin. · Mr. Yuri</p>
    </div>
</x-app-layout>
