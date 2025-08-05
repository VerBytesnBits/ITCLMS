<x-layouts.app.header :title="$title ?? null">
    <flux:main container>
        <x-layouts.app.navbar.navbar-item />
        <div>
            {{ $slot }}
        </div>
    </flux:main>
</x-layouts.app.header>
