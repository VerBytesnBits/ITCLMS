@aware(['isTailwind', 'isBootstrap'])

<input
    wire:model{{ $this->getSearchOptions() }}="search"
    type="text"
    placeholder="{{ $this->getSearchPlaceholder() }}"
    {{
        $attributes->merge($this->getSearchFieldAttributes())
        ->class([
            /* Base */
            'w-full px-4 py-2 text-sm rounded-lg transition-all duration-150 outline-none' => $isTailwind,

            /* Light / Dark */
            'bg-white text-gray-700 border border-gray-300
             dark:bg-zinc-700 dark:text-gray-200 dark:border-zinc-700' => $isTailwind,

            /* Focus */
            'focus:ring-2 focus:ring-blue-500 focus:border-blue-500
             dark:focus:ring-blue-600 dark:focus:border-blue-600' => $isTailwind,

            /* With search icon spacing */
            'pl-10 pr-4' => $this->hasSearchIcon,
            'pl-4 pr-4' => ! $this->hasSearchIcon,

            /* Bootstrap fallback */
            'form-control' => $isBootstrap && ($this->getSearchFieldAttributes()['default'] ?? true),
        ])
        ->except(['default','default-styling','default-colors'])
    }}
/>
