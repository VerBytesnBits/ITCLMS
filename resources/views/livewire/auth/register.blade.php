<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)"
    class="w-full max-w-3xl mx-auto bg-gradient-to-br from-emerald-600/60 via-teal-600/70 to-emerald-700/50 backdrop-blur-xl 
            rounded-3xl shadow-2xl border border-emerald-200/40 p-8 sm:p-10 transition-all duration-700 hover:shadow-yellow-500/10 hover:shadow-2xl hover:scale-[1.01]"
    x-show="loaded" x-transition:enter="transition ease-out duration-700"
    x-transition:enter-start="opacity-0 translate-y-6 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100">

    <!-- Header -->
    <h1 class="text-3xl font-extrabold text-white mb-2 tracking-tight text-center">Create an Account</h1>
    <p class="text-sm text-emerald-100/80 mb-2 text-center">
        @if ($step === 1)
            Enter your user information below.
        @elseif ($step === 2)
            Set up your password.
        @elseif ($step === 3)
            Configure security and confirm details.
        @endif
    </p>

    <!-- Stepper -->
    <div class="flex items-center justify-between relative px-2 sm:px-4 mb-10 mt-5">
        @php
            $steps = [
                1 => ['label' => 'Enter', 'sub' => 'User Info'],
                2 => ['label' => 'Setup', 'sub' => 'Password'],
                3 => ['label' => 'Security', 'sub' => 'Setup'],
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

    <!-- Form Section -->
    <form wire:submit.prevent="{{ $step < 3 ? 'nextStep' : 'register' }}" class="flex flex-col gap-6 max-w-md mx-auto">

        {{-- Step 1 --}}
        @if ($step === 1)
            <div x-transition.opacity.duration.300ms class="flex flex-col gap-4">
                <flux:input wire:model="name" label="Full Name" placeholder="John Doe" required />
                <flux:input wire:model="email" label="Email Address" type="email" placeholder="john@example.com" required />
            </div>
       
                <flux:button type="submit" variant="primary">Next</flux:button>
      
        @endif

        {{-- Step 2 --}}
        @if ($step === 2)
            <div x-transition.opacity.duration.300ms class="flex flex-col gap-4">
                <flux:input wire:model="password" label="Password" type="password" placeholder="Password" viewable required />
                <flux:input wire:model="password_confirmation" label="Confirm Password" type="password" placeholder="Confirm password" viewable required />
            </div>
            <div class="flex justify-between mt-6">
                <flux:button wire:click.prevent="previousStep" variant="outline" class="w-32">Back</flux:button>
                <flux:button type="submit" variant="primary" class="w-32 bg-yellow-500 hover:bg-yellow-600 transition-all">Next</flux:button>
            </div>
        @endif

        {{-- Step 3 --}}
        @if ($step === 3)
            <div x-transition.opacity.duration.300ms class="flex flex-col gap-2">
                <flux:input wire:model="date_of_birth" label="Date of Birth" type="date" />
                <flux:select wire:model.live="selectedQuestion" label="Security Question">
                    <option value="">Select a question</option>
                    @foreach ($availableQuestions as $question)
                        <option value="{{ $question }}">{{ $question }}</option>
                    @endforeach
                </flux:select>

                @if ($selectedQuestion)
                    <flux:input wire:model="answer" label="Your Answer" placeholder="Type your answer" />
                @endif

                <label class="flex items-center text-sm text-gray-200 mt-2">
                    <input type="checkbox" wire:model="agreed" class="mr-2 rounded border-gray-300 text-yellow-500 focus:ring-yellow-400">
                    I agree to the 
                    <a href="#" class="text-yellow-300 font-medium ml-1 hover:underline">Terms & Conditions</a>.
                </label>

                @error('agreed')
                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-between mt-6">
                <flux:button wire:click.prevent="previousStep" variant="outline" class="w-32">Back</flux:button>
                <flux:button type="submit" variant="primary" class="w-32 bg-yellow-500 hover:bg-yellow-600 transition-all">Finish</flux:button>
            </div>
        @endif
    </form>

    <!-- Footer -->
    <div class="mt-10 text-center text-sm text-emerald-100">
        <span>Already have an account?</span>
        <flux:link :href="route('login')" wire:navigate class="text-yellow-400 font-semibold hover:text-yellow-300 transition">Log in</flux:link>
    </div>
</div>
