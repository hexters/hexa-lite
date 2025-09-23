<?php

namespace Hexters\HexaLite\Models;

use App\Models\Team;
use Filament\Facades\Filament;
use Hexters\HexaLite\Helpers\UuidGenerator;
use Illuminate\Database\Eloquent\Model;

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

    public function team()
    {
        return $this->belongsTo(Filament::getTenantModel());
    }
}
