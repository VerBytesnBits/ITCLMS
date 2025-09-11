<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <div
            class="bg-muted relative hidden h-full flex-col p-10 text-white lg:flex dark:border-e dark:border-neutral-800">
            <div class="absolute inset-0 bg-neutral-900"></div>
            {{-- <a href="{{ route('home') }}" class="relative z-20 flex items-center  text-7xl font-medium" wire:navigate> --}}
            <div class="relative z-20 flex items-center  text-7xl font-medium">
                <span class="flex h-25 w-25 items-center justify-center rounded-md">
                    {{-- <x-app-logo-icon class="me-2 h-7 fill-current text-white" /> --}}
                    @include('partials.itclms-logo')

                </span>
            </div>



            {{-- </a> --}}

            <div class="relative z-20 mt-auto">
                <blockquote class="space-y-2">
                    <flux:heading>
                        <h1
                            class="text-7xl font-black uppercase tracking-[1.1em]
               text-white light-outline">
                            {{ config('app.name', 'Laravel') }}
                        </h1>

                    </flux:heading>
                    <footer>
                        <flux:heading>
                            <p class="text-[27px] text-gray-500 dark:text-gray-400 leading-tight italic">
                                "Information Technology Computer Laboratory Management System"
                            </p>
                        </flux:heading>
                    </footer>
                </blockquote>
            </div>
        </div>
        <div class="w-full lg:p-8">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden"
                    wire:navigate>
                    <span class="flex h-25 w-25 items-center justify-center rounded-md">
                        {{-- <x-app-logo-icon class="me-2 h-7 fill-current text-white" /> --}}
                        @include('partials.itclms-logo')

                    </span>

                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                {{ $slot }}
            </div>
        </div>
    </div>
    @fluxScripts
</body>

</html>
