<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\View\Components\MainLayout;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use App\Models\Produto;
use App\Observers\ProdutoObserver;
use App\Notifications\CustomVerifyEmail;

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
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!Cookie::has('carrinho')) {
            Cookie::queue('carrinho', json_encode([]), 60 * 24); // 24h de duração
        }
        Produto::observe(ProdutoObserver::class);
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
