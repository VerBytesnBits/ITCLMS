<div x-data="{ open: @entangle('show') }" x-show="open" x-cloak
     class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
    <div class="bg-white p-6 rounded-2xl shadow-lg w-full max-w-4xl overflow-auto">
        <h2 class="text-lg font-semibold mb-4">Restore Unit: {{ $unit->name ?? '' }}</h2>

        <div class="mb-4">
            <h3 class="font-medium">Components</h3>
            @foreach($unit->components ?? [] as $component)
                <div class="flex items-center gap-2">
                    <input type="checkbox" wire:model="selectedComponents" value="{{ $component->id }}">
                    <span>{{ $component->part ?? 'N/A' }} ({{ $component->serial_number ?? 'N/A' }})</span>
                </div>
            @endforeach
        </div>

        <div class="mb-4">
            <h3 class="font-medium">Peripherals</h3>
            @foreach($unit->peripherals ?? [] as $peripheral)
                <div class="flex items-center gap-2">
                    <input type="checkbox" wire:model="selectedPeripherals" value="{{ $peripheral->id }}">
                    <span>{{ $peripheral->name ?? 'N/A' }} ({{ $peripheral->serial_number ?? 'N/A' }})</span>
                </div>
            @endforeach
        </div>

        <div class="flex justify-end gap-2">
            <button wire:click="close" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
            <button wire:click="restore" class="px-4 py-2 bg-emerald-600 text-white rounded">Restore Selected</button>
        </div>
    </div>
</div>
