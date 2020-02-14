<?php

Route::group([
    'namespace' => 'User\Controllers\Admin',
], function () {
    Route::get('profile/password', 'ChangePasswordController@index')->name('admin.password.index');
    Route::post('profile/password', 'ChangePasswordController@change')->name('admin.password.change');
    Route::resource('admins', 'AdminController');
});
