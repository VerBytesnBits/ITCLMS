<div class="relative w-full max-w-md">
    <!-- Flux Search Input -->
    <flux:input 
        type="text"
        placeholder="Search..."
        wire:model.live.debounce.300ms="query"
        icon="magnifying-glass"
        class="!rounded-xl !border !border-gray-300 dark:!border-zinc-700 !focus:ring-2 !focus:ring-blue-500 !dark:bg-zinc-900 !dark:text-gray-200"
    />

    <!-- Dropdown Results -->
    @if(!empty($results))
        <div class="absolute z-50 mt-2 w-full bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-lg max-h-64 overflow-y-auto">
            @foreach($results as $model => $items)
                @if(count($items))
                    <!-- Model Name -->
                    <div class="px-4 py-2 text-gray-500 dark:text-gray-400 font-semibold text-sm uppercase">
                        {{ class_basename($model) }}
                    </div>

                    <!-- Items -->
                    @foreach($items as $item)
                        <a href="{{ route(strtolower(class_basename($model)).'.edit', $item->id) }}" 
                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-800 transition">
                            {{ $item->name }}
                        </a>
                    @endforeach
                @endif
            @endforeach
        </div>
    @endif
</div>
