<?php

namespace Hexters\HexaLite;

use Illuminate\Support\ServiceProvider;


class HexaLiteServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerMigration();
        $this->registerView();
        $this->setupConfig();
    }

    protected function setupConfig()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/hexa.php',
            'hexa'
        );

        $this->publishes([
            __DIR__ . '/../config/hexa.php' => config_path('hexa.php'),
        ]);
    }

    protected function registerView()
    {
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'hexa'
        );
    }

    protected function registerMigration()
    {
        $this->loadMigrationsFrom(
            __DIR__ . '/../database/migrations',
        );
    }
}
