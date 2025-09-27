<div class="flex flex-col gap-3">

    <!-- Header -->
    <x-auth-header :title="__('Reset Password')" :description="__('Follow the steps to reset your password')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center mb-6" :status="session('status')" />

    <!-- Stepper -->
    <div class="flex items-center justify-between mb-8">

        @php
            $steps = [
                1 => ['label' => 'Enter', 'sub' => 'Email'],
                2 => ['label' => 'Select', 'sub' => 'Verification Option'],
                3 => ['label' => 'Verify &', 'sub' => 'Completed'],
                4 => ['label' => 'Set New', 'sub' => 'Password'],
            ];
            $total = count($steps);
        @endphp

        @foreach ($steps as $index => $info)
            <div class="flex-1 flex flex-col items-center relative">
                <!-- Circle -->
                <div
                    class="w-10 h-10 rounded-full flex items-center justify-center z-10
                {{ $step > $index ? 'bg-green-500 text-white font-semibold' : ($step === $index ? 'bg-green-500 text-white font-semibold' : 'bg-gray-200 text-gray-500') }}">
                    {{ sprintf('%02d', $index) }}
                </div>

                <!-- Label -->
                <span class="text-xs text-center mt-1 leading-snug truncate">
                    {{ $info['label'] }}<br>{{ $info['sub'] }}
                </span>

                <!-- Connector -->
                @if ($index < $total)
                    <div class="absolute top-1/2 left-full w-full h-1 flex-1 -translate-x-1/2">
                        <div class="h-1 bg-gray-300 dark:bg-zinc-700 w-full"></div>
                        @if ($step > $index)
                            <div class="h-1 bg-green-500 w-full absolute top-0 left-0"></div>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>


    <!-- Step Content -->
    <div class="space-y-4">
        @if ($step === 1)
            <flux:input wire:model="email" :label="__('Email')" type="email" required autofocus />
            <flux:button wire:click="submitEmail" variant="primary" class="w-full mt-2">Next</flux:button>
        @elseif ($step === 2)
            <p class="mb-2 font-medium">Select Verification Option:</p>

            <div class="grid grid-cols-2 gap-2">
                @php
                    $options = [
                        '2fa' => [
                            'label' => '2FA',
                            'desc' => 'Use Google Authenticator code',
                            'enabled' => $user?->google2fa_enabled,
                        ],
                        'security' => [
                            'label' => 'Security Questions',
                            'desc' => 'Answer your predefined questions',
                            'enabled' => true,
                        ],
                    ];
                @endphp

                @foreach ($options as $value => $option)
                    <label
                        class="cursor-pointer border rounded-lg p-4 flex flex-col items-center justify-center text-center transition-all duration-200
                {{ $selectedVerification === $value ? 'border-green-500 bg-green-50 dark:bg-green-900/30' : 'border-gray-300 dark:border-zinc-700 bg-white/20 dark:bg-zinc-800' }}
                {{ $option['enabled'] ? 'hover:border-green-500 hover:bg-green-50 dark:hover:bg-green-900/20' : 'opacity-50 cursor-not-allowed' }}">
                        <input type="radio" wire:model.live="selectedVerification" value="{{ $value }}"
                            class="hidden" {{ $option['enabled'] ? '' : 'disabled' }}>
                        <div class="text-lg font-semibold">{{ $option['label'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $option['desc'] }}</div>
                    </label>
                @endforeach
            </div>



            <div class="flex justify-between mt-2">
                <flux:button wire:click="$set('step', 1)">Back</flux:button>
                <flux:button wire:click="submitVerificationOption" variant="primary">
                    Next
                </flux:button>

            </div>
        @elseif ($step === 3)
            @if ($selectedVerification === '2fa')
                <flux:input wire:model="otp" label="Enter OTP" />
            @else
                <div class="flex flex-col gap-4 md:flex-col md:gap-4">

                    {{-- Date of Birth --}}
                    <div class="flex-1">
                        <flux:input type="date" wire:model.defer="date_of_birth" label="Date of Birth"
                            :error="$errors->first('date_of_birth')" />
                    </div>

                    {{-- Security Questions --}}
                    @foreach ($user->securityAnswers as $qa)
                        <div class="flex-1">
                            <flux:input type="text" wire:model.defer="securityAnswers.{{ $qa->id }}"
                                label="{{ $qa->question }}" :error="$errors->first('securityAnswers.' . $qa->id)" />
                        </div>
                    @endforeach

                </div>

            @endif



            <div class="flex justify-between mt-2">
                <flux:button
                    wire:click="
        $set('step', 2);
        $set('selectedVerification', null);
        $resetValidation('selectedVerification')
    ">
                    Back
                </flux:button>


                <flux:button wire:click="submitSecurity" variant="primary">Next</flux:button>
            </div>
        @elseif($step === 4)
            <flux:input wire:model="password" :label="__('Password')" type="password" required />
            <flux:input wire:model="password_confirmation" :label="__('Confirm Password')" type="password" required />
            <flux:button wire:click="resetPassword" class="w-full mt-2" variant="primary">Reset Password</flux:button>
        @endif
    </div>

</div>
