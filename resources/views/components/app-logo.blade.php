<div class="flex items-center gap-3">
    <!-- Icon -->
    <div
        class="flex aspect-square w-15 h-15 items-center justify-center rounded-md ">
         {{-- <span class="flex h-10 w-10 items-center justify-center rounded-md">
                @include('partials.itclms-logo')
            </span> --}}
            @include('partials.itclms-logo')
    </div>

    <div class="w-1/2">
        <div class="grid grid-rows-[auto_auto] text-left sm:text-left">
            <h1
                class="text-2xl font-black uppercase tracking-[0.4em]
               text-black dark:text-white light-outline">
                ITCLMS
            </h1>
            <!-- Hidden on desktop, shown on small screens -->
            {{-- <p class="block  text-[10px] text-gray-500 dark:text-gray-400 leading-tight italic">
                Information Technology Computer Laboratory Management System
            </p> --}}
        </div>
    </div>
</div>
