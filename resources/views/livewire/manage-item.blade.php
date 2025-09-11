<section>

    <flux:modal.trigger name="manage-item-{{ $item->id }}">
        {{-- <flux:button variant="primary" x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'manage-item-{{ $item->id }}')">
            Manage Item
        </flux:button> --}}
        <button x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'manage-item-{{ $item->id }}')"
            class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-700">
            <flux:icon.tool-case class="h-4 w-4" />
            <span>Manage</span>
        </button>
    </flux:modal.trigger>

    <flux:modal name="manage-item-{{ $item->id }}" focusable class="max-w-lg">
        <form wire:submit.prevent="performAction" class="space-y-6">
            <div>
                <flux:heading size="lg">Choose an action for {{ $item->part ?? 'Item' }}</flux:heading>
                <flux:subheading>Select what you want to do with this item.</flux:subheading>
            </div>

            <flux:select label="Action" wire:model.live="selectedAction">
                <option value="">Select Action</option>
                <option value="junk">Move to Junk</option>
                <option value="dispose">Dispose</option>
                <option value="salvage">Salvage</option>
                <option value="decommission">Decommission</option>
                <option value="archive">Archive (Soft Delete)</option>
            </flux:select>
            <flux:textarea label="Notes" wire:model="retirementNotes" placeholder="Optional notes for this action" />

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">Cancel</flux:button>
                </flux:modal.close>

                @if (!$selectedAction)
                    <flux:button variant="danger" type="submit" disabled>Confirm</flux:button>
                @else
                    <flux:button variant="danger" type="submit">Confirm</flux:button>
                @endif


            </div>
        </form>
    </flux:modal>
</section>
