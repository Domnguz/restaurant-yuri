<x-app-layout>
    <x-slot name="header"><div class="flex flex-wrap items-center justify-between gap-4"><div><p>Administración de la carta</p><h2>Productos</h2><p class="text-sm mt-2">Mantén tus precios y disponibilidad siempre al día.</p></div><a href="{{ route('admin.productos.create') }}" class="ui-button"><x-icon name="plus" />Nuevo producto</a></div></x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6" x-data="{ search: '', status: 'all', normalize(value) { return value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase(); }, matches(name, active) { return this.normalize(name).includes(this.normalize(this.search)) && (this.status === 'all' || (this.status === 'active' ? active : !active)); } }">
        <x-flash-messages />
        <div class="grid grid-cols-3 gap-3 mb-6">
            <div class="stat-card !p-4 sm:!p-5"><p class="text-xs sm:text-sm text-gray-500">Total de productos</p><p class="stat-value">{{ $productos->count() }}</p></div>
            <div class="stat-card !p-4 sm:!p-5"><p class="text-xs sm:text-sm text-gray-500">Activos</p><p class="stat-value !text-green-700">{{ $productos->where('activo',true)->count() }}</p></div>
            <div class="stat-card !p-4 sm:!p-5"><p class="text-xs sm:text-sm text-gray-500">Inactivos</p><p class="stat-value !text-gray-400">{{ $productos->where('activo',false)->count() }}</p></div>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 mb-6"><label for="buscar-productos" class="sr-only">Buscar productos</label><input id="buscar-productos" x-model="search" type="search" class="search-input flex-1" placeholder="Buscar por producto o categoría…"><label for="estado-productos" class="sr-only">Filtrar por estado</label><select id="estado-productos" x-model="status" class="search-input sm:!w-48"><option value="all">Todos los estados</option><option value="active">Activos</option><option value="inactive">Inactivos</option></select></div>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($productos as $producto)
                <article class="product-tile" x-show="matches(@js($producto->nombre.' '.$producto->categoria->nombre), {{ $producto->activo ? 'true' : 'false' }})">
                    <div class="flex justify-between items-start gap-4"><span class="stat-icon"><x-icon :name="str_contains(mb_strtolower($producto->categoria->nombre),'bebida') ? 'drink' : 'plate'" /></span><span class="status-badge {{ $producto->activo ? 'status-listo' : '' }}">{{ $producto->activo ? 'Activo' : 'Inactivo' }}</span></div>
                    <div><p class="eyebrow mb-2">{{ $producto->categoria->nombre }}</p><h3 class="text-lg font-bold">{{ $producto->nombre }}</h3>@unless($producto->categoria->activo)<p class="text-xs text-amber-700 mt-2">Categoría inactiva · Oculto en la carta</p>@endunless</div>
                    <div class="flex items-center justify-between gap-4 pt-4 border-t border-gray-100 mt-auto"><span class="text-xl font-bold">{{ $producto->personalizable ? 'Personalizable' : 'S/ '.number_format($producto->precio,2) }}</span><a href="{{ route('admin.productos.edit',$producto) }}" class="ui-button secondary !px-3 !min-h-10 !text-xs" aria-label="Editar {{ $producto->nombre }}">Editar producto</a></div>
                </article>
            @endforeach
        </div>
        @if($productos->isEmpty())<div class="panel empty-state"><x-icon name="plate" /><strong>Tu carta empieza aquí</strong><p class="text-sm">Crea una categoría y agrega tu primer producto.</p><a href="{{ route('admin.productos.create') }}" class="ui-button mt-5">Crear producto</a></div>@else
        <div class="panel empty-state mt-4" x-cloak x-show="![@foreach($productos as $producto){ name: @js($producto->nombre.' '.$producto->categoria->nombre), active: {{ $producto->activo ? 'true' : 'false' }} },@endforeach].some(p => matches(p.name,p.active))"><x-icon name="search" /><strong>No encontramos productos</strong><p class="text-sm">Prueba otra búsqueda o cambia el filtro de estado.</p></div>@endif
        <p class="text-xs text-gray-500 flex gap-2 items-center mt-6"><x-icon name="check" />Los productos activos de categorías activas están disponibles para tomar pedidos.</p>
    </div>
</x-app-layout>
