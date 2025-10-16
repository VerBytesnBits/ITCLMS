<div class="flex flex-col justify-center items-center text-center">
    <!-- Icon -->

    <div class="relative flex items-center justify-center w-full">
        <!-- Logo centered -->
        <div class="flex items-center justify-center w-13 h-13 rounded-md">
            @include('partials.itclms-logo')
        </div>
        {{-- <!-- Collapse button positioned at the end -->
        <flux:sidebar.collapse
            class="absolute right-1 in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" /> --}}
    </div>

    <!-- Title -->
    <div>
        <h1
            class="text-5xl font-black uppercase tracking-widest text-white 
                   drop-shadow-[0_3px_3px_rgba(0,0,0,0.9)] 
                   in-data-flux-sidebar-collapsed-desktop:hidden">
            ITCLMS
        </h1>

        {{-- Optional subtitle --}}
        {{-- <p class="text-xs text-gray-400 italic hidden sm:block">
            Information Technology Computer Laboratory Management System
        </p> --}}
    </div>
</div>
