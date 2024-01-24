<?php

use app\models\User;

return [
    'guards' => [
        'web' => [
            'class' => User::class,
            'driver' => 'session',
            'primaryKey' => 'id'
        ]
    ],
    'default_guard' => 'web'
];
