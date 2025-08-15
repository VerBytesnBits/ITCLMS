<div>
    <button wire:click="openSelectComponentsModal"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">
        Print / Export PDF
    </button>

    <!-- Component Selection Modal -->
    <x-modal name="selectComponents" maxWidth="2xl" wire:model="showSelectComponents">
        <div class="p-4">
            <h2 class="text-lg font-semibold mb-4">Select Components/Peripherals to Include in PDF</h2>

            <div class="flex flex-wrap gap-4 mb-6">
                <div class="flex flex-wrap gap-4 mb-6">
                    @foreach ($partsConfig as $key => $config)
                        <label class="inline-flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model="selectedComponents.{{ $key }}"
                                class="form-checkbox h-5 w-5 text-blue-600" />
                            <span>{{ $config['label'] }}</span>
                        </label>
                    @endforeach
                </div>

            </div>

            <div class="flex justify-end gap-2">
                <button wire:click="confirmComponentSelection"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">
                    Confirm & Preview PDF
                </button>
                <button wire:click="$set('showSelectComponents', false)"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow">
                    Cancel
                </button>
            </div>
        </div>
    </x-modal>

    <!-- Print Preview Modal -->
    <x-modal name="printPreview" maxWidth="screen-2xl" wire:model="showPreview" class="p-0" :showClose="false">
        <div class="flex flex-col h-[90vh] w-full md:w-[55vw] mx-auto p-4 bg-white dark:bg-zinc-900 rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Print Preview</h2>
            </div>

            <div class="flex-grow w-full">
                @if ($pdfBase64)
                    <iframe src="data:application/pdf;base64,{{ $pdfBase64 }}"
                        class="w-full h-full rounded border border-gray-300 shadow-lg" frameborder="0"></iframe>
                @else
                    <p class="text-gray-500 text-center py-10">Generating preview...</p>
                @endif
            </div>

            <div class="mt-4 flex justify-end gap-3">
                <button wire:click="downloadPdf"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-md shadow">
                    ⬇ Download PDF
                </button>
                <button wire:click="$set('showPreview', false)"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-md shadow">
                    ✖ Close
                </button>
            </div>
        </div>
    </x-modal>
</div>
