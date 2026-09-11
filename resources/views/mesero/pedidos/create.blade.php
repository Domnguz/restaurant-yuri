<x-mesero-layout>

    <div
        x-data="pedidoForm()"
        class="min-h-screen bg-gray-50 py-6 pb-32"
    >

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Encabezado --}}
            <div class="flex items-center gap-3 mb-6">

                <a
                    href="{{ route('admin.mesero.pedidos.index') }}"
                    class="w-10 h-10 shrink-0 rounded-xl bg-gray-100 flex items-center justify-center text-gray-700 hover:bg-gray-200 transition"
                >
                    ←
                </a>

                <div class="min-w-0">

                    <p class="text-sm font-semibold text-gray-500">
                        Tomar pedido
                    </p>

                    <h2 class="mt-1 text-xl sm:text-2xl font-extrabold tracking-tight text-gray-950">
                        Mesa {{ $mesa->numero }}
                    </h2>

                </div>

            </div>


            {{-- Información de la mesa --}}
            <div class="bg-gray-900 rounded-3xl p-5 text-white shadow-sm">

                <div class="flex items-center justify-between gap-4">

                    <div>

                        <p class="text-sm text-gray-300">
                            Pedido para
                        </p>

                        <h3 class="mt-1 text-2xl font-extrabold">
                            Mesa {{ $mesa->numero }}
                        </h3>

                        <p class="mt-1 text-sm text-gray-400 capitalize">
                            {{ $mesa->zona }}
                        </p>

                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-2xl">
                        🍽️
                    </div>

                </div>

            </div>


            {{-- Categorías --}}
            <div class="mt-6">

                <div class="mb-4">

                    <p class="text-sm font-semibold text-gray-500">
                        Paso 1
                    </p>

                    <h3 class="mt-1 text-xl font-extrabold text-gray-950">
                        ¿Qué desea el cliente?
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Selecciona una categoría para ver sus productos.
                    </p>

                </div>


                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">

                    @foreach ($categorias as $categoria)

                        <button
                            type="button"
                            @click="seleccionarCategoria({{ $categoria->id }})"
                            class="text-left bg-white border rounded-2xl p-4 shadow-sm transition active:scale-[0.98]"
                            :class="categoriaSeleccionada === {{ $categoria->id }}
                                ? 'border-gray-900 ring-2 ring-gray-900'
                                : 'border-gray-200 hover:border-gray-400'"
                        >

                            <div
                                class="w-11 h-11 rounded-xl flex items-center justify-center text-xl"
                                :class="categoriaSeleccionada === {{ $categoria->id }}
                                    ? 'bg-gray-900 text-white'
                                    : 'bg-gray-100'"
                            >
                                🍽️
                            </div>

                            <p class="mt-3 font-extrabold text-gray-950">
                                {{ $categoria->nombre }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                Ver productos
                            </p>

                        </button>

                    @endforeach

                </div>

            </div>


            {{-- Productos --}}
            <div
                x-show="categoriaSeleccionada !== null"
                x-transition
                class="mt-8"
            >

                <div class="mb-4">

                    <p class="text-sm font-semibold text-gray-500">
                        Paso 2
                    </p>

                    <h3 class="mt-1 text-xl font-extrabold text-gray-950">
                        Productos
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Selecciona lo que pidió el cliente.
                    </p>

                </div>


                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                    @foreach ($productos as $producto)

                        <div
                            x-show="categoriaSeleccionada === {{ $producto->categoria_id }}"
                            class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm"
                        >

                            <div class="flex items-center justify-between gap-4">

                                <div class="min-w-0">

                                    <h4 class="font-extrabold text-gray-950">
                                        {{ $producto->nombre }}
                                    </h4>

                                    @if ($producto->precio !== null)

                                        <p class="mt-1 text-sm font-bold text-gray-600">
                                            S/ {{ number_format($producto->precio, 2) }}
                                        </p>

                                    @else

                                        <p class="mt-1 text-sm font-bold text-gray-600">
                                            Precio según selección
                                        </p>

                                    @endif

                                </div>


                                <button
                                    type="button"
                                    @click="agregarProducto(
                                        {{ $producto->id }},
                                        @js($producto->nombre),
                                        {{ $producto->precio !== null ? $producto->precio : 'null' }}
                                    )"
                                    class="shrink-0 px-4 py-2.5 rounded-xl bg-gray-900 text-white text-sm font-bold hover:bg-gray-800 active:scale-95 transition"
                                >
                                    + Agregar
                                </button>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>


            {{-- Personalización del Mixto --}}
            <div
                x-show="mixtoActivo"
                x-transition
                class="mt-8"
            >

                <div class="bg-white border border-gray-200 rounded-3xl p-5 shadow-sm">

                    <div>

                        <p class="text-sm font-semibold text-gray-500">
                            Personalización
                        </p>

                        <h3 class="mt-1 text-xl font-extrabold text-gray-950">
                            Configurar mixto
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Elige el precio y los componentes que llevará.
                        </p>

                    </div>


                    {{-- Precio --}}
                    <div class="mt-6">

                        <p class="text-sm font-extrabold text-gray-900">
                            Precio del mixto
                        </p>

                        <div class="mt-3 grid grid-cols-3 sm:grid-cols-6 gap-2">

                            <template
                                x-for="precio in preciosMixto"
                                :key="precio"
                            >

                                <button
                                    type="button"
                                    @click="precioMixto = precio"
                                    class="py-3 rounded-xl border text-sm font-extrabold transition"
                                    :class="precioMixto === precio
                                        ? 'bg-gray-900 text-white border-gray-900'
                                        : 'bg-white text-gray-700 border-gray-200 hover:border-gray-400'"
                                >
                                    S/<span x-text="precio"></span>
                                </button>

                            </template>

                        </div>

                    </div>


                    {{-- Componentes --}}
                    <div class="mt-6">

                        <p class="text-sm font-extrabold text-gray-900">
                            Componentes
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Todos están seleccionados inicialmente. Quita los que el cliente no quiera.
                        </p>


                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2">

                            @foreach ($componentesMixto as $componente)

                                <label
                                    class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50"
                                >

                                    <input
                                        type="checkbox"
                                        value="{{ $componente->id }}"
                                        x-model="componentesSeleccionados"
                                        class="w-5 h-5 rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                                    >

                                    <span class="text-sm font-bold text-gray-800">
                                        {{ $componente->nombre }}
                                    </span>

                                </label>

                            @endforeach

                        </div>

                    </div>


                    {{-- Observaciones del mixto --}}
                    <div class="mt-6">

                        <label
                            for="observaciones-mixto"
                            class="text-sm font-extrabold text-gray-900"
                        >
                            Observaciones
                        </label>

                        <textarea
                            id="observaciones-mixto"
                            x-model="observacionesMixto"
                            rows="3"
                            placeholder="Ejemplo: sin tollito..."
                            class="mt-3 w-full rounded-2xl border-gray-200 shadow-sm focus:border-gray-900 focus:ring-gray-900"
                        ></textarea>

                    </div>


                    <button
                        type="button"
                        @click="confirmarMixto()"
                        class="mt-5 w-full py-3.5 rounded-2xl bg-gray-900 text-white font-extrabold hover:bg-gray-800 active:scale-[0.99] transition"
                    >
                        Agregar mixto al pedido
                    </button>

                </div>

            </div>


            {{-- Pedido que se está preparando --}}
            <div
                x-show="pedido.length > 0"
                x-transition
                class="mt-8"
            >

                <div class="flex items-center justify-between mb-4">

                    <div>

                        <p class="text-sm font-semibold text-gray-500">
                            Paso 3
                        </p>

                        <h3 class="mt-1 text-xl font-extrabold text-gray-950">
                            Pedido actual
                        </h3>

                    </div>

                    <span
                        class="px-3 py-1 rounded-full bg-gray-900 text-white text-xs font-bold"
                        x-text="pedido.length + ' producto(s)'"
                    ></span>

                </div>


                <div class="space-y-3">

                    <template
                        x-for="(item, index) in pedido"
                        :key="index"
                    >

                        <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">

                            <div class="flex items-start justify-between gap-4">

                                <div class="min-w-0">

                                    <h4
                                        class="font-extrabold text-gray-950"
                                        x-text="item.nombre"
                                    ></h4>

                                    <p
                                        class="mt-1 text-sm font-bold text-gray-600"
                                        x-text="'S/ ' + Number(item.precio).toFixed(2)"
                                    ></p>


                                    <template x-if="item.componentes">

                                        <div class="mt-2">

                                            <p class="text-xs font-bold text-gray-500">
                                                Componentes:
                                            </p>

                                            <p
                                                class="mt-1 text-xs text-gray-600"
                                                x-text="item.componentes"
                                            ></p>

                                        </div>

                                    </template>


                                    <template x-if="item.observaciones">

                                        <p
                                            class="mt-2 text-xs text-gray-500"
                                            x-text="'Nota: ' + item.observaciones"
                                        ></p>

                                    </template>

                                </div>


                                <button
                                    type="button"
                                    @click="eliminarProducto(index)"
                                    class="shrink-0 w-9 h-9 rounded-xl bg-red-50 text-red-600 font-bold hover:bg-red-100 transition"
                                >
                                    ×
                                </button>

                            </div>

                        </div>

                    </template>

                </div>


                {{-- Total --}}
                <div class="mt-4 bg-gray-900 rounded-2xl p-5 text-white">

                    <div class="flex items-center justify-between">

                        <span class="text-sm text-gray-300">
                            Total
                        </span>

                        <span
                            class="text-2xl font-extrabold"
                            x-text="'S/ ' + total.toFixed(2)"
                        ></span>

                    </div>

                </div>

            </div>


            {{-- Observaciones generales --}}
            <div
                x-show="pedido.length > 0"
                x-transition
                class="mt-6"
            >

                <label
                    for="observaciones"
                    class="text-sm font-extrabold text-gray-900"
                >
                    Observaciones generales
                </label>

                <textarea
                    id="observaciones"
                    x-model="observaciones"
                    rows="3"
                    placeholder="Ejemplo: atender primero las bebidas..."
                    class="mt-3 w-full rounded-2xl border-gray-200 shadow-sm focus:border-gray-900 focus:ring-gray-900"
                ></textarea>

            </div>


            {{-- Botón enviar --}}
            <div
                x-show="pedido.length > 0"
                x-transition
                class="mt-6"
            >

                <button
                    type="button"
                    @click="enviarPedido()"
                    :disabled="enviando"
                    class="w-full py-4 rounded-2xl bg-gray-900 text-white font-extrabold shadow-sm hover:bg-gray-800 active:scale-[0.99] transition disabled:opacity-50 disabled:cursor-not-allowed"
                >

                    <span x-show="!enviando">
                        Enviar pedido
                    </span>

                    <span x-show="enviando">
                        Guardando pedido...
                    </span>

                </button>

            </div>

        </div>

    </div>


    <script>

        function pedidoForm() {

            return {

                categoriaSeleccionada: null,

                mixtoActivo: false,

                enviando: false,

                productoMixtoId: 6,

                preciosMixto: [7, 8, 9, 10, 11, 12],

                precioMixto: null,

                componentesSeleccionados: @js(
                    $componentesMixto
                        ->pluck('id')
                        ->map(fn ($id) => (string) $id)
                        ->values()
                ),

                observacionesMixto: '',

                observaciones: '',

                pedido: [],


                seleccionarCategoria(id) {

                    this.categoriaSeleccionada = id;

                    this.mixtoActivo = false;

                },


                agregarProducto(id, nombre, precio) {

                    if (id === this.productoMixtoId) {

                        this.mixtoActivo = true;

                        this.precioMixto = null;

                        this.componentesSeleccionados = @js(
                            $componentesMixto
                                ->pluck('id')
                                ->map(fn ($id) => (string) $id)
                                ->values()
                        );

                        this.observacionesMixto = '';

                        return;
                    }


                    this.pedido.push({

                        producto_id: id,

                        nombre: nombre,

                        precio: Number(precio),

                        componentes: null,

                        componente_ids: [],

                        observaciones: ''

                    });

                },


                confirmarMixto() {

                    if (this.precioMixto === null) {

                        alert('Selecciona el precio del mixto.');

                        return;

                    }


                    if (this.componentesSeleccionados.length === 0) {

                        alert('Selecciona al menos un componente.');

                        return;

                    }


                    const nombresComponentes = @js(
                        $componentesMixto->pluck('nombre', 'id')
                    );


                    const nombres = this.componentesSeleccionados
                        .map(id => nombresComponentes[id])
                        .filter(Boolean);


                    this.pedido.push({

                        producto_id: this.productoMixtoId,

                        nombre: 'Mixto',

                        precio: Number(this.precioMixto),

                        componentes: nombres.join(', '),

                        componente_ids: this.componentesSeleccionados,

                        observaciones: this.observacionesMixto

                    });


                    this.mixtoActivo = false;

                    this.precioMixto = null;

                    this.observacionesMixto = '';

                },


                eliminarProducto(index) {

                    this.pedido.splice(index, 1);

                },


                async enviarPedido() {

                    if (this.pedido.length === 0) {

                        alert('Agrega al menos un producto.');

                        return;

                    }


                    this.enviando = true;


                    try {

                        const respuesta = await fetch(
                            '{{ route('admin.mesero.pedidos.store', $mesa) }}',
                            {
                                method: 'POST',

                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },

                                body: JSON.stringify({

                                    items: this.pedido,

                                    observaciones: this.observaciones

                                })
                            }
                        );


                        const datos = await respuesta.json();


                        if (!respuesta.ok) {

                            throw new Error(
                                datos.message ?? 'No se pudo guardar el pedido.'
                            );

                        }


                        alert('Pedido enviado correctamente.');


                        window.location.href =
                            '{{ route('admin.mesero.pedidos.index') }}';

                    } catch (error) {

                        console.error(error);

                        alert(error.message);

                        this.enviando = false;

                    }

                },


                get total() {

                    return this.pedido.reduce(
                        (total, item) => total + Number(item.precio),
                        0
                    );

                }

            };

        }

    </script>

</x-mesero-layout>