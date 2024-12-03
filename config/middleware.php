<?php
return [
    'route' => [
        'admin' => App\Http\Middleware\IsAdmin::class,
        'CheckAddress' => \App\Http\Middleware\CheckAddressComplete::class,
    ],
];
