<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="relative min-h-screen antialiased flex flex-col">

    <!-- Background: Lab image + gradient overlay -->
    <div
        class="absolute inset-0 overflow-hidden bg-gradient-to-br from-emerald-700/70 via-emerald-800/70 to-emerald-900/80 z-0">
        <!-- Lab Image -->
        <img src="{{ asset('images/bg1.jpg') }}" alt="IT Lab"
            class="absolute inset-0 w-full h-full object-cover opacity-45 mix-blend-overlay z-0" lazy>


        <!-- Animated Blobs -->
        <div x-data="{
            blobs: [
                { x: 10, y: 20, size: 200, dx: 0.3, dy: 0.2, color: '#facc15' }, // gold
                { x: 70, y: 40, size: 300, dx: -0.2, dy: 0.3, color: '#22c55e' }, // lighter green
                { x: 40, y: 70, size: 250, dx: 0.25, dy: -0.2, color: '#ec4899' } // pink accent
            ],
            animate() {
                const loop = () => {
                    this.blobs.forEach(b => {
                        b.x += b.dx;
                        b.y += b.dy;
                        if (b.x < -50 || b.x > 150) b.dx *= -1;
                        if (b.y < -50 || b.y > 150) b.dy *= -1;
                    });
                    requestAnimationFrame(loop);
                };
                loop();
            }
        }" x-init="animate()" class="w-full h-full relative">
            <template x-for="b in blobs" :key="b.color">
                <div
                    :style="`
                                                                                                        position:absolute;
                                                                                                        top:${b.y}%;
                                                                                                        left:${b.x}%;
                                                                                                        width:${b.size}px;
                                                                                                        height:${b.size}px;
                                                                                                        background:${b.color};
                                                                                                        border-radius:50%;
                                                                                                        filter: blur(100px);
                                                                                                        mix-blend-mode: screen;
                                                                                                        opacity:0.7;
                                                                                                    `">
                </div>
            </template>
        </div>
    </div>

    <!-- Main Content Wrapper -->
    <div class="relative z-10 flex-1 flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8">

        <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)"
            class="flex flex-col lg:flex-row w-full max-w-6xl justify-center items-center">

            <!-- Left Column: Branding / Logo -->
            <div class="lg:w-1/2 flex flex-col items-center lg:items-start text-center lg:text-left" x-show="show"
                x-cloak x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="-translate-x-20 opacity-0" x-transition:enter-end="translate-x-0 opacity-100">

                <a href="{{ route('home') }}" class="mb-6 flex flex-col items-center lg:items-start gap-5 font-medium">

                    <div class="relative flex items-center justify-center">

                        <img src="{{ asset('images/PIT.png') }}" alt="PIT Logo" loading="lazy" {{-- These classes define the responsive size of the logo image itself --}}
                            class="w-20 h-20  md:w-20 md:h-20 lg:w-24 lg:h-24 flex-shrink-0">

                        <img src="{{ asset('images/ICT-logo1.png') }}" alt="ICT Logo" loading="lazy"
                            {{-- These classes define the responsive size of the logo image itself --}} class="w-20 h-20  md:w-20 md:h-20 lg:w-24 lg:h-24 flex-shrink-0">


                    </div>

                    <!-- Text Branding -->
                    <blockquote class="space-y-3">
                        <!-- Title -->
                        <h1
                            class="text-6xl lg:text-[5.9rem] font-extrabold uppercase tracking-widest 
                                text-white drop-shadow-lg leading-tight">
                            {{ config('app.name', 'ITCLMS') }}
                        </h1>

                        <!-- Subtitle (hidden on small screens) -->
                        <p
                            class="hidden sm:block text-base sm:text-lg md:text-2xl lg:text-[1.9rem] text-gray-200 leading-snug italic max-w-xl">
                            Information Technology Computer Laboratory Management System
                        </p>

                        <!-- Tagline (hidden on small screens) -->
                        <p class="hidden sm:block text-sm sm:text-base text-emerald-200 tracking-wide">
                            Tracking & Inventory Management
                        </p>

                        <!-- Accent Line -->
                        <div
                            class="mt-3 h-1 w-16 sm:w-20 md:w-24 rounded-full bg-gradient-to-r from-emerald-400 to-emerald-600 mx-auto lg:mx-0">
                        </div>
                    </blockquote>
                </a>
            </div>


            <!-- Right Column: Slot (Login/Register/Reset) -->
            <div class="lg:w-1/2 flex justify-center items-center" x-show="show" x-cloak
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="translate-x-20 opacity-0" x-transition:enter-end="translate-x-0 opacity-100">
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <x-footer />
    @fluxScripts
</body>

</html>
