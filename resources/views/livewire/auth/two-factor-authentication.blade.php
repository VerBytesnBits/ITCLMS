<div 
    x-data="{ loaded: false }" 
    x-init="setTimeout(() => loaded = true, 100)" 
    x-show="loaded"
    x-transition:enter="transition ease-out duration-700"
    x-transition:enter-start="opacity-0 translate-y-6 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    class="w-full max-w-md p-8 sm:p-10 rounded-3xl border border-emerald-200/40 
           bg-gradient-to-br from-emerald-500/40 via-emerald-600/50 to-teal-700/60 
           backdrop-blur-md shadow-xl flex flex-col gap-5 transition-all duration-700 ease-out 
           hover:shadow-2xl hover:scale-[1.01]"
>

    <!-- Header -->
    <x-auth-header 
        :title="__('Verify OTP')" 
        :description="__('Enter your 6-digit code to complete Google 2FA verification')" 
    />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />
    <x-alert />

    <!-- OTP Form -->
    <form wire:submit.prevent="verifyOtp" class="flex flex-col gap-5">

        <!-- OTP Field -->
        <div x-data="{ focused: false }" 
             class="relative transition-all duration-300" 
             :class="{ 'scale-[1.02]': focused }">
            <flux:input 
                wire:model.defer="otp"
                label="One-Time Password"
                type="text"
                maxlength="6"
                required
                placeholder="Enter 6-digit code"
                inputmode="numeric"
                @focus="focused = true" 
                @blur="focused = false"
                class="tracking-widest text-center"
                oninput="this.value=this.value.replace(/[^0-9]/g,'');"
            />
        </div>

        <!-- Primary Button -->
        <div class="mt-2">
            <flux:button 
                type="submit"
                variant="primary"
                class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2.5 
                       rounded-xl shadow-sm hover:shadow-lg transition-all duration-200 
                       hover:-translate-y-0.5 active:scale-95">
                {{ __('Verify OTP') }}
            </flux:button>
        </div>

        <!-- Go Back Button -->
        <flux:button 
            wire:click="goBackToLogin"
            class="w-full bg-transparent border border-yellow-300/40 text-yellow-200 font-semibold py-2.5 
                   rounded-xl hover:bg-yellow-200/20 transition-all duration-200 
                   hover:-translate-y-0.5 active:scale-95">
            {{ __('Return to Login') }}
        </flux:button>
    </form>

    <!-- Footer Note -->
    <div class="text-center text-xs text-yellow-100/70 mt-3 italic">
        {{ __('Use the code from your Google Authenticator app.') }}
    </div>
</div>
