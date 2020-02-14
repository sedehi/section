<div class="row">
    <div class="form-group col-md-5">
        <label for="">@lang('admin.title')</label>
        <input type="text" name="title" class="form-control" value="{{ old('title',isset($item) ? $item->title : null) }}" />
    </div>
    <div class="form-group col-md-5">
        <div class="form-check">
            <br>
            <label class="form-check-label">
                <input id="select_all" class="form-check-input" type="checkbox" style="margin-right: -1.25rem">
                @lang('admin.select_all')
            </label>
        </div>
    </div>
</div>

<hr>

@foreach(\App\Http\Controllers\Role\Models\Role::allRoles() as $section => $value)
    <div class="col-md-12">
        <div class="row justify-content-between">
            <h4 class="row justify-content-start">{{ $value['title'] }}</h4>
            <div class="row justify-content-end">
                <div class="form-check">
                    <input name="select_part" class="form-check-input checkPart" type="checkbox" id="checkPart{{$section}}" data-part="{{$section}}">
                    <label class="form-check-label" for="checkPart{{$section}}">@lang('admin.select_this_part')</label>
                </div>
            </div>
        </div>
        <br>
        <div class="row {{ $section }}">

            @foreach($value['access'] as $controller => $access)

                @foreach($access as $accessTitle => $methods)
                    @php
                        $check = false;
                        $htmlMethodName = is_array($methods) ? implode(',', $methods) : $methods;
                        if (is_array($methods)) {
                            if (old('accessMultiple'.'.'.strtolower($section).'.'.strtolower($controller).'.'.strtolower($htmlMethodName))) {
                                $check = true;
                            } else {
                                if (isset($item) && empty(old())) {
                                    $check = true;
                                    foreach ((array) $methods as $method) {
                                        if ( ! Illuminate\Support\Arr::has($item->permission, strtolower($section.'.'.$controller.'.'.$method))) {
                                            $check = false;
                                            break;
                                        }
                                    }
                                }
                            }
                        } else {
                            if (old('access'.'.'.strtolower($section).'.'.strtolower($controller).'.'.strtolower($methods))) {
                                $check = true;
                            } else {
                                if (isset($item) && empty(old()) && Illuminate\Support\Arr::has($item->permission, strtolower($section.'.'.$controller.'.'.$methods))) {
                                    $check = true;
                                }
                            }
                        }
                    @endphp
                    <div class="col-md-3">
                        @if(is_array($methods))
                            <input type="checkbox"
                                   name="{!! 'accessMultiple['.strtolower($section).']['.strtolower($controller).']['.strtolower($htmlMethodName).']' !!}"
                                   id="{!! 'check'.strtolower($controller).strtolower(implode(',',$methods)) !!}" class="{!! $section !!}"
                                   data-multiple-class="{!! $section.$controller.str_replace(',','',$htmlMethodName) !!}"
                                   value="1"
                                   @if ($check) checked="checked" @endif
                            >
                            <label for="{!! 'check'.strtolower($controller).strtolower(implode(',',$methods)) !!}" class="d-inline">{{ $accessTitle }}</label>
                            <div class="d-none">
                                @foreach($methods as $method)
                                    <input type="checkbox"
                                           name="{!! 'access['.strtolower($section).']['.strtolower($controller).']['.strtolower($method).']' !!}"
                                           class="{!! $section.' '.$section.$controller.str_replace(',','',$htmlMethodName) !!}"
                                           value="1"
                                           @if ($check) checked="checked" @endif
                                    >
                                @endforeach
                            </div>
                        @else
                            <input type="checkbox"
                                   name="{!! 'access['.strtolower($section).']['.strtolower($controller).']['.strtolower($methods).']' !!}"
                                   id="{!! 'check'.strtolower($controller).strtolower($methods) !!}"
                                   value="1"
                                   @if ($check) checked="checked" @endif
                            >
                            <label for="{!! 'check'.strtolower($controller).strtolower($methods) !!}" class="d-inline">{{ $accessTitle }}</label>
                        @endif
                    </div>
                @endforeach
            @endforeach
            @if(isset($value['onlyByUser']))
                <div class="col-md-3">
                    @php
                        $onlyCheck = false;
                        if (old('access'.'.'.strtolower($section).'.'.'onlybyuser')) {
                            $onlyCheck = true;
                        } else {
                            if (isset($item) && empty(old()) && Illuminate\Support\Arr::has($item->permission,strtolower($section).'.'.'onlybyuser')) {
                                $onlyCheck = true;
                            }
                        }
                    @endphp
                    <input type="checkbox"
                           name="{!! 'access['.strtolower($section).'][onlybyuser]' !!}"
                           id="{!! 'onlybyuser'.$section.strtolower($methods) !!}"
                           value="1"
                           @if ($onlyCheck) checked="checked" @endif
                    >
                    <label for="{!! 'onlybyuser'.$section.strtolower($methods) !!}" class="d-inline">@lang('admin.modification_only_by_owner')</label>
                </div>
            @endif
        </div>
    </div>
    <hr>

@endforeach


@push('js')
    <script>
        window.jquery(document).ready(function () {
            window.jquery("#select_all").on('change', function () {
                if (window.jquery(this).is(':checked')) {
                    window.jquery.each(window.jquery("input"), function (index, value) {
                        if (value.type == 'checkbox') {
                            window.jquery(this).prop("checked", true);
                        }
                    })

                } else {
                    window.jquery.each(window.jquery("input"), function (index, value) {
                        if (value.type == 'checkbox') {
                            window.jquery(this).prop("checked", false);
                        }
                    })
                }
            });

            window.jquery('.checkPart').on('change', function () {
                if (window.jquery(this).is(':checked')) {
                    var className = window.jquery(this).attr('data-part');
                    window.jquery.each(window.jquery('.' + className + " input"), function (index, value) {
                        if (value.type == 'checkbox') {
                            window.jquery(this).prop("checked", true);

                        }
                    });
                } else {
                    var className = window.jquery(this).attr('data-part');
                    window.jquery.each(window.jquery('.' + className + " input"), function (index, value) {
                        if (value.type == 'checkbox') {
                            window.jquery(this).prop("checked", false);

                        }
                    });
                }

            });

            $("input[type=checkbox]").on('change', function () {
                var multipleClass = $(this).data('multiple-class');
                if (multipleClass !== undefined) {
                    if ($(this).is(':checked')) {
                        $('.'+multipleClass).prop("checked",true);
                    } else {
                        $('.'+multipleClass).prop("checked",false);
                    }
                }
            });
        })
        ;
    </script>
@endpush
