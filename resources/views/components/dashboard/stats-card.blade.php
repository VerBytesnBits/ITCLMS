@props([
    'title' => 'Title',
    'value' => 0,
    'icon' => null,
    'iconBg' => 'bg-indigo-500',
    'href' => null,   // ⬅ added
])

@php
$wrapperTag = $href ? 'a' : 'div';
@endphp

<{{ $wrapperTag }}
    @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge([
        'class' => 'bg-white dark:bg-zinc-800 rounded-2xl shadow p-4 flex items-center justify-between hover:shadow-lg transition cursor-pointer'
    ]) }}
>
{{-- 
<div {{ $attributes->merge(['class' => 'bg-white dark:bg-zinc-800 rounded-2xl shadow p-4 flex items-center justify-between']) }}> --}}
    <!-- Left: Title + Value -->
    <div class="flex flex-col">
        <h3 class="text-sm font-medium text-zinc-400 dark:text-zinc-500">{{ $title }}</h3>
        <p x-data="{ count: 0 }"
           x-init="(() => {
               const target = {{ $value }};
               const duration = 1000;
               let start = null;
               const step = (timestamp) => {
                   if (!start) start = timestamp;
                   const progress = timestamp - start;
                   const progressRatio = Math.min(progress / duration, 1);
                   count = target * progressRatio;
                   if (progress < duration) requestAnimationFrame(step);
                   else count = target;
               };
               requestAnimationFrame(step);
           })()"
           x-text="Math.floor(count).toLocaleString()"
           class="mt-1 text-2xl sm:text-3xl font-bold text-zinc-600 dark:text-zinc-100">
        </p>
    </div>

    <!-- Right: Icon -->
    @if($icon)
        <div class="flex items-center justify-center w-12 h-12 rounded-full text-white {{ $iconBg }}">
            @php $iconClass = 'w-6 h-6'; @endphp

            @switch($icon)
                @case('cpu-chip')
                    <flux:icon.cpu-chip class="{{ $iconClass }}" />
                    @break
                @case('monitor')
                    <flux:icon.monitor class="{{ $iconClass }}" />
                    @break
                @case('computer-desktop')
                    <flux:icon.computer-desktop class="{{ $iconClass }}" />
                    @break
                @case('check-circle')
                    <flux:icon.check-circle class="{{ $iconClass }}" />
                    @break
                @case('x-circle')
                    <flux:icon.x-circle class="{{ $iconClass }}" />
                    @break
                @case('computer')
                    <flux:icon.computer class="{{ $iconClass }}" />
                    @break    
                @default
                    <flux:icon.cpu-chip class="{{ $iconClass }}" />
            @endswitch
        </div>
    @endif
{{-- </div> --}}
</{{ $wrapperTag }}>
