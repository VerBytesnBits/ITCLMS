<div>
    <flux:tooltip hoverable>
        <flux:button icon="printer" size="sm" variant="subtle" wire:click="exportPDF" />
        <flux:tooltip.content class="max-w-[20rem] space-y-2">
            <p>Print Component Reports</p>
        </flux:tooltip.content>
    </flux:tooltip>
    @if ($showPreview && $pdfBase64)
        <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">
            <div class="bg-white p-4 rounded shadow-lg w-3/4 h-3/4 flex flex-col">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-bold">Component Summary Preview</h3>
                    <div class="space-x-2">
                        <flux:button variant="outline" wire:click="downloadPDF">Download</flux:button>
                        <flux:button variant="danger" wire:click="$set('showPreview', false)">Close</flux:button>
                    </div>
                </div>
                <iframe src="data:application/pdf;base64,{{ $pdfBase64 }}" class="flex-1 w-full"></iframe>
            </div>
        </div>
    @endif
</div>
