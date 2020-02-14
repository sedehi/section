<?php

use Faker\Generator as Faker;
use App\Http\Controllers\Role\Models\Role;
use App\Http\Controllers\Role\database\seeds\RoleTableSeeder;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name'       => $faker->realText(10),
        'permission' => serialize((new RoleTableSeeder())->rolePermissions())
    ];
});
