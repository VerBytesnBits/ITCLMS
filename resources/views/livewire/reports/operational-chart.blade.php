<div 
    x-data="labChart(
        @js($chartData->pluck('lab')->values()),
        @js($chartData->pluck('operational')->values()),
        @js($chartData->pluck('non_operational')->values())
    )" 
    class="w-full flex flex-col h-full"
>
    <!-- Card -->
    <div
        class="bg-white dark:bg-zinc-800 backdrop-blur-md rounded-2xl shadow-lg hover:shadow-2xl
               transition transform hover:-translate-y-1 border border-zinc-200 dark:border-zinc-700
               p-6 flex flex-col h-full"
    >
        <!-- Canvas fills remaining height -->
        <div class="flex-1 w-full">
            <canvas x-ref="canvas" class="w-full h-full"></canvas>
        </div>
    </div>
</div>
