<div class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-logo">
            <div class="peers ai-c fxw-nw">
                <div class="peer peer-greed">
                    <a class="sidebar-link td-n" href="{{route('admin.homepage')}}">
                        <div class="peers ai-c fxw-nw">
                            <div class="peer">
                                <div class="logo"></div>
                            </div>
                            <div class="peer peer-greed">
                                <h5 class="lh-1 mB-0 logo-text">@lang('admin.name')</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="peer">
                    <div class="mobile-toggle sidebar-toggle">
                        <a href="" class="td-n">
                            <i class="ti-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <ul class="sidebar-menu scrollable pos-r">
            <li class="nav-item mT-30 active">
                <a class="sidebar-link" href="{{route('admin.homepage')}}">
                <span class="icon-holder">
                  <i class="c-blue-500 ti-home"></i>
                </span>
                    <span class="title">@lang('admin.first_page')</span>
                </a>
            </li>
            @foreach(adminMenu() as $keyMenu=>$valueMenu)
                @if(permission($keyMenu))
                    @php
                        $parameters = [];
                    @endphp
                    @if(isset($valueMenu['parameters']))
                        @php $parameters = $valueMenu['parameters'] @endphp
                    @endif

                    <li @if(isset($valueMenu['action'])) class="nav-item" @else class="nav-item dropdown" @endif  title="{{$valueMenu['title']}}">
                        <a @if(!isset($valueMenu['action'])) class="dropdown-toggle" @endif href="@if(isset($valueMenu['action'])){!! action($valueMenu['action'],$parameters) !!}@else javascript:void(0); @endif">
                            <span class="icon-holder"><i class="c-blue-500 {{$valueMenu['icon']}}"></i></span>
                            <span class="title">{{$valueMenu['title']}}</span>
                            @if(isset($valueMenu['submenu']))
                                <span class="arrow">
                                    <i class="ti-angle-right"></i>
                                </span>
                            @endif
                        </a>
                        @if(isset($valueMenu['submenu']))
                            <ul class="dropdown-menu">
                                @foreach($valueMenu['submenu'] as $keyLevelTwo=>$valueLevelTwo)
                                    @if(isset($valueLevelTwo['action']))
                                        @php
                                            $subMenuAction = explode('\\', strtolower($valueLevelTwo['action']));
                                            $submenuController = explode('@', end($subMenuAction));
                                        @endphp
                                        @if(permission($keyMenu.'.'.$submenuController[0].'.'.$submenuController[1]))
                                            <li>
                                                @php
                                                    $parameters = [];
                                                @endphp
                                                @if(isset($valueLevelTwo['parameters']))
                                                    @php $parameters = $valueLevelTwo['parameters'] @endphp
                                                @endif
                                                @php
                                                    $actionUrl = action($valueLevelTwo['action'],$parameters);
                                                @endphp
                                                <a class="sidebar-link @if(request()->fullUrl() == $actionUrl) font-weight-bold sidebar-active-link @endif" href="{{ $actionUrl }}">
                                                    {{ $valueLevelTwo['title'] }}
                                                </a>
                                            </li>
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
@push('js')
    <script>
        $(document).ready(function () {
            var activeLink = $("a.sidebar-active-link")[0];
            if (activeLink){
                $("a.sidebar-active-link").parents('li.dropdown').addClass('active open');
            }
        });
    </script>
@endpush
