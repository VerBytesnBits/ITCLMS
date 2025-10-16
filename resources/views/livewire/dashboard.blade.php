<div class="space-y-4">
    <!-- Heading -->
    <livewire:dashboard-heading title="Dashboard" subtitle="Overview of IT Computer Laboratory Management System"
        icon="layout-grid" gradient-from-color="#CE4A3E" gradient-to-color="#C2CE3E" icon-color="text-orange-500" />

    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <x-dashboard.stats-card title="Total Units" :value="$totalUnits" iconBg="bg-green-500" />
        <x-dashboard.stats-card title="Total Components" :value="$totalComponents" iconBg="bg-purple-500" />
        <x-dashboard.stats-card title="Total Peripherals" :value="$totalPeripherals" iconBg="bg-blue-500" />
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

    <!-- Recent Activity Logs -->
    <div
        class="p-6 bg-white dark:bg-zinc-800 rounded-2xl shadow-lg border border-zinc-200 dark:border-zinc-700
           hover:shadow-2xl transition transform hover:-translate-y-1">

        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            {{-- <h3 class="text-lg font-semibold flex items-center gap-2 text-zinc-800 dark:text-zinc-100">

            </h3> --}}
            <flux:heading size="lg" level="1" class="text-lg flex items-center gap-2  text-zinc-600 ">
                <flux:icon.activity class="w-5 h-5 text-blue-500" />
                Recent Activity
            </flux:heading>
            <a href="{{ route('activitylogs') }}" class="text-zinc-600 dark:hover:text-blue-300 hover:text-blue-500">
                <flux:icon.eye />
            </a>
        </div>

        <!-- Logs List -->
        <ul class="divide-y divide-zinc-200 dark:divide-zinc-700">
            @forelse($recentLogs as $log)
                <li class="py-3 text-sm text-zinc-700 dark:text-zinc-300 flex justify-between items-center">
                    <div>
                        <span class="font-medium">{{ $log->description }}</span>
                        <span class="text-xs text-zinc-500">by {{ $log->causer?->name ?? 'System' }}</span>
                    </div>
                    <div class="text-xs text-zinc-400">{{ $log->created_at->diffForHumans() }}</div>
                </li>
            @empty
                <li class="py-3 text-sm text-zinc-500 italic">No recent activity logs</li>
            @endforelse
        </ul>
    </div>

</div>
