<div>
    <h2 class="text-lg font-bold mb-2">Assign Lab In-Charge</h2>
    <select wire:model="user_id" class="border px-2 py-1">
        <option value="">Select User</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>
    <button wire:click="save" class="bg-blue-500 text-white px-3 py-1 rounded mt-2">Save</button>
</div>
