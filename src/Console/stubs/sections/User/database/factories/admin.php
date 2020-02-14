<?php

use App\Http\Controllers\Role\Models\Role;
use App\Http\Controllers\User\Models\Admin;
use Faker\Generator as Faker;

$factory->define(Admin::class, function (Faker $faker) {
    return [
        'first_name'    => $faker->firstName,
        'last_name'     => $faker->lastName,
        'email'         => $faker->unique()->email,
        'mobile'        => $faker->mobileNumber,
        'password'      => bcrypt('123456'),
    ];
});
$factory->state(Admin::class, 'withRole', function ($faker) {
    return [];
});
$factory->afterCreatingState(Admin::class, 'withRole', function ($item, $faker) {
    $item->roles()->attach(factory(Role::class)->create());
});
