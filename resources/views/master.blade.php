<!doctype html>
<html lang="{{app()->getLocale()}}" dir="rtl">
<head>
    <meta name="robots" content="noindex">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @lang('admin.name')
        @if (trim($__env->yieldContent('title')))
            - @yield('title')
        @endif
    </title>
    <link href="{!! asset('assets/admin/css/app.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/admin/css/fileinput.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/admin/css/fileinput-rtl.min.css') !!}" rel="stylesheet">
    <script type="text/javascript" src="{!! asset('assets/admin/editor/ckeditor.js') !!}"></script>
    <style>
        .clickable-row:hover {
            cursor: pointer;
        }

        .table-responsive {
            overflow-y: visible;
            overflow-x: visible;
        }
    </style>
    @stack('css')

</head>

<body class="app">
<div id="loader">
    <div class="spinner"></div>
</div>
<script>
    window.addEventListener('load', function () {
        var loader = document.getElementById('loader');
        setTimeout(function () {
            loader.classList.add('fadeOut');
        }, 300);
    });
</script>
<div>
    @include('vendor.section.partials.sidebar')
    <div class="page-container">
        @include('vendor.section.partials.header')
        <main class="main-content bgc-grey-100">
            <div id="mainContent">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</div>
<script type="text/javascript" src="{!! asset('assets/admin/js/persian-date.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/admin/js/app.js') !!}"></script>
<script>
    $ = window.jquery;
</script>
@include('vendor.section.partials.notifications')
<script>
    $(document).ready(function ($) {
        $('.clickable-row td').on('click', function (e) {

            if ($(this).children().hasClass('form-check') || $(this).children().is('a') || $(this).children().hasClass('btn') || $(this).children().hasClass('dropdown')) {
                return true;
            }

            window.location = $(this).parent().data("href");
        });
    });
    $(document).on('change', '.delete-item , .check-all', function () {
        var btn = $('.delete-btn');
        btn.html('@lang('admin.delete')');
        $(this).closest('table').find('.delete-item:checked').each(function () {
            btn.append('<input type="hidden" name="deleteId[]" value="' + $(this).val() + '">');
        });

    });
    var deleteBtn = '<button type="button" class="file-input-remove btn btn-sm btn-kv btn-default btn-outline-secondary" title="Remove"{dataKey}>' +
        '<i class="fa fa-trash"></i>' +
        '</button>';
    $.fn.fileinputLocales['fa'].browseLabel = 'انتخاب فایل';
    var initialPreview = [];
    var initialPreviewConfig = [];
    $('.upload-files').each(function () {
        initialPreview.push($(this).data('url'));
        var configs = $.extend({}, $(this).data(), {showDrag: false});
        delete configs.url;
        configs.showRemove = false;
        initialPreviewConfig.push(configs);
        var abtn = '';
        if ($(this).data('remove')) {
            abtn = deleteBtn;
        }
        $(this).fileinput({
            otherActionButtons: abtn,
            showUpload: false,
            theme: "explorer-fa",
            initialPreviewAsData: true,
            previewFileType: 'any',
            showCaption: false,
            showRemove: false,
            browseClass: "btn btn-primary btn-block",
            language: ($('html').attr('lang') == 'fa') ? 'fa' : 'en',
            rtl: ($('html').attr('dir') == 'rtl') ? 'rtl' : 'ltr',
            initialPreview: initialPreview,
            initialPreviewConfig: initialPreviewConfig,
            overwriteInitial: true,
        });
    });
    $(document).on('click', '.file-input-remove', function () {
        $('.upload-input').after().append('<input type="hidden" value="1" name="delete-files[]" />');
        $('.upload-input').fileinput('clear');
    });
</script>
@stack('js')
</body>
</html>
