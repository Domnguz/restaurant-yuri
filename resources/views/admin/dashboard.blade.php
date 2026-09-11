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
                    class="group relative overflow-hidden bg-gray-900 rounded-3xl p-5 text-white shadow-sm hover:shadow-lg transition"
                >
                    <div class="pointer-events-none absolute -right-8 -top-8 w-32 h-32 rounded-full bg-white/5 blur-2xl"></div>

                    <div class="relative flex items-center justify-between">

                        <div>
                            <p class="text-lg font-extrabold">
                                Ver pedidos
                            </p>

                            <p class="mt-1 text-sm text-gray-400">
                                Controla y actualiza los pedidos activos.
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center transition group-hover:bg-white/15">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.7">
                                <path d="M6 3h12v18l-3-2-3 2-3-2-3 2V3z" stroke-linejoin="round"/>
                                <path d="M8.5 8h7M8.5 11.5h7M8.5 15h4" stroke-linecap="round"/>
                            </svg>
                        </div>

                    </div>
                </a>


                <a
                    href="{{ route('admin.pedidos.index') }}"
                    class="group relative overflow-hidden bg-white border border-gray-200 rounded-3xl p-5 shadow-sm hover:border-gray-300 hover:shadow-lg transition"
                >
                    <div class="pointer-events-none absolute -right-8 -top-8 w-32 h-32 rounded-full bg-indigo-50 blur-2xl"></div>

                    <div class="relative flex items-center justify-between">

                        <div>
                            <p class="text-lg font-extrabold text-gray-950">
                                Ver mesas
                            </p>

                            <p class="mt-1 text-sm text-gray-500">
                                Consulta cuáles están ocupadas o disponibles.
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center transition group-hover:bg-indigo-100">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#4338CA" stroke-width="1.7">
                                <path d="M5 10h14M5 10v9M19 10v9M8 10V6a1 1 0 011-1h6a1 1 0 011 1v4" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                    </div>
                </a>

            </div>


            {{-- Indicadores --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                {{-- Ventas --}}
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-5 transition hover:shadow-md">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-semibold text-gray-500">
                                Ventas de hoy
                            </p>

                            <p class="mt-2 text-3xl font-extrabold text-gray-950">
                                S/ {{ number_format($ventasHoy, 2) }}
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="1.7">
                                <circle cx="12" cy="12" r="8"/>
                                <path d="M12 8v8M9.5 10a2.5 2.5 0 012.5-1.5c1.4 0 2.5.8 2.5 2s-1.1 1.7-2.5 2c-1.4.3-2.5.9-2.5 2s1.1 2 2.5 2 2.5-.6 2.5-1.5" stroke-linecap="round"/>
                            </svg>
                        </div>

                    </div>

                </div>


                {{-- Pedidos --}}
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-5 transition hover:shadow-md">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-semibold text-gray-500">
                                Pedidos de hoy
                            </p>

                            <p class="mt-2 text-3xl font-extrabold text-gray-950">
                                {{ $pedidosHoy }}
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#E11D48" stroke-width="1.7">
                                <path d="M6 3h12v18l-3-2-3 2-3-2-3 2V3z" stroke-linejoin="round"/>
                                <path d="M8.5 8h7M8.5 11.5h7" stroke-linecap="round"/>
                            </svg>
                        </div>

                    </div>

                </div>


                {{-- Mesas --}}
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-5 transition hover:shadow-md">

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

                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#4338CA" stroke-width="1.7">
                                <path d="M5 10h14M5 10v9M19 10v9M8 10V6a1 1 0 011-1h6a1 1 0 011 1v4" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
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

        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 320);
        gradient.addColorStop(0, 'rgba(79,70,229,0.18)');
        gradient.addColorStop(1, 'rgba(79,70,229,0)');

        new Chart(ctx, {
            type: 'line',

            data: {
                labels: etiquetas,

                datasets: [{
                    label: 'Ventas',

                    data: valores,

                    borderColor: '#4F46E5',
                    borderWidth: 3,

                    backgroundColor: gradient,

                    pointBackgroundColor: '#111827',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,

                    tension: 0.4,

                    fill: true,

                    pointRadius: 4,

                    pointHoverRadius: 6
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
                        backgroundColor: '#111827',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return 'S/ ' + Number(context.raw).toFixed(2);
                            }
                        }
                    }
                },

                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6B7280' }
                    },
                    y: {
                        beginAtZero: true,

                        grid: { color: '#F3F4F6' },

                        ticks: {
                            color: '#6B7280',
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