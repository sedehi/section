@if (Gate::any([
        'role.rolecontroller.index'
    ]))
    <li class="nav-item dropdown">
        <a class="dropdown-toggle" href="javascript:void(0);">
            <span class="icon-holder"><i class="c-orange-500 ti-layout-list-thumb"></i></span>
            <span class="title">@lang('admin.admins_groups')</span>
            <span class="arrow"><i class="ti-angle-right"></i></span>
        </a>
        <ul class="dropdown-menu">
            @if (Gate::allows('role.rolecontroller.index'))
                <li><a class="sidebar-link" href="{{ action('Role\Controllers\Admin\RoleController@index') }}">@lang('admin.list')</a></li>
            @endif
        </ul>
    </li>
@endif
