<div>

    {{-- 🔍 Search --}}
    <flux:input
        wire:model.live.debounce.300ms="search"
        placeholder="Search users..."
        class="w-full sm:max-w-xs"
    />

    <div class="flex items-center gap-3">

        {{-- ⬇ Per Page --}}
        <flux:select
            wire:model.live="perPage"
            size="sm"
        >
            <option value="5">5 entries</option>
            <option value="10">10 entries</option>
            <option value="25">25 entries</option>
            <option value="50">50 entries</option>
        </flux:select>



    </div>
</div>
