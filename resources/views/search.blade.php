@php
    $controllerClass = str_replace('@index','',Route::currentRouteAction());
    $sectionName = explode('\\',str_replace("App\Http\Controllers\\",'',$controllerClass))[0];
@endphp
<div class="col-md-12 collapse mb-3 @if(count(request()->except('page'))) show @endif" id="searchCollapse">
    <div class="bgc-white p-20 bd">
        <button type="button" class="close" aria-label="Close" data-toggle="collapse" data-target="#searchCollapse">
            <span aria-hidden="true">&times;</span>
        </button>
        <h6 class="c-grey-900">@lang('admin.search')</h6>
        <div class="mT-30">
            <form method="get">
                @include("$sectionName.views.admin.".$controllerClass::$viewForm.".search-form")
                <button type="submit" class="btn btn-primary">@lang('admin.search')</button>
            </form>
        </div>
    </div>
</div>
