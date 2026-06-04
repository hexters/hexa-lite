<?php

namespace Hexters\HexaLite\Helpers;

use Exception;
use Filament\Panel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class HexaHelper
{

    private ?Authenticatable $user;

    public function guard(): string
    {
        return explode('_', Auth::guard()->getName())[1] ?? 'web';
    }

    public function can(array|string $gates): bool
    {
        return empty($this->user) ? Gate::allows($gates) : $this->userCan($this->user, $gates);
    }


    public function panelGates(Panel $panel): array
    {
        try {
            return $panel->getPlugin('filament-hexa')->gates($panel);
        } catch (Exception $e) {
            return [];
        }
    }

    public function user(Authenticatable $user)
    {
        $this->user = $user;
        return $this;
    }
    

    private function userCan(Authenticatable $user, array|string $permissions)
    {
        if (! method_exists($user, 'roles')) {
            return false;
        }

        // No role assigned means the user is treated as a Superuser.
        if ($user->roles->isEmpty()) {
            return true;
        }

        // Role access is stored grouped by section, e.g. ['role' => ['role.index', ...]],
        // so flatten every level down to the individual gate keys.
        $gates = collect($user->roles)
            ->pluck('access')
            ->flatten()
            ->all();

        $permissions = is_array($permissions) ? $permissions : [$permissions];

        return ! empty(array_intersect($gates, $permissions));
    }
}
