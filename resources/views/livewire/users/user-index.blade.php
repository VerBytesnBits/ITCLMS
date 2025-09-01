<div class="w-full">
    <h1 class="text-2xl font-bold mb-6">Users</h1>

    <div class="overflow-x-auto bg-white dark:bg-zinc-900 shadow rounded-lg">
        <table class="min-w-full text-sm text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-gray-300 uppercase text-xs font-semibold">
                    <th class="px-4 py-3 border-b">Name</th>
                    <th class="px-4 py-3 border-b">Email</th>
                    <th class="px-4 py-3 border-b">Roles</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800 transition">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                            {{ $user->name }}
                        </td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                            {{ $user->email }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                            {{ $user->roles->pluck('name')->join(', ') ?: '—' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
