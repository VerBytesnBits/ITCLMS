<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Google 2FA')" :subheading="__('Manage your two-factor authentication settings')">

        @if (! $user->google2fa_enabled)
            <div>
                <p>Setup your two factor authentication by scanning the QR code below.
                   Alternatively you can use the code <strong>{{ $secret }}</strong></p>
                {!! $urlQRCode !!}
            </div>
            <form wire:submit="verifyOTP">
                <flux:input wire:model="otp" :label="__('Enter OTP to Enable')" type="text" />
                <div class="pt-3">
                    <flux:button variant="primary" type="submit">{{ __('Enable 2FA') }}</flux:button>
                </div>
            </form>
        @else
            <div class="mb-4">
                <p class="text-green-600 font-semibold">✅ Two-factor authentication is enabled</p>
                <p>If you want to disable it, please enter your OTP.</p>
            </div>
            <form wire:submit="verifyOTP">
                <flux:input wire:model="otp" :label="__('Enter OTP to Disable')" type="text" />
                <div class="pt-3">
                    <flux:button variant="danger" type="submit">{{ __('Disable 2FA') }}</flux:button>
                </div>
            </form>
        @endif

    </x-settings.layout>
</section>
