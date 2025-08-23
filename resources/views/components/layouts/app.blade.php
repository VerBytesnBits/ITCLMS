<x-layouts.app.header :title="$title ?? null">
    <flux:main container>
        <x-layouts.app.navbar.navbar-item />

        @include('components.alert')

        {{ $slot }}
    </flux:main>
</x-layouts.app.header>
