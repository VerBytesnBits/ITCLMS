<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)"
    class="w-full max-w-3xl mx-auto bg-gradient-to-br from-emerald-600/60 via-teal-600/70 to-emerald-700/50 backdrop-blur-xl 
            rounded-3xl shadow-2xl border border-emerald-200/40 p-8 sm:p-10 transition-all duration-700 hover:shadow-yellow-500/10 hover:shadow-2xl hover:scale-[1.01]"
    x-show="loaded" x-transition:enter="transition ease-out duration-700"
    x-transition:enter-start="opacity-0 translate-y-6 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100">

    <!-- Header -->
    <x-auth-header :title="__('Reset Password')" :description="__('Follow the steps to securely reset your password')" />

    <!-- Alerts -->
    <x-auth-session-status class="text-center" :status="session('status')" />
    <x-alert />

    <!-- Stepper -->
    <div class="flex items-center justify-between relative px-2 sm:px-4 mb-6">
        @php
            $steps = [
                1 => ['label' => 'Enter', 'sub' => 'Email'],
                2 => ['label' => 'Select', 'sub' => 'Verification'],
                3 => ['label' => 'Security', 'sub' => 'Check'],
                4 => ['label' => 'Set', 'sub' => 'Password'],
            ];
            $total = count($steps);
        @endphp

        @foreach ($steps as $index => $info)
            <div class="flex flex-col items-center flex-1 text-center relative">

                <!-- Connector -->
                @if ($index < $total)
                    <div class="absolute top-5 right-[-50%] w-full h-[2px] bg-gray-200">
                        @if ($step > $index)
                            <div class="absolute top-0 left-0 h-[2px] bg-yellow-500 transition-all duration-500 w-full"></div>
                        @endif
                    </div>
                @endif

                <!-- Step Circle -->
                <div
                    class="z-10 w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center font-semibold shadow
                    {{ $step > $index 
                        ? 'bg-yellow-500 text-white shadow-yellow-200' 
                        : ($step === $index 
                            ? 'bg-yellow-400 text-white ring-4 ring-yellow-100' 
                            : 'bg-gray-200 text-gray-600') }}">
                    {{ sprintf('%02d', $index) }}
                </div>

                <!-- Labels -->
                <div class="mt-2 text-xs sm:text-sm leading-tight">
                    <span class="block font-medium text-zinc-800">{{ $info['label'] }}</span>
                    <span class="block text-emerald-100/80">{{ $info['sub'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Step Content -->
    <div class="space-y-6">

        {{-- STEP 1: Enter Email --}}
        @if ($step === 1)
            <flux:input wire:model="email" label="Email Address" type="email" required autofocus />
            <flux:button wire:click="submitEmail" variant="primary" color="yellow" class="w-full mt-3">
                Continue
            </flux:button>

        {{-- STEP 2: Select Verification --}}
        @elseif ($step === 2)
            <h3 class="font-semibold text-gray-800 text-center text-lg">Select Verification Option</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @php
                    $options = [
                        '2fa' => [
                            'label' => '2FA',
                            'desc' => 'Use Google Authenticator',
                            'enabled' => $user?->google2fa_enabled,
                        ],
                        'security' => [
                            'label' => 'Security Questions',
                            'desc' => 'Answer predefined questions',
                            'enabled' => true,
                        ],
                    ];
                @endphp

                @foreach ($options as $value => $option)
                    <label
                        class="cursor-pointer border rounded-2xl p-5 flex flex-col items-center justify-center text-center transition-all duration-300 
                        {{ $selectedVerification === $value ? 'border-yellow-400 bg-yellow-50 shadow-sm' : 'border-gray-200 bg-white' }}
                        {{ $option['enabled'] ? 'hover:border-yellow-300 hover:bg-yellow-50' : 'opacity-50 cursor-not-allowed' }}">
                        <input type="radio" wire:model.live="selectedVerification" value="{{ $value }}" class="hidden"
                            {{ $option['enabled'] ? '' : 'disabled' }}>
                        <span class="font-semibold text-gray-800 text-base">{{ $option['label'] }}</span>
                        <span class="text-xs text-gray-500 mt-1">{{ $option['desc'] }}</span>
                    </label>
                @endforeach
            </div>

            <div class="flex justify-between mt-6">
                <flux:button wire:click="$set('step', 1)" variant="outline" class="w-1/2 sm:w-auto">
                    Back
                </flux:button>
                <flux:button wire:click="submitVerificationOption" variant="primary" color="yellow" class="w-1/2 sm:w-auto">
                    Continue
                </flux:button>
            </div>

        {{-- STEP 3: Verification --}}
        @elseif ($step === 3)
            @if ($selectedVerification === '2fa')
                <flux:input wire:model="otp" label="Enter 2FA Code" />
            @else
                <div class="flex flex-col gap-4">
                    <flux:input type="date" wire:model.defer="date_of_birth" label="Date of Birth" />
                    @foreach ($user->securityAnswers as $qa)
                        <flux:input type="text" wire:model.defer="securityAnswers.{{ $qa->id }}" label="{{ $qa->question }}" />
                    @endforeach
                </div>
            @endif

            <div class="flex justify-between mt-6">
                <flux:button
                    wire:click="
                        $set('step', 2);
                        $set('selectedVerification', null);
                        $resetValidation('selectedVerification');
                    "
                    variant="outline">
                    Back
                </flux:button>
                <flux:button wire:click="submitSecurity" variant="primary" color="yellow">
                    Continue
                </flux:button>
            </div>

        {{-- STEP 4: Set New Password --}}
        @elseif ($step === 4)
            <div class="space-y-4">
                <flux:input wire:model="password" label="New Password" type="password" required viewable />
                <flux:input wire:model="password_confirmation" label="Confirm Password" type="password" required viewable />
            </div>

            <flux:button wire:click="resetPassword" variant="primary" color="yellow" class="w-full mt-4">
                Reset Password
            </flux:button>
        @endif
    </div>
</div>
