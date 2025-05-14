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
        if (method_exists($user, 'roles')) {
            if (! empty($user->roles)) {
                $gates = [];
                foreach ($user->roles as $role) {
                    if (is_array($role->access)) {
                        foreach ($role->access as $access) {
                            $gates[] = $access;
                        }
                    }
                }
                $permissions = is_array($permissions) ? $permissions : [$permissions];
                return ! empty(array_intersect($gates, $permissions));
            }
            return true;
        }
        return false;
    }
}
