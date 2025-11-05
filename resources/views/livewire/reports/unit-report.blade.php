<div class="p-6 space-y-6 relative">
    <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
        <flux:icon name="printer" />
        System Unit Report
    </h2>

    {{-- Options --}}
    <div class="space-y-2">
        <label class="flex items-center gap-2">
            <input type="checkbox" wire:model="includeComponents" class="rounded text-blue-600">
            <span>Include Components</span>
        </label>

        <label class="flex items-center gap-2">
            <input type="checkbox" wire:model="includePeripherals" class="rounded text-blue-600">
            <span>Include Peripherals</span>
        </label>

        <label class="flex items-center gap-2">
            <input type="checkbox" wire:model="includeHistory" class="rounded text-blue-600">
            <span>Include Unit History Logs</span>
        </label>
    </div>

    {{-- Action Buttons --}}
    <div class="flex gap-3 mt-4">
        <button
            wire:click="previewReport"
            wire:loading.attr="disabled"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            <flux:icon name="eye" class="w-4 h-4 inline-block mr-1" />
            Preview PDF
        </button>

        @if ($pdfUrl)
            <a href="{{ $pdfUrl }}" target="_blank"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                <flux:icon name="file-text" class="w-4 h-4 inline-block mr-1" />
                Open / Print PDF
            </a>
        @endif
    </div>

    {{-- Loading Overlay --}}
    <div wire:loading wire:target="previewReport"
         class="absolute inset-0 bg-black/50 flex items-center justify-center z-50 rounded-lg">
        <div class="bg-white text-gray-700 px-6 py-4 rounded-lg shadow-xl flex flex-col items-center">
            <svg class="animate-spin h-6 w-6 text-blue-600 mb-2"
                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                        stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <span class="font-semibold">Generating report, please wait…</span>
        </div>
    </div>

    {{-- PDF Preview --}}
    @if ($pdfUrl)
        <div class="mt-6 border rounded-lg overflow-hidden shadow">
            <iframe src="{{ $pdfUrl }}" class="w-full h-[80vh] border-none"></iframe>
        </div>
    @endif
</div>
