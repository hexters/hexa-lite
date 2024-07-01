<?php

namespace Hexters\HexaLite;

use Filament\Facades\Filament;
use Hexters\HexaLite\Commands\AdminCommand;
use Hexters\HexaLite\Commands\InstallCommand;
use Hexters\HexaLite\Models\HexaAdmin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class HexaLiteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (!$this->hasPackage('hexters/hexa')) {
            $this->registerMigration();

            $this->registerView();

            $this->registerGates();

            $this->registerPublishes();

            $this->registerCommands();

            $this->mergeConfig();
        }
    }

    protected function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../configs/hexa-core.php',
            'hexa-core'
        );
    }

    protected function getClusterComponents($panel)
    {
        $components = collect();
        foreach ($panel->getClusteredComponents() as $cluster => $items) {
            $components->push($cluster);
            foreach ($items as $item) {
                $components->push($item);
            }
        }

        return $components;
    }

    protected function registerGates()
    {
        if ($this->hasPackage('filament/filament')) {
            $panel = Filament::getCurrentPanel();
            if ($panel && in_array('filament-hexa-lite', array_keys($panel->getPlugins()))) {

                collect([
                    ...array_values($panel->getPages()),
                    ...array_values($panel->getResources()),
                ])
                    ->filter(fn ($item) => method_exists(app($item), 'getPermissionId'))
                    ->map(fn ($item)  => collect(app($item)->getKeySubPermissions())
                        ->push(app($item)->getPermissionId())
                        ->toArray())
                    ->each(function ($accesss) {
                        foreach ($accesss as $access) {
                            Gate::define($access, function ($admin) use ($access) {
                                if ($admin instanceof HexaAdmin) {
                                    $gates = collect();
                                    foreach ($admin->roles as $role) {
                                        if (!is_null($role->permissions)) {
                                            $gates->push(...$role->permissions);
                                        }
                                    }
                                    return in_array($access, $gates->toArray());
                                }
                                return false;
                            });
                        }
                    });
            }
        }
    }

    protected function hasPackage($package)
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        return array_key_exists($package, $composer['require'] ?? [])
            || array_key_exists($package, $composer['require-dev'] ?? []);
    }

    protected function registerMigration()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }

    protected function registerView()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources', 'filament-hexa');
    }

    protected function registerPublishes()
    {
        $this->publishes([
            __DIR__ . '/../stubs/configs/auth.php' => config_path('auth.php'),
            __DIR__ . '/../stubs/models' => app_path('Models'),
        ], 'filament-hexa');
    }

    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                AdminCommand::class,
            ]);
        }
    }
}
