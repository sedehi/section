<div class="row">
    <div class="form-group col-md-6">
        <lable for="first_name">نام</lable>
        <input type="text" name="first_name" value="{{ old('first_name',$item->first_name ?? null) }}" class="form-control">
    </div>
    <div class="form-group col-md-6">
        <lable for="last_name">نام خانوادگی</lable>
        <input type="text" name="last_name" value="{{ old('last_name',$item->last_name ?? null) }}" class="form-control">
    </div>
    <div class="form-group col-md-6">
        <lable for="password">رمز عبور</lable>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="form-group col-md-6">
        <lable for="email">ایمیل</lable>
        <input type="text" name="email" value="{{ old('email',$item->email ?? null) }}" class="form-control">
    </div>
    <div class="col-md-12 row">
        <h6 class="mb-3">گروه های کاربری</h6>
        <div class="col-md-12 row mb-5">
            @foreach($roles as $role)
                <div class="form-check col-md-3 mb-2 mt-2">
                    <input type="checkbox" name="role[{{ $role->id }}]" value="{{ $role->id }}" id="{{ 'role_'.$role->id }}" class="form-check-input" @if (old('role.'.$role->id,isset($relatedRoles) ? in_array($role->id,$relatedRoles) : false)) checked @endif>
                    <label class="form-check-label" for="role_{{$role->id}}">
                        {{$role->name}}
                    </label>
                </div>
            @endforeach
        </div>
    </div>
    <br>
    <div class="clearfix"></div>
    <br>
</div>
