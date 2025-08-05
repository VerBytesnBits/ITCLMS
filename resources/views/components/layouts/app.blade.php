<x-layouts.app.header :title="$title ?? null">
    <flux:main container>
        <x-layouts.app.navbar.navbar-item />
        <div class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 ">
            @include('components.alert')

        {{ $slot ?? '' }}
    </flux:main>
</x-layouts.app.header>
