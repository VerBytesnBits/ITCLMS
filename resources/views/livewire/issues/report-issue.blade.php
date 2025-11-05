<div>
    @if ($showModal)
        <div 
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4"
            x-data="{ show: @entangle('showModal') }"
            x-show="show"
            x-transition.opacity
        >
            <div 
                class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-zinc-200 dark:border-zinc-700 animate-[fade-in-scale_0.2s_ease]"
            >
                <!-- Header -->
                <div class="flex items-center justify-between px-5 py-3 bg-blue-600 text-white">
                    <h2 class="text-lg font-semibold flex items-center gap-2">
                        <flux:icon.triangle-alert class="w-5 h-5" />
                        Report Issue
                    </h2>
                    <button wire:click="close" class="hover:text-gray-200">
                        <flux:icon.x class="w-5 h-5" />
                    </button>
                </div>

                <!-- Body -->
                <form wire:submit.prevent="submit" class="px-6 py-5 space-y-5">
                    
                    {{-- Issue Category --}}
                    <div>
                        <flux:label>Issue Category</flux:label>
                        <flux:select wire:model.live="issueCategory">
                            <option value="general">General</option>
                            <option value="component">Component</option>
                            <option value="peripheral">Peripheral</option>
                        </flux:select>
                    </div>

                    {{-- Dynamic Item Selection --}}
                    @if ($issueCategory === 'component')
                        <div>
                            <flux:label>Select Component</flux:label>
                            <flux:select wire:model="selectedItemId">
                                <option value="">-- Choose Component --</option>
                                @foreach ($components as $comp)
                                    <option value="{{ $comp->id }}">
                                        {{ $comp->brand ?? '' }} {{ $comp->model ?? ($comp->type ?? '') }} {{ $comp->capacity ? "({$comp->capacity})" : '' }}
                                    </option>
                                @endforeach
                            </flux:select>
                        </div>
                    @elseif ($issueCategory === 'peripheral')
                        <div>
                            <flux:label>Select Peripheral</flux:label>
                            <flux:select wire:model="selectedItemId">
                                <option value="">-- Choose Peripheral --</option>
                                @foreach ($peripherals as $periph)
                                    <option value="{{ $periph->id }}">
                                        {{ $periph->brand ?? '' }} {{ $periph->model ?? ($periph->type ?? '') }} {{ $periph->capacity ? "({$periph->capacity})" : '' }}
                                    </option>
                                @endforeach
                            </flux:select>
                        </div>
                    @endif

                    {{-- Issue Type --}}
                    <div x-data="{ showCustom: @entangle('issueType') === 'Other' }" x-on:change="$event.target.value === 'Other' ? showCustom = true : showCustom = false">
                        <flux:label>Issue Type</flux:label>
                        <flux:select wire:model="issueType">
                            <option value="">-- Select Issue Type --</option>
                            <option value="Not Working">Not Working</option>
                            <option value="Overheating">Overheating</option>
                            <option value="Loose Cable">Loose Cable</option>
                            <option value="Missing Part">Missing Part</option>
                            <option value="Damaged">Damaged</option>
                            <option value="Connectivity Issue">Connectivity Issue</option>
                            <option value="Slow Performance">Slow Performance</option>
                            <option value="Other">Other</option>
                        </flux:select>

                        <div x-show="showCustom" x-cloak class="mt-2">
                            <flux:input 
                                wire:model="customIssueType"
                                placeholder="Enter custom issue type"
                            />
                        </div>
                    </div>

                    {{-- Remarks --}}
                    <div>
                        <flux:label>Remarks (optional)</flux:label>
                        <flux:textarea 
                            wire:model="remarks"
                            placeholder="Describe the issue..."
                            rows="3"
                        />
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-3 pt-3">
                        <flux:button 
                            type="button" 
                            variant="ghost" 
                            wire:click="close"
                        >
                            Cancel
                        </flux:button>
                        <flux:button 
                            type="submit"
                            variant="primary"
                        >
                            Submit Report
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
