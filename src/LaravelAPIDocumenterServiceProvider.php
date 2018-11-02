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
            __DIR__.'/../config/laravelapidocumenter.php' => config_path('laravelapidocumenter.php'),
        ], 'laravelapidocumenter.config');

        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'dogedev');
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
        $this->mergeConfigFrom(__DIR__.'/../config/laravelapidocumenter.php', 'laravelapidocumenter');

        // Register the service the package provides.
        $this->app->singleton('laravelapidocumenter', function ($app) {
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
        return ['laravelapidocumenter'];
    }
}
