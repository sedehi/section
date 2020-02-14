<div class="form-row">
    <div class="form-group col-md-4">
        <label for="title">نام یا نام خانوادگی</label>
        <input type="text" name="firstname_lastname" class="form-control" value="{{ request('firstname_lastname') }}">
    </div>
    <div class="form-group col-md-4">
        <label for="email">ایمیل</label>
        <input type="text" name="email" class="form-control" value="{{ request('email') }}">
    </div>
</div>
