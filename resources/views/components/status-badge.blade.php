@props(['status'])

@php
    $statusBgClasses = [
        'Operational' => 'bg-green-500',
        'Needs Repair' => 'bg-yellow-400 dark:bg-yellow-500',
        'Non-operational' => 'bg-red-500',
    ];
@endphp

<span class="inline-block px-2 py-1 rounded-full text-xs font-semibold {{ $statusBgClasses[$status] ?? 'bg-gray-200 dark:bg-gray-700' }} text-gray-100 w-[170px] text-center">
    {{ $status }}
</span>
