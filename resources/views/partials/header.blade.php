<div class="header navbar">
    <div class="header-container">
        <ul class="nav-left ">
            <li>
                <a id="sidebar-toggle" class="sidebar-toggle" href="javascript:void(0);">
                    <i class="ti-menu"></i>
                </a>
            </li>
        </ul>
        <ul class="nav-right">
            <li class="dropdown">
                <a href="" class="dropdown-toggle no-after peers fxw-nw ai-c lh-1" data-toggle="dropdown">
                    <div class="peer mR-10">
                        <img class="w-2r bdrs-50p" src="{!! asset('assets/admin/images/avatar.png') !!}">
                    </div>
                    <div class="peer">
                        <span class="fsz-sm c-grey-900">{{auth()->user()->full_name}}</span>
                    </div>
                </a>
                <ul class="dropdown-menu fsz-sm">
                    <li>
                        <a href="{!! route('admin.password.index') !!}" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                            <span>{{ __('admin.change_password') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{!! route('logout') !!}" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                            <span>{{ __('admin.logout') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
