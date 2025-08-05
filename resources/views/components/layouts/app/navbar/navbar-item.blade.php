<flux:header container
    class="max-lg:hidden border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 mb-4">
    <nav class="flex flex-col md:flex-row md:justify-center md:items-center space-y-2 md:space-y-0 md:space-x-4 w-full">
        <flux:navbar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
            wire:navigate>
            {{ __('Dashboard') }}
        </flux:navbar.item>

        @can('manage.users')
            <flux:navbar.item icon="users" :href="route('users')" :current="request()->routeIs('users')"
                wire:navigate>
                {{ __('Users') }}
            </flux:navbar.item>
        @endcan

        @can('manage.roles')
            <flux:navbar.item icon="link-slash" :href="route('roles')" :current="request()->routeIs('roles')"
                wire:navigate>
                {{ __('Roles') }}
            </flux:navbar.item>
        @endcan
        @can('view.laboratories')
            <flux:navbar.item icon="link-slash" :href="route('rooms.index')" :current="request()->routeIs('rooms.index')"
                wire:navigate>
                {{ __('Rooms') }}
            </flux:navbar.item>
        @endcan
        <a href="#"
            class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-zinc-600">
            Equipment
        </a>

        <a href="#"
            class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-zinc-600">
            Inventory
        </a>

        <a href="#"
            class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-zinc-600">
            Reports
        </a>
    </nav>

</flux:header>
<!-- Mobile Menu -->
<flux:sidebar stashable sticky
    class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    <a href="{{ route('dashboard') }}" class="ms-1 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
        <x-app-logo />
    </a>

    <flux:navlist variant="outline">
        <flux:navlist.group :heading="__('Platform')">
            <flux:navlist.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                wire:navigate>
                {{ __('Dashboard') }}
            </flux:navlist.item>
            @can('manage.users')
                <flux:navlist.item icon="users" :href="route('users')" :current="request()->routeIs('users')"
                    wire:navigate>
                    {{ __('Users') }}
                </flux:navlist.item>
            @endcan
            @can('manage.roles')
                <flux:navlist.item icon="link-slash" :href="route('roles')" :current="request()->routeIs('roles')"
                    wire:navigate>
                    {{ __('Roles') }}
                </flux:navlist.item>
            @endcan
            <flux:navlist.item icon="home" :href="route('rooms.index')" :current="request()->routeIs('rooms')"
                wire:navigate>
                {{ __('Rooms') }}
            </flux:navlist.item>
            <flux:navlist.item icon="computer-desktop" :href="'#'"
                :current="request()->routeIs('equipment')" wire:navigate>
                {{ __('Equipment') }}
            </flux:navlist.item>
            <flux:navlist.item icon="circle-stack" :href="'#'" :current="request()->routeIs('inventory')"
                wire:navigate>
                {{ __('Inventory') }}
            </flux:navlist.item>
            <flux:navlist.item icon="presentation-chart-line" :href="'#'"
                :current="request()->routeIs('report')" wire:navigate>
                {{ __('Reports') }}
            </flux:navlist.item>
        </flux:navlist.group>
    </flux:navlist>

    <flux:spacer />

</flux:sidebar>
