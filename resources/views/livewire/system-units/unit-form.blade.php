<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4 ">
    <div
        class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-4xl animate-[fade-in-scale_0.2s_ease-out] overflow-y-auto max-h-[90vh]">

        <h2 class="text-xl font-bold mb-4 dark:text-white">
            {{ $modalMode === 'create' ? 'Create System Unit' : 'Edit System Unit' }}
        </h2>

        <form wire:submit.prevent="save" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Unit Name --}}
                <div>
                    <label for="name" class="block mb-1 font-semibold dark:text-white">Unit Name</label>
                    <input type="text" id="name" wire:model.defer="name"
                        class="w-full border rounded px-3 py-2 dark:bg-zinc-700 dark:text-white" />
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Room --}}
                <div>
                    <label for="room_id" class="block mb-1 font-semibold dark:text-white">Room</label>
                    <select id="room_id" wire:model.defer="room_id"
                        class="w-full border rounded px-3 py-2 dark:bg-zinc-700 dark:text-white">
                        <option value="">-- Select Room --</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Processor --}}
                <div class="sm:col-span-2">
                    <label for="processor_id" class="block mb-1 font-semibold dark:text-white">Processor</label>
                    <select id="processor_id" wire:model.defer="processor_id"
                        class="w-full border rounded px-3 py-2 dark:bg-zinc-700 dark:text-white">
                        <option value="">-- Select Processor --</option>
                        @foreach ($processors as $cpu)
                            <option value="{{ $cpu->id }}">
                                {{ $cpu->brand }} {{ $cpu->model }}
                                {{ $cpu->base_clock ? $cpu->base_clock . 'GHz' : '' }}
                                {{ $cpu->boost_clock ? '(Boost ' . $cpu->boost_clock . 'GHz)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('processor_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- CPU Cooler --}}
                <div>
                    <label for="cpu_cooler_id" class="block mb-1 font-semibold dark:text-white">CPU Cooler</label>
                    <select id="cpu_cooler_id" wire:model.defer="cpu_cooler_id"
                        class="w-full border rounded px-3 py-2 dark:bg-zinc-700 dark:text-white">
                        <option value="">-- Select CPU Cooler --</option>
                        @foreach ($cpuCoolers as $cooler)
                            <option value="{{ $cooler->id }}">{{ $cooler->brand }} {{ $cooler->model }}</option>
                        @endforeach
                    </select>
                    @error('cpu_cooler_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Motherboard --}}
                <div>
                    <label for="motherboard_id" class="block mb-1 font-semibold dark:text-white">Motherboard</label>
                    <select id="motherboard_id" wire:model.defer="motherboard_id"
                        class="w-full border rounded px-3 py-2 dark:bg-zinc-700 dark:text-white">
                        <option value="">-- Select Motherboard --</option>
                        @foreach ($motherboards as $board)
                            <option value="{{ $board->id }}">{{ $board->brand }} {{ $board->model }}</option>
                        @endforeach
                    </select>
                    @error('motherboard_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Memory --}}
                <div>
                    <label for="memory_id" class="block mb-1 font-semibold dark:text-white">RAM</label>
                    <select id="memory_id" wire:model.defer="memory_id"
                        class="w-full border rounded px-3 py-2 dark:bg-zinc-700 dark:text-white">
                        <option value="">-- Select RAM --</option>
                        @foreach ($memories as $ram)
                            <option value="{{ $ram->id }}">{{ $ram->type }} {{ $ram->capacity }}GB</option>
                        @endforeach
                    </select>
                    @error('memory_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Graphics Card --}}
                <div>
                    <label for="graphics_card_id" class="block mb-1 font-semibold dark:text-white">Graphics Card</label>
                    <select id="graphics_card_id" wire:model.defer="graphics_card_id"
                        class="w-full border rounded px-3 py-2 dark:bg-zinc-700 dark:text-white">
                        <option value="">-- Select GPU --</option>
                        @foreach ($graphicsCards as $gpu)
                            <option value="{{ $gpu->id }}">{{ $gpu->brand }} {{ $gpu->model }}</option>
                        @endforeach
                    </select>
                    @error('graphics_card_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Drive --}}
                <div class="sm:col-span-2">
                    <label for="drive" class="block mb-1 font-semibold dark:text-white">Select Drive</label>
                    <select id="drive" wire:model="drive_id"
                        class="w-full border rounded px-3 py-2 dark:bg-zinc-700 dark:text-white">
                        <option value="">-- Select Drive --</option>
                        <optgroup label="M.2 SSDs">
                            @foreach ($m2Ssds as $ssd)
                                <option value="m2|{{ $ssd->id }}">{{ $ssd->brand }} {{ $ssd->model }}
                                    ({{ $ssd->capacity }}GB)
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="SATA SSDs">
                            @foreach ($sataSsds as $ssd)
                                <option value="sata|{{ $ssd->id }}">{{ $ssd->brand }} {{ $ssd->model }}
                                    ({{ $ssd->capacity }}GB)
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Hard Disk Drives">
                            @foreach ($hardDiskDrives as $hdd)
                                <option value="hdd|{{ $hdd->id }}">{{ $hdd->brand }} {{ $hdd->model }}
                                    ({{ $hdd->capacity }}GB)
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                {{-- Power Supply --}}
                <div>
                    <label for="power_supply_id" class="block mb-1 font-semibold dark:text-white">Power Supply</label>
                    <select id="power_supply_id" wire:model.defer="power_supply_id"
                        class="w-full border rounded px-3 py-2 dark:bg-zinc-700 dark:text-white">
                        <option value="">-- Select PSU --</option>
                        @foreach ($powerSupplies as $psu)
                            <option value="{{ $psu->id }}">{{ $psu->brand }} {{ $psu->wattage }}W</option>
                        @endforeach
                    </select>
                    @error('power_supply_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Case --}}
                <div>
                    <label for="computer_case_id" class="block mb-1 font-semibold dark:text-white">Case</label>
                    <select id="computer_case_id" wire:model.defer="computer_case_id"
                        class="w-full border rounded px-3 py-2 dark:bg-zinc-700 dark:text-white">
                        <option value="">-- Select Case --</option>
                        @foreach ($computerCases as $case)
                            <option value="{{ $case->id }}">{{ $case->brand }} {{ $case->model }}</option>
                        @endforeach
                    </select>
                    @error('computer_case_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block mb-1 font-semibold dark:text-white">Status</label>
                    <select id="status" wire:model.defer="status" class="...">
                        @foreach ($statuses as $statusOption)
                            <option value="{{ $statusOption }}">{{ $statusOption }}</option>
                        @endforeach
                    </select>

                    @error('status')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            {{-- Buttons --}}
            <div class="flex justify-end space-x-2 pt-4">
                <button type="button" wire:click="$dispatch('closeModal')"
                    class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 dark:bg-zinc-600 dark:hover:bg-zinc-700">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    {{ $modalMode === 'create' ? 'Save' : 'Update' }}
                </button>
            </div>
        </form>
    </div>
</div>
