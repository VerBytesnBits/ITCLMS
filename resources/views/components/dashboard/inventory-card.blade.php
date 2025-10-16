<div
    class="p-6 bg-gradient-to-r from-{{ $fromColor }} to-{{ $toColor }} rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
    <flux:heading size="lg" level="1" class="font-bold! mb-3 text-white">{{ $title }}</flux:heading>
    {{-- <h3 class="font-bold mb-6 text-lg text-white">{{ $title }}</h3> --}}

    <!-- Circular Progress -->
    <div class="flex items-center justify-center">
        <div class="relative w-40 h-40" x-data="{
            percent: 0,
            display: 0,
            target: {{ $percentage }},
            duration: 1000,
            animate() {
                let start = null;
                const step = (timestamp) => {
                    if (!start) start = timestamp;
                    let progress = Math.min((timestamp - start) / this.duration, 1);
                    this.percent = progress * this.target;
                    this.display = Math.round(this.percent);
                    if (progress < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            }
        }" x-init="animate()">

            <svg class="w-full h-full transform -rotate-90">
                <circle cx="50%" cy="50%" r="70" stroke="currentColor" class="text-white/30" stroke-width="12"
                    fill="transparent" />
                <circle cx="50%" cy="50%" r="70" stroke="white" stroke-width="12" stroke-linecap="round"
                    fill="transparent" stroke-dasharray="439.8" :stroke-dashoffset="439.8 - (439.8 * percent) / 100" />
            </svg>

            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-3xl font-extrabold text-white" x-text="display + '%'"></span>
                <span class="text-sm font-medium text-white/80">Available</span>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-3 gap-4 text-center text-white mb-4 mt-4">
        <div>
            <p class="text-xl font-bold">{{ $stats['available'] }}</p>
            <p class="text-xs">Available</p>
        </div>
        <div>
            <p class="text-xl font-bold">{{ $stats['defective'] }}</p>
            <p class="text-xs">Defective</p>
        </div>
        <div>
            <p class="text-xl font-bold">{{ $stats['In use'] }}</p>
            <p class="text-xs">In Use</p>
        </div>
    </div>

    <!-- Alerts -->
    <div class="mt-6 min-h-[8rem] flex flex-col gap-2">
        @if ($belowThreshold)
            <flux:callout variant="warning" icon="exclamation-circle"
                heading="{{ $belowThreshold }} Low Stock Alerts" />
        @endif

        @if ($outOfStock)
            <flux:callout variant="danger" icon="x-circle" heading="{{ $outOfStock }} Out of Stock Alerts" />
        @endif

        @if (!$belowThreshold && !$outOfStock)
            <p class="text-xs text-white/70 italic text-center mt-6">All stock levels are healthy</p>
        @endif
    </div>
    <p class="text-[11px] text-white/60 text-center mt-4">
    Last updated: {{ now()->format('M d, Y h:i A') }}
</p>

</div>
