<x-mesero-layout>
    <x-slot name="header"><div><p>Atención al cliente</p><h2>Mesas del restaurante</h2><p class="text-sm mt-2">Selecciona la mesa, llena el pedido del cliente y envíalo al administrador.</p></div></x-slot>
    @php
        $tableData = $mesas->map(fn($table) => $table->only(['id','numero','zona','estado']))->values();
        $orderData = $mesas->flatMap(fn($table) => $table->pedidos->map(fn($order) => app(\App\Services\RestaurantSnapshot::class)->order($order)))->values();
    @endphp
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6" x-data="waiterTables(@js($tableData), @js($orderData), @js(url('/admin/mesero/pedidos')))"><x-flash-messages />
        <div class="grid grid-cols-3 gap-3 mb-6">
            <div class="stat-card !p-4"><p class="text-xs text-gray-500">Disponibles</p><p class="stat-value !text-green-700" x-text="available">{{ $mesas->where('estado','disponible')->count() }}</p></div>
            <div class="stat-card !p-4"><p class="text-xs text-gray-500">Ocupadas</p><p class="stat-value !text-orange-700" x-text="occupied">{{ $mesas->where('estado','ocupada')->count() }}</p></div>
            <div class="stat-card !p-4"><p class="text-xs text-gray-500">Total de mesas</p><p class="stat-value" x-text="tables.length">{{ $mesas->count() }}</p></div>
        </div>
        <div class="flex flex-wrap gap-3 items-center justify-between mb-6"><div class="flex gap-2"><button class="filter-chip" :class="{ selected: zone === 'all' }" @click="zone = 'all'">Todas</button><button class="filter-chip" :class="{ selected: zone === 'interior' }" @click="zone = 'interior'">Interior</button><button class="filter-chip" :class="{ selected: zone === 'exterior' }" @click="zone = 'exterior'">Exterior</button></div><label for="estado-mesas" class="sr-only">Estado de la mesa</label><select id="estado-mesas" class="search-input !w-auto" x-model="status"><option value="all">Todos los estados</option><option value="disponible">Disponibles</option><option value="ocupada">Ocupadas</option><option value="reservada">Reservadas</option></select></div>
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
            <template x-for="table in filtered" :key="table.id">
                <article class="panel !p-4 sm:!p-6" :data-table="table.numero" :class="{ '!border-orange-200 !bg-orange-50/30': table.estado === 'ocupada' }">
                    <div class="flex justify-between items-center mb-5"><span class="stat-icon" :class="{ '!bg-green-50 !text-green-700': table.estado === 'disponible' }"><x-icon name="table" /></span><span class="w-2 h-2 rounded-full" :class="table.estado === 'disponible' ? 'bg-green-600' : 'bg-orange-600'"></span></div>
                    <h3 class="text-xl sm:text-2xl font-bold">Mesa <span x-text="table.numero"></span></h3><p class="text-xs text-gray-500 capitalize mt-1" x-text="table.zona"></p><span class="status-badge mt-3 capitalize" :class="table.estado === 'disponible' ? 'status-listo' : 'status-pendiente'" x-text="table.estado"></span>
                    <div x-show="orderFor(table.id)"><p class="text-sm font-semibold mt-4" x-text="$store.restaurant.money(orderFor(table.id)?.total)"></p><p class="text-xs text-gray-500" x-text="(orderFor(table.id)?.productos || 0) + ' productos · ' + $store.restaurant.stateLabel(orderFor(table.id)?.estado)"></p></div>
                    <p x-show="!orderFor(table.id)" class="text-xs text-gray-500 mt-4" x-text="table.estado === 'disponible' ? 'Lista para una nueva atención' : 'Consulta con administración'"></p>
                    <a x-show="table.estado === 'disponible'" :href="baseUrl + '/' + table.id + '/crear'" class="ui-button w-full mt-5 !px-2 !text-xs sm:!text-sm">Tomar pedido</a>
                    <a x-show="orderFor(table.id)" :href="baseUrl + '/' + table.id + '/agregar'" class="ui-button secondary w-full mt-5 !px-2 !text-xs sm:!text-sm">Ver y agregar</a>
                </article>
            </template>
        </div>
        <div class="panel empty-state mt-4" x-show="filtered.length === 0"><x-icon name="table" /><strong>No hay mesas para este filtro</strong><p class="text-sm">Elige otra zona o estado de atención.</p></div>
    </div>
</x-mesero-layout>