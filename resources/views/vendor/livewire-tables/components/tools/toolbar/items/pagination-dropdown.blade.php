@aware([ 'tableName','isTailwind','isBootstrap','isBootstrap4','isBootstrap5', 'localisationPath'])
<div class="relative inline-flex items-center gap-2">
    {{-- Label --}}
    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
        Show
    </span>

    {{-- Select --}}
    <div class="relative">
        <select
            wire:model.live="perPage"
            id="{{ $tableName }}-perPage"
            {{
                $attributes->merge($this->getPerPageFieldAttributes())
                    ->class([
                        // base
                        'appearance-none pr-9 pl-3 py-2 text-sm rounded-lg transition',
                        'bg-white dark:bg-zinc-700',
                        'border border-gray-300 dark:border-zinc-700',
                        'text-gray-700 dark:text-gray-200',
                        'focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500',
                    ])
                    ->except(['default','default-styling','default-colors'])
            }}
        >
            @foreach ($this->getPerPageAccepted() as $item)
                <option
                    value="{{ $item }}"
                    wire:key="{{ $tableName }}-per-page-{{ $item }}"
                >
                    {{ $item === -1 ? __('All') : $item }}
                </option>
            @endforeach
        </select>

        {{-- Chevron icon --}}
        <svg
            class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7"/>
        </svg>
    </div>

    {{-- Suffix --}}
    <span class="text-sm text-gray-600 dark:text-gray-400">
        entries
    </span>
</div>

