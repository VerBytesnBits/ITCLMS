<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-blue-200 dark:bg-zinc-800">
    <flux:sidebar sticky collapsible
        class="border-zinc-200 bg-gradient-to-b from-blue-400 to-blue-300 
           dark:from-gray-900 dark:to-gray-800 dark:border-zinc-700 drop-shadow-lg">

        <x-app-logo />

        <flux:sidebar.nav>

            {{-- Dashboard --}}
            @can('view.dashboard')
                <flux:sidebar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>
                    {{ __('Dashboard') }}
                </flux:sidebar.item>
            @endcan


            {{-- Computer Units --}}
            @can('view.unit')
                <flux:sidebar.item icon="computer-desktop" :href="route('units')" :current="request()->routeIs('units')"
                    wire:navigate>
                    {{ __('Computer Units') }}
                </flux:sidebar.item>
            @endcan


            {{-- Inventory Group (Components & Peripherals) --}}
            @canany(['view.component', 'view.peripheral'])
                <flux:sidebar.group expandable icon="warehouse" heading="Inventory" class="grid">
                    @can('view.component')
                        <flux:sidebar.item icon="cpu-chip" :href="route('components')"
                            :current="request()->routeIs('components')" wire:navigate>
                            {{ __('Components') }}
                        </flux:sidebar.item>
                    @endcan

                    @can('view.peripheral')
                        <flux:sidebar.item icon="cube" :href="route('peripherals')"
                            :current="request()->routeIs('peripherals')" wire:navigate>
                            {{ __('Peripherals') }}
                        </flux:sidebar.item>
                    @endcan
                </flux:sidebar.group>
            @endcanany


            {{-- QR Generator --}}
            {{-- @can('view.qr')
                <flux:sidebar.item icon="qr-code" :href="route('qr-manager')" :current="request()->routeIs('qr-manager')"
                    wire:navigate>
                    {{ __('QR Generator') }}
                </flux:sidebar.item>
            @endcan --}}


            {{-- Laboratories / Rooms --}}
            @can('view.laboratories')
                <flux:sidebar.item icon="home" :href="route('rooms')" :current="request()->routeIs('rooms')"
                    wire:navigate>
                    {{ __('Rooms') }}
                </flux:sidebar.item>
            @endcan


            {{-- Roles & Users (Chairman only, by permission) --}}
            @canany(['manage.roles', 'manage.users'])
                @can('manage.roles')
                    <flux:sidebar.item icon="link-slash" :href="route('roles')" :current="request()->routeIs('roles')"
                        wire:navigate>
                        {{ __('Roles/Permissions') }}
                    </flux:sidebar.item>
                @endcan

                @can('manage.users')
                    <flux:sidebar.item icon="user-group" :href="route('users')" :current="request()->routeIs('users')"
                        wire:navigate>
                        {{ __('Users') }}
                    </flux:sidebar.item>
                @endcan
            @endcanany


            {{-- Activity Logs
            @can('view.activitylogs')
                <flux:sidebar.item icon="clock" :href="route('activitylogs')"
                    :current="request()->routeIs('activitylogs')" wire:navigate>
                    {{ __('Activity Logs') }}
                </flux:sidebar.item>
            @endcan --}}
            @if (auth()->user()->hasRole('chairman'))
                <flux:sidebar.item icon="clock" :href="route('activitylogs')"
                    :current="request()->routeIs('activitylogs')" wire:navigate>
                    {{ __('Activity Logs') }}
                </flux:sidebar.item>
            @endif

            @can('view.reports')
                <flux:sidebar.item icon="badge-alert" :href="route('report-issue')"
                    :current="request()->routeIs('report-issue')" wire:navigate>
                    {{ __('Issues') }}
                </flux:sidebar.item>
            @endcan
            {{-- <flux:sidebar.item icon="wrench" :href="route('maintenance')"
                :current="request()->routeIs('maintenance')" wire:navigate>
                {{ __('Maintenance') }}
            </flux:sidebar.item> --}}
        </flux:sidebar.nav>


        {{-- <flux:sidebar.item icon="document-chart-bar" :href="route('reports')"
                :current="request()->routeIs('reports')" wire:navigate>
                {{ __('Reports') }}
            </flux:sidebar.item> --}}

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
        class="border-zinc-200 bg-gradient-to-r from-blue-400 to-blue-300 
           dark:from-gray-900 dark:to-gray-800 dark:border-zinc-700 drop-shadow-lg">

        <!-- Sidebar Toggle -->
        <flux:sidebar.collapse icon="bars-3" inset="left" />

        <flux:spacer />

        <!-- Notification + User Section -->
        <div class="flex items-center gap-3">

            <!-- Activity Bell -->
            <div class="flex items-center">
                @livewire('activity-bell')
            </div>

            <!-- Vertical Divider -->
            <div class="w-px h-6 bg-white/50 dark:bg-gray-700 mx-2"></div>

            <!--  User Info -->
            <div
                class="relative flex items-center gap-3 
                    bg-white/20 dark:bg-gray-800/30 
                    backdrop-blur-md rounded-xl px-3 py-1 
                     transition hover:bg-white/30 dark:hover:bg-gray-700/40 inset-shadow-sm inset-shadow-indigo-500">

                <!-- Desktop View -->
                <div class="hidden md:flex items-center gap-2">
                    <div
                        class="flex items-center justify-center w-8 h-8 rounded-full 
                           bg-indigo-500 text-white font-semibold text-sm shadow-sm ">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div class="flex flex-col">
                        <span class="font-medium text-zinc-900 dark:text-zinc-100 text-sm leading-tight">
                            {{ auth()->user()->name }}
                        </span>

                        <div class="flex gap-1 mt-0.5 flex-wrap">
                            @if (auth()->user()->getRoleNames()->isNotEmpty())
                                @foreach (auth()->user()->getRoleNames() as $role)
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[12px] font-medium
                                    {{ match ($role) {
                                        'chairman' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                                        'lab_incharge' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
                                        'lab_technician' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                                        default => 'bg-gray-100 text-gray-700 dark:bg-gray-800/40 dark:text-gray-300',
                                    } }}">
                                        {{ ucwords(str_replace('_', ' ', $role)) }}
                                    </span>
                                @endforeach
                            @else
                                <span
                                    class="px-2 py-0.5 rounded-full text-[12px] font-medium 
                                       bg-gray-100 text-gray-700 dark:bg-gray-800/40 dark:text-gray-300">
                                    Guest
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Mobile Dropdown -->
                <div class="block md:hidden">
                    <flux:dropdown position="bottom" align="end">
                        <button
                            class="flex items-center justify-center w-9 h-9 rounded-full 
                               bg-indigo-500 text-white font-semibold text-sm 
                               focus:outline-none hover:ring-2 hover:ring-indigo-300 
                               dark:hover:ring-indigo-700 transition">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </button>

                        <flux:menu class="w-56 p-0 overflow-hidden rounded-2xl">
                            <!-- Profile Info -->
                            <div
                                class="flex items-center gap-3 px-3 py-3 border-b border-gray-200 dark:border-gray-700">
                                <div
                                    class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-500 text-white font-semibold text-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-sm text-gray-900 dark:text-gray-100">
                                        {{ auth()->user()->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ auth()->user()->email }}
                                    </div>
                                </div>
                            </div>

                            <!-- Menu Items -->
                            <flux:menu.radio.group>
                                <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                                    {{ __('Settings') }}
                                </flux:menu.item>
                            </flux:menu.radio.group>

                            <flux:menu.separator />

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle">
                                    {{ __('Log Out') }}
                                </flux:menu.item>
                            </form>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>
        </div>
    </flux:header>


    <flux:main>
        {{ $slot }}
    </flux:main>

    @fluxScripts
</body>

</html>
