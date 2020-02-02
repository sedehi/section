<div class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-logo">
            <div class="peers ai-c fxw-nw">
                <div class="peer peer-greed">
                    <a class="sidebar-link td-n" href="#">
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
                <a class="sidebar-link" href="#">
                <span class="icon-holder">
                  <i class="c-blue-500 ti-home"></i>
                </span>
                    <span class="title">@lang('admin.first_page')</span>
                </a>
            </li>
            @foreach (glob(app_path('Http/Controllers/*/views/menu*.blade.php')) as $menu)
                @include(\Illuminate\Support\Str::after(str_replace('.blade.php','',$menu),'Controllers/'))
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
