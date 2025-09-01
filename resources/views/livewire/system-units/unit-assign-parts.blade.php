<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
    <div class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh] overflow-auto">

        {{-- Title --}}
        <h2 class="text-2xl font-bold mb-6 text-center text-zinc-800 dark:text-white">
            Assign Parts to {{ $unit->name ?? 'Unit' }}
        </h2>

        {{-- Tabs --}}
        <div class="flex space-x-6 border-b mb-6 justify-center">
            <button wire:click="$set('tab', 'peripherals')" 
                class="pb-2 {{ $tab === 'peripherals' ? 'border-b-2 border-blue-600 font-semibold text-blue-600' : 'hover:text-blue-500' }}">
                Peripherals
            </button>
            <button wire:click="$set('tab', 'components')" 
                class="pb-2 {{ $tab === 'components' ? 'border-b-2 border-blue-600 font-semibold text-blue-600' : 'hover:text-blue-500' }}">
                Components
            </button>
        </div>

        {{-- Peripherals Tab --}}
        @if($tab === 'peripherals')
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Left Column: Peripheral Types --}}
                <div class="border rounded-md p-2 overflow-auto max-h-[60vh]">
                    @foreach($availablePeripherals as $type => $list)
                        <button wire:click="$set('selectedType', '{{ $type }}')"
                            class="block w-full text-left px-3 py-2 rounded mb-1
                            {{ ($selectedType ?? null) === $type ? 'bg-blue-300 font-semibold' : 'hover:bg-gray-100' }}">
                            {{ ucfirst($type) }}
                        </button>
                    @endforeach
                </div>

                {{-- Right Column --}}
                <div class="md:col-span-3 border rounded-md p-2 overflow-auto max-h-[60vh]">
                    @if($selectedType)
                        @php
                            $available = $availablePeripherals[$selectedType] ?? [];
                            $assignedId = $selectedPeripherals[$selectedType] ?? null;
                            $assigned = $assignedId ? \App\Models\Peripheral::find($assignedId) : null;
                        @endphp

                        <h3 class="font-semibold mb-2">Available {{ ucfirst($selectedType) }}</h3>
                        @forelse($available as $peripheral)
                            <div class="flex justify-between items-center border-b py-1">
                                <span>{{ $peripheral->brand ?? '' }} {{ $peripheral->model ?? '' }}
                                    ({{ $peripheral->serial_number ?? 'No Serial' }})
                                </span>
                                <button wire:click="assignSelected('{{ $selectedType }}', {{ $peripheral->id }})"
                                    class="bg-blue-500 text-white px-2 py-1 rounded text-sm">
                                    Assign
                                </button>
                            </div>
                        @empty
                            <p class="text-gray-500">No available {{ $selectedType }} peripherals.</p>
                        @endforelse

                        @if($assigned)
                            <h3 class="font-semibold mt-4 mb-2">Assigned {{ ucfirst($selectedType) }}</h3>
                            <div class="flex justify-between items-center border-b py-1">
                                <span>{{ $assigned->brand ?? '' }} {{ $assigned->model ?? '' }}
                                    ({{ $assigned->serial_number ?? 'No Serial' }})
                                </span>
                                <button wire:click="unassign('{{ $selectedType }}')"
                                    class="bg-red-500 text-white px-2 py-1 rounded text-sm">
                                    Unassign
                                </button>
                            </div>
                        @endif
                    @else
                        <p class="text-gray-500">Select a peripheral type from the left column.</p>
                    @endif
                </div>
            </div>
        @endif

        {{-- Components Tab --}}
        @if($tab === 'components')
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Left Column: Component Parts --}}
                <div class="border rounded-md p-2 overflow-auto max-h-[60vh]">
                    @foreach($availableComponents as $part => $list)
                        <button wire:click="$set('selectedPart', '{{ $part }}')"
                            class="block w-full text-left px-3 py-2 rounded mb-1
                            {{ ($selectedPart ?? null) === $part ? 'bg-blue-300 font-semibold' : 'hover:bg-gray-100' }}">
                            {{ ucfirst($part) }}
                        </button>
                    @endforeach
                </div>

                {{-- Right Column --}}
                <div class="md:col-span-3 border rounded-md p-2 overflow-auto max-h-[60vh]">
                    @if($selectedPart)
                        @php
                            $available = $availableComponents[$selectedPart] ?? [];
                            $assignedId = $selectedComponents[$selectedPart] ?? null;
                            $assigned = $assignedId ? \App\Models\ComponentParts::find($assignedId) : null;
                        @endphp

                        <h3 class="font-semibold mb-2">Available {{ ucfirst($selectedPart) }}</h3>
                        @forelse($available as $component)
                            <div class="flex justify-between items-center border-b py-1">
                                <span>{{ $component->brand ?? '' }} {{ $component->model ?? '' }}
                                    ({{ $component->serial_number ?? 'No Serial' }})
                                </span>
                                <button wire:click="assignComponent('{{ $selectedPart }}', {{ $component->id }})"
                                    class="bg-blue-500 text-white px-2 py-1 rounded text-sm">
                                    Assign
                                </button>
                            </div>
                        @empty
                            <p class="text-gray-500">No available {{ $selectedPart }} components.</p>
                        @endforelse

                        @if($assigned)
                            <h3 class="font-semibold mt-4 mb-2">Assigned {{ ucfirst($selectedPart) }}</h3>
                            <div class="flex justify-between items-center border-b py-1">
                                <span>{{ $assigned->brand ?? '' }} {{ $assigned->model ?? '' }}
                                    ({{ $assigned->serial_number ?? 'No Serial' }})
                                </span>
                                <button wire:click="unassignComponent('{{ $selectedPart }}')"
                                    class="bg-red-500 text-white px-2 py-1 rounded text-sm">
                                    Unassign
                                </button>
                            </div>
                        @endif
                    @else
                        <p class="text-gray-500">Select a component part from the left column.</p>
                    @endif
                </div>
            </div>
        @endif

        {{-- Footer --}}
        <div class="flex justify-end mt-6 gap-2">
            <button wire:click="$dispatch('closeAssignModal')"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Close
            </button>
        </div>
    </div>
</div>
