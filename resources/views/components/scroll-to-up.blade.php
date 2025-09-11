<div 
    x-data="{ scrolled: false }" 
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
>
    <button 
        x-show="scrolled"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-75"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-75"
        x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        x-cloak
        class="fixed bottom-6 right-6 bg-gradient-to-r from-blue-500 to-indigo-600 
               hover:from-indigo-600 hover:to-blue-500 text-white 
               w-12 h-12 flex items-center justify-center
               rounded-full shadow-lg hover:shadow-2xl 
               transform hover:scale-110 transition-all duration-300
               border border-white/20"
    >
        <!-- Up Arrow Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" 
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
        </svg>
    </button>
</div>
