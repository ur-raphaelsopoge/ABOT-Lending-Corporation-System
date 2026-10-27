@if ($items->hasPages())
    <nav class="pager" aria-label="Pagination">
        @if ($items->onFirstPage())
            <span class="pager-btn is-disabled">Previous</span>
        @else
            <a class="pager-btn" href="{{ $items->previousPageUrl() }}">Previous</a>
        @endif

        <span class="pager-page">Page {{ $items->currentPage() }}</span>

        @if ($items->hasMorePages())
            <a class="pager-btn" href="{{ $items->nextPageUrl() }}">Next</a>
        @else
            <span class="pager-btn is-disabled">Next</span>
        @endif
    </nav>
@endif
