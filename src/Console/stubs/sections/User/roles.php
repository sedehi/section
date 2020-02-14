<?php

return [
    'user' => [
        'title'  => trans('admin.admins_list'),
        'access' => [
            'AdminController'           => [
                trans('admin.admins_list')                       => 'index',
                trans('admin.create').' '.trans('admin.manager') => ['create', 'store'],
                trans('admin.edit').' '.trans('admin.manager')   => ['edit', 'update'],
                trans('admin.delete').' '.trans('admin.manager') => 'destroy',
            ],
        ],
    ],
];
