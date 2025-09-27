@props([
    'title' => 'Title',
    'value' => 0,
    'icon' => null,
    'iconBg' => 'bg-indigo-500',
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-zinc-800 rounded-2xl shadow p-4 flex flex-col']) }}>
    <div class="flex items-center gap-3">
        @if($icon)
            <div class="flex items-center justify-center w-8 h-8 rounded-full text-white {{ $iconBg }}">
                {!! $icon !!}
            </div>
        @endif
        <h3 class="text-sm font-medium text-zinc-400 dark:text-zinc-500">{{ $title }}</h3>
    </div>

    <p x-data="{ count: 0 }"
       x-init="(() => {
           const target = {{ $value }};
           const duration = 1000; // 1 second
           let start = null;

           const step = (timestamp) => {
               if (!start) start = timestamp;
               const progress = timestamp - start;
               const progressRatio = Math.min(progress / duration, 1); // 0 → 1
               count = target * progressRatio;
               if (progress < duration) {
                   requestAnimationFrame(step);
               } else {
                   count = target; // ensure final value
               }
           };

           requestAnimationFrame(step);
       })()"
       x-text="Math.floor(count).toLocaleString()"
       class="mt-2 text-2xl sm:text-3xl font-bold text-zinc-600 dark:text-zinc-100">
    </p>
</div>
