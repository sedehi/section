@extends('admin.master')
@section('content')
    <div class="row gap-20 pos-r">
        <div class="col-md-12">
            <div class="bgc-white p-20 bd">
                <div class="mT-30">
                    <form action="{{ action('User\Controllers\Admin\ChangePasswordController@change') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-5">
                                <lable for="old_password">رمز عبور فعلی</lable>
                                <input type="password" name="old_password" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-5">
                                <lable for="password">رمز جدید</lable>
                                <input type="password" name="password" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-5">
                                <lable for="password_confirmation">تکرار رمز جدید</lable>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">تغییر رمز عبور</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
