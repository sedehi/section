@php
    $createAction = str_replace('create','store',Route::currentRouteAction());
    $controllerClass = str_replace('@store','',$createAction);
    $createAction = str_replace("App\Http\Controllers\\",'',$createAction);
    $ns = explode('\\',str_replace("Controller@store",'',$createAction));
    $sectionName = $ns[0];
    $controllerName = $ns[3];
@endphp
@extends('vendor.section.master')
@section('title',trans('admin.sections.'.strtolower($sectionName)).' | '.trans('admin.create').' '.trans('admin.'.strtolower($controllerName)))
@section('content')
    <div class="row gap-20 pos-r">
        <div class="col-md-12">
            <div class="bgc-white p-20 bd">
                <div class="mT-30">
                    <form action="{{ action($createAction,Route::current()->parameters()) }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include("$sectionName.views.admin.".$controllerClass::$viewForm.".form")
                        <button type="submit" class="btn btn-primary">@lang('admin.submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection