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

    @php
        $estadoStyles = [
            'pendiente'   => ['bg' => 'bg-amber-50',   'text' => 'text-amber-700',   'dot' => 'bg-amber-500'],
            'preparando'  => ['bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'dot' => 'bg-blue-500'],
            'listo'       => ['bg' => 'bg-indigo-50',  'text' => 'text-indigo-700',  'dot' => 'bg-indigo-500'],
            'entregado'   => ['bg' => 'bg-violet-50',  'text' => 'text-violet-700',  'dot' => 'bg-violet-500'],
            'pagado'      => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500'],
            'cancelado'   => ['bg' => 'bg-red-50',     'text' => 'text-red-700',     'dot' => 'bg-red-500'],
        ];

        $estadoStyle = $estadoStyles[$pedido->estado] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'dot' => 'bg-gray-400'];
    @endphp

    <div class="min-h-screen bg-gray-50 py-6 pb-24">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Información general --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div class="bg-white rounded-3xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-gray-500">
                            Mesa
                        </p>

                        <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#4338CA" stroke-width="1.7">
                                <path d="M5 10h14M5 10v9M19 10v9M8 10V6a1 1 0 011-1h6a1 1 0 011 1v4" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>

                    <p class="mt-3 text-3xl font-extrabold text-gray-950">
                        {{ $pedido->mesa->numero }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $pedido->mesa->zona }}
                    </p>
                </div>

                <div class="bg-white rounded-3xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-gray-500">
                            Mesero
                        </p>

                        <div class="w-9 h-9 rounded-xl bg-rose-50 flex items-center justify-center">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#E11D48" stroke-width="1.7">
                                <circle cx="12" cy="8" r="3.2"/>
                                <path d="M5.5 20a6.5 6.5 0 0113 0" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>

                    <p class="mt-3 text-xl font-extrabold text-gray-950 truncate">
                        {{ $pedido->usuario->name }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500 truncate">
                        {{ $pedido->usuario->email }}
                    </p>
                </div>

                <div class="bg-white rounded-3xl border border-gray-200 p-5 shadow-sm">
                    <p class="text-sm font-semibold text-gray-500">
                        Estado
                    </p>

                    <span class="mt-3 inline-flex items-center gap-2 rounded-full {{ $estadoStyle['bg'] }} {{ $estadoStyle['text'] }} px-3 py-1.5 text-sm font-bold capitalize">
                        <span class="w-2 h-2 rounded-full {{ $estadoStyle['dot'] }}"></span>
                        {{ $pedido->estado }}
                    </span>

                    <p class="mt-3 text-sm text-gray-500">
                        {{ $pedido->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

            </div>


            {{-- Productos --}}
            <div class="mt-6 bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">

                <div class="p-5 border-b border-gray-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-950">
                            Productos del pedido
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Productos registrados por el mesero.
                        </p>
                    </div>

                    <span class="text-sm font-semibold text-gray-400">
                        {{ $pedido->detalles->count() }} {{ Str::plural('ítem', $pedido->detalles->count()) }}
                    </span>
                </div>


                <div class="divide-y divide-gray-100">

                    @forelse ($pedido->detalles as $detalle)

                        <div class="p-5">

                            <div class="flex items-start justify-between gap-4">

                                <div class="flex items-start gap-3">

                                    <div class="mt-0.5 w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.8">
                                            <path d="M4 7h16l-1.5 11a2 2 0 01-2 1.7H7.5a2 2 0 01-2-1.7L4 7z" stroke-linejoin="round"/>
                                            <path d="M9 7V5a3 3 0 016 0v2" stroke-linecap="round"/>
                                        </svg>
                                    </div>

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

                                            <div class="mt-3 flex items-start gap-1.5 rounded-xl bg-amber-50 border border-amber-100 px-3 py-2">

                                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="#B45309" stroke-width="1.8">
                                                    <path d="M12 3l9 16H3L12 3z" stroke-linejoin="round"/>
                                                    <path d="M12 10v3.5" stroke-linecap="round"/>
                                                    <circle cx="12" cy="16.5" r="0.6" fill="#B45309" stroke="none"/>
                                                </svg>

                                                <p class="text-sm text-amber-800">
                                                    {{ $detalle->observaciones }}
                                                </p>

                                            </div>

                                        @endif

                                    </div>

                                </div>


                                <div class="text-right flex-shrink-0">

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
                Sigue los pasos para finalizar el pedido.
            </p>
        </div>

        <form
            action="{{ route('admin.pedidos.pago', $pedido) }}"
            method="POST"
            class="p-5"
            id="form-pago"
        >

            @csrf

            {{-- select real que envía el dato del formulario; se controla desde los botones de abajo --}}
            <select id="metodo_pago" name="metodo_pago" required class="sr-only">
                <option value="">Seleccionar método</option>
                <option value="efectivo">Efectivo</option>
                <option value="yape">Yape</option>
                <option value="plin">Plin</option>
                <option value="tarjeta">Tarjeta</option>
            </select>

            {{-- Paso 1 --}}
            <div>
                <p class="flex items-center gap-2 text-sm font-bold text-gray-700">
                    <span class="w-5 h-5 rounded-full bg-gray-900 text-white text-xs font-extrabold flex items-center justify-center">1</span>
                    Método de pago
                </p>

                <div class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-3" id="metodos-pago">

                    <button type="button" data-metodo="efectivo" class="metodo-btn flex flex-col items-center gap-2 rounded-2xl border-2 border-gray-200 bg-white p-4 text-sm font-bold text-gray-700 transition hover:border-gray-300">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <rect x="2.5" y="6" width="19" height="12" rx="2"/>
                            <circle cx="12" cy="12" r="2.5"/>
                        </svg>
                        Efectivo
                    </button>

                    <button type="button" data-metodo="yape" class="metodo-btn flex flex-col items-center gap-2 rounded-2xl border-2 border-gray-200 bg-white p-4 text-sm font-bold text-gray-700 transition hover:border-gray-300">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <rect x="6" y="2.5" width="12" height="19" rx="2.5"/>
                            <path d="M10 18.5h4" stroke-linecap="round"/>
                        </svg>
                        Yape
                    </button>

                    <button type="button" data-metodo="plin" class="metodo-btn flex flex-col items-center gap-2 rounded-2xl border-2 border-gray-200 bg-white p-4 text-sm font-bold text-gray-700 transition hover:border-gray-300">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <rect x="6" y="2.5" width="12" height="19" rx="2.5"/>
                            <path d="M10 18.5h4" stroke-linecap="round"/>
                        </svg>
                        Plin
                    </button>

                    <button type="button" data-metodo="tarjeta" class="metodo-btn flex flex-col items-center gap-2 rounded-2xl border-2 border-gray-200 bg-white p-4 text-sm font-bold text-gray-700 transition hover:border-gray-300">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <rect x="2.5" y="5" width="19" height="14" rx="2.2"/>
                            <path d="M2.5 9.5h19" stroke-linecap="round"/>
                        </svg>
                        Tarjeta
                    </button>

                </div>
            </div>


            {{-- Paso 2: efectivo --}}
            <div id="contenedor-efectivo" class="hidden mt-6">

                <p class="flex items-center gap-2 text-sm font-bold text-gray-700">
                    <span class="w-5 h-5 rounded-full bg-gray-900 text-white text-xs font-extrabold flex items-center justify-center">2</span>
                    Monto recibido
                </p>

                <div class="mt-3 flex flex-wrap gap-2" id="montos-rapidos"></div>

                <input
                    type="number"
                    id="monto_recibido"
                    name="monto_recibido"
                    step="0.01"
                    min="0"
                    placeholder="0.00"
                    class="mt-3 w-full rounded-2xl border-gray-300 text-lg font-bold focus:border-gray-900 focus:ring-gray-900"
                >

                <p class="mt-1 text-xs text-gray-500">
                    Ingresa cuánto dinero entrega el cliente, o elige un monto rápido.
                </p>

            </div>


            {{-- Total a pagar --}}
            <div class="mt-6 rounded-2xl bg-gray-50 border border-gray-200 p-4">

                <div class="flex items-center justify-between">

                    <span class="font-bold text-gray-600">
                        Total a pagar
                    </span>

                    <span class="text-xl font-extrabold text-gray-950">
                        S/ {{ number_format($pedido->total, 2) }}
                    </span>

                </div>

            </div>


            {{-- Vuelto --}}
            <div
                id="contenedor-vuelto"
                class="hidden mt-4 rounded-2xl border p-4"
            >

                <div class="flex items-center justify-between">

                    <span
                        id="texto-vuelto"
                        class="font-bold"
                    >
                        Vuelto
                    </span>

                    <span
                        id="vuelto"
                        class="text-2xl font-extrabold"
                    >
                        S/ 0.00
                    </span>

                </div>

            </div>


            {{-- Mensaje de error --}}
            <div
                id="mensaje-pago"
                class="hidden mt-4 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-700"
            >
            </div>


            {{-- Errores de Laravel --}}
            @error('monto_recibido')
                <div class="mt-4 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-700">
                    {{ $message }}
                </div>
            @enderror


            {{-- Botón --}}
            <button
                type="submit"
                id="btn-pagar"
                disabled
                class="mt-6 w-full flex items-center justify-center gap-2 rounded-2xl bg-gray-900 px-5 py-4 text-sm font-extrabold text-white hover:bg-gray-800 transition opacity-50 cursor-not-allowed"
            >
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2">
                    <path d="M4 12l5 5L20 6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Finalizar y marcar como pagado
            </button>

        </form>

    </div>


    {{-- Cálculo automático del vuelto --}}
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const metodoPago = document.getElementById('metodo_pago');
            const montoRecibido = document.getElementById('monto_recibido');

            const contenedorEfectivo =
                document.getElementById('contenedor-efectivo');

            const contenedorVuelto =
                document.getElementById('contenedor-vuelto');

            const vuelto =
                document.getElementById('vuelto');

            const textoVuelto =
                document.getElementById('texto-vuelto');

            const mensajePago =
                document.getElementById('mensaje-pago');

            const botonPagar =
                document.getElementById('btn-pagar');

            const montosRapidos =
                document.getElementById('montos-rapidos');

            const total =
                {{ (float) $pedido->total }};


            // Botones de método de pago -> sincronizan el <select> real
            document.querySelectorAll('.metodo-btn').forEach(function (btn) {

                btn.addEventListener('click', function () {

                    document.querySelectorAll('.metodo-btn').forEach(function (b) {
                        b.classList.remove('border-gray-900', 'bg-gray-900', 'text-white');
                        b.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
                    });

                    btn.classList.remove('border-gray-200', 'bg-white', 'text-gray-700');
                    btn.classList.add('border-gray-900', 'bg-gray-900', 'text-white');

                    metodoPago.value = btn.dataset.metodo;
                    metodoPago.dispatchEvent(new Event('change'));
                });

            });


            // Montos rápidos: exacto + billetes comunes por encima del total
            function construirMontosRapidos() {

                montosRapidos.innerHTML = '';

                const opciones = [total, 10, 20, 50, 100, 200]
                    .filter(function (v, i, arr) {
                        return v >= total && arr.indexOf(v) === i;
                    })
                    .sort(function (a, b) { return a - b; })
                    .slice(0, 4);

                opciones.forEach(function (monto) {

                    const boton = document.createElement('button');
                    boton.type = 'button';
                    boton.textContent = monto === total
                        ? 'Exacto (S/ ' + total.toFixed(2) + ')'
                        : 'S/ ' + monto.toFixed(0);

                    boton.className = 'rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm font-bold text-gray-700 hover:border-gray-400 transition';

                    boton.addEventListener('click', function () {
                        montoRecibido.value = monto.toFixed(2);
                        calcularVuelto();
                    });

                    montosRapidos.appendChild(boton);
                });
            }


            function calcularVuelto() {

                const esEfectivo =
                    metodoPago.value === 'efectivo';


                // Mostrar u ocultar campo de efectivo
                if (esEfectivo) {

                    contenedorEfectivo.classList.remove('hidden');
                    construirMontosRapidos();

                } else {

                    contenedorEfectivo.classList.add('hidden');

                    contenedorVuelto.classList.add('hidden');

                    mensajePago.classList.add('hidden');

                    montoRecibido.value = '';

                    if (metodoPago.value) {
                        botonPagar.disabled = false;
                        botonPagar.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        botonPagar.disabled = true;
                        botonPagar.classList.add('opacity-50', 'cursor-not-allowed');
                    }

                    return;
                }


                function pintarVuelto(clases) {
                    contenedorVuelto.classList.remove(
                        'bg-gray-50', 'border-gray-200', 'text-gray-600',
                        'bg-red-50', 'border-red-200', 'text-red-700',
                        'bg-emerald-50', 'border-emerald-200', 'text-emerald-700'
                    );
                    contenedorVuelto.classList.add(clases[0], clases[1]);
                    textoVuelto.classList.remove('text-gray-600', 'text-red-700', 'text-emerald-700');
                    vuelto.classList.remove('text-gray-950', 'text-red-700', 'text-emerald-700');
                    textoVuelto.classList.add(clases[2]);
                    vuelto.classList.add(clases[2]);
                }


                // Si todavía no se ha ingresado dinero
                if (montoRecibido.value === '') {

                    contenedorVuelto.classList.add('hidden');

                    mensajePago.classList.add('hidden');

                    botonPagar.disabled = true;

                    botonPagar.classList.add(
                        'opacity-50',
                        'cursor-not-allowed'
                    );

                    return;
                }


                const recibido =
                    parseFloat(montoRecibido.value) || 0;

                const diferencia =
                    recibido - total;


                // Dinero insuficiente
                if (diferencia < 0) {

                    const falta =
                        Math.abs(diferencia).toFixed(2);

                    textoVuelto.textContent = 'Falta';

                    vuelto.textContent = `S/ ${falta}`;

                    contenedorVuelto.classList.remove('hidden');
                    pintarVuelto(['bg-red-50', 'border-red-200', 'text-red-700']);

                    mensajePago.textContent =
                        `El monto recibido no alcanza. Faltan S/ ${falta}.`;

                    mensajePago.classList.remove('hidden');

                    botonPagar.disabled = true;

                    botonPagar.classList.add(
                        'opacity-50',
                        'cursor-not-allowed'
                    );

                    return;
                }


                // Dinero exacto
                if (diferencia === 0) {

                    textoVuelto.textContent = 'Vuelto';

                    vuelto.textContent = 'S/ 0.00';

                    contenedorVuelto.classList.remove('hidden');
                    pintarVuelto(['bg-emerald-50', 'border-emerald-200', 'text-emerald-700']);

                    mensajePago.classList.add('hidden');

                    botonPagar.disabled = false;

                    botonPagar.classList.remove(
                        'opacity-50',
                        'cursor-not-allowed'
                    );

                    return;
                }


                // Hay vuelto
                const montoVuelto =
                    diferencia.toFixed(2);

                textoVuelto.textContent = 'Vuelto';

                vuelto.textContent =
                    `S/ ${montoVuelto}`;

                contenedorVuelto.classList.remove('hidden');
                pintarVuelto(['bg-emerald-50', 'border-emerald-200', 'text-emerald-700']);

                mensajePago.classList.add('hidden');

                botonPagar.disabled = false;

                botonPagar.classList.remove(
                    'opacity-50',
                    'cursor-not-allowed'
                );
            }


            metodoPago.addEventListener(
                'change',
                calcularVuelto
            );

            montoRecibido.addEventListener(
                'input',
                calcularVuelto
            );


            calcularVuelto();

        });

    </script>

@endif

            {{-- Volver --}}
            <div class="mt-6">

                <a
                    href="{{ route('admin.pedidos.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-900 text-white text-sm font-bold hover:bg-gray-800 transition"
                >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path d="M19 12H5M11 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Volver a pedidos
                </a>

            </div>

        </div>

    </div>

</x-app-layout>