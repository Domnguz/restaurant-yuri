<x-app-layout>

    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-gray-500">
                Atención al cliente
            </p>

            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-gray-950">
                Nuevo pedido
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Selecciona una mesa para comenzar.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-6 pb-24">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Resumen --}}
            <div class="bg-gray-900 rounded-3xl p-5 sm:p-6 text-white shadow-sm">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm font-medium text-gray-300">
                            Mesas disponibles
                        </p>

                        <p class="mt-1 text-3xl font-extrabold">
                            {{ $mesas->where('estado', 'disponible')->count() }}
                        </p>
                    </div>

                    <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-2xl">
                        🪑
                    </div>

                </div>

            </div>


            {{-- Mesas --}}
            <div class="mt-6">

                <div class="flex items-center justify-between mb-4">

                    <div>
                        <h3 class="text-lg font-extrabold text-gray-950">
                            Selecciona una mesa
                        </h3>

                        <p class="text-sm text-gray-500">
                            Toca una mesa disponible para continuar.
                        </p>
                    </div>

                </div>


                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">

                    @foreach ($mesas as $mesa)

                        @if ($mesa->estado === 'disponible')

                            <a
                               href="{{ route('admin.mesero.pedidos.create', $mesa)}}"
                                class="group bg-white border border-gray-200 rounded-3xl p-5 shadow-sm hover:shadow-md hover:border-gray-300 active:scale-[0.98] transition"
                            >

                                <div class="flex items-start justify-between">

                                    <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-2xl">
                                        🪑
                                    </div>

                                    <span class="w-3 h-3 rounded-full bg-green-500"></span>

                                </div>

                                <div class="mt-5">

                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                        Mesa
                                    </p>

                                    <h4 class="mt-1 text-2xl font-extrabold text-gray-950">
                                        {{ $mesa->numero }}
                                    </h4>

                                    <p class="mt-1 text-sm text-gray-500 capitalize">
                                        {{ $mesa->zona }}
                                    </p>

                                </div>

                                <div class="mt-4">

                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-green-50 text-green-700 text-xs font-bold">
                                        Disponible
                                    </span>

                                </div>

                            </a>

@else

    <a
        href="{{ route('admin.mesero.pedidos.agregar', $mesa) }}"
        class="group bg-white border border-gray-200 rounded-3xl p-5 shadow-sm hover:shadow-md hover:border-gray-300 active:scale-[0.98] transition block"
    >

        <div class="flex items-start justify-between">

            <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-2xl">
                🍽️
            </div>

            <span class="w-3 h-3 rounded-full bg-orange-500"></span>

        </div>

        <div class="mt-5">

            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                Mesa
            </p>

            <h4 class="mt-1 text-2xl font-extrabold text-gray-950">
                {{ $mesa->numero }}
            </h4>

            <p class="mt-1 text-sm text-gray-500 capitalize">
                {{ $mesa->zona }}
            </p>

        </div>

        <div class="mt-4">

            <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-orange-50 text-orange-700 text-xs font-bold">
                Agregar al pedido
            </span>

        </div>

    </a>

@endif

                    @endforeach

                </div>

            </div>

        </div>

    </div>

</x-app-layout>