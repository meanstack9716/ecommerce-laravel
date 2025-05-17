@if ($paginator->hasPages())
    <div class="flex items-center text-center gap-4 w-fit">
        @if ($paginator->onFirstPage())
            <span class="px-4 py-2 bg-gray-200 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                Previous
            </span>

        @else
            <a href="{{ $paginator->previousPageUrl() }}" 
               class="px-4 py-2 bg-[#334a8b] hover:bg-blue-950 text-white text-sm rounded-lg transition-colors duration-200">
                Previous
            </a>
        @endif

        <span class="text-sm font-medium text-gray-700">
            Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
        </span>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" 
               class="px-4 py-2 bg-[#334a8b] hover:bg-blue-950 text-white text-sm rounded-lg transition-colors duration-200">
                Next
            </a>
            
        @else
            <span class="px-4 py-2 bg-gray-200 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                Next
            </span>
        @endif
    </div>
@endif