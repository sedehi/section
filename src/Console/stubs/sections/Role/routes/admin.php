<?php

Route::group(['namespace' => 'Role\Controllers\Admin'], function () {
    Route::resource('role', 'RoleController', ['except' => ['show']]);
});
