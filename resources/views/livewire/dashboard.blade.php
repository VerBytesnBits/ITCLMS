<div class="p-4 space-y-6 bg-gray-50 dark:bg-gray-950 min-h-screen">
    <livewire:dashboard-heading title="Dashboard Overview" subtitle="" icon="layout-grid"
        gradient-from-color="#CE4A3E" gradient-to-color="#C2CE3E" icon-color="text-orange-500" />
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Units -->
        <div
            class="p-6 bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
            <h2 class="text-lg font-bold mb-4">All Units</h2>
            <p class="text-green-600 dark:text-green-400 font-semibold">
                Operational: {{ $stats['units']['Operational'] }}
            </p>
            <p class="text-red-600 dark:text-red-400 font-semibold">
                Defective: {{ $stats['units']['defective'] }}
            </p>
        </div>

        <!-- Parts -->
        <div
            class="p-6 bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
            <h2 class="text-lg font-bold mb-4">Parts Inventory</h2>
            <p class="text-blue-600 dark:text-blue-400 font-semibold">
                Available: {{ $stats['parts']['available'] }}
            </p>
            <p class="text-orange-600 dark:text-orange-400 font-semibold">
                In Use: {{ $stats['parts']['In use'] }}
            </p>
            <p class="text-red-600 dark:text-red-400 font-semibold">
                Defective: {{ $stats['parts']['defective'] }}
            </p>
        </div>

        <!-- Maintenance -->
        <div
            class="p-6 bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
            <h2 class="text-lg font-bold mb-4">Maintenance</h2>
            <p class="text-yellow-600 dark:text-yellow-400 font-semibold">
                Pending: {{ $stats['maintenance']['pending'] }}
            </p>
            <p class="text-blue-600 dark:text-blue-400 font-semibold">
                In Progress: {{ $stats['maintenance']['In Progress'] }}
            </p>
            <p class="text-green-600 dark:text-green-400 font-semibold">
                Resolved: {{ $stats['maintenance']['completed'] }}
            </p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Defective Units Trend -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-xl hover:shadow-2xl transition">
            <h2 class="text-lg font-bold mb-4">Defective Units (This Year)</h2>
            <canvas id="unitTrendChart" class="h-64"></canvas>
        </div>

        <!-- Maintenance Trend -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-xl hover:shadow-2xl transition">
            <h2 class="text-lg font-bold mb-4">Maintenance Reports (This Year)</h2>
            <canvas id="maintenanceTrendChart" class="h-64"></canvas>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="flex flex-wrap gap-4 pt-8">
        <a href="units" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg transition">
            Add Unit
        </a>
        <a href="components"
            class="px-5 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-lg transition">
            Add Part
        </a>
        <a href="maintenance"
            class="px-5 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl shadow-lg transition">
            New Report
        </a>
    </div>
</div>

@script
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Defective Units Chart
        const unitCtx = document.getElementById('unitTrendChart');
        new Chart(unitCtx, {
            type: 'line',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: 'Defective Units',
                    data: @json($unitTrends),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.2)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: 'rgb(239, 68, 68)',
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Maintenance Chart
        const maintCtx = document.getElementById('maintenanceTrendChart');
        new Chart(maintCtx, {
            type: 'bar',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: 'Maintenance Reports',
                    data: @json($maintenanceTrends),
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderRadius: 8,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endscript
