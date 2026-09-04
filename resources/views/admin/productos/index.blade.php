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
                    Gestiona los productos y sus precios.
                </p>

            </div>

            <a
                href="{{ route('admin.productos.create') }}"
                class="shrink-0 w-11 h-11 rounded-xl bg-gray-900 text-white flex items-center justify-center text-2xl font-light shadow-sm active:scale-95 transition"
            >
                +
            </a>

        </div>

    </x-slot>


    <div class="py-6">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- Resumen --}}
            <div class="bg-gray-900 rounded-2xl p-5 text-white shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-300">
                            Productos registrados
                        </p>

                        <p class="mt-1 text-3xl font-extrabold">
                            {{ $productos->count() }}
                        </p>

                    </div>

                    <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">

                        <span class="text-xl">
                            🍽️
                        </span>

                    </div>

                </div>

            </div>


            {{-- Lista de productos --}}
            <div class="mt-5 space-y-3">

                @forelse ($productos as $producto)

                    <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">

                        <div class="flex items-center gap-4">


                            {{-- Icono --}}
                            <div class="w-12 h-12 shrink-0 rounded-xl bg-gray-100 flex items-center justify-center">

                                <span class="text-xl">
                                    🍽️
                                </span>

                            </div>


                            {{-- Información --}}
                            <div class="min-w-0 flex-1">

                                <div class="flex items-start justify-between gap-3">

                                    <div class="min-w-0">

                                        <h3 class="font-bold text-gray-950 truncate">
                                            {{ $producto->nombre }}
                                        </h3>

                                        <p class="mt-1 text-sm text-gray-600">
                                            {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                                        </p>

                                    </div>


                                    {{-- Precio --}}
                                    <div class="text-right shrink-0">

                                        <p class="text-lg font-extrabold text-gray-950">
                                            S/ {{ number_format($producto->precio, 2) }}
                                        </p>

                                    </div>

                                </div>


                                {{-- Parte inferior --}}
                                <div class="mt-3 flex items-center justify-between gap-3">


                                    {{-- Estado --}}
                                    @if ($producto->activo)

                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold">

                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>

                                            Activo

                                        </span>

                                    @else

                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold">

                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>

                                            Inactivo

                                        </span>

                                    @endif


                                    {{-- Editar --}}
                                    <a
                                        href="{{ route('admin.productos.edit', $producto) }}"
                                        class="inline-flex items-center gap-1 px-3 py-2 rounded-lg bg-gray-100 text-gray-800 text-sm font-bold hover:bg-gray-200 active:scale-95 transition"
                                    >
                                        Editar
                                        <span>→</span>
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">

                        <div class="w-14 h-14 mx-auto rounded-2xl bg-gray-100 flex items-center justify-center">

                            <span class="text-2xl">
                                🍽️
                            </span>

                        </div>

                        <h3 class="mt-4 text-lg font-bold text-gray-950">
                            No hay productos
                        </h3>

                        <p class="mt-1 text-sm text-gray-600">
                            Todavía no tienes productos registrados.
                        </p>

                        <a
                            href="{{ route('admin.productos.create') }}"
                            class="inline-flex items-center justify-center mt-5 px-4 py-3 rounded-xl bg-gray-900 text-white text-sm font-bold active:scale-95 transition"
                        >
                            + Crear producto
                        </a>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</x-app-layout>