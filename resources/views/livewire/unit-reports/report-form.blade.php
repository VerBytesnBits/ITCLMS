<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">
    <div
        class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-xl 
              animate-[fade-in-scale_0.2s_ease-out]">


        <flux:heading size="lg">Report Issue</flux:heading>

        {{-- Unit Info --}}
        <div
            class="rounded-2xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/60 p-4 shadow-sm">
            <p class="text-sm text-gray-600 dark:text-gray-300">
                Reporting Unit:
                <span class="font-semibold text-gray-900 dark:text-white">{{ $unit->name }}</span>
            </p>
            <p class="text-xs text-gray-500 mt-2 flex items-center gap-2">
                Current Status:
                <span
                    class="px-2 py-0.5 text-xs font-medium rounded-full 
                        {{ $unit->status === 'Operational'
                            ? 'bg-green-100 text-green-700'
                            : ($unit->status === 'Needs Repair'
                                ? 'bg-yellow-100 text-yellow-700'
                                : 'bg-red-100 text-red-700') }}">
                    {{ $unit->status }}
                </span>
            </p>
        </div>

        <form wire:submit.prevent="submit" class="space-y-4">
            <!-- Part select -->
            <flux:field>
                <flux:label for="part_id">Select Part</flux:label>
                <flux:select wire:model="partId" id="part_id">
                    @foreach ($parts as $part)
                        <option value="{{ $part['id'] }}">{{ $part['label'] }}</option>
                    @endforeach
                </flux:select>
            </flux:field>

            <!-- Issue -->
            <flux:field>
                <flux:label for="issue">Issue Description</flux:label>
                <flux:textarea wire:model="issue" id="issue" rows="3" />
            </flux:field>

            <!-- Actions -->
            <div class="flex justify-end gap-2">
                <flux:button variant="primary" wire:click="$dispatch('closeModal')">Cancel</flux:button>
                <flux:button type="submit" variant="primary">Submit Report</flux:button>
            </div>
        </form>



    </div>
</div>
