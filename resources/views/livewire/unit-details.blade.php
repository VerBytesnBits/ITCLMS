<div>
    <h2 class="text-xl font-semibold mb-4">Unit Details: {{ $unit->name }}</h2>

    <div class="mb-4 space-x-4">
        <button wire:click="$dispatch('showSection', 'components')"
            class="px-4 py-2 rounded {{ $section === 'components' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
            Components
        </button>
        <button wire:click="$dispatch('showSection', 'peripherals')"
            class="px-4 py-2 rounded {{ $section === 'peripherals' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
            Peripherals
        </button>
    </div>

    @if ($section === 'components')
        <div>
            <h3 class="font-bold mb-2">Components</h3>

            <ul class="list-disc list-inside space-y-1">
                @if ($unit->processors->isNotEmpty())
                    <li>Processor: {{ $unit->processors->first()->brand }} {{ $unit->processors->first()->model }} </li>
                @else
                    <li>No Processor added.</li>
                @endif

                @if ($unit->memories->isNotEmpty())
                    <li>Memory: {{ $unit->memories->first()->brand }} {{ $unit->memories->first()->model }}</li>
                @else
                    <li>No Memory added.</li>
                @endif

                {{-- Add more component lists similarly --}}
            </ul>
        </div>
    @elseif ($section === 'peripherals')
        <div>
            <h3 class="font-bold mb-2">Peripherals</h3>

            <ul class="list-disc list-inside space-y-1">
                @if ($unit->keyboard->isNotEmpty())
                    <li>Keyboard: {{ $unit->keyboard->first()->brand }} {{ $unit->keyboard->first()->model }}</li>
                @else
                    <li>No Keyboard added.</li>
                @endif

                @if ($unit->mouse->isNotEmpty())
                    <li>Mouse: {{ $unit->mouse->first()->brand }} {{ $unit->mouse->first()->model }}</li>
                @else
                    <li>No Mouse added.</li>
                @endif

                {{-- Add more peripheral lists similarly --}}
            </ul>
        </div>
    @endif
</div>
