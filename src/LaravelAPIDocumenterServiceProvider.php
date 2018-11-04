<?php

namespace DogeDev\LaravelAPIDocumenter;

use DogeDev\LaravelAPIDocumenter\Commands\GenerateDocumentation;
use Illuminate\Support\ServiceProvider;

class LaravelAPIDocumenterServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/laravel-api-documenter.php' => config_path('laravel-api-documenter.php'),
            __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-api-documenter'),
        ], 'laravel-api-documenter.config');

        $this->loadTranslationsFrom(__DIR__ . '../resources/lang', 'validation');

        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'dogedev');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateDocumentation::class,
            ]);
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-api-documenter.php', 'laravel-api-documenter');

        // Register the service the package provides.
        $this->app->singleton('laravel-api-documenter', function ($app) {
            return new LaravelAPIDocumenter;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravel-api-documenter'];
    }
}
