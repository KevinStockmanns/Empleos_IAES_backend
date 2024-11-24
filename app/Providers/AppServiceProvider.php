<?php

namespace App\Providers;

use App\Services\FileService;
use App\Services\ContactoService;
use App\Services\EmpresaService;
use App\Services\EncryptService;
use App\Services\HabilidadService;
use App\Services\HorarioService;
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
            return new UsuarioService(
                $app->make(UbicacionService::class),
                $app->make(ContactoService::class),
                $app->make(HabilidadService::class),
                $app->make(FileService::class),
            );
        });
        $this->app->singleton(EmpresaService::class, function($app){
            return new EmpresaService(
                $app->make(UbicacionService::class),
                $app->make(HorarioService::class),
            );
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
