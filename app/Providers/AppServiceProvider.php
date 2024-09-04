<?php

namespace App\Providers;
use App\View\Components\MainLayout;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $router = $this->app->make(Router::class);
        $router->pushMiddlewareToGroup('web', \App\Http\Middleware\PreventDirectoryAccess::class);
        Blade::component('components.main-layout', MainLayout::class);
    }
}
