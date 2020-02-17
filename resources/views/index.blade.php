@php
    $action = Route::currentRouteAction();
    $action = str_replace('@index','',$action);
    $actionClass = str_replace(app()->getNamespace().'Http\Controllers\\','',$action);
    $action = explode('\\',$action);
    $sectionName = $action[3];
    $controllerName = $action[6];
@endphp
@extends('vendor.section.master')
@section('title',trans('admin.name'))
@section('content')
    <div class="row">
        @includeIf('vendor.section.search')
        <div class="col-md-12">
            <div class="card">
                @yield('table_header',View::make('vendor.section.index-table-header',compact([
                    'items',
                    'sectionName',
                    'actionClass',
                    'controllerName'
                ])))
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            @yield('table_head',View::make('vendor.section.index-table-head'))
                        </tr>
                        </thead>
                        <tbody>
                        @yield('table_body',View::make('vendor.section.index-table-body',compact([
                            'items',
                            'sectionName',
                            'actionClass',
                            'controllerName'
                        ])))
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {!! $items->appends(Request::except('page'))->render() !!}
                </div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{action($actionClass.'@destroy',1)}}" id="delete-form">
        @csrf
        @method('delete')
    </form>
@endsection
