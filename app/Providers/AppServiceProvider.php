<?php

namespace App\Providers;

use App\Services\EncryptService;
use App\Services\UsuarioService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(EncryptService::class, function($app){
            return new EncryptService();
        });
        $this->app->singleton(UsuarioService::class, function ($app) {
            return new UsuarioService($app->make(EncryptService::class));
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
