<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')

    <style>
        /* Gradient Animation */
        @keyframes gradient-move {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .animated-gradient {
            background: linear-gradient(-45deg, #064e3b, #047857, #065f46, #10b981);
            background-size: 300% 300%;
            animation: gradient-move 12s ease infinite;
        }
    </style>
</head>

<body class="relative min-h-screen antialiased">

    <!-- Animated Gradient Background with Image -->
    <div class="absolute inset-0">
        <img src="{{ asset('images/laboratory-bg.jpg') }}" alt="IT Laboratory"
            class="h-full w-full object-cover opacity-30" />
        <div class="absolute inset-0 animated-gradient opacity-80 mix-blend-multiply"></div>
    </div>

    <!-- Content Wrapper -->
    <div class="relative z-10 flex min-h-screen flex-col items-center justify-center px-4 sm:px-6 lg:px-8">

        <!-- Logo + Brand -->
        <a href="{{ route('home') }}" class="mb-8 flex flex-col items-center gap-3 font-medium text-center"
            wire:navigate>
            <span class="flex h-16 w-16 items-center justify-center rounded-2xl shadow-xl dark:bg-neutral-800">
                {{-- <x-app-logo-icon class="size-10 fill-current text-emerald-600 dark:text-emerald-400" /> --}}
                @include('partials.itclms-logo')
            </span>
            <div class="relative z-20 mt-auto">
                <blockquote class="space-y-2 flex flex-col items-center text-center">
                    <h1 class="text-6xl font-black uppercase tracking-widest text-white drop-shadow-lg">
                        {{ config('app.name', 'ITCLMS') }} </h1>
                    <p class="text-xl text-gray-300 leading-tight italic"> "Information Technology Computer Laboratory
                        Management System" </p>
                    <p class="text-sm text-emerald-200 tracking-wide">
                        Tracking & Inventory Management
                    </p>
                    <div class="mt-4 h-1 w-24 rounded-full bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
                </blockquote>
            </div>
        </a>

        <!-- Login Card -->
        <div class="w-full max-w-md">
            <div
                class="rounded-2xl border border-white/20 
                       bg-white/10 p-8 shadow-2xl 
                       backdrop-blur-lg 
                       dark:border-neutral-700/40 dark:bg-neutral-900/20">
                {{ $slot }}
            </div>
        </div>

        <!-- Footer -->
        <p class="mt-6 text-xs text-gray-200 dark:text-gray-400 tracking-wide">
            © {{ date('Y') }} ITCLMS. All rights reserved.
        </p>
    </div>

    @fluxScripts
</body>

</html>
