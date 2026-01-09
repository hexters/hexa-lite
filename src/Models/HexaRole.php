<?php

namespace Hexters\HexaLite\Models;

use Filament\Facades\Filament;
use Hexters\HexaLite\Helpers\UuidGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class HexaRole extends Model
{
    use UuidGenerator;

    protected $table = 'hexa_roles';

    protected $fillable = [
        'name',
        'created_by_name',
        'access',
        'team_id',
        'guard',
    ];

    protected $casts = [
        'access' => 'array',
        'gates' => 'array',
        'checkall' => 'array',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Filament::getTenantModel());
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            config('hexa.models.user'),
            'hexa_role_user',
            'role_id',
            'user_id'
        );
    }
}
