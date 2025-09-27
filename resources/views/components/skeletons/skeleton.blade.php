<div class="animate-pulse space-y-6">
    <!-- Fake Header -->
    <div class="h-8 bg-gray-300 rounded w-1/3"></div>

    <!-- Fake Summary Box -->
    <div class="border rounded-lg bg-white dark:bg-zinc-800 shadow p-4 space-y-3">
        <div class="flex justify-between">
            <div class="h-6 w-40 bg-gray-300 rounded"></div>
            <div class="h-6 w-16 bg-gray-300 rounded"></div>
        </div>
        <div class="h-6 bg-gray-300 rounded w-1/2"></div>
    </div>

    <!-- Fake Search + Button -->
    <div class="flex justify-between gap-4">
        <div class="flex-1 h-10 bg-gray-300 rounded"></div>
        <div class="h-10 w-32 bg-gray-300 rounded"></div>
    </div>

    <!-- Fake Table -->
    <div class="border rounded-xl bg-white dark:bg-zinc-900 shadow-lg">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="h-6 bg-gray-300 rounded w-24"></th>
                    <th class="h-6 bg-gray-300 rounded w-24"></th>
                    <th class="h-6 bg-gray-300 rounded w-24"></th>
                    <th class="h-6 bg-gray-300 rounded w-24"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @for ($i = 0; $i < 5; $i++)
                    <tr>
                        <td class="h-6 bg-gray-200 rounded"></td>
                        <td class="h-6 bg-gray-200 rounded"></td>
                        <td class="h-6 bg-gray-200 rounded"></td>
                        <td class="h-6 bg-gray-200 rounded"></td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
