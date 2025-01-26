<div>
    <div class="flex items-end mb-0 mt-6">
        <div class="inline-flex items-center space-x-2 bg-white dark:bg-gray-800 px-6 py-2 rounded-t-lg ">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                {{ __('Manage Charts') }}
            </h2>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 dark:border-gray-700 mb-6">
        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
            <div class="w-full sm:w-auto">
                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Start date:') }}</label>
                <input type="date" id="start_date" wire:model.live="startDate"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="w-full sm:w-auto">
                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('End date:') }}</label>
                <input type="date" id="end_date" wire:model.live="endDate"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Gráfico de Línea y Dona en la primera fila -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">{{ __('Monthly Sales') }}</h3>
            <div class="h-[400px]" wire:ignore>
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">{{ __('Payment Types') }}</h3>
            <div class="h-[400px]" wire:ignore>
                <canvas id="doughnutChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Gráfico de Barras separado para mejor visualización en móvil -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700 mb-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">{{ __('Top Clients') }}</h3>
        <div class="h-[400px]" wire:ignore>
            <canvas id="barChart"></canvas>
        </div>
    </div>
</div>

@assets
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endassets

@script
    <script>
        let barChart, lineChart, doughnutChart;

        function initializeCharts(data) {
            // Destruir gráficos existentes si existen
            if (barChart) barChart.destroy();
            if (lineChart) lineChart.destroy();
            if (doughnutChart) doughnutChart.destroy();

            const isDarkMode = document.documentElement.classList.contains('dark');
            const textColor = isDarkMode ? '#ffffff' : '#1f2937'; 
            const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.2)' : 'rgba(107, 114, 128, 0.2)'; 

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        labels: {
                            color: textColor,
                            font: {
                                size: 12,
                                weight: isDarkMode ? 'normal' : 'bold' 
                            }
                        }
                    },
                    title: {
                        color: textColor,
                        font: {
                            weight: isDarkMode ? 'normal' : 'bold'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: textColor,
                            font: {
                                weight: isDarkMode ? 'normal' : 'bold'
                            }
                        },
                        grid: {
                            color: gridColor,
                            borderColor: textColor,
                            lineWidth: isDarkMode ? 0.5 : 1
                        },
                        title: {
                            color: textColor,
                            font: {
                                weight: isDarkMode ? 'normal' : 'bold'
                            }
                        }
                    },
                    y: {
                        ticks: {
                            color: textColor,
                            font: {
                                weight: isDarkMode ? 'normal' : 'bold'
                            }
                        },
                        grid: {
                            color: gridColor,
                            borderColor: textColor,
                            lineWidth: isDarkMode ? 0.5 : 1
                        },
                        title: {
                            color: textColor,
                            font: {
                                weight: isDarkMode ? 'normal' : 'bold'
                            }
                        }
                    }
                }
            };

            // Inicializar gráficos con los datos
            const barChartCtx = document.getElementById('barChart').getContext('2d');
            barChart = new Chart(barChartCtx, {
                type: 'bar',
                data: {
                    labels: data.clients.map(client => `${client.name} ${client.last_name}`), // Nombres de clientes
                    datasets: [{
                        label: '{{ __("Top 10 Clients by Total Sales") }}',
                        data: data.clients.map(client => parseFloat(client.total_sales)), // Ventas totales
                        backgroundColor: data.clients.map((_, index) => {
                            // Usar un solo color base (azul) con opacidad decreciente
                            const opacity = 0.9 - (index * 0.07); // Comienza en 0.9 y decrece gradualmente
                            return `rgba(129, 140, 248, ${opacity})`; // Color blue-500 de Tailwind
                        }),
                        borderColor: data.clients.map((_, index) => {
                            // Borde ligeramente más oscuro que el fondo
                            const opacity = 1 - (index * 0.07);
                            return `rgba(99, 108, 191, ${opacity})`; // Color blue-600 de Tailwind
                        }),
                        borderWidth: 1
                    }]
                },
                options: {
                    ...commonOptions,
                    maintainAspectRatio: false,
                    indexAxis: 'y', 
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '{{ __("Client") }}',
                                color: textColor
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '{{ __("Total Sales ($)") }}',
                                color: textColor
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const client = data.clients[context.dataIndex];
                                    return [
                                        `{{ __("Sales") }}: $${parseFloat(client.total_sales).toLocaleString()}`,
                                        `{{ __("Invoices") }}: ${client.total_invoices}`
                                    ];
                                }
                            }
                        }
                    }
                }
            });

            const lineChartCtx = document.getElementById('lineChart').getContext('2d');
            // Ordenar los datos por fecha antes de crear el gráfico
            const sortedMonthlySales = [...data.monthlySales].sort((a, b) => {
                return new Date(a.month) - new Date(b.month);
            });

            lineChart = new Chart(lineChartCtx, {
                type: 'line',
                data: {
                    labels: sortedMonthlySales.map(sale => sale.month),
                    datasets: [{
                        label: '{{ __("Total Monthly Sales") }}',
                        data: sortedMonthlySales.map(sale => parseFloat(sale.total_sales)),
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1,
                        fill: true
                    }]
                },
                options: {
                    ...commonOptions,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '{{ __("Total Sales") }}',
                                color: textColor
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '{{ __("Month") }}',
                                color: textColor
                            }
                        }
                    }
                }
            });

            const doughnutChartCtx = document.getElementById('doughnutChart').getContext('2d');
            const paymentTypes = [...new Set(data.paymentDistribution.map(dist => dist.payment_type))];
            const paymentCounts = paymentTypes.map(type =>
                data.paymentDistribution.filter(dist => dist.payment_type === type).reduce((sum, dist) => sum +
                    dist.count, 0)
            );
            doughnutChart = new Chart(doughnutChartCtx, {
                type: 'doughnut',
                data: {
                    labels: paymentTypes,
                    datasets: [{
                        label: '{{ __("Payment Type Distribution") }}',
                        data: paymentCounts,
                        backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)'
                        ],
                        borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'right'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const total = paymentCounts.reduce((sum, val) => sum + val, 0);
                                    const percentage = ((value / total) * 100).toFixed(2);
                                    return `${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        (async () => {
            const data = await $wire.getChartData();
            initializeCharts(data);

            window.addEventListener('theme-changed', () => {
                initializeCharts(data);
            });
        })();

        $wire.on('charts-updated', (event) => {
            initializeCharts(event.data);
        });
    </script>
@endscript
