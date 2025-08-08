<div>
    <h1 class="text-lg font-bold mb-4">Add Processor to System Unit: {{ $unit->name }}</h1>

    <form wire:submit.prevent="save">
        <div class="mb-4">
            <label class="block font-semibold mb-1">Brand</label>
            <input type="text" wire:model="brand" class="border p-2 w-full" />
            @error('brand') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Model</label>
            <input type="text" wire:model="model" class="border p-2 w-full" />
            @error('model') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Generation</label>
                <input type="text" wire:model="generation" class="border p-2 w-full" />
                @error('generation') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-semibold mb-1">Cores</label>
                <input type="number" wire:model="cores" min="1" class="border p-2 w-full" />
                @error('cores') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Threads</label>
                <input type="number" wire:model="threads" min="1" class="border p-2 w-full" />
                @error('threads') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-semibold mb-1">Base Clock (GHz)</label>
                <input type="text" wire:model="base_clock" placeholder="e.g., 3.6" class="border p-2 w-full" />
                @error('base_clock') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Boost Clock (GHz)</label>
                <input type="text" wire:model="boost_clock" placeholder="e.g., 4.2" class="border p-2 w-full" />
                @error('boost_clock') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-semibold mb-1">Serial Number</label>
                <input type="text" wire:model="serial_number" class="border p-2 w-full" />
                @error('serial_number') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Status</label>
            <select wire:model="status" class="border p-2 w-full">
                <option value="">Select status</option>
                <option value="Working">Working</option>
                <option value="Under Maintenance">Under Maintenance</option>
                <option value="Decommissioned">Decommissioned</option>
            </select>
            @error('status') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Date Purchased</label>
            <input type="date" wire:model="date_purchased" class="border p-2 w-full" />
            @error('date_purchased') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
            Save
        </button>
    </form>
</div>
