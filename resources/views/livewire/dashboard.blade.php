<div>
    <livewire:dashboard-heading title="Dashboard" subtitle="Overview of IT Computer Laboratory Management System"
        icon="layout-grid" gradient-from-color="#CE4A3E" gradient-to-color="#C2CE3E" icon-color="text-orange-500" />


    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
        <x-dashboard.stats-card title="Total Units" :value="$totalUnits" iconBg="bg-green-500" />
        <x-dashboard.stats-card title="Total Components" :value="$totalComponents" iconBg="bg-purple-500" />
        <x-dashboard.stats-card title="Total Peripherals" :value="$totalPeripherals" iconBg="bg-blue-500" />
    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-4">
        <!-- Units -->
        <div
            class="p-6 bg-white dark:bg-zinc-800  dark:text-white backdrop-blur-md rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1 border border-zinc-200  dark:border-zinc-700">
            <livewire:reports.operational-chart />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-dashboard.inventory-card title="Components Inventory" from-color="emerald-500" to-color="blue-500"
                :percentage="round($operationalPercentage, 0)" :stats="$stats['components']" :below-threshold="$componentsBelowThreshold" :out-of-stock="$componentsOutOfStock" />

            <x-dashboard.inventory-card title="Peripherals" from-color="amber-500" to-color="red-500" :percentage="round($peripheralPercentage, 0)"
                :stats="$stats['peripherals']" :below-threshold="$peripheralsBelowThreshold" :out-of-stock="$peripheralsOutOfStock" />
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 gap-8">
        <!-- Defective Units Trend -->
        <div
            class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-xl hover:shadow-2xl transition transform hover:-translate-y-1">
            <h1 class="text-lg mb-4">Recent Activity</h1>
            {{-- <livewire:defective-units-chart /> --}}
        </div>
    </div>
</div>
