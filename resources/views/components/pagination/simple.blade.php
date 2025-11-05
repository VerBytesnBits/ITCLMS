@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-3">
        {{-- Showing Results --}}
        <div class="text-gray-600 text-sm mb-2 sm:mb-0">
            Showing 
            {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} 
            of {{ $paginator->total() }} results
        </div>

        {{-- Pagination Controls --}}
        <nav class="flex gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="text-gray-400 px-3 py-1 border rounded">Previous</span>
            @else
                <button wire:click="previousPage('{{ $paginator->getPageName() }}')" 
                    class="px-3 py-1 border rounded hover:bg-gray-100">
                    Previous
                </button>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <button wire:click="nextPage('{{ $paginator->getPageName() }}')" 
                    class="px-3 py-1 border rounded hover:bg-gray-100">
                    Next
                </button>
            @else
                <span class="text-gray-400 px-3 py-1 border rounded">Next</span>
            @endif
        </nav>
    </div>
@endif
