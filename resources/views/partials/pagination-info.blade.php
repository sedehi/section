@if (!$items->isEmpty())
    <span>&nbsp;&nbsp;&nbsp;</span>
    <span class="fsz-sm">نمایش</span>
    <span class="fsz-sm">{{ $items->firstItem() }} تا {{ $items->lastItem() }}</span>
    <span class="fsz-sm">از</span>
    <span class="fsz-sm">{{ $items->total() }} نتیجه</span>
@endif
