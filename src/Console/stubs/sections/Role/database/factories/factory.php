<?php

use App\Http\Controllers\Role\database\seeds\RoleTableSeeder;
use App\Http\Controllers\Role\Models\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name'       => $faker->realText(10),
        'permission' => serialize((new RoleTableSeeder())->rolePermissions()),
    ];
});
