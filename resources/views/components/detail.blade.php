@props(['label', 'value' => '—'])

<div class="flex flex-col">
    <span class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">{{ $label }}</span>
    <span class="font-medium text-gray-900 dark:text-white">{{ $value }}</span>
</div>
