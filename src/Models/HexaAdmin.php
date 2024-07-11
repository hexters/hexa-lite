<?php

namespace Hexters\HexaLite\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Hexters\HexaLite\Traits\UlidGenerator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class HexaAdmin extends Authenticatable implements HasAvatar, FilamentUser
{
    use HasFactory, Notifiable, UlidGenerator;

    protected $table = 'hexa_admins';

    protected $casts = [
        'online_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ulid',
        'name',
        'email',
        'email_verified_at',
        'online_at',
        'is_superadmin',
        'password',
        'avatar_url',
        'type',
        'state',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFilamentAvatarUrl(): ?string
    {
        $gravurl = "https://www.gravatar.com/avatar/" . hash("sha256", strtolower(trim($this->email))) .  "&s=200";
        return is_null($this->avatar_url) ? $gravurl : asset("storage/{$this->avatar_url}");
    }

    public function roles()
    {
        return $this->belongsToMany(HexaRole::class, 'hexa_role_admin', 'hexa_admin_id', 'hexa_role_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->state, ['active']);
    }

    public function permissions(): Attribute
    {
        return Attribute::make(
            get: function () {
                $gates = [];
                foreach ($this->roles as $role) {
                    if (!is_null($role->permissions)) {
                        array_push($gates, ...$role->permissions);
                    } else {
                        array_push($gates);
                    }
                }
                return $gates;
            }
        );
    }
}
