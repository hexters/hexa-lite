<?php

namespace Hexters\HexaLite;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Hexters\HexaLite\Middleware\OnlineMiddleware;
use Hexters\HexaLite\Resources\AdminResource;
use Hexters\HexaLite\Resources\RoleResource;

class HexaLite implements Plugin
{
    public function getId(): string
    {
        return 'filament-hexa';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->authGuard('admin')
            ->registration(false)
            ->resources([
                AdminResource::class,
                RoleResource::class,
            ])
            ->authMiddleware([
                OnlineMiddleware::class,
            ])
            ->passwordReset()
            ->authPasswordBroker('admins')
            ->spa();
    }

    public function boot(Panel $panel): void
    {
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
