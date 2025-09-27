<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Verify OTP')" :description="__('Enter your otp for google 2fa')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit.prevent="verifyOtp" class="flex flex-col gap-6">
        <label class="text-gray-700 font-medium mb-2">{{ __('OTP') }}</label>

        <div class="flex gap-2 justify-center">
            <input type="text" maxlength="6" wire:model.defer="otp"
                class="w-48 text-center border rounded-lg focus:ring-2 focus:ring-indigo-500 text-lg"
                oninput="this.value=this.value.replace(/[^0-9]/g,'');" />

        </div>

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Verify') }}
            </flux:button>
        </div>
    </form>
</div>
