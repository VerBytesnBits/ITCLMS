@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <flux:heading size="xl">{{ $title }}</flux:heading>
    <flux:subheading class="text-gray-400/60">{{ $description }}</flux:subheading>
</div>
