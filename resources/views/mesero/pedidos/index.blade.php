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
        <div class="table-toolbar">
            <div class="table-zone-filters" role="group" aria-label="Filtrar mesas por zona">
                <button type="button" class="filter-chip" :class="{ selected: zone === 'all' }" :aria-pressed="zone === 'all'" @click="zone = 'all'">Todas</button>
                <button type="button" class="filter-chip" :class="{ selected: zone === 'interior' }" :aria-pressed="zone === 'interior'" @click="zone = 'interior'">Interior</button>
                <button type="button" class="filter-chip" :class="{ selected: zone === 'exterior' }" :aria-pressed="zone === 'exterior'" @click="zone = 'exterior'">Exterior</button>
            </div>
            <div class="table-toolbar-actions">
                <label for="estado-mesas" class="sr-only">Estado de la mesa</label>
                <select id="estado-mesas" class="search-input table-status-filter" x-model="status">
                    <option value="all">Todos los estados</option>
                    <option value="disponible">Disponibles</option>
                    <option value="ocupada">Ocupadas</option>
                    <option value="reservada">Reservadas</option>
                </select>
                <div x-data="{ addTableOpen: {{ $errors->has('zona') ? 'true' : 'false' }} }">
                    <button
                        type="button"
                        class="ui-button add-table-button"
                        @click="addTableOpen = true; $nextTick(() => $refs.tableZone.focus())"
                        :aria-expanded="addTableOpen"
                        aria-haspopup="dialog"
                    >
                        <x-icon name="plus" /> Agregar mesa
                    </button>
                    <div
                        x-show="addTableOpen"
                        x-cloak
                        x-transition.opacity
                        class="table-modal-backdrop"
                        @click.self="addTableOpen = false"
                        @keydown.escape.window="addTableOpen = false"
                    >
                        <section
                            role="dialog"
                            aria-modal="true"
                            aria-labelledby="add-table-title"
                            class="table-modal"
                            @click.stop
                        >
                            <div class="table-modal-heading">
                                <span class="stat-icon"><x-icon name="table" /></span>
                                <button type="button" class="table-modal-close" @click="addTableOpen = false" aria-label="Cerrar">×</button>
                            </div>
                            <h3 id="add-table-title" class="text-xl font-bold mt-4">Agregar una mesa</h3>
                            <p class="text-sm text-gray-500 mt-2">Se asignará automáticamente el siguiente número disponible.</p>
                            <form method="POST" action="{{ route('admin.mesero.mesas.store') }}" class="mt-5">
                                @csrf
                                <label for="nueva-mesa-zona" class="block text-sm font-semibold mb-2">¿En qué zona estará?</label>
                                <select
                                    id="nueva-mesa-zona"
                                    name="zona"
                                    x-ref="tableZone"
                                    class="search-input"
                                    required
                                >
                                    <option value="interior" @selected(old('zona', 'interior') === 'interior')>Interior</option>
                                    <option value="exterior" @selected(old('zona') === 'exterior')>Exterior</option>
                                </select>
                                @error('zona')
                                    <p class="text-sm text-red-700 mt-2" role="alert">{{ $message }}</p>
                                @enderror
                                <div class="table-modal-actions">
                                    <button type="button" class="ui-button secondary" @click="addTableOpen = false">Cancelar</button>
                                    <button type="submit" class="ui-button"><x-icon name="check" /> Crear mesa</button>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
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