<div class="row justify-content-between pagination">
    <div class="col-6">
        @if ($paginator->onFirstPage())
            <a class="btn btn-info disabled" href="#">@lang('pagination.previous')</a>
        @else
            <a class="btn btn-info" href="{{ $paginator->previousPageUrl() }}">@lang('pagination.previous')</a>
        @endif

    </div>
    <div class="col-6 row justify-content-end">
        @if ($paginator->hasMorePages())
            <a class="btn btn-info" href="{{ $paginator->nextPageUrl() }}">@lang('pagination.next')</a>
        @else
            <a class="btn btn-info disabled" href="#">@lang('pagination.next')</a>
        @endif
    </div>
</div>

