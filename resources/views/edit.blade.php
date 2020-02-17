@php
    $editAction = str_replace('edit','update',Route::currentRouteAction());
    $controllerClass = str_replace('@update','',$editAction);
    $editAction = str_replace("App\Http\Controllers\\",'',$editAction);
    $ns = explode('\\',str_replace("Controller@update",'',$editAction));
    $sectionName = $ns[0];
    $controllerName = $ns[3];
@endphp
@extends('admin.master')
@section('title',trans('admin.sections.'.strtolower($sectionName)).' | '.trans('admin.edit').' '.trans('admin.'.strtolower($controllerName)))
@section('content')
    <div class="row gap-20 pos-r">
        <div class="col-md-12">
            <div class="bgc-white p-20 bd">
                <div class="mT-30">
                    <form action="{{ action($editAction,Route::current()->parameters()) }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('put') }}
                        @include("$sectionName.views.admin.".$controllerClass::$viewForm.".form")
                        <button type="submit" class="btn btn-primary">@lang('admin.submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection