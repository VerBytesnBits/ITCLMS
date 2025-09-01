<div
    class="relative w-full h-28 px-6 bg-white dark:bg-gray-900 rounded-2xl shadow-md hover:shadow-lg flex items-center gap-4 overflow-hidden">

    <!-- Left Gradient Bar -->
    <div class="absolute left-0 top-0 bottom-0 w-3 rounded-l-2xl"
        style="background: linear-gradient(to bottom, {{ $gradientFromColor }}, {{ $gradientToColor }});">
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
