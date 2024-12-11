<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;

use App\View\Components\MainLayout;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/middleware.php',
            'middleware'
        );
    }
    protected $listen = [
        Registered::class => [
            SendCustomEmailVerification::class,

        ],
    ];
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::addNamespace('mail', resource_path('views/vendor/mail/html'));

        $router = $this->app->make(Router::class);

        $router->pushMiddlewareToGroup('web', \App\Http\Middleware\PreventDirectoryAccess::class);

        $middlewares = config('middleware.route', []);
        foreach ($middlewares as $alias => $middlewareClass) {
            $router->aliasMiddleware($alias, $middlewareClass);
        }

        Blade::component('components.main-layout', MainLayout::class);
    }
}
