<div class="flex flex-wrap gap-1">
    @forelse($user->roles as $role)
        <span
            class="px-2 py-1 rounded-full text-xs font-semibold
            {{ match ($role->name) {
                'chairman' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                'lab_incharge' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
                'lab_technician' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
            } }}">
            {{ ucwords(str_replace('_', ' ', $role->name)) }}
        </span>
    @empty
        <span class="text-gray-400 dark:text-gray-500">—</span>
    @endforelse
</div>
