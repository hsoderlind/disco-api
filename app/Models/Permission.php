<?php

namespace App\Models;

use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    public static function findOrCreateWithGroup(string $name, string $description, string $group, $guardName = null): PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermission(['name' => $name, 'guard_name' => $guardName]);

        if (! $permission) {
            return static::query()->create(
                [
                    'name' => $name,
                    'description' => $description,
                    'group' => $group,
                    'guard_name' => $guardName,
                ]
            );
        }

        return $permission;
    }
}
