@props([
    'title',
    'description',
])

<div class="text-center space-y-2">
    {{-- <h1 class="text-3xl font-extrabold text-white mb-2 tracking-tight">{{ $title }}</h1>
    <p class="text-xs text-emerald-100/80 mb-2">{{ $description }}</p> --}}
     <flux:heading level="1" class="!text-3xl !font-extrabold text-white mb-2 tracking-tight">{{ $title }}</flux:heading>
    <flux:subheading class="text-sm text-emerald-100/80 mb-2">{{ $description }}</flux:subheading>
</div>





{{-- @props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-left">
    <flux:heading size="xl" class="font-extrabold!">{{ $title }}</flux:heading>
    <flux:subheading class="text-zinc-100">{{ $description }}</flux:subheading>
    {{-- <flux:subheading class="text-gray-400/60">{{ $description }}</flux:subheading> 
</div> --}}
