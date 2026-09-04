<x-app-layout>

    <x-slot name="header">

        <div>
            <p class="text-sm font-semibold text-gray-600">
                Restaurant Yuri
            </p>

            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-gray-950">
                Panel de Administración
            </h2>

            <p class="mt-1 text-sm font-medium text-gray-600">
                Gestiona el restaurante desde aquí.
            </p>
        </div>

    </x-slot>


    <div class="min-h-screen bg-gray-50 py-6 pb-24">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Fecha --}}
            <div class="mb-6">
                <p class="text-sm font-semibold text-gray-700">
                    Resumen del día
                </p>

                <p class="text-sm text-gray-500 mt-1">
                    {{ now()->format('d/m/Y') }}
                </p>
            </div>


            {{-- Botones principales --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

                <a
                    href="{{ route('admin.pedidos.index') }}"
                    class="group bg-gray-900 rounded-3xl p-5 text-white shadow-sm hover:bg-gray-800 transition"
                >
                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-lg font-extrabold">
                                Ver pedidos
                            </p>

                            <p class="mt-1 text-sm text-gray-300">
                                Controla y actualiza los pedidos activos.
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-2xl">
                            🧾
                        </div>

                    </div>
                </a>


                <a
                    href="{{ route('admin.pedidos.index') }}"
                    class="group bg-white border border-gray-200 rounded-3xl p-5 shadow-sm hover:border-gray-400 transition"
                >
                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-lg font-extrabold text-gray-950">
                                Ver mesas
                            </p>

                            <p class="mt-1 text-sm text-gray-500">
                                Consulta cuáles están ocupadas o disponibles.
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center text-2xl">
                            🪑
                        </div>

                    </div>
                </a>

            </div>


            {{-- Indicadores --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                {{-- Ventas --}}
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-5">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-semibold text-gray-500">
                                Ventas de hoy
                            </p>

                            <p class="mt-2 text-3xl font-extrabold text-gray-950">
                                S/ {{ number_format($ventasHoy, 2) }}
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-2xl bg-gray-900 flex items-center justify-center text-xl">
                            💰
                        </div>

                    </div>

                </div>


                {{-- Pedidos --}}
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-5">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-semibold text-gray-500">
                                Pedidos de hoy
                            </p>

                            <p class="mt-2 text-3xl font-extrabold text-gray-950">
                                {{ $pedidosHoy }}
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center text-xl">
                            🧾
                        </div>

                    </div>

                </div>


                {{-- Mesas --}}
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-5">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-semibold text-gray-500">
                                Mesas ocupadas
                            </p>

                            <p class="mt-2 text-3xl font-extrabold text-gray-950">
                                {{ $mesasOcupadas }}
                                <span class="text-lg text-gray-400">
                                    / {{ $totalMesas }}
                                </span>
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center text-xl">
                            🪑
                        </div>

                    </div>

                </div>

            </div>


            {{-- Gráfico --}}
            <div class="mt-6 bg-white rounded-3xl border border-gray-200 shadow-sm p-5">

                <div class="mb-5">

                    <h3 class="text-xl font-extrabold text-gray-950">
                        Ventas de los últimos 7 días
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Evolución de las ventas registradas y pagadas.
                    </p>

                </div>

                <div class="h-80">

                    <canvas id="ventasChart"></canvas>

                </div>

            </div>


        </div>

    </div>


    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ventasSemana = @json($ventasSemana);

        const etiquetas = ventasSemana.map(item => item.fecha);
        const valores = ventasSemana.map(item => item.ventas);

        const ctx = document.getElementById('ventasChart');

        new Chart(ctx, {
            type: 'line',

            data: {
                labels: etiquetas,

                datasets: [{
                    label: 'Ventas',

                    data: valores,

                    borderWidth: 3,

                    tension: 0.4,

                    fill: true,

                    pointRadius: 5,

                    pointHoverRadius: 7
                }]
            },

            options: {
                responsive: true,

                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    },

                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'S/ ' + Number(context.raw).toFixed(2);
                            }
                        }
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true,

                        ticks: {
                            callback: function(value) {
                                return 'S/ ' + value;
                            }
                        }
                    }
                }
            }
        });
    </script>

</x-app-layout>