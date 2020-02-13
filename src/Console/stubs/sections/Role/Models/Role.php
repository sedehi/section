<?php

namespace App\Http\Controllers\Role\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\User\Models\Admin;

class Role extends Model
{
    protected $table   = 'roles';

    public function users()
    {
        return $this->belongsToMany(Admin::class);
    }

    public static function allRoles()
    {
        $list = glob(app_path('Http/Controllers/*/roles.php'));
        $role = [];
        foreach ($list as $dir) {
            $role += include $dir;
        }
        return $role;
    }
}
