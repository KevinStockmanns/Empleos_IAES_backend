<?php

namespace App\Providers;

use App\Services\EncryptService;
use App\Services\UbicacionService;
use App\Services\UsuarioService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->singleton(UsuarioService::class, function ($app) {
        //     return new UsuarioService($app->make(EncryptService::class));
        // });
        $this->app->singleton(UsuarioService::class, function ($app) {
            return new UsuarioService($app->make(UbicacionService::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
