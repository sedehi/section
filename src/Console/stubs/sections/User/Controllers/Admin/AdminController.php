<?php

namespace App\Http\Controllers\User\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Role\Models\Role;
use App\Http\Controllers\User\Models\Admin;
use App\Http\Controllers\User\Requests\Admin\AdminRequest;

class AdminController extends Controller
{
    public function index()
    {
        $items = Admin::with('roles')->latest('id')->paginate(10);
        $roles = $this->roles()->pluck('name', 'id')->prepend('...', '');

        return view('User.views.admin.admin.index', compact('items', 'roles'));
    }

    public function create()
    {
        $roles = $this->roles();

        return view('User.views.admin.admin.add', compact('roles'));
    }

    public function store(AdminRequest $request)
    {
        $item = Admin::create([
            'first_name' => $request->get('first_name'),
            'last_name'  => $request->get('last_name'),
            'email'      => $request->get('email'),
            'mobile'     => $request->get('mobile'),
            'password'   => bcrypt($request->get('password')),
        ]);
        $item->roles()->attach($request->get('role'));

        return redirect()
            ->action('User\Controllers\Admin\AdminController@index')
            ->with('success', trans('admin.saved'));
    }

    public function edit($id)
    {
        $item = Admin::withTrashed()->findOrFail($id);
        $roles = $this->roles();
        $relatedRoles = $item->roles()->allRelatedIds()->toArray();

        return view('User.views.admin.admin.edit', compact('item', 'roles', 'relatedRoles'));
    }

    public function update(AdminRequest $request, $id)
    {
        $item = Admin::withTrashed()->findOrFail($id);
        $item->fill([
            'first_name' => $request->get('first_name'),
            'last_name'  => $request->get('last_name'),
            'email'      => $request->get('email'),
            'mobile'     => $request->get('mobile'),
        ]);
        if ($request->filled('password')) {
            $item->password = bcrypt($request->get('password'));
        }
        $item->save();
        $item->roles()->sync($request->get('role'));

        return redirect()
            ->action('User\Controllers\Admin\AdminController@index')
            ->with('success', trans('admin.saved'));
    }

    public function destroy(AdminRequest $request)
    {
        Admin::whereIn('id', $request->get('deleteId'))->get()->each->delete();

        return redirect()->action('User\Controllers\Admin\AdminController@index')
            ->with('success', trans('admin.deleted'));
    }

    protected function roles()
    {
        return Role::all();
    }
}
