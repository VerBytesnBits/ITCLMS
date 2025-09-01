<div class="w-full">
    <!-- Tabs -->
    <div class="flex flex-wrap gap-2 mb-6 border-b border-gray-200 dark:border-zinc-700">
        <button 
            wire:click="setTab('cpu')" 
            class="px-4 py-2 text-sm font-medium rounded-t-lg transition 
                   {{ $tab==='cpu' ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-zinc-700' }}">
            CPU
        </button>

        <button 
            wire:click="setTab('motherboard')" 
            class="px-4 py-2 text-sm font-medium rounded-t-lg transition 
                   {{ $tab==='motherboard' ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-zinc-700' }}">
            Motherboard
        </button>

        <button 
            wire:click="setTab('ram')" 
            class="px-4 py-2 text-sm font-medium rounded-t-lg transition 
                   {{ $tab==='ram' ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-zinc-700' }}">
            RAM
        </button>

        <button 
            wire:click="setTab('drive')" 
            class="px-4 py-2 text-sm font-medium rounded-t-lg transition 
                   {{ $tab==='drive' ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-zinc-700' }}">
            Drive
        </button>

        <button 
            wire:click="setTab('gpu')" 
            class="px-4 py-2 text-sm font-medium rounded-t-lg transition 
                   {{ $tab==='gpu' ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-zinc-700' }}">
            GPU
        </button>

        <button 
            wire:click="setTab('psu')" 
            class="px-4 py-2 text-sm font-medium rounded-t-lg transition 
                   {{ $tab==='psu' ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-zinc-700' }}">
            PSU
        </button>

        <button 
            wire:click="setTab('computer_case')" 
            class="px-4 py-2 text-sm font-medium rounded-t-lg transition 
                   {{ $tab==='computer_case' ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-zinc-700' }}">
            Case
        </button>
    </div>

    <!-- Render selected tab -->
    <div>
        @if($tab === 'cpu')
            @livewire('components.cpu.index')
        @elseif($tab === 'motherboard')
            @livewire('components.motherboard.index')
        @elseif($tab === 'ram')
            @livewire('components.ram.index')
        @elseif($tab === 'drive')
            @livewire('components.drive.index')
        @elseif($tab === 'gpu')
            @livewire('components.gpu.index')
        @elseif($tab === 'psu')
            @livewire('components.psu.index')
        @elseif($tab === 'computer_case')
            @livewire('components.computer-case.index')
        @endif
    </div>
</div>
