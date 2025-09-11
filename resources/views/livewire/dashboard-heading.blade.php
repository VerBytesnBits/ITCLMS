<div
    class="relative w-full h-17 px-6 bg-white dark:bg-gray-900 rounded-2xl shadow-md hover:shadow-lg flex items-center gap-4 overflow-hidden">

    <!-- Left Gradient Bar -->
    <!-- Gradient bar at the bottom -->
    <div class="absolute left-0 right-0 bottom-0 h-1 rounded-b-2xl"
        style="background: linear-gradient(to right, {{ $gradientFromColor }}, {{ $gradientToColor }});">
    </div>

    <!-- Icon -->
    <flux:icon icon="{{ $icon }}" class="!h-8 !w-8 !flex-shrink-0 {{ $iconColor }}" />

    <!-- Heading -->
    <div>
        <flux:heading class="!text-xl !font-bold !text-transparent !bg-clip-text"
            style="background: linear-gradient(to bottom, {{ $gradientFromColor }}, {{ $gradientToColor }});">
            {{ $title }}
        </flux:heading>

        <flux:subheading size="sm" class="!text-gray-500 dark:!text-gray-400">
            {{ $subtitle }}
        </flux:subheading>
    </div>
</div>
