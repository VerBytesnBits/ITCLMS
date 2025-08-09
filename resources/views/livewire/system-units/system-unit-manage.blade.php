<div>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">
        <div
            class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-5xl
            animate-[fade-in-scale_0.2s_ease-out] max-h-[90vh] overflow-auto relative">
            <button wire:click="$dispatch('closeModal')"
                class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-2xl font-bold"
                aria-label="Close modal">&times;</button>

            {{-- Modal Title --}}
            <h2 class="text-2xl font-bold mb-6 text-center text-zinc-800 dark:text-white">
                Manage System Unit: {{ $selectedUnit->name ?? 'N/A' }}
            </h2>

            {{-- Your existing 3-column layout goes here --}}
            <div class="flex space-x-6 p-6">

                {{-- Left box: System Units in selected unit's room --}}
                <div class="w-1/4 border rounded p-4 overflow-auto max-h-[70vh]">
                    <h2 class="font-bold text-lg mb-4">
                        System Units in Room: {{ $selectedUnit->room->name ?? 'Unknown' }}
                    </h2>

                    @if ($units->isEmpty())
                        <p>No units found in this room.</p>
                    @endif

                    @foreach ($units as $unit)
                        <button wire:click="selectUnit({{ $unit->id }})"
                            class="block w-full text-left mb-2 py-2 px-3 rounded
                            {{ $selectedUnit && $selectedUnit->id === $unit->id ? 'bg-blue-300 font-semibold' : 'hover:bg-gray-100' }}">
                            {{ $unit->name ?? 'Unnamed Unit' }}
                        </button>
                    @endforeach
                </div>

                {{-- Middle box: Component or Peripheral types --}}
                <div class="w-1/4 border rounded p-4 overflow-auto max-h-[70vh]">
                    <div class="mt-2">
                        @php
                            $types = $middleTab === 'components' ? $componentTypes : $peripheralTypes;
                            $selectedType =
                                $middleTab === 'components' ? $selectedComponentType : $selectedPeripheralType;
                        @endphp

                        @foreach ($types as $type)
                            <button wire:click="selectMiddleType('{{ $type }}')"
                                class="block w-full text-left py-2 px-3 mb-1 rounded
                                {{ $selectedType === $type ? 'bg-blue-300 font-semibold' : 'hover:bg-gray-100' }}">
                                {{ ucfirst($type) }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Right box: Items for selected component/peripheral type --}}
                <div class="flex-1 border rounded p-4 overflow-auto max-h-[70vh]">
                    <h2 class="font-bold text-lg mb-4 flex space-x-4">
                        <button wire:click="setMiddleTab('components')"
                            class="{{ $middleTab === 'components' ? 'border-b-2 border-blue-600 font-semibold' : 'text-gray-500' }}">
                            Components
                        </button>
                        <button wire:click="setMiddleTab('peripherals')"
                            class="{{ $middleTab === 'peripherals' ? 'border-b-2 border-blue-600 font-semibold' : 'text-gray-500' }}">
                            Peripherals
                        </button>
                    </h2>
                    <h2 class="font-bold text-lg mb-4">Details</h2>

                    @php
                        $items = collect();
                        $type = $middleTab === 'components' ? $selectedComponentType : $selectedPeripheralType;

                        if ($selectedUnit && $type) {
                            $relation = $selectedUnit->$type ?? collect();

                            // If single model → wrap into collection
                            if ($relation instanceof \Illuminate\Database\Eloquent\Model) {
                                $items = collect([$relation]);
                            }
                            // If already a collection
                            elseif ($relation instanceof \Illuminate\Support\Collection) {
                                $items = $relation;
                            }
                            // If it's an array
                            elseif (is_array($relation)) {
                                $items = collect($relation);
                            }

                            // Special handling for computer case
                            if ($type === 'computerCase' && $items->isNotEmpty()) {
                                                        $items = collect([$items->first()]);
                                                    }
                        }
                    @endphp



                    @if ($items->isEmpty())
                        <p class="text-gray-500">No {{ ucfirst($type) }} added yet.</p>
                        @if (Route::has($type . '.create'))
                            <a href="{{ route($type . '.create', ['unit' => $selectedUnit->id]) }}"
                                class="text-green-600 underline">
                                Add {{ ucfirst($type) }}
                            </a>
                        @endif
                    @else
                        <ul>
                            @foreach ($items as $item)
                                <li>
                                    <button wire:click="selectItem(@js($item))"
                                        class="text-left py-1 px-2 hover:bg-blue-100 rounded
                                        {{ $selectedItem && $selectedItem->id === $item->id ? 'font-semibold bg-blue-200' : '' }}">
                                        {{ $item->brand ?? 'Unknown Brand' }} {{ $item->model ?? '' }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($selectedItem)
                        <div class="mt-4 border-t pt-4">
                            <h3 class="font-bold">{{ $selectedItem->brand ?? '' }} {{ $selectedItem->model ?? '' }}
                            </h3>
                            <p>Status: {{ $selectedItem->status ?? 'Unknown' }}</p>
                            <p>Serial Number: {{ $selectedItem->serial_number ?? 'N/A' }}</p>
                            <p>Date Purchased:
                                {{ $selectedItem->date_purchased ? $selectedItem->date_purchased->format('Y-m-d') : 'N/A' }}
                            </p>
                            <p>Notes: {{ $selectedItem->notes ?? 'None' }}</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
