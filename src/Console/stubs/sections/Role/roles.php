<?php

return [
    'role' => [
        'title'  => trans('admin.admins_groups'),
        'access' => [
            'RoleController' => [
                trans('admin.list')   => 'index',
                trans('admin.create') => ['create', 'store'],
                trans('admin.edit')   => ['edit', 'update'],
                trans('admin.delete') => 'destroy',
            ],
        ],
    ],
];
