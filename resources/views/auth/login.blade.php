<!doctype html>
<html dir="ltr">
<head>
    <title>{{ __('admin.login') }}</title>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="{!! asset('assets/admin/css/app.css') !!}" rel="stylesheet">
</head>
<body>
<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100 pt-4 pb-5">
            <form action="{!! action('Auth\Controllers\Admin\LoginController@login') !!}" class="login100-form flex-sb flex-w" method="post">
                {{ csrf_field() }}
                <span class="login100-form-title pb-5">{{ __('admin.login') }}</span>
                <div class="wrap-input100 mb-3">
                    <input class="input100 ltr-field" type="text" name="email" placeholder="Email">
                </div>
                <div class="wrap-input100 mb-3">
                    <input class="input100 center-field " type="password" name="password" placeholder="Password">
                </div>
                <div class="container-login100-form-btn mt-3">
                    <button class="login100-form-btn" type="submit">{{ __('admin.signin') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="{!! asset('assets/admin/js/app.js') !!}"></script>
@include('vendor.section.partials.notifications')
</body>
</html>
