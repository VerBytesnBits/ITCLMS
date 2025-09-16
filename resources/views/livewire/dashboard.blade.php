<div>
    <livewire:dashboard-heading title="Dashboard" subtitle="" icon="layout-grid" gradient-from-color="#CE4A3E"
        gradient-to-color="#C2CE3E" icon-color="text-orange-500" />
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-4">
        <!-- Units -->
        <div
            class="p-6 bg-white/70 dark:bg-gray-900 backdrop-blur-lg rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">

            <livewire:reports.operational-chart />
        </div>

        <!-- Parts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Components Card -->
            <div
                class="p-6 bg-gradient-to-r from-emerald-500 to-blue-500 rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
                <h3 class="font-bold mb-6 text-lg text-white">Components</h3>

                <!-- Circular Progress -->
                <div class="flex items-center justify-center">
                    <div class="relative w-40 h-40">
                        <svg class="w-full h-full transform -rotate-90">
                            <!-- Background circle -->
                            <circle cx="50%" cy="50%" r="70" stroke="currentColor" class="text-white/30"
                                stroke-width="12" fill="transparent" />

                            <!-- Progress circle (white) -->
                            <circle cx="50%" cy="50%" r="70" stroke="white" stroke-width="12"
                                stroke-linecap="round" fill="transparent" stroke-dasharray="439.8"
                                stroke-dashoffset="{{ 439.8 - (439.8 * $operationalPercentage) / 100 }}"
                                class="transition-all duration-700 ease-out" />
                        </svg>

                        <!-- Percentage text inside -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-3xl font-extrabold text-white">
                                {{ round($operationalPercentage, 0) }}%
                            </span>
                            <span class="text-sm font-medium text-white/80">
                                Available
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Below Threshold -->


                {{-- Low Stock Components --}}
                @if ($componentsBelowThreshold)
                    <flux:callout variant="warning" icon="exclamation-circle"
                        heading="{{ $componentsBelowThreshold }} Low Stock Alerts" />
                @endif

                {{-- Out of Stock Components --}}
                @if ($componentsOutOfStock)
                    <flux:callout variant="danger" icon="x-circle"
                        heading="{{ $componentsOutOfStock }} Out of Stock Alerts" />
                @endif
            </div>

            <!-- Peripherals Card -->
            <div
                class="p-6 bg-gradient-to-r from-amber-500 to-red-500 rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
                <h3 class="font-bold mb-6 text-lg text-white">Peripherals</h3>

                <!-- Circular Progress -->
                <div class="flex items-center justify-center">
                    <div class="relative w-40 h-40">
                        <svg class="w-full h-full transform -rotate-90">
                            <!-- Background circle -->
                            <circle cx="50%" cy="50%" r="70" stroke="currentColor" class="text-white/30"
                                stroke-width="12" fill="transparent" />

                            <!-- Progress circle (white) -->
                            <circle cx="50%" cy="50%" r="70" stroke="white" stroke-width="12"
                                stroke-linecap="round" fill="transparent" stroke-dasharray="439.8"
                                stroke-dashoffset="{{ 439.8 - (439.8 * $peripheralPercentage) / 100 }}"
                                class="transition-all duration-700 ease-out" />
                        </svg>

                        <!-- Percentage text inside -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-3xl font-extrabold text-white">
                                {{ round($peripheralPercentage, 0) }}%
                            </span>
                            <span class="text-sm font-medium text-white/80">
                                Available
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Below Threshold -->
                {{-- Low Stock --}}
                @if ($peripheralsBelowThreshold)
                    <flux:callout variant="warning" icon="exclamation-circle"
                        heading="{{ $peripheralsBelowThreshold }} Low Stock Alerts" />
                @endif

                @if ($peripheralsOutOfStock)
                    <flux:callout variant="danger" icon="x-circle"
                        heading="{{ $peripheralsOutOfStock }} Out of Stock Alerts" />
                @endif
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 gap-8">
        <!-- Defective Units Trend -->
        <div
            class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-xl hover:shadow-2xl transition transform hover:-translate-y-1">
            <h1 class="text-lg font-bold mb-4">Recent Activity</h1>
            {{-- <livewire:defective-units-chart /> --}}

        </div>
    </div>


</div>
