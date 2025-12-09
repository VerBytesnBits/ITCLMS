<div 
    wire:navigate
    href="/dashboard"
    class="flex flex-col justify-center items-center text-center cursor-pointer select-none"
>
    <!-- Icon -->
    <div class="relative flex items-center justify-center w-full">
        <div class="flex items-center justify-center w-20 h-20 rounded-md">
            @include('partials.itclms-logo')
        </div>
    </div>

    <!-- Title -->
    <div>
        <h1
            class="text-5xl font-black uppercase tracking-widest text-white 
                   drop-shadow-[0_3px_3px_rgba(0,0,0,0.9)] 
                   in-data-flux-sidebar-collapsed-desktop:hidden"
        >
            ITCLMS
        </h1>
    </div>
</div>
