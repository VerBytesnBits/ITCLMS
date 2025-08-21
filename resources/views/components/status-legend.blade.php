@props(['counts' => ['operational' => 0, 'non_operational' => 0, 'needs_repair' => 0]])

<div {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-2']) }}>
    <span class="w-3 h-3 bg-green-500 rounded-full inline-block"></span>
    Operational: {{ data_get($counts, 'operational', 0) }}

    <span class="w-3 h-3 bg-red-500 rounded-full inline-block"></span>
    Non-operational: {{ data_get($counts, 'non_operational', 0) }}

    <span class="w-3 h-3 bg-yellow-500 rounded-full inline-block"></span>
    Needs Repair: {{ data_get($counts, 'needs_repair', 0) }}
</div>
