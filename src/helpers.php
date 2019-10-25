<?php

use Illuminate\Support\Arr;

if (!function_exists('permission')) {
    function permission($permission)
    {
        if (auth('admin')->check()) {
            return auth('admin')->user()->hasPermission($permission);
        }

        return false;
    }
}
if (!function_exists('adminRole')) {
    function adminRole()
    {
        $list = glob(app_path('Http/Controllers/*/roles.php'));
        $role = [];
        foreach ($list as $dir) {
            $role += include($dir);
        }

        return $role;
    }
}
if (!function_exists('adminMenu')) {
    function adminMenu()
    {
        $list = glob(app_path('Http/Controllers/*/menu.php'));
        $menu = [];
        foreach ($list as $dir) {
            $menu += include($dir);
        }
        $unSort = [];
        foreach ($menu as $key => $row) {
            $unSort[$key] = $row['title'];
        }
        if (config('site.menu_sorting') == 'alphabet') {
            array_multisort($unSort, SORT_ASC, $menu);
        } else {
            return Arr::sort($menu, function ($value) {
                return (isset($value['order'])) ? $value['order'] : 1000;
            });
        }

        return $menu;
    }
}