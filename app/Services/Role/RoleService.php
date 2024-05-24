<?php

namespace App\Services\Role;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Models\Shop;
use App\Services\AbstractService;
use App\Services\Permissions\PermissionsService;

class RoleService extends AbstractService
{
    protected $resource = RoleResource::class;

    protected Shop $shop;

    protected function boot()
    {
        $this->shop = Shop::find($this->shopId);
    }

    public function list()
    {
        $this->data = PermissionsService::getRolesByShop($this->shop);

        return $this;
    }

    public function create(array $data)
    {
        $this->data = PermissionsService::createRole($data['name']);

        return $this;
    }

    public function read(int $id)
    {
        $this->data = Role::findById($id);

        return $this;
    }

    public function update(int $id, array $data)
    {
        $this->data = PermissionsService::updateRoleName($id, $data['name']);

        return $this;
    }

    public function delete(int $id)
    {
        return PermissionsService::delete($id);
    }
}
