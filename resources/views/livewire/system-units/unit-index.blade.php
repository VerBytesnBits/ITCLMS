<!-- resources/views/livewire/system-units/unit-index.blade.php -->
<div>
    <button wire:click="$dispatch('open-unit-form')" 
        class="px-4 py-2 bg-blue-600 text-white rounded-lg">
        + Add Unit
    </button>

    <table class="w-full mt-4 border text-sm">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2">ID</th>
                <th class="p-2">Name</th>
                <th class="p-2">Serial</th>
                <th class="p-2">Room</th>
                <th class="p-2">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($units as $unit)
                <tr class="border-t">
                    <td class="p-2">{{ $unit->id }}</td>
                    <td class="p-2">{{ $unit->name }}</td>
                    <td class="p-2">{{ $unit->serial_number ?? '-' }}</td>
                    <td class="p-2">{{ $unit->room->name }}</td>
                    <td class="p-2">{{ $unit->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @livewire('system-units.unit-form')
</div>
