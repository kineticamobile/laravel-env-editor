<?php

namespace Kineticamobile\EnvEditor;

use Illuminate\Support\ServiceProvider;

class EnvEditorServiceProvider extends ServiceProvider
{
    
    public function boot()
    {
       
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'env-editor');
        if (config('env-editor.enable')) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/env-editor.php' => config_path('laravel-env-editor.php'),
            ], 'config');

            // Publishing the views.
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/env-editor'),
            ], 'views');

        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/env-editor.php', 'env-editor');

        // Register the main class to use with the facade
        $this->app->singleton('env-editor', function () {
            return new EnvEditor;
        });
    }
}
