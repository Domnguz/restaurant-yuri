<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between gap-4">

            <div>

                <p class="text-sm font-semibold text-gray-600">
                    Administración
                </p>

                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-gray-950">
                    Productos
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Productos organizados por categorías.
                </p>

            </div>

            <a
                href="{{ route('admin.productos.create') }}"
                class="shrink-0 w-11 h-11 rounded-xl bg-gray-900 text-white flex items-center justify-center text-2xl font-light shadow-sm hover:bg-gray-700 active:scale-95 transition"
            >
                +
            </a>

        </div>

    </x-slot>


    <div class="py-6">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- RESUMEN --}}
            <div class="bg-gray-900 rounded-2xl p-5 sm:p-6 text-white shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-300">
                            Productos registrados
                        </p>

                        <p class="mt-1 text-3xl font-extrabold">
                            {{ $productos->count() }}
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            {{ $categorias->count() }} categorías activas
                        </p>

                    </div>

                    <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">

                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 8c-3.314 0-6 1.343-6 3s2.686 3 6 3 6-1.343 6-3-2.686-3-6-3z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 11v5c0 1.657 2.686 3 6 3s6-1.343 6-3v-5"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 16c0 1.657 2.686 3 6 3s6-1.343 6-3"
                            />

                        </svg>

                    </div>

                </div>

            </div>


            {{-- CATEGORÍAS --}}
            <div class="mt-6 space-y-6">

                @foreach ($categorias as $categoria)

                    @if ($categoria->productos->count() > 0)

                        <section>

                            {{-- CABECERA DE CATEGORÍA --}}
                            <div class="flex items-center justify-between mb-3">

                                <div class="flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-xl bg-gray-900 text-white flex items-center justify-center">

                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M3 7h5l2 2h11v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"
                                            />
                                        </svg>

                                    </div>

                                    <div>

                                        <h3 class="text-lg font-extrabold text-gray-950">
                                            {{ $categoria->nombre }}
                                        </h3>

                                        <p class="text-xs text-gray-500">
                                            {{ $categoria->productos->count() }}
                                            {{ $categoria->productos->count() === 1 ? 'producto' : 'productos' }}
                                        </p>

                                    </div>

                                </div>

                            </div>


                            {{-- PRODUCTOS DE LA CATEGORÍA --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                                @foreach ($categoria->productos as $producto)

                                    <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm hover:shadow-md hover:border-gray-300 transition">

                                        {{-- PARTE SUPERIOR --}}
                                        <div class="flex items-start justify-between gap-3">

                                            <div class="flex items-center gap-3 min-w-0">

                                                <div class="w-11 h-11 shrink-0 rounded-xl bg-gray-100 flex items-center justify-center">

                                                    <svg
                                                        class="w-5 h-5 text-gray-600"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.8"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M12 3v18M5 8h14M5 16h14"
                                                        />
                                                    </svg>

                                                </div>

                                                <div class="min-w-0">

                                                    <h4 class="font-bold text-gray-950 truncate">
                                                        {{ $producto->nombre }}
                                                    </h4>

                                                    <p class="text-xs text-gray-500 mt-0.5">
                                                        {{ $categoria->nombre }}
                                                    </p>

                                                </div>

                                            </div>


                                            {{-- PRECIO --}}
                                            <div class="shrink-0 text-right">

                                                <p class="text-lg font-extrabold text-gray-950">
                                                    S/ {{ number_format($producto->precio, 2) }}
                                                </p>

                                            </div>

                                        </div>


                                        {{-- SEPARADOR --}}
                                        <div class="my-4 border-t border-gray-100"></div>


                                        {{-- PARTE INFERIOR --}}
                                        <div class="flex items-center justify-between gap-3">

                                            {{-- ESTADO --}}
                                            @if ($producto->activo)

                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold">

                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>

                                                    Disponible

                                                </span>

                                            @else

                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold">

                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>

                                                    Inactivo

                                                </span>

                                            @endif


                                            {{-- EDITAR --}}
                                            <a
                                                href="{{ route('admin.productos.edit', $producto) }}"
                                                class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-100 text-gray-700 text-xs font-bold hover:bg-gray-900 hover:text-white active:scale-95 transition"
                                            >

                                                <svg
                                                    class="w-4 h-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="2"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"
                                                    />

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"
                                                    />
                                                </svg>

                                                Editar

                                            </a>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </section>

                    @endif

                @endforeach


                {{-- PRODUCTOS SIN CATEGORÍA --}}
                @php
                    $productosSinCategoria = $productos->filter(function ($producto) {
                        return !$producto->categoria;
                    });
                @endphp


                @if ($productosSinCategoria->count() > 0)

                    <section>

                        <div class="flex items-center gap-3 mb-3">

                            <div class="w-10 h-10 rounded-xl bg-gray-200 text-gray-700 flex items-center justify-center">

                                <svg
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z"
                                    />
                                </svg>

                            </div>

                            <div>

                                <h3 class="text-lg font-extrabold text-gray-950">
                                    Sin categoría
                                </h3>

                                <p class="text-xs text-gray-500">
                                    {{ $productosSinCategoria->count() }}
                                    productos
                                </p>

                            </div>

                        </div>


                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                            @foreach ($productosSinCategoria as $producto)

                                <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">

                                    <div class="flex items-center justify-between gap-3">

                                        <div class="min-w-0">

                                            <h4 class="font-bold text-gray-950 truncate">
                                                {{ $producto->nombre }}
                                            </h4>

                                            <p class="text-sm text-gray-500 mt-1">
                                                Sin categoría
                                            </p>

                                        </div>

                                        <p class="text-lg font-extrabold text-gray-950">
                                            S/ {{ number_format($producto->precio, 2) }}
                                        </p>

                                    </div>

                                    <div class="mt-4 flex items-center justify-between">

                                        <span class="text-xs font-bold text-gray-500">
                                            Requiere organización
                                        </span>

                                        <a
                                            href="{{ route('admin.productos.edit', $producto) }}"
                                            class="px-3 py-2 rounded-xl bg-gray-100 text-gray-700 text-xs font-bold hover:bg-gray-900 hover:text-white transition"
                                        >
                                            Editar
                                        </a>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </section>

                @endif


                {{-- SIN PRODUCTOS --}}
                @if ($productos->count() === 0)

                    <div class="bg-white border border-gray-200 rounded-2xl p-8 sm:p-10 text-center shadow-sm">

                        <div class="w-16 h-16 mx-auto rounded-2xl bg-gray-100 flex items-center justify-center">

                            <svg
                                class="w-8 h-8 text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 3v18M5 8h14M5 16h14"
                                />
                            </svg>

                        </div>

                        <h3 class="mt-4 text-lg font-extrabold text-gray-950">
                            No hay productos
                        </h3>

                        <p class="mt-1 text-sm text-gray-600">
                            Todavía no tienes productos registrados.
                        </p>

                        <a
                            href="{{ route('admin.productos.create') }}"
                            class="inline-flex items-center justify-center mt-5 px-4 py-3 rounded-xl bg-gray-900 text-white text-sm font-bold hover:bg-gray-700 active:scale-95 transition"
                        >
                            + Crear producto
                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>