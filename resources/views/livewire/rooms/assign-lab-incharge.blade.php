<div>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">
        <div
            class="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-2xl w-full max-w-xl 
              animate-[fade-in-scale_0.2s_ease-out]">
            <h2 class="text-lg font-bold mb-2">Assign Lab In-Charge</h2>
            {{-- <select wire:model="user_id" class="border px-2 py-1">
                <option value="">Select User</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select> --}}
            <div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Lab In-Charge(s)</label>
                    <select wire:model="user_ids" multiple class="w-full rounded border-gray-300">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_ids')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>


                @error('user_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-2">
                <button wire:click="$dispatch('closeModal')" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">
                    Cancel
                </button>
                <button wire:click="save" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
