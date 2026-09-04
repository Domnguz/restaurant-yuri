<x-app-layout>

    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-gray-500">
                Administración
            </p>

            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-gray-950">
                Pedidos
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Consulta y controla los pedidos activos del restaurante.
            </p>
        </div>
    </x-slot>


    <div class="min-h-screen bg-gray-50 py-6 pb-24">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Mensaje de éxito --}}
            @if (session('success'))
                <div class="mb-6 rounded-2xl bg-green-50 border border-green-200 p-4">
                    <p class="text-sm font-bold text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            @endif


            {{-- Resumen --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                <div class="bg-gray-900 rounded-3xl p-5 text-white shadow-sm">
                    <p class="text-sm text-gray-300">
                        Pedidos activos
                    </p>

                    <p class="mt-1 text-3xl font-extrabold">
                        {{ $pedidos->count() }}
                    </p>
                </div>


                <div class="bg-white border border-gray-200 rounded-3xl p-5 shadow-sm">
                    <p class="text-sm text-gray-500">
                        Pendientes
                    </p>

                    <p class="mt-1 text-3xl font-extrabold text-yellow-600">
                        {{ $pedidos->where('estado', 'pendiente')->count() }}
                    </p>
                </div>


                <div class="bg-white border border-gray-200 rounded-3xl p-5 shadow-sm">
                    <p class="text-sm text-gray-500">
                        Preparando
                    </p>

                    <p class="mt-1 text-3xl font-extrabold text-blue-600">
                        {{ $pedidos->where('estado', 'preparando')->count() }}
                    </p>
                </div>

            </div>

            {{-- Mesas --}}
            <div class="mt-8">

                <div class="mb-4">
                    <h3 class="text-xl font-extrabold text-gray-950">
                        Mesas
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Estado actual de las mesas del restaurante.
                    </p>
                </div>


                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">

                    @foreach ($mesas as $mesa)

                        @php
                            $mesaOcupada = $mesa->estado === 'ocupada';
                        @endphp

                        <div
                            class="rounded-3xl border p-4 shadow-sm
                            {{ $mesaOcupada
                                ? 'bg-red-50 border-red-200'
                                : 'bg-white border-gray-200' }}"
                        >

                            <div class="flex items-center justify-between">

                                <span class="text-xs font-bold uppercase tracking-wide text-gray-500">
                                    Mesa
                                </span>

                                <span
                                    class="w-3 h-3 rounded-full
                                    {{ $mesaOcupada
                                        ? 'bg-red-500'
                                        : 'bg-green-500' }}"
                                ></span>

                            </div>


                            <p class="mt-3 text-3xl font-extrabold text-gray-950">
                                {{ $mesa->numero }}
                            </p>


                            <p
                                class="mt-1 text-sm font-bold
                                {{ $mesaOcupada
                                    ? 'text-red-700'
                                    : 'text-green-700' }}"
                            >
                                {{ $mesaOcupada ? 'Ocupada' : 'Disponible' }}
                            </p>


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
                                        class="mt-4 block w-full text-center px-3 py-2 rounded-xl bg-gray-900 text-white text-xs font-extrabold hover:bg-gray-800 transition"
                                    >
                                        Ver pedido
                                    </a>

                                @endif

                            @endif

                        </div>

                    @endforeach

                </div>

            </div>


            {{-- Pedidos --}}
            <div class="mt-8">

                <div class="mb-4">
                    <h3 class="text-xl font-extrabold text-gray-950">
                        Pedidos activos
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Aquí aparecen los pedidos que todavía no han sido pagados o cancelados.
                    </p>
                </div>


                @if ($pedidos->isEmpty())

                    <div class="bg-white border border-gray-200 rounded-3xl p-8 text-center shadow-sm">

                        <div class="mx-auto w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center text-2xl">
                            🍽️
                        </div>

                        <h3 class="mt-4 text-lg font-extrabold text-gray-950">
                            No hay pedidos activos
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Cuando un mesero registre un pedido aparecerá aquí.
                        </p>

                    </div>

                @else

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                        @foreach ($pedidos as $pedido)

                            <div class="bg-white border border-gray-200 rounded-3xl p-5 shadow-sm">

                                {{-- Encabezado --}}
                                <div class="flex items-start justify-between gap-4">

                                    <div>

                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">
                                            Mesa
                                        </p>

                                        <h4 class="mt-1 text-2xl font-extrabold text-gray-950">
                                            {{ $pedido->mesa->numero }}
                                        </h4>

                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ $pedido->mesa->zona }}
                                        </p>

                                    </div>


                                    {{-- Estado --}}
                                    @php
                                        $estadoClases = [
                                            'pendiente' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                            'preparando' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'listo' => 'bg-green-50 text-green-700 border-green-200',
                                            'entregado' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        ];
                                    @endphp

                                    <span class="px-3 py-1.5 rounded-full border text-xs font-extrabold {{ $estadoClases[$pedido->estado] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                        {{ ucfirst($pedido->estado) }}
                                    </span>

                                </div>


                                {{-- Información --}}
                                <div class="mt-5 grid grid-cols-2 gap-3">

                                    <div class="rounded-2xl bg-gray-50 p-3">

                                        <p class="text-xs font-semibold text-gray-500">
                                            Mesero
                                        </p>

                                        <p class="mt-1 text-sm font-extrabold text-gray-900">
                                            {{ $pedido->usuario->name }}
                                        </p>

                                    </div>


                                    <div class="rounded-2xl bg-gray-50 p-3">

                                        <p class="text-xs font-semibold text-gray-500">
                                            Productos
                                        </p>

                                        <p class="mt-1 text-sm font-extrabold text-gray-900">
                                            {{ $pedido->detalles->count() }}
                                        </p>

                                    </div>

                                </div>


                                {{-- Total --}}
                                <div class="mt-4 flex items-center justify-between">

                                    <span class="text-sm font-semibold text-gray-500">
                                        Total
                                    </span>

                                    <span class="text-2xl font-extrabold text-gray-950">
                                        S/ {{ number_format($pedido->total, 2) }}
                                    </span>

                                </div>


                                {{-- Acciones --}}
                                <div class="mt-5 flex flex-col sm:flex-row gap-2">

                                    <a
                                        href="{{ route('admin.pedidos.show', $pedido) }}"
                                        class="flex-1 text-center px-4 py-3 rounded-2xl bg-gray-900 text-white text-sm font-extrabold hover:bg-gray-800 transition"
                                    >
                                        Ver pedido
                                    </a>


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
                                                value="preparando"
                                            >

                                            <button
                                                type="submit"
                                                class="w-full px-4 py-3 rounded-2xl bg-blue-600 text-white text-sm font-extrabold hover:bg-blue-700 transition"
                                            >
                                                Empezar
                                            </button>

                                        </form>

                                    @elseif ($pedido->estado === 'preparando')

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
                                                class="w-full px-4 py-3 rounded-2xl bg-green-600 text-white text-sm font-extrabold hover:bg-green-700 transition"
                                            >
                                                Marcar listo
                                            </button>

                                        </form>

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
                                                class="w-full px-4 py-3 rounded-2xl bg-purple-600 text-white text-sm font-extrabold hover:bg-purple-700 transition"
                                            >
                                                Marcar entregado
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>