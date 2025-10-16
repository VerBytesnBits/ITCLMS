<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)"
    class="w-full max-w-md p-8 sm:p-10 rounded-3xl border border-emerald-200/40 bg-gradient-to-br from-emerald-500/40 via-emerald-600/50 to-teal-700/60 backdrop-blur-md shadow-xl flex flex-col gap-5 transition-all duration-700 ease-out hover:shadow-2xl hover:scale-[1.01]"
    x-show="loaded" x-transition:enter="transition ease-out duration-700"
    x-transition:enter-start="opacity-0 translate-y-6 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100">

    <!-- Animated Header -->
    <x-auth-header :title="__('Welcome Back!')" :description="__('Log in  to your account')" />

    
    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />
    <x-alert />

    <!-- Login Form -->
    <form wire:submit.prevent="login" class="flex flex-col gap-5">

        <!-- Email -->
        <div x-data="{ focused: false }" class="relative transition-all duration-300" :class="{ 'scale-[1.02]': focused }">
            <flux:input wire:model="email" :label="__('Email address')" type="email" required autofocus
                autocomplete="email" @focus="focused = true" @blur="focused = false" />
        </div>

        <!-- Password -->
        <div x-data="{ focused: false }" class="relative transition-all duration-300" :class="{ 'scale-[1.02]': focused }">
            <flux:input wire:model="password" :label="__('Password')" type="password" required
                autocomplete="current-password" viewable @focus="focused = true" @blur="focused = false" />

            @if (Route::has('password.request'))
                <flux:link :href="route('password.request')" wire:navigate
                    class="absolute top-0 right-0 mt-2 text-xs text-yellow-300 hover:text-yellow-100 transition">
                    {{ __('Forgot password?') }}
                </flux:link>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="mt-2">
            <flux:button variant="primary" type="submit"
                class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2.5 rounded-xl shadow-sm hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5 active:scale-95">
                {{ __('Log in') }}
            </flux:button>
        </div>
    </form>

    <!-- Footer -->
    @if (Route::has('register'))
        <div x-data="{ hover: false }" class="text-center text-sm text-zinc-200 mt-2">
            <span>{{ __("Don't have an account?") }}</span>
            <flux:link :href="route('register')" wire:navigate @mouseenter="hover = true" @mouseleave="hover = false"
                class="font-semibold text-yellow-300 hover:text-yellow-200 transition"
                x-bind:class="{ 'underline decoration-yellow-300/60': hover }">
                {{ __('Sign up') }}
            </flux:link>
        </div>
    @endif
</div>
