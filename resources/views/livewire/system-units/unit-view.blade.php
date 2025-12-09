<div x-data x-on:keydown.escape.window="$dispatch('closeModal')"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    
    <div x-transition
        class="w-full max-w-5xl max-h-[90vh] bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl overflow-hidden flex flex-col border border-gray-200/60 dark:border-zinc-700/40">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <flux:icon.computer-desktop class="w-5 h-5 text-blue-100" />
                Computer Unit Details
            </h2>
            <button wire:click="$dispatch('closeModal')" 
                class="p-2 rounded-full hover:bg-red-500 transition" title="Close">
                <flux:icon.x class="w-5 h-5 text-white" />
            </button>
        </div>

        <!-- Body -->
        <div class="flex-1 overflow-y-auto p-8 space-y-10">
            @if ($unit)
                <!-- Section: Basic Info -->
                <div class="space-y-5">
                    <h3 class="text-sm font-semibold uppercase text-gray-600 dark:text-gray-400 tracking-wider flex items-center gap-2">
                        Basic Information
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
                        @foreach ([
                            ['label' => 'Unit Name', 'value' => $unit['name']],
                            ['label' => 'Serial Number', 'value' => $unit['serial_number'] ?? 'N/A'],
                            ['label' => 'Room', 'value' => $unit['room']->name ?? 'N/A'],
                            ['label' => 'Status', 'value' => $unit['status']]
                        ] as $item)
                            <div class="p-4 rounded-lg bg-white dark:bg-zinc-800 border border-gray-200/50 dark:border-zinc-700">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium tracking-wider">
                                    {{ $item['label'] }}
                                </p>
                                @if ($item['label'] === 'Status')
                                    <div class="flex items-center gap-2 mt-1.5">
                                        @if ($unit['status'] === 'Operational')
                                            <flux:icon.circle-check class="w-4 h-4 text-green-600" />
                                            <span class="font-semibold text-green-700 dark:text-green-400">Operational</span>
                                        @else
                                            <flux:icon.circle-x class="w-4 h-4 text-red-600" />
                                            <span class="font-semibold text-red-700 dark:text-red-400">{{ $item['value'] }}</span>
                                        @endif
                                    </div>
                                @else
                                    <p class="mt-1.5 text-sm font-semibold text-gray-800 dark:text-gray-200">
                                        {{ $item['value'] }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Section: Components & Peripherals -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Components -->
                    <div>
                        <h3 class="text-sm font-semibold uppercase mb-4 flex items-center gap-2 text-gray-600 dark:text-gray-400 tracking-wider">
                            Components
                        </h3>

                        <div class="space-y-3 max-h-[40vh] overflow-y-auto pr-2">
                            @forelse ($unit->components ?? [] as $component)
                                @php
                                    $icon = $partIcons[$component->part] ?? asset('images/icons/default.png');
                                @endphp
                                <div
                                    class="relative flex items-center gap-4 p-3 rounded-lg bg-white dark:bg-zinc-800 border border-gray-200/50 dark:border-zinc-700 shadow-sm hover:shadow-md transition">
                                    <img src="{{ asset($icon) }}" class="w-10 h-10 flex-shrink-0 rounded-md bg-gray-100 dark:bg-zinc-700 p-1" alt="{{ $component->part }} icon">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">
                                            {{ $component->part }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                            {{ $component->brand }} {{ $component->model }}
                                            ({{ $component->serial_number ?? 'N/A' }})
                                        </p>
                                    </div>

                                    <div class="absolute top-2 right-3 flex items-center gap-1 text-xs font-semibold">
                                        @if ($component->status === 'In Use')
                                            <flux:icon.circle-check class="w-3.5 h-3.5 text-green-600" />
                                            <span class="text-green-700 dark:text-green-400">OK</span>
                                        @else
                                            <flux:icon.triangle-alert class="w-3.5 h-3.5 text-red-600" />
                                            <span class="text-red-700 dark:text-red-400">{{ $component->status }}</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 italic">No components assigned.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Peripherals -->
                    <div>
                        <h3 class="text-sm font-semibold uppercase mb-4 flex items-center gap-2 text-gray-600 dark:text-gray-400 tracking-wider">
                           Peripherals
                        </h3>

                        <div class="space-y-3 max-h-[40vh] overflow-y-auto pr-2">
                            @forelse ($unit->peripherals ?? [] as $peripheral)
                                @php
                                    $icon = $partIcons[$peripheral->type] ?? asset('images/icons/default.png');
                                @endphp
                                <div
                                    class="relative flex items-center gap-4 p-3 rounded-lg bg-white dark:bg-zinc-800 border border-gray-200/50 dark:border-zinc-700 shadow-sm hover:shadow-md transition">
                                    <img src="{{ asset($icon) }}" class="w-10 h-10 flex-shrink-0 rounded-md bg-gray-100 dark:bg-zinc-700 p-1" alt="{{ $peripheral->type }} icon">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">
                                            {{ $peripheral->type }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                            {{ $peripheral->brand }} {{ $peripheral->model }}
                                            ({{ $peripheral->serial_number ?? 'N/A' }})
                                        </p>
                                    </div>

                                    <div class="absolute top-2 right-3 flex items-center gap-1 text-xs font-semibold">
                                        @if ($peripheral->status === 'In Use')
                                            <flux:icon.circle-check class="w-3.5 h-3.5 text-green-600" />
                                            <span class="text-green-700 dark:text-green-400">OK</span>
                                        @else
                                            <flux:icon.triangle-alert class="w-3.5 h-3.5 text-red-600" />
                                            <span class="text-red-700 dark:text-red-400">{{ $peripheral->status }}</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 italic">No peripherals assigned.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Activity Log -->
                <div class="mt-10">
                    <livewire:system-units.activity-log :systemUnitId="$unit->id" />
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="px-8 py-4 bg-gray-50 dark:bg-zinc-800 border-t border-gray-200 dark:border-zinc-700 flex justify-end">
            <button wire:click="$dispatch('closeModal')"
                class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium shadow-sm transition">
                Close
            </button>
        </div>
    </div>
</div>
