@php $editing = isset($producto); @endphp
<x-app-layout>
    <x-slot name="header"><div><p>Administración de la carta</p><h2>{{ $editing ? 'Editar producto' : 'Nuevo producto' }}</h2><p class="text-sm mt-2">{{ $editing ? 'Actualiza los datos de tu producto.' : 'Agrega un nuevo sabor a tu carta.' }}</p></div></x-slot>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6">
        <a href="{{ route('admin.productos.index') }}" class="inline-block text-sm text-gray-500 mb-5">← Volver a productos</a>
        <x-flash-messages />
        <form method="POST" action="{{ $editing ? route('admin.productos.update',$producto) : route('admin.productos.store') }}" x-data="{ custom: {{ old('precio_personalizado', $editing && ($producto->precio === null || ($producto->personalizable && (float)$producto->precio <= 0))) ? 'true' : 'false' }} }">
            @csrf @if($editing) @method('PUT') @endif
            <section class="panel space-y-6">
                <div class="flex items-center gap-4 pb-5 border-b border-gray-100"><span class="stat-icon"><x-icon name="plate" /></span><div><h3 class="text-lg font-bold">Información del producto</h3><p class="text-xs text-gray-500">Estos datos se mostrarán a los trabajadores al tomar pedidos.</p></div></div>
                <div><label for="nombre" class="block text-sm font-semibold mb-2">Nombre del producto <span class="text-orange-700">*</span></label><input id="nombre" name="nombre" class="search-input" value="{{ old('nombre',$producto->nombre ?? '') }}" placeholder="Por ejemplo: Gaseosa personal" maxlength="255" required></div>
                <div><label for="categoria_id" class="block text-sm font-semibold mb-2">Categoría <span class="text-orange-700">*</span></label><select id="categoria_id" name="categoria_id" class="search-input" required><option value="">Selecciona una categoría</option>@foreach($categorias as $categoria)<option value="{{ $categoria->id }}" @selected(old('categoria_id',$producto->categoria_id ?? '') == $categoria->id)>{{ $categoria->nombre }}{{ $categoria->activo ? '' : ' (inactiva)' }}</option>@endforeach</select>@if($categorias->isEmpty())<p class="text-sm text-amber-700 mt-2">Necesitas <a href="{{ route('admin.categorias.create') }}" class="underline">crear una categoría</a> antes de guardar un producto.</p>@endif</div>
                <div><label class="flex gap-3 items-start"><input type="checkbox" name="precio_personalizado" value="1" x-model="custom" class="mt-1 rounded text-orange-600"><span><strong class="block text-sm font-semibold">Mixto personalizable</strong><small class="text-gray-500">Permite elegir precio y componentes al preparar el pedido.</small></span></label></div>
                <div x-show="!custom"><label for="precio" class="block text-sm font-semibold mb-2">Precio en soles (S/) <span class="text-orange-700">*</span></label><input type="number" id="precio" name="precio" :disabled="custom" :required="!custom" min="0" max="99999999.99" step="0.01" value="{{ old('precio',$producto->precio ?? '') }}" class="search-input" placeholder="0.00"></div>
                <div class="rounded-xl bg-gray-50 p-4"><input type="hidden" name="activo" value="0"><label class="flex items-start gap-3"><input type="checkbox" name="activo" value="1" @checked(old('activo',$producto->activo ?? true)) class="mt-1 rounded text-orange-600"><span><strong class="text-sm block font-semibold">Producto disponible</strong><small class="text-gray-500">Los trabajadores podrán agregarlo a los pedidos si su categoría también está activa.</small></span></label></div>
            </section>
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-5"><a href="{{ route('admin.productos.index') }}" class="ui-button secondary">Cancelar</a><button type="submit" class="ui-button" @disabled($categorias->isEmpty())><x-icon name="check" />{{ $editing ? 'Guardar cambios' : 'Crear producto' }}</button></div>
        </form>
    </div>
</x-app-layout>
