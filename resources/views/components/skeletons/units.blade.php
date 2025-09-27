<div class="space-y-6 animate-pulse">
    <!-- Heading Skeleton -->
    <div class="h-8 w-1/3 bg-gray-300 dark:bg-zinc-700 rounded"></div>
    <div class="h-4 w-1/2 bg-gray-300 dark:bg-zinc-700 rounded"></div>

    <!-- Controls Card Skeleton -->
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-md border border-zinc-200 dark:border-zinc-700">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700">
            <div class="h-6 w-1/4 bg-gray-200 dark:bg-zinc-700 rounded mb-2"></div>
            <div class="h-4 w-1/3 bg-gray-200 dark:bg-zinc-700 rounded"></div>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-6">
            <!-- Stats Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="h-16 bg-gray-200 dark:bg-zinc-700 rounded-2xl"></div>
                <div class="h-16 bg-gray-200 dark:bg-zinc-700 rounded-2xl"></div>
            </div>

            <!-- Filters Row -->
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <div class="h-10 flex-1 bg-gray-200 dark:bg-zinc-700 rounded"></div>
                <div class="h-10 flex-1 bg-gray-200 dark:bg-zinc-700 rounded"></div>
                <div class="h-10 flex-1 bg-gray-200 dark:bg-zinc-700 rounded"></div>
                <div class="h-10 w-32 bg-gray-200 dark:bg-zinc-700 rounded"></div>
            </div>
        </div>
    </div>

    <!-- Table Skeleton -->
    <div class="overflow-x-auto bg-white dark:bg-zinc-900 border rounded-xl shadow-lg">
        <table class="min-w-full text-sm">
            <thead class="bg-zinc-200 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-center">Room</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                @for ($i = 0; $i < 2; $i++)
                    <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-zinc-800 dark:even:bg-zinc-700">
                        <td class="px-4 py-3">
                            <div class="h-4 w-32 bg-gray-200 dark:bg-zinc-700 rounded"></div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="h-4 w-20 mx-auto bg-gray-200 dark:bg-zinc-700 rounded"></div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="h-4 w-16 mx-auto bg-gray-200 dark:bg-zinc-700 rounded-full"></div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="h-8 w-20 mx-auto bg-gray-200 dark:bg-zinc-700 rounded"></div>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
