<div>
    <form wire:submit.prevent="save">
        @foreach ($fields as $name => $value)
            <div class="mb-3">
                <label class="block capitalize">{{ str_replace('_', ' ', $name) }}</label>

                {{-- If this is an enum, render as dropdown --}}
                @if (isset($enumOptions[$name]))
                    <select wire:model.defer="fields.{{ $name }}" class="border p-2 w-full rounded">
                        <option value="">-- Select {{ ucfirst(str_replace('_', ' ', $name)) }} --</option>
                        @foreach ($enumOptions[$name] as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                @else
                    {{-- Otherwise render text/date input --}}
                    <input type="{{ $name === 'date_purchased' ? 'date' : 'text' }}"
                        wire:model.defer="fields.{{ $name }}" class="border p-2 w-full rounded">
                @endif

                @error("fields.$name")
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        @endforeach

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            {{ $partId ? 'Update' : 'Save' }}
        </button>
    </form>
</div>
