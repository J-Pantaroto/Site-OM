<?php
return [
    'route' => [
        'admin' => App\Http\Middleware\IsAdmin::class,
    ],
];
