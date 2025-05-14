<?php

namespace Hexters\HexaLite;

use Filament\Contracts\Plugin;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Hexters\HexaLite\Resources\RoleResource;
use Hexters\HexaLite\Traits\GateTrait;

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
            NavigationItem::make(__('Role & Permissions'))
                ->visible(fn() => hexa()->can('role.index'))
                ->url(RoleResource::getUrl())
                ->isActiveWhen(fn() => request()->fullUrlIs(RoleResource::getUrl() . '*'))
                ->icon('heroicon-o-lock-closed')
                ->group(__('Settings')),
        ]);
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
