<div class="space-y-4">

    {{-- Success Message --}}
    @if (session()->has('success'))
        <div class="px-4 py-2 bg-green-100 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif


    {{-- Table Card --}}
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-4 bg-blue-600">
            <h2 class="text-lg font-semibold text-white">Reported Issues</h2>
        </div>

        {{-- Table Body --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-200">
                <thead class="text-xs uppercase bg-gray-100 dark:bg-zinc-700 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-2">Unit / Item</th>
                        <th class="px-4 py-2">Issue Type</th>
                        <th class="px-4 py-2">Reported By</th>
                        <th class="px-4 py-2">Resolved By</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($this->issues as $issue)
                        <tr>
                            <td class="px-4 py-2">
                                @if ($issue->componentPart)
                                    {{ $issue->componentPart->brand ?? '' }}
                                    {{ $issue->componentPart->model ?? ($issue->componentPart->type ?? '') }}
                                    @if ($issue->componentPart->capacity)
                                        ({{ $issue->componentPart->capacity }})
                                    @endif
                                    <br>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        Unit: {{ $issue->systemUnit->name ?? 'N/A' }}
                                    </span>
                                @elseif($issue->peripheral)
                                    {{ $issue->peripheral->brand ?? '' }}
                                    {{ $issue->peripheral->model ?? ($issue->peripheral->type ?? '') }}
                                    @if ($issue->peripheral->capacity)
                                        ({{ $issue->peripheral->capacity }})
                                    @endif
                                    <br>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        Unit: {{ $issue->systemUnit->name ?? 'N/A' }}
                                    </span>
                                @else
                                    <span>{{ $issue->systemUnit->name ?? 'Unknown Unit' }} (General Issue)</span>
                                @endif
                            </td>


                            <td class="px-4 py-2">{{ $issue->issue_type }}</td>
                            <td class="px-4 py-2">{{ $issue->reporter?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $issue->resolver?->name ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <span
                                    class="px-2 py-1 rounded-lg text-xs font-semibold 
                                    {{ $issue->status === 'Completed' ? 'bg-green-200 text-green-800 dark:bg-green-700 dark:text-green-100' : 'bg-yellow-200 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100' }}">
                                    {{ $issue->status }}
                                </span>
                            </td>

                            <td class="px-4 py-2">
                                @if (
                                    $issue->status !== 'Resolved' &&
                                        $issue->status !== 'Decommissioned' &&
                                        (auth()->user()->hasRole('lab_incharge') || auth()->user()->hasRole('lab_technician')))
                                    <button
                                        wire:click="$dispatch('openResolveIssue', { issueId: {{ $issue->id }} })"
                                        class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 text-xs">
                                        Resolve
                                    </button>
                                @else
                                    <span class="text-gray-500 text-xs">—</span>
                                @endif


                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500 dark:text-gray-300">No issues
                                reported yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($resolveModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
            <div class="bg-zinc-800 text-zinc-100 rounded-2xl shadow-xl w-full max-w-md p-0 overflow-hidden">
                {{-- Header --}}
                <div class="bg-blue-600 px-4 py-3 font-semibold text-white">Resolve Issue</div>

                {{-- Body --}}
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm mb-1">Resolution Action</label>
                        <select wire:model="resolutionAction"
                            class="w-full rounded-lg bg-zinc-700 border-zinc-600 text-white">
                            <option value="Resolved">Resolved</option>
                            <option value="Replacement Needed">Replacement Needed</option>
                            <option value="Decommissioned">Decommissioned</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Resolution Notes (optional)</label>
                        <textarea wire:model="resolutionNotes" rows="3" class="w-full rounded-lg bg-zinc-700 border-zinc-600 text-white"
                            placeholder="Add notes or steps taken..."></textarea>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-2 px-6 py-3 border-t border-zinc-600">
                    <button wire:click="closeResolveModal"
                        class="px-4 py-2 bg-gray-600 rounded-lg hover:bg-gray-500">Cancel</button>
                    <button wire:click="resolveIssue"
                        class="px-4 py-2 bg-green-600 rounded-lg hover:bg-green-700">Save</button>
                </div>
            </div>
        </div>
    @endif


</div>
