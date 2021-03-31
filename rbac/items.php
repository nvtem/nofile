<?php
return [
    'admin' => [
        'type' => 1,
        'children' => [
            'admin-fileset-view-list',
            'admin-fileset-delete',
            'admin-logout',
        ],
    ],
    'admin-fileset-view-list' => [
        'type' => 2,
    ],
    'admin-fileset-delete' => [
        'type' => 2,
    ],
    'admin-logout' => [
        'type' => 2,
    ],
];
