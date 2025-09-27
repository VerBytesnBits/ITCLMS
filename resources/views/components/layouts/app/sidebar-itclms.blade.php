<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-gray-200 dark:bg-zinc-800">
    <flux:sidebar sticky collapsible
        class="border-r border-zinc-200 bg-zinc-200 dark:bg-gray-900  dark:border-zinc-700 drop-shadow-lg">

        <x-app-logo />

        <flux:sidebar.nav>

            <flux:sidebar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                wire:navigate>
                {{ __('Dashboard') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="computer-desktop" :href="route('units')" :current="request()->routeIs('units')"
                wire:navigate>
                {{ __('Computer Units') }}
            </flux:sidebar.item>

            <flux:sidebar.group expandable icon="warehouse" heading="Inventory" class="grid">
                <flux:sidebar.item icon="cpu-chip" :href="route('components')"
                    :current="request()->routeIs('components')" wire:navigate>
                    {{ __('Components') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="cube" :href="route('peripherals')"
                    :current="request()->routeIs('peripherals')" wire:navigate>
                    {{ __('Peripherals') }}
                </flux:sidebar.item>
            </flux:sidebar.group>

            <flux:sidebar.item icon="qr-code" :href="route('qr-manager')" :current="request()->routeIs('qr-manager')"
                wire:navigate>
                {{ __('QR Generator') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="home" :href="route('rooms')" :current="request()->routeIs('rooms')"
                wire:navigate>
                {{ __('Rooms') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="link-slash" :href="route('roles')" :current="request()->routeIs('roles')"
                wire:navigate>
                {{ __('Roles/Permissions') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="user-group" :href="route('users')" :current="request()->routeIs('users')"
                wire:navigate>
                {{ __('Users') }}
            </flux:sidebar.item>

            {{-- <flux:sidebar.item icon="wrench" :href="route('maintenance')" 
            :current="request()->routeIs('maintenance')" wire:navigate>
            {{ __('Maintenance') }}
        </flux:sidebar.item> --}}

            <flux:sidebar.item icon="clock" :href="route('activitylogs')"
                :current="request()->routeIs('activitylogs')" wire:navigate>
                {{ __('Activity Logs') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="document-chart-bar" :href="route('reports')"
                :current="request()->routeIs('reports')" wire:navigate>
                {{ __('Reports') }}
            </flux:sidebar.item>



        </flux:sidebar.nav>

        <flux:sidebar.spacer />

        <flux:sidebar.nav>
            <flux:sidebar.item icon="cog-6-tooth" :href="route('settings.profile')"
                :current="request()->routeIs('settings.profile')" wire:navigate>
                {{ __('Settings') }}</flux:sidebar.item>
            <flux:sidebar.item icon="information-circle" href="#">Help</flux:sidebar.item>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:sidebar.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                    {{ __('Log Out') }}
                    </flux:sidebar.item.item>
            </form>
        </flux:sidebar.nav>

    </flux:sidebar>

    <flux:header sticky
        class="border-e border-zinc-200 bg-zinc-200 dark:bg-gray-900  dark:border-zinc-700 drop-shadow-lg">
        <flux:sidebar.collapse icon="bars-3" inset="left" />
        {{-- 
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}" icon="home" />
            <flux:breadcrumbs.item>{{ $title ?? '' }}</flux:breadcrumbs.item>
        </flux:breadcrumbs> --}}
        <flux:spacer />


        <div class="flex items-center gap-2">
            @if (auth()->check())
                <!-- Authenticated User -->
                <div
                    class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-500 text-white font-semibold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="flex flex-col">
                    <span class="font-medium text-zinc-900 dark:text-zinc-100 text-sm">
                        {{ auth()->user()->name }}
                    </span>

                    <div class="flex gap-1 mt-0.5 flex-wrap">
                        @foreach (auth()->user()->getRoleNames() as $role)
                            <span
                                class="px-2 py-0.5 rounded-full text-[13px] font-medium
                            {{ match ($role) {
                                'chairman' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                                'lab_incharge' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300',
                                'lab_technician' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
                                default => 'bg-gray-100 text-gray-700 dark:bg-gray-800/30 dark:text-gray-300',
                            } }}">
                                {{ ucwords(str_replace('_', ' ', $role)) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Guest Fallback -->
                <div
                    class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-400 text-white font-semibold text-sm">
                    ?
                </div>
                <div class="flex flex-col">
                    <span class="font-medium text-gray-700 dark:text-gray-300 text-sm">Guest</span>
                </div>
            @endif
        </div>






    </flux:header>

    <flux:main>
        {{ $slot }}
    </flux:main>

    @fluxScripts
</body>

</html>
