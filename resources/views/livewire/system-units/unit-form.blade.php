<div x-data="{ startX: 0, endX: 0 }" x-on:touchstart="startX = $event.touches[0].clientX"
    x-on:touchend="endX = $event.changedTouches[0].clientX; if (startX - endX > 100) { $wire.dispatch('closeModal') }"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">

    <div
        class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-6xl
        animate-[fade-in-scale_0.2s_ease-out] max-h-[90vh] overflow-auto relative">

        {{-- Custom Close Button --}}
        <button wire:click="$dispatch('closeModal')"
            class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-2xl font-bold"
            aria-label="Close modal">&times;
        </button>

        {{-- Title --}}
        <h2 class="text-2xl font-bold mb-6 text-center text-zinc-800 dark:text-white">
            {{ $modalMode === 'edit' ? 'Edit Unit' : 'Add Unit' }}
        </h2>

        {{-- Unit Info Fields --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block mb-1 font-semibold">Unit Name</label>
                <input type="text" wire:model.defer="name" @if ($modalMode === 'create') readonly @endif
                    class="w-full rounded-md border-gray-300 dark:bg-zinc-700 dark:border-zinc-600">
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block mb-1 font-semibold">Room</label>
                <select wire:model="room_id" wire:change="regenerateName($event.target.value)"
                    class="w-full rounded-md border-gray-300 dark:bg-zinc-700 dark:border-zinc-600">
                    <option value="">-- Select Room --</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                </select>

                @error('room_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block mb-1 font-semibold">Status</label>
                <select wire:model.defer="status"
                    class="w-full rounded-md border-gray-300 dark:bg-zinc-700 dark:border-zinc-600">
                    <option value="Operational">Operational</option>
                    <option value="Needs Repair">Needs Repair</option>
                    <option value="Non-Operational">Non-Operational</option>
                </select>
                @error('status')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Middle Tabs --}}
        <div class="flex mb-4 border-b">
            <button wire:click="setMiddleTab('components')"
                class="px-4 py-2 {{ $middleTab === 'components' ? 'border-b-2 border-blue-600 font-semibold' : '' }}">
                Components
            </button>
            <button wire:click="setMiddleTab('peripherals')"
                class="px-4 py-2 {{ $middleTab === 'peripherals' ? 'border-b-2 border-blue-600 font-semibold' : '' }}">
                Peripherals
            </button>
        </div>

        {{-- 2-Column Layout --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            {{-- Left Column: Types --}}
            <div class="border rounded-md p-2 overflow-auto max-h-[60vh]">
                @php
                    $types = $middleTab === 'components' ? $componentTypes : $peripheralTypes;
                    $selectedType = $middleTab === 'components' ? $selectedComponentType : $selectedPeripheralType;
                @endphp

                @foreach ($types as $type)
                    <button wire:click="selectMiddleType('{{ $type }}')"
                        class="block w-full text-left px-3 py-2 rounded mb-1
                        {{ $selectedType === $type ? 'bg-blue-300 font-semibold' : 'hover:bg-gray-100' }}">
                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                    </button>
                @endforeach
            </div>

            {{-- Right Column: Items --}}
            <div class="md:col-span-3 border rounded-md p-2 overflow-auto max-h-[60vh]">
                @if ($selectedType)
                    @php
                        $list =
                            $middleTab === 'components'
                                ? $availableComponents[$selectedType] ?? []
                                : $availablePeripherals[$selectedType] ?? [];

                        $selectedList =
                            $middleTab === 'components'
                                ? $unitSelections['components'][$selectedType] ?? []
                                : $unitSelections['peripherals'][$selectedType] ?? [];
                    @endphp

                    {{-- Add/Edit Form --}}
                    @if ($formMode ?? false)
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold">
                                {{ $editingPartId ? 'Edit ' : 'Add ' }}
                                {{ ucfirst(str_replace('_', ' ', $selectedType)) }}
                            </h3>
                            <button wire:click="$set('formMode', false)"
                                class="bg-gray-500 text-white px-2 py-1 rounded">
                                Back
                            </button>
                        </div>

                        <livewire:part-form :unitId="$unitId ?? null" :type="$selectedType" :partId="$editingPartId"
                            key="{{ $selectedType . '-' . ($editingPartId ?? 'new') }}" />
                    @else
                        {{-- Available Items --}}
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-semibold">Available {{ ucfirst(str_replace('_', ' ', $selectedType)) }}
                            </h3>
                            <button wire:click="$set('formMode', true); $set('editingPartId', null)"
                                class="bg-green-500 text-white px-2 py-1 rounded">+ Add</button>
                        </div>

                        @forelse ($list as $item)
                            @php $itemId = $item['id'] ?? $item['temp_id']; @endphp
                            <div class="flex justify-between items-center border-b py-1">
                                <span>{{ $item['brand'] ?? '' }} {{ $item['model'] ?? '' }}</span>
                                <div class="flex gap-2">
                                    <button
                                        wire:click="$set('formMode', true); $set('editingPartId', {{ $item['id'] ?? 'null' }})"
                                        class="bg-yellow-500 text-white px-2 py-1 rounded text-sm">
                                        Edit
                                    </button>
                                    <button wire:click="addToUnit('{{ $selectedType }}', '{{ $itemId }}')"
                                        class="bg-blue-500 text-white px-2 py-1 rounded text-sm">
                                        + Add
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No items found.</p>
                        @endforelse

                        {{-- Selected Items --}}
                        @if (!empty($selectedList))
                            <h3 class="font-semibold mt-4 mb-2">Selected</h3>
                            @foreach ($selectedList as $sel)
                                @php $selId = $sel['id'] ?? $sel['temp_id']; @endphp
                                <div class="flex justify-between items-center border-b py-1">
                                    <span>{{ $sel['brand'] ?? '' }} {{ $sel['model'] ?? '' }}</span>
                                    <button wire:click="removeFromUnit('{{ $selectedType }}', '{{ $selId }}')"
                                        class="bg-red-500 text-white px-2 py-1 rounded text-sm">
                                        Remove
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    @endif
                @else
                    <p class="text-gray-500">Select a type from the left to view items.</p>
                @endif
            </div>


        </div>

        {{-- Save Button --}}
        <div class="mt-6 flex justify-end gap-2">
            <button wire:click="$dispatch('closeModal')"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                Cancel
            </button>
            <button wire:click="save" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                Save Unit
            </button>
        </div>
    </div>
</div>
