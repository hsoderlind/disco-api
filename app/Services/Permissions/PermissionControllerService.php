<?php

namespace App\Services\Permissions;

use App\Http\Resources\PermissionResource;
use App\Models\Role;
use App\Models\User;
use App\Services\AbstractService;

class PermissionControllerService extends AbstractService
{
    protected $resource = PermissionResource::class;

    public function list()
    {
        $this->data = PermissionsService::getAllPermissions();

        return $this;
    }

    public function listByRole(int|Role $role)
    {
        if (is_int($role)) {
            $role = Role::findById($role);
        }

        /** @var \App\Models\Role $role */
        $this->data = $role->permissions;

        return $this;
    }

    public function syncToRole(int|Role $role, array $permissionIds)
    {
        $role = PermissionsService::syncRolesAndPermissions($role, $permissionIds);
        $this->data = $role->permissions;

        return $this;
    }

    public function userPermissions(User $user)
    {
        $this->data = $user->getAllPermissions();

        return $this;
    }
}
