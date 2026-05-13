<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="flex items-center justify-between p-4 rounded-2xl shadow-sm 
        bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/40 
        hover:shadow-md transition">
        <span class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-200">
            <span class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></span> Operational
        </span>
        <span class="text-xl font-bold text-green-700 dark:text-green-300">{{ $this->operationalCount }}</span>
    </div>
    <div class="flex items-center justify-between p-4 rounded-2xl shadow-sm 
        bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/40 
        hover:shadow-md transition">
        <span class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-200">
            <span class="w-3 h-3 rounded-full bg-red-500"></span> Non-Operational
        </span>
        <span class="text-xl font-bold text-red-700 dark:text-red-300">{{ $this->nonOperationalCount }}</span>
    </div>
</div>