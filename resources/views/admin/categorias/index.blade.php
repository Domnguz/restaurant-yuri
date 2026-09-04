<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between gap-4">

            <div>

                <p class="text-sm font-semibold text-gray-600">
                    Administración
                </p>

                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-gray-950">
                    Categorías
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Organiza los productos del restaurante.
                </p>

            </div>

            <a
                href="{{ route('admin.categorias.create') }}"
                class="shrink-0 inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-gray-900 text-white text-sm font-bold shadow-sm hover:bg-gray-700 active:scale-95 transition"
            >
                <span class="text-lg leading-none">+</span>
                <span class="hidden sm:inline">Nueva categoría</span>
            </a>

        </div>

    </x-slot>


    <div class="py-6">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- Resumen --}}
            <div class="bg-gray-900 rounded-2xl p-5 sm:p-6 text-white shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-300">
                            Categorías registradas
                        </p>

                        <p class="mt-1 text-3xl font-extrabold">
                            {{ $categorias->count() }}
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
                                d="M3 7h5l2 2h11v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"
                            />
                        </svg>

                    </div>

                </div>

            </div>


            {{-- Lista --}}
            <div class="mt-5 space-y-3">

                @forelse ($categorias as $categoria)

                    <div class="bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-md transition">

                        <div class="flex items-center gap-4">


                            {{-- Icono --}}
                            <div class="w-12 h-12 shrink-0 rounded-xl bg-gray-100 flex items-center justify-center">

                                <svg
                                    class="w-6 h-6 text-gray-700"
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


                            {{-- Información --}}
                            <div class="min-w-0 flex-1">

                                <h3 class="font-bold text-gray-950 truncate">
                                    {{ $categoria->nombre }}
                                </h3>

                                <p class="mt-1 text-sm text-gray-600">
                                    Categoría de productos
                                </p>


                                {{-- Estado --}}
                                <div class="mt-3">

                                    @if ($categoria->activo)

                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold">

                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>

                                            Activa

                                        </span>

                                    @else

                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold">

                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>

                                            Inactiva

                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- Editar --}}
                            <a
                                href="{{ route('admin.categorias.edit', $categoria) }}"
                                class="shrink-0 inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-100 text-gray-700 text-sm font-bold hover:bg-gray-900 hover:text-white transition"
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

                                <span class="hidden sm:inline">
                                    Editar
                                </span>

                            </a>

                        </div>

                    </div>

                @empty

                    <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">

                        <div class="w-14 h-14 mx-auto rounded-2xl bg-gray-100 flex items-center justify-center">

                            <svg
                                class="w-7 h-7 text-gray-500"
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

                        <h3 class="mt-4 text-lg font-bold text-gray-950">
                            No hay categorías
                        </h3>

                        <p class="mt-1 text-sm text-gray-600">
                            Todavía no tienes categorías registradas.
                        </p>

                        <a
                            href="{{ route('admin.categorias.create') }}"
                            class="inline-flex items-center justify-center mt-5 px-4 py-3 rounded-xl bg-gray-900 text-white text-sm font-bold hover:bg-gray-700 active:scale-95 transition"
                        >
                            + Crear categoría
                        </a>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</x-app-layout>