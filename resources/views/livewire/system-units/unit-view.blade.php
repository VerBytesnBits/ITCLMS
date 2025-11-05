<div x-data x-on:keydown.escape.window="$dispatch('closeModal')"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div x-transition
        class="w-full max-w-4xl max-h-[90vh] bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl overflow-hidden flex flex-col">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <flux:icon.computer-desktop class="w-5 h-5 text-blue-100" />
                Computer Unit Details
            </h2>
            <button wire:click="$dispatch('closeModal')" class="p-2 rounded-full hover:bg-blue-500/20 transition"
                title="Close">
                <flux:icon.x class="w-5 h-5 text-white" />
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 flex-1 overflow-y-auto space-y-8">

            @if ($unit)
                <!-- Section: Basic Info -->
                <div class="space-y-3 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/40">
                    <h3
                        class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-300 tracking-wider flex items-center gap-2">
                        <flux:icon.info class="w-4 h-4 text-blue-500" /> Basic Information
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-2 rounded-md bg-white dark:bg-zinc-800 shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Unit Name</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $unit['name'] }}</p>
                        </div>

                        <div class="p-2 rounded-md bg-white dark:bg-zinc-800 shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Serial Number</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">
                                {{ $unit['serial_number'] ?? 'N/A' }}</p>
                        </div>

                        <div class="p-2 rounded-md bg-white dark:bg-zinc-800 shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Room</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $unit['room']->name ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="p-2 rounded-md bg-white dark:bg-zinc-800 shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                            <div class="flex items-center gap-2">
                                @if ($unit['status'] === 'Operational')
                                    <flux:icon.circle-check class="w-4 h-4 text-green-600" />
                                    <span class="font-semibold text-green-700 dark:text-green-400">Operational</span>
                                @else
                                    <flux:icon.circle-x class="w-4 h-4 text-red-600" />
                                    <span
                                        class="font-semibold text-red-700 dark:text-red-400">{{ $unit['status'] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Section: Components & Peripherals -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Components -->
                    <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/40">
                        <h3 class="text-sm font-semibold text-blue-700 dark:text-blue-300 mb-3 flex items-center gap-2">
                            <flux:icon.cpu-chip class="w-5 h-5" /> Components
                        </h3>

                        <div class="space-y-3 max-h-[40vh] overflow-y-auto">
                            @forelse ($unit->components ?? [] as $component)
                                @php
                                    $icon = $partIcons[$component->part] ?? asset('images/icons/default.png');
                                @endphp
                                <div
                                    class="relative flex items-start gap-3 p-3 rounded-md bg-white dark:bg-zinc-800 shadow-sm transition hover:bg-blue-50 dark:hover:bg-zinc-700">
                                    <img src="{{ asset($icon) }}" class="w-10 h-10" alt="{{ $component->part }} icon">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                            {{ $component->part }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $component->brand }} {{ $component->model }}
                                            ({{ $component->serial_number ?? 'N/A' }})
                                        </p>
                                    </div>

                                    <div class="absolute top-2 right-2 flex items-center gap-1 text-xs font-semibold">
                                        @if ($component->status === 'In Use')
                                            <flux:icon.circle-check class="w-3.5 h-3.5 text-green-600" />
                                            <span class="text-green-700">Operational</span>
                                        @else
                                            <flux:icon.triangle-alert class="w-3.5 h-3.5 text-red-600" />
                                            <span class="text-red-700">{{ $component->status }}</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 italic">No components assigned.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Peripherals -->
                    <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/40">
                        <h3 class="text-sm font-semibold text-blue-700 dark:text-blue-300 mb-3 flex items-center gap-2">
                            <flux:icon.monitor class="w-5 h-5" /> Peripherals
                        </h3>

                        <div class="space-y-3 max-h-[40vh] overflow-y-auto">
                            @forelse ($unit->peripherals ?? [] as $peripheral)
                                @php
                                    $icon = $partIcons[$peripheral->type] ?? asset('images/icons/default.png');
                                @endphp
                                <div
                                    class="relative flex items-start gap-3 p-3 rounded-md bg-white dark:bg-zinc-800 shadow-sm transition hover:bg-blue-50 dark:hover:bg-zinc-700">
                                    <img src="{{ asset($icon) }}" class="w-10 h-10"
                                        alt="{{ $peripheral->type }} icon">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                            {{ $peripheral->type }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $peripheral->brand }}
                                            {{ $peripheral->model }}</p>
                                    </div>

                                    @if (isset($peripheral->status))
                                        <div
                                            class="absolute top-2 right-2 flex items-center gap-1 text-xs font-semibold">
                                            @if ($peripheral->status === 'In Use')
                                                <flux:icon.circle-check class="w-3.5 h-3.5 text-green-600" />
                                                <span class="text-green-700">Operational</span>
                                            @else
                                                <flux:icon.triangle-alert class="w-3.5 h-3.5 text-red-600" />
                                                <span class="text-red-700">{{ $peripheral->status }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 italic">No peripherals assigned.</p>
                            @endforelse
                        </div>
                    </div>

                </div>

              
                <livewire:system-units.activity-log :systemUnitId="$unit->id" />



            @endif
        </div>

        <!-- Footer -->
        <div
            class="px-6 py-4 bg-gray-50 dark:bg-zinc-800 border-t border-gray-200 dark:border-zinc-700 flex justify-end">
            <button wire:click="$dispatch('closeModal')"
                class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition">
                Close
            </button>
        </div>
    </div>
</div>
