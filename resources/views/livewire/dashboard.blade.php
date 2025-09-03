<div class="p-4 space-y-6 min-h-screen">
    <livewire:dashboard-heading title="Dashboard Overview" subtitle="" icon="layout-grid" gradient-from-color="#CE4A3E"
        gradient-to-color="#C2CE3E" icon-color="text-orange-500" />
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Units -->
        <div
            class="p-6 bg-white/70 dark:bg-gray-900 backdrop-blur-lg rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
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
            class="p-6 bg-white/70 dark:bg-gray-900 backdrop-blur-lg rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
            <h2 class="text-lg font-bold mb-4">Components/Peripherals Inventory</h2>
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
    </div>
    <!-- Quick Actions -->
    <div class="flex flex-wrap gap-4 pt-8">
        <h1 class="px-5 py-3 font-bold">Quick Actions:</h1>
        <a href="units" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg transition">
            Go to Unit
        </a>
        <a href="components"
            class="px-5 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-lg transition">
            Go to Component
        </a>
         <a href="peripherals"
            class="px-5 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-xl shadow-lg transition">
            Go to Peripherals
        </a>
        <a href="dashboard"
            class="px-5 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl shadow-lg transition">
            Refresh
        </a>
    </div>
    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Defective Units Trend -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-xl hover:shadow-2xl transition">
            <h2 class="text-lg font-bold mb-4">Defective Units (This Year)</h2>
            <livewire:defective-units-chart />

        </div>

        <!-- Maintenance Trend -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-xl hover:shadow-2xl transition">
            <h2 class="text-lg font-bold mb-4"> Reports (Academic Year)</h2>
            {{-- @php
                $labels = ['Aug 28', 'Aug 29', 'Aug 30', 'Aug 31', 'Sep 01', 'Sep 02', 'Sep 03'];
                $values = [5, 7, 6, 10, 8, 12, 9];
            @endphp --}}

            <livewire:line-chart />


        </div>
    </div>


</div>
