<x-app-layout>

    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-gray-500">
                Administración de pedidos
            </p>

            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-gray-950">
                Pedido #{{ $pedido->id }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Detalle completo del pedido.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-6 pb-24">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Información general --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div class="bg-white rounded-3xl border border-gray-200 p-5 shadow-sm">
                    <p class="text-sm font-semibold text-gray-500">
                        Mesa
                    </p>

                    <p class="mt-2 text-3xl font-extrabold text-gray-950">
                        {{ $pedido->mesa->numero }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $pedido->mesa->zona }}
                    </p>
                </div>

                <div class="bg-white rounded-3xl border border-gray-200 p-5 shadow-sm">
                    <p class="text-sm font-semibold text-gray-500">
                        Mesero
                    </p>

                    <p class="mt-2 text-xl font-extrabold text-gray-950">
                        {{ $pedido->usuario->name }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $pedido->usuario->email }}
                    </p>
                </div>

                <div class="bg-white rounded-3xl border border-gray-200 p-5 shadow-sm">
                    <p class="text-sm font-semibold text-gray-500">
                        Estado
                    </p>

                    <p class="mt-2 text-xl font-extrabold capitalize text-gray-950">
                        {{ $pedido->estado }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $pedido->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

            </div>


            {{-- Productos --}}
            <div class="mt-6 bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">

                <div class="p-5 border-b border-gray-200">
                    <h3 class="text-lg font-extrabold text-gray-950">
                        Productos del pedido
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Productos registrados por el mesero.
                    </p>
                </div>


                <div class="divide-y divide-gray-100">

                    @forelse ($pedido->detalles as $detalle)

                        <div class="p-5">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    @foreach ($detalle->productos as $producto)

                                        <h4 class="font-bold text-gray-950">
                                            {{ $producto->nombre }}
                                        </h4>

                                    @endforeach


                                    @if ($detalle->componentesMixto->count())

                                        <div class="mt-3">

                                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">
                                                Componentes
                                            </p>

                                            <div class="mt-2 flex flex-wrap gap-2">

                                                @foreach ($detalle->componentesMixto as $componente)

                                                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">
                                                        {{ $componente->nombre }}
                                                    </span>

                                                @endforeach

                                            </div>

                                        </div>

                                    @endif


                                    @if ($detalle->observaciones)

                                        <div class="mt-3">

                                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">
                                                Observación
                                            </p>

                                            <p class="mt-1 text-sm text-gray-700">
                                                {{ $detalle->observaciones }}
                                            </p>

                                        </div>

                                    @endif

                                </div>


                                <div class="text-right">

                                    <p class="text-lg font-extrabold text-gray-950">
                                        S/ {{ number_format($detalle->precio, 2) }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="p-8 text-center text-gray-500">
                            Este pedido no tiene productos registrados.
                        </div>

                    @endforelse

                </div>


                {{-- Total --}}
                <div class="p-5 bg-gray-50 border-t border-gray-200">

                    <div class="flex items-center justify-between">

                        <span class="text-base font-bold text-gray-600">
                            Total
                        </span>

                        <span class="text-2xl font-extrabold text-gray-950">
                            S/ {{ number_format($pedido->total, 2) }}
                        </span>

                    </div>

                </div>

            </div>
            
            {{-- Registrar pago --}}
@if ($pedido->estado !== 'pagado' && $pedido->estado !== 'cancelado')

    <div class="mt-6 bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">

        <div class="p-5 border-b border-gray-200">
            <h3 class="text-lg font-extrabold text-gray-950">
                Registrar pago
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Selecciona el método de pago para finalizar el pedido.
            </p>
        </div>

        <form
            action="{{ route('admin.pedidos.pago', $pedido) }}"
            method="POST"
            class="p-5"
        >

            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Método de pago --}}
                <div>
                    <label
                        for="metodo_pago"
                        class="block text-sm font-bold text-gray-700"
                    >
                        Método de pago
                    </label>

                    <select
                        id="metodo_pago"
                        name="metodo_pago"
                        required
                        class="mt-2 w-full rounded-2xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                    >
                        <option value="">Seleccionar método</option>
                        <option value="efectivo">💵 Efectivo</option>
                        <option value="yape">📱 Yape</option>
                        <option value="plin">📱 Plin</option>
                        <option value="tarjeta">💳 Tarjeta</option>
                    </select>
                </div>

                {{-- Monto recibido --}}
                <div>
                    <label
                        for="monto_recibido"
                        class="block text-sm font-bold text-gray-700"
                    >
                        Monto recibido
                    </label>

                    <input
                        type="number"
                        id="monto_recibido"
                        name="monto_recibido"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        class="mt-2 w-full rounded-2xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                    >

                    <p class="mt-1 text-xs text-gray-500">
                        Solo necesario cuando el pago sea en efectivo.
                    </p>
                </div>

            </div>

            {{-- Total a pagar --}}
            <div class="mt-5 rounded-2xl bg-gray-50 border border-gray-200 p-4">

                <div class="flex items-center justify-between">

                    <span class="font-bold text-gray-600">
                        Total a pagar
                    </span>

                    <span class="text-xl font-extrabold text-gray-950">
                        S/ {{ number_format($pedido->total, 2) }}
                    </span>

                </div>

            </div>

            {{-- Botón --}}
            <button
                type="submit"
                class="mt-5 w-full rounded-2xl bg-gray-900 px-5 py-4 text-sm font-extrabold text-white hover:bg-gray-800 transition"
            >
                ✓ Finalizar y marcar como pagado
            </button>

        </form>

    </div>

@endif

            {{-- Volver --}}
            <div class="mt-6">

                <a
                    href="{{ route('admin.pedidos.index') }}"
                    class="inline-flex items-center px-5 py-3 rounded-2xl bg-gray-900 text-white text-sm font-bold hover:bg-gray-800 transition"
                >
                    ← Volver a pedidos
                </a>

            </div>

        </div>

    </div>

</x-app-layout>