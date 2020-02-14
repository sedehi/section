<?php

namespace App\Http\Controllers\Role\database\seeds;

use Illuminate\Database\Seeder;
use App\Http\Controllers\Role\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $data             = new Role();
        $data->title      = trans('admin.super_admin');
        $data->permission = serialize($this->rolePermissions());
        $data->save();
    }

    public function rolePermissions()
    {
        $data = ['filemanager' => 1];
        foreach (Role::allRoles() as $section => $role) {
            foreach ($role['access'] as $controller => $methods) {
                foreach ($methods as $method) {
                    if (is_array($method)) {
                        foreach ($method as $item) {
                            $finalMethods[strtolower($item)] = 1;
                        }
                    } else {
                        $finalMethod = strtolower($method);
                    }
                    $finalMethods[$finalMethod] = 1;
                }

                $data[strtolower($section)][strtolower($controller)] = $finalMethods;
                $finalMethods                                        = [];
            }
        }

        return $data;
    }
}
