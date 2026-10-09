@props(['producto', 'componentes'])
<div class="mixto-options" x-data="{ mixtoPrice: {{ $producto->precios_personalizacion[0] }}, mixtoComponents: @js($componentes->pluck('id')->map(fn($id) => (string)$id)->values()), mixtoError: '', mixtoAdded: false }">
    <fieldset>
        <legend class="text-sm font-semibold mb-3">Precio del mixto</legend>
        <div class="flex flex-wrap gap-2">
            @foreach($producto->precios_personalizacion as $precio)
                <label class="mixto-price" :class="{ selected: mixtoPrice === {{ $precio }} }">
                    <input type="radio" name="mixto-precio-{{ $producto->id }}" value="{{ $precio }}" x-model.number="mixtoPrice" class="sr-only">
                    S/ {{ number_format($precio, 2) }}
                </label>
            @endforeach
        </div>
    </fieldset>
    <fieldset class="mt-5">
        <legend class="text-base font-bold">¿Qué llevará el mixto?</legend>
        <p class="text-xs text-gray-500 mt-1 mb-3">Todos están seleccionados. Quita los que el cliente no desee.</p>
        <div class="grid grid-cols-2 gap-2">
            @foreach($componentes as $componente)
                <label class="mixto-component" :class="{ selected: mixtoComponents.includes('{{ $componente->id }}') }">
                    <input type="checkbox" value="{{ $componente->id }}" x-model="mixtoComponents" @change="mixtoAdded = false" class="rounded text-orange-600">
                    <span>{{ $componente->nombre }}</span>
                </label>
            @endforeach
        </div>
        @if($componentes->isEmpty())<p class="text-sm text-amber-800 bg-amber-50 p-3 rounded-lg">No hay componentes disponibles para armar el mixto.</p>@endif
    </fieldset>
    <p role="alert" class="text-sm text-red-700 mt-3" x-show="mixtoError" x-text="mixtoError" x-cloak></p>
    <p role="status" class="text-sm text-green-700 mt-3" x-show="mixtoAdded" x-cloak>Mixto agregado con los componentes seleccionados.</p>
    <button type="button" class="ui-button w-full mt-5" @disabled($componentes->isEmpty()) @click="mixtoError = addMixto({{ $producto->id }}, mixtoPrice, mixtoComponents); mixtoAdded = !mixtoError">
        <x-icon name="plus" />Agregar mixto <span x-text="money(mixtoPrice)"></span>
    </button>
</div>
