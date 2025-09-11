<div class="p-4 space-y-6 min-h-screen">
    <livewire:dashboard-heading title="Dashboard Overview" subtitle="" icon="layout-grid" gradient-from-color="#CE4A3E"
        gradient-to-color="#C2CE3E" icon-color="text-orange-500" />
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Units -->
        <div
            class="p-6 bg-white/70 dark:bg-gray-900 backdrop-blur-lg rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">

            <livewire:reports.operational-chart />
        </div>

        <!-- Parts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Components Card -->
            <div class="p-4 bg-white dark:bg-zinc-900 rounded-lg shadow">
                <h3 class="font-bold mb-2">Components</h3>
                <p>Available: {{ round($operationalPercentage, 2) }}%</p>

                @if ($componentsBelowThreshold->count())
                    <h4 class="mt-2 font-semibold">Below Threshold</h4>
                    <ul class="list-disc ml-4">
                        @foreach ($componentsBelowThreshold as $component)
                            <li>{{ $component->part }} — low stock</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Peripherals Card -->
            <div class="p-4 bg-white dark:bg-zinc-900 rounded-lg shadow">
                <h3 class="font-bold mb-2">Peripherals</h3>
                <p>Available: {{ round($peripheralPercentage, 2) }}%</p>

                @if ($peripheralsBelowThreshold->count())
                    <h4 class="mt-2 font-semibold">Below Threshold</h4>
                    <ul class="list-disc ml-4">
                        @foreach ($peripheralsBelowThreshold as $peripheral)
                            <li>{{ $peripheral->type }} — low stock</li>
                        @endforeach
                    </ul>
                @endif
            </div>

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
            {{-- <livewire:defective-units-chart /> --}}

        </div>

        <!-- Maintenance Trend -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-xl hover:shadow-2xl transition">
            <h2 class="text-lg font-bold mb-4"> Reports (Academic Year)</h2>
            {{-- @php
                $labels = ['Aug 28', 'Aug 29', 'Aug 30', 'Aug 31', 'Sep 01', 'Sep 02', 'Sep 03'];
                $values = [5, 7, 6, 10, 8, 12, 9];
            @endphp --}}

            {{-- <livewire:line-chart /> --}}


        </div>
    </div>


</div>
