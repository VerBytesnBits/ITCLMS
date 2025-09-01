<div>
    <div
        class="relative w-full px-6 py-6 bg-white dark:bg-gray-900 rounded-2xl shadow-md hover:shadow-lg flex items-start gap-4">

        <!-- Left Gradient Bar -->
        <div class="absolute left-0 top-0 bottom-0 w-3 rounded-l-2xl"
            style="background: linear-gradient(to bottom, {{ $gradientFromColor }}, {{ $gradientToColor }});">
        </div>

        <!-- Icon -->
        <flux:icon icon="{{ $icon }}" class="!h-12 !w-12 !flex-shrink-0 {{ $iconColor }}" />

        <!-- Heading -->
        <div>
            <flux:heading class="!text-3xl !font-bold !mb-1 !text-transparent !bg-clip-text"
                style="background: linear-gradient(to bottom, {{ $gradientFromColor }}, {{ $gradientToColor }});">
                {{ $title }}
            </flux:heading>

            <flux:subheading size="lg" class="!mb-4 !text-gray-500 dark:!text-gray-400">
                {{ $subtitle }}
            </flux:subheading>
        </div>

    </div>

   
</div>
