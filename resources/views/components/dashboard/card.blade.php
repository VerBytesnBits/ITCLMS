@props(['title', 'value' => 0, 'color' => 'blue', 'action' => null])

<div class="bg-white dark:bg-zinc-900 border border-neutral-200 dark:border-neutral-700 
            rounded-xl p-6 shadow flex flex-col justify-between">
    <div>
        <h3 class="text-lg font-semibold mb-2">{{ $title }}</h3>
        <p class="text-3xl font-bold">{{ $value }}</p>
    </div>
    <button 
        @if($action) wire:click="{{ $action }}" @endif
        class="mt-4 px-4 py-2 bg-{{ $color }}-600 text-white rounded hover:bg-{{ $color }}-700 self-start">
        View
    </button>
</div>
