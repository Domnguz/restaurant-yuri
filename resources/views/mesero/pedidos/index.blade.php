<x-mesero-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-gray-500">
                Atención al cliente
            </p>

            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-gray-950">
                Mesas
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Selecciona una mesa para atender al cliente.
            </p>
        </div>
    </x-slot>


    <div class="min-h-screen bg-gray-50 py-6 pb-24">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Resumen --}}
            <div class="bg-gray-900 rounded-3xl p-5 sm:p-6 text-white shadow-sm">

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <p class="text-sm font-medium text-gray-300">
                            Mesas disponibles
                        </p>

                        <p class="mt-1 text-3xl font-extrabold">
                            {{ $mesas->where('estado', 'disponible')->count() }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-300">
                            Mesas ocupadas
                        </p>

                        <p class="mt-1 text-3xl font-extrabold">
                            {{ $mesas->where('estado', 'ocupada')->count() }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- Mesas --}}
            <div class="mt-6">

                <div class="flex items-center justify-between mb-4">

                    <div>
                        <h3 class="text-lg font-extrabold text-gray-950">
                            Mesas
                        </h3>

                        <p class="text-sm text-gray-500">
                            Selecciona una mesa para ver su pedido.
                        </p>
                    </div>

                </div>


                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                    @foreach ($mesas as $mesa)

                        @php
                            $pedido = $mesa->pedidos->first();
                        @endphp


                        {{-- MESA DISPONIBLE --}}
                        @if ($mesa->estado === 'disponible')

                            <a
                                href="{{ route('admin.mesero.pedidos.create', $mesa) }}"
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


                        {{-- MESA OCUPADA --}}
                        @else

                            <a
                                href="{{ route('admin.mesero.pedidos.agregar', $mesa) }}"
                                class="group bg-white border border-orange-200 rounded-3xl p-5 shadow-sm hover:shadow-md hover:border-orange-300 active:scale-[0.98] transition block"
                            >

                                {{-- Cabecera --}}
                                <div class="flex items-start justify-between">

                                    <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-2xl">
                                        🍽️
                                    </div>

                                    <span class="w-3 h-3 rounded-full bg-orange-500"></span>

                                </div>


                                {{-- Mesa --}}
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


                                {{-- Pedido actual --}}
                                @if ($pedido)

                                    <div class="mt-5 pt-4 border-t border-gray-100">

                                        <div class="flex items-center justify-between">

                                            <p class="text-sm font-bold text-gray-900">
                                                Pedido actual
                                            </p>

                                            <span class="text-xs font-bold text-orange-600">
                                                {{ ucfirst($pedido->estado) }}
                                            </span>

                                        </div>


                                        {{-- Productos --}}
                                        <div class="mt-3 space-y-3">

                                            @foreach ($pedido->detalles as $detalle)

                                                <div class="bg-gray-50 rounded-2xl p-3">

                                                    <div class="flex items-center justify-between">

                                                        <div>
                                                            @foreach ($detalle->productos as $producto)

                                                                <p class="text-sm font-bold text-gray-900">
                                                                    {{ $producto->nombre }}
                                                                </p>

                                                            @endforeach
                                                        </div>

                                                        <p class="text-sm font-extrabold text-gray-900">
                                                            S/ {{ number_format((float) $detalle->precio, 2) }}
                                                        </p>

                                                    </div>


                                                    {{-- Componentes del Mixto --}}
                                                    @if ($detalle->componentesMixto->count())

                                                        <div class="mt-2 flex flex-wrap gap-1">

                                                            @foreach ($detalle->componentesMixto as $componente)

                                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-white border border-gray-200 text-[11px] font-medium text-gray-600">
                                                                    {{ $componente->nombre }}
                                                                </span>

                                                            @endforeach

                                                        </div>

                                                    @endif


                                                    {{-- Observaciones --}}
                                                    @if ($detalle->observaciones)

                                                        <p class="mt-2 text-xs text-gray-500">
                                                            📝 {{ $detalle->observaciones }}
                                                        </p>

                                                    @endif

                                                </div>

                                            @endforeach

                                        </div>


                                        {{-- Total --}}
                                        <div class="mt-4 flex items-center justify-between">

                                            <span class="text-sm font-semibold text-gray-500">
                                                Total acumulado
                                            </span>

                                            <span class="text-xl font-extrabold text-gray-950">
                                                S/ {{ number_format((float) $pedido->total, 2) }}
                                            </span>

                                        </div>

                                    </div>

                                @endif


                                {{-- Botón --}}
                                <div class="mt-5">

                                    <span class="inline-flex w-full justify-center items-center px-4 py-3 rounded-2xl bg-orange-50 text-orange-700 text-sm font-bold">
                                        + Agregar pedido
                                    </span>

                                </div>

                            </a>

                        @endif

                    @endforeach

                </div>

            </div>

        </div>

    </div>

</x-mesero-layout>