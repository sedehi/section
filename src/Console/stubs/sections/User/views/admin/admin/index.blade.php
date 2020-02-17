@extends('vendor.section.index')

@section('table_head')
    <th scope="col" class="w-50px">
        <div class="form-check">
            <input class="form-check-input position-static check-all" type="checkbox">
        </div>
    </th>
    <th scope="col">@lang('validation.attributes.name')</th>
    <th scope="col">@lang('admin.creation_date')</th>
    <th scope="col" class="w-120px"></th>
@endsection

@section('table_body')

    @forelse($items as $item)
        <tr>
            <td scope="row">
                <div class="form-check">
                    <input type="checkbox" name="deleteId[]" value="{{$item->id}}" class="form-check-input position-static delete-item">
                </div>
            </td>
            <td>{{ $item->full_name }}</td>
            <td>
                {{ Jalalian::fromCarbon($item->created_at)->format('H:i - Y/m/d') }}
            </td>
            <td>
                @if(Gate::allows('user.admincontroller.edit'))
                    <a href="{!! action('User\Controllers\Admin\AdminController@edit',$item->id) !!}"><i class="fa fa-pencil-square-o"></i></a>
                @endif
                @if(Gate::allows('user.admincontroller.show'))
                    <a href="{!! action('User\Controllers\Admin\AdminController@show',$item->id) !!}"><i class="fa fa-eye"></i></a>
                @endif
            </td>
        </tr>
    @empty
        @include('vendor.section.table-empty-row')
    @endforelse

@endsection
