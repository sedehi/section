<?php

namespace App\Http\Controllers\Role\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Role\Models\Role;
use App\Http\Controllers\Role\Requests\Admin\RoleRequest;

class RoleController extends Controller
{
    public static $viewForm = 'role';

    public function index()
    {
        $items = Role::latest('id')->paginate(10);
        $list  = Role::pluck('title', 'id');

        return view('Role.views.admin.role.index', compact('items', 'list'));
    }

    public function create()
    {
        return view('admin.create')->with('item', null);
    }

    public function store(RoleRequest $request)
    {
        $item             = new Role();
        $item->title       = $request->get('title');
        $item->permission = serialize($request->get('access'));
        $item->save();

        return redirect()->action('Role\Controllers\Admin\RoleController@index')
            ->with('success', trans('admin.saved'));
    }

    public function edit($id)
    {
        $item             = Role::findOrFail($id);
        $item->permission = unserialize($item->permission);

        return view('admin.edit', compact('item'));
    }

    public function update(RoleRequest $request, $id)
    {
        $item       = Role::findOrFail($id);
        $item->title = $request->get('title');
        $item->permission = serialize($request->get('access'));
        $item->save();

        return redirect()->action('Role\Controllers\Admin\RoleController@index')
            ->with('success', trans('admin.saved'));
    }

    public function destroy(RoleRequest $request)
    {
        Role::whereIn('id', $request->get('deleteId'))->get()->each->delete();

        return back()->with('success', trans('admin.deleted'));
    }
}
