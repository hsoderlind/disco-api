<?php

namespace App\Models;

use Spatie\Permission\Guard;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\PermissionRegistrar;

class Role extends SpatieRole
{
    protected $casts = [
        'editable' => 'boolean',
        'deletable' => 'boolean',
    ];

    /**
     * Get all roles that belongs to the current team
     *
     * @param  string|null  $guardName
     * @return Spatie\Permission\Contracts\Role[]
     */
    public static function findAll($guardName = null)
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $query = static::query();

        $query->where(function ($q) {
            $q->whereNull(PermissionRegistrar::$teamsKey)
                ->orWhere(PermissionRegistrar::$teamsKey, getPermissionsTeamId());
        });

        $query->where('guard_name', $guardName);

        $roles = $query->get();

        return $roles;
    }
}
