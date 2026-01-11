<?php

namespace Hexters\HexaLite;

use Filament\Panel;
use Filament\Contracts\Plugin;
use Filament\Navigation\NavigationItem;
use Filament\Support\Icons\Heroicon;
use Hexters\HexaLite\Traits\GateTrait;
use Hexters\HexaLite\Resources\Roles\RoleResource;

class HexaLite implements Plugin
{

    use GateTrait;

    public function getId(): string
    {
        return 'filament-hexa-lite';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            RoleResource::class
        ]);
    }

    public function boot(Panel $panel): void
    {
        $this->registerGates($panel);
        $this->registerGateList($panel);

        $panel->navigationItems([
            NavigationItem::make($this->getNavigationLabel())
                ->visible(fn() => hexa()->can('role.index'))
                ->url(RoleResource::getUrl())
                ->isActiveWhen(fn() => request()->fullUrlIs(RoleResource::getUrl() . '*'))
                ->icon(Heroicon::OutlinedLockClosed)
                ->group($this->getNavigationGroup()),
        ]);
    }

    /**
     * Get the navigation label for the Role & Permissions menu item.
     */
    protected function getNavigationLabel(): string
    {
        $label = config('hexa.navigation.label', 'Role & Permissions');

        if ($label instanceof \Closure) {
            return $label();
        }

        return __($label);
    }

    /**
     * Get the navigation group for the Role & Permissions menu item.
     */
    protected function getNavigationGroup(): string
    {
        $group = config('hexa.navigation.group', 'Settings');

        if ($group instanceof \Closure) {
            return $group();
        }

        return __($group);
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
