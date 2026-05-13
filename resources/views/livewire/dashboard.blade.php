<div class="space-y-4">
    <!-- Heading -->
    <livewire:dashboard-heading title="Dashboard" subtitle="Overview of IT Computer Laboratory Management System"
        icon="layout-grid" gradient-from-color="#CE4A3E" gradient-to-color="#C2CE3E" icon-color="text-orange-500" />

    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <x-dashboard.stats-card title="Total Units" :value="$totalUnits" icon="computer" iconBg="bg-green-500" :href="route('units')" />
        <x-dashboard.stats-card title="Total Components" :value="$totalComponents" icon="cpu-chip" iconBg="bg-blue-500"  :href="route('components')"/>
        <x-dashboard.stats-card title="Total Peripherals" :value="$totalPeripherals" icon="monitor" iconBg="bg-orange-500" :href="route('peripherals')" />
    </div>


    <!-- Charts + Inventory -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
        <!-- Units Chart -->

        <livewire:reports.operational-chart />


        <!-- Inventory Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-dashboard.inventory-card title="Components Inventory" from-color="emerald-500" to-color="blue-500"
                :percentage="round($operationalPercentage, 0)" :stats="$stats['components']" :below-threshold="$componentsBelowThreshold" :out-of-stock="$componentsOutOfStock" />

            <x-dashboard.inventory-card title="Peripherals" from-color="amber-500" to-color="red-500" :percentage="round($peripheralPercentage, 0)"
                :stats="$stats['peripherals']" :below-threshold="$peripheralsBelowThreshold" :out-of-stock="$peripheralsOutOfStock" />
        </div>
    </div>

   
</div>
