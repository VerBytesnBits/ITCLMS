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
                {{-- Processor --}}
                <li>
                    Processor:
                    {{ $units->processor ? $units->processor->brand . ' ' . $units->processor->model : 'No Processor added.' }}
                </li>

                {{-- Memory (RAM) --}}
                <li>
                    Memory:
                    @if ($unit->memories->isNotEmpty())
                        @foreach ($unit->memories as $memory)
                            {{ $memory->brand }} {{ $memory->model }}<br>
                        @endforeach
                    @else
                        No Memory added.
                    @endif
                </li>

                {{-- Motherboard --}}
                <li>
                    Motherboard:
                    {{ $unit->motherboard ? $unit->motherboard->brand . ' ' . $unit->motherboard->model : 'No Motherboard added.' }}
                </li>

                {{-- Graphics Cards --}}
                <li>
                    Graphics Card:
                    @if ($unit->graphicsCards->isNotEmpty())
                        @foreach ($unit->graphicsCards as $gpu)
                            {{ $gpu->brand }} {{ $gpu->model }}<br>
                        @endforeach
                    @else
                        No Graphics Card added.
                    @endif
                </li>

                {{-- CPU Cooler --}}
                <li>
                    CPU Cooler:
                    {{ $unit->cpuCooler ? $unit->cpuCooler->brand . ' ' . $unit->cpuCooler->model : 'No CPU Cooler added.' }}
                </li>

                {{-- Power Supply --}}
                <li>
                    Power Supply:
                    {{ $unit->powerSupply ? $unit->powerSupply->brand . ' ' . $unit->powerSupply->model : 'No Power Supply added.' }}
                </li>

                {{-- Computer Case --}}
                <li>
                    Computer Case:
                    {{ $unit->computerCase ? $unit->computerCase->brand . ' ' . $unit->computerCase->model : 'No Case added.' }}
                </li>
            </ul>
        </div>
    @endif
    @if ($section === 'peripherals')
        <div>
            <h3 class="font-bold mb-2">Peripherals</h3>

            <ul class="list-disc list-inside space-y-1">
                {{-- Keyboard --}}
                <li>
                    Keyboard:
                    {{ $unit->keyboard ? $unit->keyboard->brand . ' ' . $unit->keyboard->model : 'No Keyboard added.' }}
                </li>

                {{-- Mouse --}}
                <li>
                    Mouse:
                    {{ $unit->mouse ? $unit->mouse->brand . ' ' . $unit->mouse->model : 'No Mouse added.' }}
                </li>

                {{-- Headset --}}
                <li>
                    Headset:
                    {{ $unit->headset ? $unit->headset->brand . ' ' . $unit->headset->model : 'No Headset added.' }}
                </li>

                {{-- Speaker --}}
                <li>
                    Speaker:
                    {{ $unit->speaker ? $unit->speaker->brand . ' ' . $unit->speaker->model : 'No Speaker added.' }}
                </li>

                {{-- Webcam --}}
                <li>
                    Webcam:
                    {{ $unit->webCamera ? $unit->webCamera->brand . ' ' . $unit->webCamera->model : 'No Webcam added.' }}
                </li>

                {{-- Monitors --}}
                <li>
                    Monitors:
                    @if ($unit->monitor->isNotEmpty())
                        @foreach ($unit->monitor as $display)
                            {{ $display->brand }} {{ $display->size }}"<br>
                        @endforeach
                    @else
                        No Monitors added.
                    @endif
                </li>
            </ul>
        </div>
    @endif

</div>
