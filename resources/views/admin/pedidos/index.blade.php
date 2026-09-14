
<x-app-layout>

    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-gray-500">
                Administración
            </p>

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2">
                <div>
                    <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-gray-950">
                        Pedidos
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Supervisa las mesas y controla el estado de los pedidos.
                    </p>
                </div>
            </div>
        </div>
    </x-slot>


    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-6 pb-24">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- Mensaje de éxito --}}
            @if (session('success'))

                <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 shadow-sm">

                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-green-100 text-green-700">
                            ✓
                        </div>

                        <p class="text-sm font-bold text-green-800">
                            {{ session('success') }}
                        </p>

                    </div>

                </div>

            @endif


            {{-- Mensaje de error --}}
            @if (session('error'))

                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 shadow-sm">

                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-700">
                            !
                        </div>

                        <p class="text-sm font-bold text-red-800">
                            {{ session('error') }}
                        </p>

                    </div>

                </div>

            @endif


{{-- ========================================================= --}}
{{-- RESUMEN --}}
{{-- ========================================================= --}}

<div class="grid grid-cols-3 gap-2 sm:gap-4">

    {{-- Pedidos activos --}}
    <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-gray-950 px-3 py-4 sm:p-5 text-white shadow-sm">

        <div class="flex items-center justify-between">

            <div class="flex h-8 w-8 sm:h-10 sm:w-10 items-center justify-center rounded-xl bg-white/10 text-sm sm:text-lg">
                🍽️
            </div>

            <span class="hidden sm:block text-[10px] font-bold uppercase tracking-wider text-gray-400">
                Ahora
            </span>

        </div>

        <p class="mt-3 text-[10px] sm:text-sm font-semibold text-gray-400">
            Pedidos activos
        </p>

            <span id="contador-activos">
                {{ $pedidos->count() }}
            </span>

    </div>


    {{-- Pendientes --}}
    <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl border border-yellow-200 bg-yellow-50/60 px-3 py-4 sm:p-5 shadow-sm">

        <div class="flex items-center justify-between">

            <div class="flex h-8 w-8 sm:h-10 sm:w-10 items-center justify-center rounded-xl bg-yellow-100 text-sm sm:text-lg">
                ⏳
            </div>

            <span class="h-2.5 w-2.5 rounded-full bg-yellow-400"></span>

        </div>

        <p class="mt-3 text-[10px] sm:text-sm font-semibold text-gray-500">
            Pendientes
        </p>

        <span id="contador-pendientes">
            {{ $pedidos->where('estado', 'pendiente')->count() }}
        </span>

        <p class="hidden sm:block mt-0.5 text-xs font-medium text-gray-500">
            Esperando atención
        </p>

    </div>


    {{-- Listos --}}
    <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl border border-green-200 bg-green-50/60 px-3 py-4 sm:p-5 shadow-sm">

        <div class="flex items-center justify-between">

            <div class="flex h-8 w-8 sm:h-10 sm:w-10 items-center justify-center rounded-xl bg-green-100 text-sm sm:text-lg">
                ✓
            </div>

            <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>

        </div>

        <p class="mt-3 text-[10px] sm:text-sm font-semibold text-gray-500">
            Listos
        </p>

        <span id="contador-listos">
            {{ $pedidos->where('estado', 'listo')->count() }}
        </span>

        <p class="hidden sm:block mt-0.5 text-xs font-medium text-gray-500">
            Listos para entregar
        </p>

    </div>

</div>

            <div class="mt-10">

                <div class="mb-5 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2">

                    <div>

                        <p class="text-xs font-extrabold uppercase tracking-widest text-gray-400">
                            Restaurante
                        </p>

                        <h3 class="mt-1 text-2xl font-black tracking-tight text-gray-950">
                            Mesas
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Vista rápida del estado de cada mesa.
                        </p>

                    </div>

                    <div class="flex items-center gap-4 text-xs font-bold text-gray-500">

                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
                            Disponible
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                            Ocupada
                        </div>

                    </div>

                </div>


                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">

                    @foreach ($mesas as $mesa)

                        @php
                            $mesaOcupada = $mesa->estado === 'ocupada';
                        @endphp
<div
    id="mesa-{{ $mesa->id }}"
    data-mesa-id="{{ $mesa->id }}"
    class="
        group relative overflow-hidden rounded-[1.75rem] border p-5
        transition duration-200 hover:-translate-y-1 hover:shadow-md
        {{ $mesaOcupada
            ? 'border-red-200 bg-red-50/70'
            : 'border-gray-200 bg-white' }}
    "
>

    <div class="flex items-center justify-between">

        <span class="text-[11px] font-black uppercase tracking-widest text-gray-400">
            Mesa
        </span>

        <span
            class="
                h-3 w-3 rounded-full ring-4
                {{ $mesaOcupada
                    ? 'bg-red-500 ring-red-100'
                    : 'bg-green-500 ring-green-100' }}
            "
        ></span>

    </div>


    <div class="mt-4 flex items-end justify-between">

        <div>

            <p class="text-4xl font-black tracking-tight text-gray-950">
                {{ $mesa->numero }}
            </p>

            <p
                id="estado-mesa-{{ $mesa->id }}"
                class="
                    mt-1 text-xs font-extrabold
                    {{ $mesaOcupada
                        ? 'text-red-600'
                        : 'text-green-600' }}
                "
            >
                {{ $mesaOcupada ? 'Ocupada' : 'Disponible' }}
            </p>

        </div>

        <span class="text-2xl opacity-60">
            🪑
        </span>

    </div>

                            @if ($mesaOcupada)

                                @php
                                    $pedidoActivo = $mesa->pedidos()
                                        ->whereNotIn('estado', ['pagado', 'cancelado'])
                                        ->latest()
                                        ->first();
                                @endphp

                                @if ($pedidoActivo)

                                    <a
                                        href="{{ route('admin.pedidos.show', $pedidoActivo) }}"
                                        class="
                                            mt-4 flex items-center justify-center gap-2
                                            w-full rounded-xl bg-gray-950 px-3 py-2.5
                                            text-xs font-extrabold text-white
                                            transition hover:bg-gray-800
                                        "
                                    >
                                        Ver pedido
                                        <span>→</span>
                                    </a>

                                @endif

                            @endif

                        </div>

                    @endforeach

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- PEDIDOS ACTIVOS --}}
            {{-- ========================================================= --}}

            <div class="mt-12">

                <div class="mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">

                    <div>

                        <p class="text-xs font-extrabold uppercase tracking-widest text-gray-400">
                            Control de pedidos
                        </p>

                        <h3 class="mt-1 text-2xl font-black tracking-tight text-gray-950">
                            Pedidos activos
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Pedidos que todavía no han sido pagados o cancelados.
                        </p>

                    </div>


                    @if ($pedidos->count() > 0)

                        <div class="inline-flex w-fit items-center gap-2 rounded-full bg-gray-100 px-4 py-2">

                            <span class="h-2 w-2 rounded-full bg-green-500"></span>

                            <span class="text-xs font-extrabold text-gray-600">
                                {{ $pedidos->count() }}
                                {{ $pedidos->count() === 1 ? 'pedido activo' : 'pedidos activos' }}
                            </span>

                        </div>

                    @endif

                </div>


                @if ($pedidos->isEmpty())
                <div data-contenedor-pedidos>
                    {{-- Estado vacío --}}
                    <div class="relative overflow-hidden rounded-[2.5rem] border border-gray-200 bg-white px-6 py-14 text-center shadow-sm">

                        <div class="absolute -left-12 -top-12 h-32 w-32 rounded-full bg-gray-50"></div>
                        <div class="absolute -right-12 -bottom-12 h-32 w-32 rounded-full bg-gray-50"></div>

                        <div class="relative">

                            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[1.75rem] bg-gray-100 text-4xl shadow-inner">
                                🍽️
                            </div>

                            <h3 class="mt-6 text-xl font-black text-gray-950">
                                Todo está tranquilo
                            </h3>

                            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                                No hay pedidos activos por ahora.
                                Cuando un mesero registre un nuevo pedido,
                                aparecerá automáticamente en esta sección.
                            </p>

                            <div class="mx-auto mt-6 h-1 w-12 rounded-full bg-gray-200"></div>

                        </div>

                    </div>

                @else

                    <div
                        id="lista-pedidos"
                        class="grid grid-cols-1 lg:grid-cols-2 gap-5"
>

                        @foreach ($pedidos as $pedido)

                            @php

                                $estadoClases = [
                                    'pendiente' => [
                                        'badge' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        'dot' => 'bg-yellow-400',
                                    ],

                                    'listo' => [
                                        'badge' => 'bg-green-50 text-green-700 border-green-200',
                                        'dot' => 'bg-green-500',
                                    ],

                                    'entregado' => [
                                        'badge' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'dot' => 'bg-purple-500',
                                    ],
                                ];

                                $estadoActual = $estadoClases[$pedido->estado]
                                    ?? [
                                        'badge' => 'bg-gray-50 text-gray-700 border-gray-200',
                                        'dot' => 'bg-gray-400',
                                    ];

                            @endphp


                          <div
                                id="pedido-{{ $pedido->id }}"
                                data-pedido-id="{{ $pedido->id }}"
                                class="
                                    group relative overflow-hidden rounded-[2rem]
                                    border border-gray-200 bg-white shadow-sm
                                    transition duration-200
                                    hover:-translate-y-1 hover:shadow-lg
                                "
                            >

                                {{-- Línea superior --}}
                                <div
                                    class="
                                        h-1
                                        {{ $estadoActual['dot'] }}
                                    "
                                ></div>


                                <div class="p-5 sm:p-6">


                                    {{-- Encabezado --}}
                                    <div class="flex items-start justify-between gap-4">

                                        <div class="flex items-center gap-4">

                                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gray-950 text-white">

                                                <span class="text-xs font-bold text-gray-400">
                                                    M
                                                </span>

                                                <span class="ml-0.5 text-xl font-black">
                                                    {{ $pedido->mesa->numero }}
                                                </span>

                                            </div>


                                            <div>

                                                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                                                    Mesa
                                                </p>

                                                <h4 class="mt-0.5 text-lg font-black text-gray-950">
                                                    Mesa {{ $pedido->mesa->numero }}
                                                </h4>

                                                @if ($pedido->mesa->zona)

                                                    <p class="mt-0.5 text-xs font-medium text-gray-500">
                                                        {{ $pedido->mesa->zona }}
                                                    </p>

                                                @endif
                                                 </div>       
                                            </div>

                                        </div>


                                        {{-- Estado --}}
                                        <span
                                            class="
                                                inline-flex shrink-0 items-center gap-2
                                                rounded-full border px-3 py-1.5
                                                text-[11px] font-black uppercase tracking-wide
                                                {{ $estadoActual['badge'] }}
                                            "
                                        >

                                            <span
                                                class="h-1.5 w-1.5 rounded-full {{ $estadoActual['dot'] }}"
                                            ></span>

                                            {{ ucfirst($pedido->estado) }}

                                        </span>

                                    </div>


                                    {{-- Información --}}
                                    <div class="mt-6 grid grid-cols-2 gap-3">

                                        <div class="rounded-2xl bg-gray-50 px-4 py-3">

                                            <div class="flex items-center gap-2">

                                                <span class="text-sm">
                                                    👤
                                                </span>

                                                <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400">
                                                    Mesero
                                                </p>

                                            </div>

                                            <p class="mt-1 text-sm font-extrabold text-gray-900 truncate">
                                                {{ $pedido->usuario->name }}
                                            </p>

                                        </div>


                                        <div class="rounded-2xl bg-gray-50 px-4 py-3">

                                            <div class="flex items-center gap-2">

                                                <span class="text-sm">
                                                    🍽️
                                                </span>

                                                <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400">
                                                    Productos
                                                </p>

                                            </div>

                                           <p
                                                data-productos
                                                class="mt-1 text-sm font-extrabold text-gray-900"
                                            >
                                                {{ $pedido->detalles->count() }}
                                                {{ $pedido->detalles->count() === 1 ? 'producto' : 'productos' }}
                                            </p>

                                        </div>

                                    </div>


                                    {{-- Total --}}
                                    <div class="mt-5 flex items-end justify-between border-t border-gray-100 pt-5">

                                        <div>

                                            <p class="text-xs font-bold uppercase tracking-wide text-gray-400">
                                                Total del pedido
                                            </p>

                                                <p
                                                    data-total
                                                    class="mt-1 text-2xl font-black tracking-tight text-gray-950"
                                                >
                                                    S/ {{ number_format($pedido->total, 2) }}
                                                </p>

                                        </div>

                                        <span class="text-xs font-semibold text-gray-400">
                                            #{{ $pedido->id }}
                                        </span>

                                    </div>


                                    {{-- Acciones --}}
                                    <div class="mt-5 flex flex-col sm:flex-row gap-2">


                                        <a
                                            href="{{ route('admin.pedidos.show', $pedido) }}"
                                            class="
                                                flex-1 inline-flex items-center justify-center gap-2
                                                rounded-2xl bg-gray-950 px-4 py-3
                                                text-sm font-extrabold text-white
                                                transition hover:bg-gray-800
                                            "
                                        >
                                            Ver pedido
                                            <span>→</span>
                                        </a>


                                        {{-- Pendiente --}}
                                        @if ($pedido->estado === 'pendiente')

                                            <form
                                                method="POST"
                                                action="{{ route('admin.pedidos.estado', $pedido) }}"
                                                class="flex-1"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <input
                                                    type="hidden"
                                                    name="estado"
                                                    value="listo"
                                                >

                                                <button
                                                    type="submit"
                                                    class="
                                                        w-full rounded-2xl
                                                        bg-green-600 px-4 py-3
                                                        text-sm font-extrabold text-white
                                                        transition hover:bg-green-700
                                                    "
                                                >
                                                    Marcar listo
                                                </button>

                                            </form>


                                        {{-- Listo --}}
                                        @elseif ($pedido->estado === 'listo')

                                            <form
                                                method="POST"
                                                action="{{ route('admin.pedidos.estado', $pedido) }}"
                                                class="flex-1"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <input
                                                    type="hidden"
                                                    name="estado"
                                                    value="entregado"
                                                >

                                                <button
                                                    type="submit"
                                                    class="
                                                        w-full rounded-2xl
                                                        bg-purple-600 px-4 py-3
                                                        text-sm font-extrabold text-white
                                                        transition hover:bg-purple-700
                                                    "
                                                >
                                                    Marcar entregado
                                                </button>

                                            </form>


                                        {{-- Entregado --}}
                                        @elseif ($pedido->estado === 'entregado')

                                            <a
                                                href="{{ route('admin.pedidos.show', $pedido) }}"
                                                class="
                                                    flex-1 inline-flex items-center justify-center
                                                    rounded-2xl border border-green-200
                                                    bg-green-50 px-4 py-3
                                                    text-sm font-extrabold text-green-700
                                                    transition hover:bg-green-100
                                                "
                                            >
                                                Cobrar pedido
                                            </a>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>

    </div>
<script>
document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | Escuchar pedidos que llegan por Socket.IO
    |--------------------------------------------------------------------------
    */

    window.addEventListener('pedido-actualizado', (evento) => {

        const pedido = evento.detail.pedido;

        if (!pedido) {
            return;
        }

        console.log('⚡ Pedido recibido en la interfaz:', pedido);

        actualizarResumen(pedido);
        actualizarMesa(pedido);
        actualizarTarjetaPedido(pedido);
        mostrarAviso(pedido);
    });


    /*
    |--------------------------------------------------------------------------
    | Actualizar los contadores
    |--------------------------------------------------------------------------
    */

    function actualizarResumen(pedido) {

        const tarjetaExistente = document.getElementById(
            `pedido-${pedido.id}`
        );

        /*
        | Si ya existe, significa que solamente agregaron
        | productos al mismo pedido.
        */
        if (tarjetaExistente) {
            return;
        }


        const activos =
            document.getElementById('contador-activos');

        const pendientes =
            document.getElementById('contador-pendientes');

        const listos =
            document.getElementById('contador-listos');


        if (activos) {
            activos.textContent =
                Number(activos.textContent.trim()) + 1;
        }


        if (
            pedido.estado === 'pendiente'
            && pendientes
        ) {
            pendientes.textContent =
                Number(pendientes.textContent.trim()) + 1;
        }


        if (
            pedido.estado === 'listo'
            && listos
        ) {
            listos.textContent =
                Number(listos.textContent.trim()) + 1;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Cambiar mesa a ocupada
    |--------------------------------------------------------------------------
    */

    function actualizarMesa(pedido) {

        const mesa = document.getElementById(
            `mesa-${pedido.mesa_id}`
        );

        if (!mesa) {
            return;
        }


        /*
        | Fondo de la tarjeta
        */

        mesa.classList.remove(
            'border-gray-200',
            'bg-white'
        );

        mesa.classList.add(
            'border-red-200',
            'bg-red-50/70'
        );


        /*
        | Texto Disponible → Ocupada
        */

        const estado = document.getElementById(
            `estado-mesa-${pedido.mesa_id}`
        );

        if (estado) {

            estado.textContent = 'Ocupada';

            estado.classList.remove(
                'text-green-600'
            );

            estado.classList.add(
                'text-red-600'
            );
        }


        /*
        | Puntito verde → rojo
        */

        const punto = mesa.querySelector(
            '.ring-4'
        );

        if (punto) {

            punto.classList.remove(
                'bg-green-500',
                'ring-green-100'
            );

            punto.classList.add(
                'bg-red-500',
                'ring-red-100'
            );
        }


        /*
        | Agregar botón "Ver pedido"
        | si todavía no existe
        */

        if (
            !mesa.querySelector(
                '.boton-ver-pedido-realtime'
            )
        ) {

            const boton =
                document.createElement('a');

            boton.href =
                `/admin/pedidos/${pedido.id}`;

            boton.className = `
                boton-ver-pedido-realtime
                mt-4 flex items-center justify-center
                gap-2 w-full rounded-xl
                bg-gray-950 px-3 py-2.5
                text-xs font-extrabold text-white
                transition hover:bg-gray-800
            `;

            boton.innerHTML =
                'Ver pedido <span>→</span>';

            mesa.appendChild(boton);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Crear o actualizar tarjeta del pedido
    |--------------------------------------------------------------------------
    */

    function actualizarTarjetaPedido(pedido) {

        /*
        | ¿Ya existe el pedido?
        */

        const existente = document.getElementById(
            `pedido-${pedido.id}`
        );


        /*
        | Si existe, solo actualizamos
        | total y cantidad de productos
        */

        if (existente) {

            const total =
                existente.querySelector('[data-total]');

            const productos =
                existente.querySelector('[data-productos]');


            if (total) {

                total.textContent =
                    `S/ ${Number(pedido.total).toFixed(2)}`;
            }


            if (productos) {

                productos.textContent =
                    pedido.productos === 1
                        ? '1 producto'
                        : `${pedido.productos} productos`;
            }


            /*
            | Pequeño efecto visual
            */

            existente.classList.add(
                'ring-2',
                'ring-green-300'
            );


            setTimeout(() => {

                existente.classList.remove(
                    'ring-2',
                    'ring-green-300'
                );

            }, 1500);


            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Pedido nuevo
        |--------------------------------------------------------------------------
        */

        const contenedor =
            document.querySelector(
                '[data-contenedor-pedidos]'
            );


        if (!contenedor) {
            console.error(
                'No se encontró el contenedor de pedidos.'
            );

            return;
        }


        /*
        | Si antes decía "Todo está tranquilo",
        | lo eliminamos y creamos el grid
        */

        let lista =
            document.getElementById(
                'lista-pedidos'
            );


        if (!lista) {

            contenedor.innerHTML = `
                <div
                    id="lista-pedidos"
                    class="grid grid-cols-1 lg:grid-cols-2 gap-5"
                ></div>
            `;


            lista =
                document.getElementById(
                    'lista-pedidos'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Crear nueva tarjeta
        |--------------------------------------------------------------------------
        */

        const tarjeta =
            document.createElement('div');


        tarjeta.id =
            `pedido-${pedido.id}`;


        tarjeta.dataset.pedidoId =
            pedido.id;


        tarjeta.className = `
            group relative overflow-hidden
            rounded-[2rem]
            border border-gray-200
            bg-white shadow-sm
            transition duration-200
            hover:-translate-y-1
            hover:shadow-lg
            ring-2 ring-green-300
        `;


        tarjeta.innerHTML = `

            <div class="h-1 bg-yellow-400"></div>

            <div class="p-5 sm:p-6">

                <div
                    class="flex items-start justify-between gap-4"
                >

                    <div class="flex items-center gap-4">

                        <div
                            class="
                                flex h-14 w-14
                                shrink-0 items-center
                                justify-center rounded-2xl
                                bg-gray-950 text-white
                            "
                        >

                            <span
                                class="
                                    text-xs font-bold
                                    text-gray-400
                                "
                            >
                                M
                            </span>

                            <span
                                class="
                                    ml-0.5 text-xl
                                    font-black
                                "
                            >
                                ${pedido.mesa_numero}
                            </span>

                        </div>


                        <div>

                            <p
                                class="
                                    text-xs font-bold
                                    uppercase tracking-wider
                                    text-gray-400
                                "
                            >
                                Mesa
                            </p>

                            <h4
                                class="
                                    mt-0.5 text-lg
                                    font-black
                                    text-gray-950
                                "
                            >
                                Mesa ${pedido.mesa_numero}
                            </h4>

                            ${
                                pedido.zona
                                    ? `
                                        <p
                                            class="
                                                mt-0.5 text-xs
                                                font-medium
                                                text-gray-500
                                            "
                                        >
                                            ${pedido.zona}
                                        </p>
                                    `
                                    : ''
                            }

                        </div>

                    </div>


                    <span
                        class="
                            inline-flex shrink-0
                            items-center gap-2
                            rounded-full border
                            border-yellow-200
                            bg-yellow-50
                            px-3 py-1.5
                            text-[11px] font-black
                            uppercase tracking-wide
                            text-yellow-700
                        "
                    >

                        <span
                            class="
                                h-1.5 w-1.5
                                rounded-full
                                bg-yellow-400
                            "
                        ></span>

                        Pendiente

                    </span>

                </div>


                <div
                    class="
                        mt-6 grid
                        grid-cols-2 gap-3
                    "
                >

                    <div
                        class="
                            rounded-2xl
                            bg-gray-50
                            px-4 py-3
                        "
                    >

                        <p
                            class="
                                text-[11px]
                                font-bold uppercase
                                tracking-wide
                                text-gray-400
                            "
                        >
                            👤 Mesero
                        </p>

                        <p
                            class="
                                mt-1 text-sm
                                font-extrabold
                                text-gray-900
                            "
                        >
                            ${pedido.mesero}
                        </p>

                    </div>


                    <div
                        class="
                            rounded-2xl
                            bg-gray-50
                            px-4 py-3
                        "
                    >

                        <p
                            class="
                                text-[11px]
                                font-bold uppercase
                                tracking-wide
                                text-gray-400
                            "
                        >
                            🍽️ Productos
                        </p>

                        <p
                            data-productos
                            class="
                                mt-1 text-sm
                                font-extrabold
                                text-gray-900
                            "
                        >
                            ${
                                pedido.productos === 1
                                    ? '1 producto'
                                    : `${pedido.productos} productos`
                            }
                        </p>

                    </div>

                </div>


                <div
                    class="
                        mt-5 flex
                        items-end justify-between
                        border-t border-gray-100
                        pt-5
                    "
                >

                    <div>

                        <p
                            class="
                                text-xs font-bold
                                uppercase tracking-wide
                                text-gray-400
                            "
                        >
                            Total del pedido
                        </p>

                        <p
                            data-total
                            class="
                                mt-1 text-2xl
                                font-black
                                tracking-tight
                                text-gray-950
                            "
                        >
                            S/ ${Number(pedido.total).toFixed(2)}
                        </p>

                    </div>


                    <span
                        class="
                            text-xs
                            font-semibold
                            text-gray-400
                        "
                    >
                        #${pedido.id}
                    </span>

                </div>


                <div
                    class="
                        mt-5 flex
                        flex-col sm:flex-row
                        gap-2
                    "
                >

                    <a
                        href="/admin/pedidos/${pedido.id}"
                        class="
                            flex-1 inline-flex
                            items-center justify-center
                            gap-2 rounded-2xl
                            bg-gray-950
                            px-4 py-3
                            text-sm font-extrabold
                            text-white
                            transition
                            hover:bg-gray-800
                        "
                    >
                        Ver pedido
                        <span>→</span>
                    </a>


                    <form
                        method="POST"
                        action="/admin/pedidos/${pedido.id}/estado"
                        class="flex-1"
                    >

                        <input
                            type="hidden"
                            name="_token"
                            value="{{ csrf_token() }}"
                        >

                        <input
                            type="hidden"
                            name="_method"
                            value="PATCH"
                        >

                        <input
                            type="hidden"
                            name="estado"
                            value="listo"
                        >

                        <button
                            type="submit"
                            class="
                                w-full rounded-2xl
                                bg-green-600
                                px-4 py-3
                                text-sm font-extrabold
                                text-white
                                transition
                                hover:bg-green-700
                            "
                        >
                            Marcar listo
                        </button>

                    </form>

                </div>

            </div>
        `;


        /*
        | Poner arriba el pedido nuevo
        */

        lista.prepend(tarjeta);


        /*
        | Quitar resaltado después
        */

        setTimeout(() => {

            tarjeta.classList.remove(
                'ring-2',
                'ring-green-300'
            );

        }, 1800);
    }


    /*
    |--------------------------------------------------------------------------
    | Aviso tipo notificación
    |--------------------------------------------------------------------------
    */

    function mostrarAviso(pedido) {

        const anterior =
            document.getElementById(
                'aviso-pedido-realtime'
            );


        if (anterior) {
            anterior.remove();
        }


        const aviso =
            document.createElement('div');


        aviso.id =
            'aviso-pedido-realtime';


        aviso.className = `
            fixed top-5 right-5
            z-[9999]
            w-[calc(100%-2rem)]
            max-w-sm
            rounded-2xl
            bg-gray-950
            p-4 text-white
            shadow-2xl
        `;


        aviso.innerHTML = `

            <div class="flex items-center gap-3">

                <div
                    class="
                        flex h-10 w-10
                        items-center justify-center
                        rounded-xl
                        bg-white/10
                    "
                >
                    🍽️
                </div>

                <div>

                    <p class="font-extrabold">
                        Pedido actualizado
                    </p>

                    <p
                        class="
                            mt-1 text-sm
                            text-gray-300
                        "
                    >
                        Mesa ${pedido.mesa_numero}
                        ·
                        S/ ${Number(pedido.total).toFixed(2)}
                    </p>

                </div>

            </div>
        `;


        document.body.appendChild(aviso);


        setTimeout(() => {

            aviso.remove();

        }, 3500);
    }

});
</script>
</x-app-layout>

