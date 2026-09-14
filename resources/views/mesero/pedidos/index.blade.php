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


        {{-- =========================
            RESUMEN
        ========================== --}}

        <div class="bg-gray-900 rounded-3xl p-5 sm:p-6 text-white shadow-sm">

            <div class="grid grid-cols-2 gap-4">

                {{-- Disponibles --}}
                <div>

                    <p class="text-sm font-medium text-gray-300">
                        Mesas disponibles
                    </p>

                    <p class="mt-1 text-3xl font-extrabold">
                        {{ $mesas->where('estado', 'disponible')->count() }}
                    </p>

                </div>


                {{-- Ocupadas --}}
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



        {{-- =========================
            MESAS
        ========================== --}}

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



            {{-- GRID DE MESAS --}}

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">


                @foreach ($mesas as $mesa)

                    @php
                        $pedido = $mesa->pedidos->first();
                    @endphp



                    {{-- ==========================================
                        MESA DISPONIBLE
                    =========================================== --}}

                    @if ($mesa->estado === 'disponible')

                        <a
                            href="{{ route('admin.mesero.pedidos.create', $mesa) }}"
                            class="group bg-white border border-gray-200 rounded-2xl p-3 shadow-sm hover:shadow-md hover:border-gray-300 active:scale-[0.97] transition"
                        >


                            {{-- Icono + Estado --}}

                            <div class="flex items-center justify-between">

                                <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center text-lg">
                                    🪑
                                </div>

                                <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>

                            </div>



                            {{-- Información de mesa --}}

                            <div class="mt-3">

                                <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Mesa
                                </p>

                                <h4 class="mt-0.5 text-xl font-extrabold text-gray-950">
                                    {{ $mesa->numero }}
                                </h4>

                                <p class="mt-0.5 text-xs text-gray-500 capitalize truncate">
                                    {{ $mesa->zona }}
                                </p>

                            </div>



                            {{-- Estado --}}

                            <div class="mt-3">

                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-50 text-green-700 text-[10px] font-bold">
                                    Disponible
                                </span>

                            </div>


                        </a>



                    {{-- ==========================================
                        MESA OCUPADA
                    =========================================== --}}

                    @else

                        <a
                            href="{{ route('admin.mesero.pedidos.agregar', $mesa) }}"
                            class="group bg-white border border-orange-200 rounded-2xl p-3 shadow-sm hover:shadow-md hover:border-orange-300 active:scale-[0.97] transition block"
                        >


                            {{-- Icono + Estado --}}

                            <div class="flex items-center justify-between">

                                <div class="w-9 h-9 rounded-xl bg-orange-50 flex items-center justify-center text-lg">
                                    🍽️
                                </div>

                                <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>

                            </div>



                            {{-- Información de mesa --}}

                            <div class="mt-3">

                                <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Mesa
                                </p>

                                <h4 class="mt-0.5 text-xl font-extrabold text-gray-950">
                                    {{ $mesa->numero }}
                                </h4>

                                <p class="mt-0.5 text-xs text-gray-500 capitalize truncate">
                                    {{ $mesa->zona }}
                                </p>

                            </div>



                            {{-- ======================================
                                PEDIDO ACTUAL
                            ======================================= --}}

                            @if ($pedido)

                                <div class="mt-3 pt-3 border-t border-gray-100">


                                    {{-- Estado del pedido --}}

                                    <div class="flex items-center justify-between gap-2">

                                        <p class="text-[11px] font-bold text-gray-900">
                                            Pedido
                                        </p>

                                        <span class="text-[10px] font-bold text-orange-600">
                                            {{ ucfirst($pedido->estado) }}
                                        </span>

                                    </div>



                                    {{-- Productos --}}

                                    <div class="mt-2 space-y-2">


                                        @foreach ($pedido->detalles as $detalle)

                                            <div class="bg-gray-50 rounded-xl p-2">


                                                <div class="flex items-center justify-between gap-2">

                                                    <div class="min-w-0">

                                                        @foreach ($detalle->productos as $producto)

                                                            <p class="text-xs font-bold text-gray-900 truncate">
                                                                {{ $producto->nombre }}
                                                            </p>

                                                        @endforeach

                                                    </div>


                                                    <p class="text-xs font-extrabold text-gray-900 whitespace-nowrap">
                                                        S/ {{ number_format((float) $detalle->precio, 2) }}
                                                    </p>

                                                </div>



                                                {{-- Componentes del Mixto --}}

                                                @if ($detalle->componentesMixto->count())

                                                    <div class="mt-1 flex flex-wrap gap-1">

                                                        @foreach ($detalle->componentesMixto as $componente)

                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-white border border-gray-200 text-[9px] font-medium text-gray-600">
                                                                {{ $componente->nombre }}
                                                            </span>

                                                        @endforeach

                                                    </div>

                                                @endif



                                                {{-- Observaciones --}}

                                                @if ($detalle->observaciones)

                                                    <p class="mt-1 text-[10px] text-gray-500 truncate">
                                                        📝 {{ $detalle->observaciones }}
                                                    </p>

                                                @endif


                                            </div>

                                        @endforeach


                                    </div>



                                    {{-- ==================================
                                        TOTAL
                                    =================================== --}}

                                    <div class="mt-3 flex items-center justify-between">

                                        <span class="text-[10px] font-semibold text-gray-500">
                                            Total
                                        </span>

                                        <span class="text-base font-extrabold text-gray-950">
                                            S/ {{ number_format((float) $pedido->total, 2) }}
                                        </span>

                                    </div>


                                </div>

                            @endif



                            {{-- ==================================
                                BOTÓN AGREGAR PEDIDO
                            =================================== --}}

                            <div class="mt-3">

                                <span class="inline-flex w-full justify-center items-center px-3 py-2 rounded-xl bg-orange-50 text-orange-700 text-xs font-bold">
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
