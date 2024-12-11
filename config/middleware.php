<?php
return [
    'route' => [
        'admin' => App\Http\Middleware\IsAdmin::class,
        'CheckAddress' => \App\Http\Middleware\CheckAddressComplete::class,
        'user.approved' => \App\Http\Middleware\CheckUserApproval::class,
        'supervisor' => \App\Http\Middleware\EnsureUserIsSupervisor::class,
    ],
];
