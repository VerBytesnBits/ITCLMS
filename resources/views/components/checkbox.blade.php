@props([
    'id' => 'checkbox-' . \Illuminate\Support\Str::uuid(),
    'value' => null,
])

<flux:checkbox
    :id="$id"
    :value="$value"
    {{ $attributes }}
/>
