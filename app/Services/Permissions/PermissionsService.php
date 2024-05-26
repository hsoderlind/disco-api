<?php

namespace App\Services\Permissions;

use App\Exceptions\RoleNotDeletableException;
use App\Exceptions\RoleNotEditableException;
use App\Models\Role;
use App\Models\Shop;
use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

abstract class PermissionsService
{
    const ROLE_SUPER_ADMIN = 'Kontoägare';

    const ROLE_ADMIN = 'Administratör';

    const ROLE_CASHIER = 'Kassör';

    const ROLE_WAREHOUSE_WORKER = 'Lagerarbetare';

    /**
     * Create default roles
     *
     * @return Role[]
     */
    public static function createDefaultRoles(int $shopId): array
    {
        $key = PermissionRegistrar::$teamsKey;
        $superAdmin = Role::create(
            [
                'name' => static::ROLE_SUPER_ADMIN,
                'editable' => false,
                'deletable' => false,
                $key => $shopId,
            ]
        );
        $admin = Role::create(
            [
                'name' => static::ROLE_ADMIN,
                'editable' => false,
                'deletable' => false,
                $key => $shopId,
            ]
        );
        $cashier = Role::create(
            [
                'name' => static::ROLE_CASHIER,
                'editable' => false,
                'deletable' => false,
                $key => $shopId,
            ]
        );
        $warehouseWorker = Role::create(
            [
                'name' => static::ROLE_WAREHOUSE_WORKER,
                'editable' => false,
                'deletable' => false,
                $key => $shopId,
            ]
        );

        return [
            static::ROLE_SUPER_ADMIN => $superAdmin,
            static::ROLE_ADMIN => $admin,
            static::ROLE_CASHIER => $cashier,
            static::ROLE_WAREHOUSE_WORKER => $warehouseWorker,
        ];
    }

    /**
     * Create default permissions
     *
     * @return Permission[]
     */
    public static function findOrCreatePermissions(string $guardName = 'sanctum'): array
    {
        $permissions = [];

        // Catalog
        $permissions['access catalog'] = Permission::findOrCreate('access catalog', $guardName);

        // Categories
        $permissions['access category'] = Permission::findOrCreate('access categories', $guardName);
        $permissions['create category'] = Permission::findOrCreate('create category', $guardName);
        $permissions['read category'] = Permission::findOrCreate('read category', $guardName);
        $permissions['update category'] = Permission::findOrCreate('update category', $guardName);
        $permissions['delete category'] = Permission::findOrCreate('delete category', $guardName);

        // Products
        $permissions['access product'] = Permission::findOrCreate('access product', $guardName);
        $permissions['create product'] = Permission::findOrCreate('create product', $guardName);
        $permissions['read product'] = Permission::findOrCreate('read product', $guardName);
        $permissions['update product'] = Permission::findOrCreate('update product', $guardName);
        $permissions['delete product'] = Permission::findOrCreate('delete product', $guardName);

        // Product type
        $permissions['access product type'] = Permission::findOrCreate('access product type', $guardName);
        $permissions['create product type'] = Permission::findOrCreate('create product type', $guardName);
        $permissions['read product type'] = Permission::findOrCreate('read product type', $guardName);
        $permissions['update product type'] = Permission::findOrCreate('update product type', $guardName);
        $permissions['delete product type'] = Permission::findOrCreate('delete product type', $guardName);

        // Stock
        $permissions['access stock'] = Permission::findOrCreate('access stock', $guardName);
        $permissions['update stock'] = Permission::findOrCreate('update stock', $guardName);
        $permissions['read stock'] = Permission::findOrCreate('read stock', $guardName);

        // Sales
        $permissions['access sales'] = Permission::findOrCreate('access sales', $guardName);

        // Customers
        $permissions['access customer'] = Permission::findOrCreate('access customer', $guardName);
        $permissions['create customer'] = Permission::findOrCreate('create customer', $guardName);
        $permissions['read customer'] = Permission::findOrCreate('read customer', $guardName);
        $permissions['update customer'] = Permission::findOrCreate('update customer', $guardName);
        $permissions['delete customer'] = Permission::findOrCreate('delete customer', $guardName);

        // Orders
        $permissions['access order'] = Permission::findOrCreate('access order', $guardName);
        $permissions['create order'] = Permission::findOrCreate('create order', $guardName);
        $permissions['read order'] = Permission::findOrCreate('read order', $guardName);
        $permissions['update order'] = Permission::findOrCreate('update order', $guardName);
        $permissions['delete order'] = Permission::findOrCreate('delete order', $guardName);

        // Shop profile
        $permissions['access shop profile'] = Permission::findOrCreate('access shop profile', $guardName);
        $permissions['read shop profile'] = Permission::findOrCreate('read shop profile', $guardName);
        $permissions['update shop profile'] = Permission::findOrCreate('update shop profile', $guardName);

        // Company profile
        $permissions['access company profile'] = Permission::findOrCreate('access company profile', $guardName);
        $permissions['read company profile'] = Permission::findOrCreate('read company profile', $guardName);
        $permissions['update company profile'] = Permission::findOrCreate('update company profile', $guardName);

        // Settings
        $permissions['access shop settings'] = Permission::findOrCreate('access shop settings', $guardName);

        // Taxes
        $permissions['access tax'] = Permission::findOrCreate('access tax', $guardName);
        $permissions['create tax'] = Permission::findOrCreate('create tax', $guardName);
        $permissions['read tax'] = Permission::findOrCreate('read tax', $guardName);
        $permissions['update tax'] = Permission::findOrCreate('update tax', $guardName);
        $permissions['delete tax'] = Permission::findOrCreate('delete tax', $guardName);

        // Sales Channels
        $permissions['access sales channels'] = Permission::findOrCreate('access sales channels', $guardName);
        $permissions['update sales channels'] = Permission::findOrCreate('update sales channels', $guardName);

        // Tradera settings
        $permissions['access tradera settings'] = Permission::findOrCreate('access tradera settings', $guardName);

        // Discogs settings
        $permissions['access discogs settings'] = Permission::findOrCreate('access discogs settings', $guardName);

        // PoS settings
        $permissions['access pos settings'] = Permission::findOrCreate('access pos settings', $guardName);

        // Shop staff
        $permissions['access shop staff'] = Permission::findOrCreate('access shop staff', $guardName);
        $permissions['create shop staff'] = Permission::findOrCreate('create shop staff', $guardName);
        $permissions['read shop staff'] = Permission::findOrCreate('read shop staff', $guardName);
        $permissions['update shop staff'] = Permission::findOrCreate('update shop staff', $guardName);
        $permissions['delete shop staff'] = Permission::findOrCreate('delete shop staff', $guardName);

        // Shop team
        $permissions['access shop team'] = Permission::findOrCreate('access shop team', $guardName);
        $permissions['create shop team'] = Permission::findOrCreate('create shop team', $guardName);
        $permissions['read shop team'] = Permission::findOrCreate('read shop team', $guardName);
        $permissions['update shop team'] = Permission::findOrCreate('update shop team', $guardName);
        $permissions['delete shop team'] = Permission::findOrCreate('delete shop team', $guardName);

        // Shop permission
        $permissions['access shop permission'] = Permission::findOrCreate('access shop permission', $guardName);
        $permissions['read shop permission'] = Permission::findOrCreate('read shop permission', $guardName);
        $permissions['update shopp ermission'] = Permission::findOrCreate('update shop permission', $guardName);

        // Subscription
        $permissions['access subscription'] = Permission::findOrCreate('access subscription', $guardName);
        $permissions['create subscription'] = Permission::findOrCreate('create subscription', $guardName);
        $permissions['read subscription'] = Permission::findOrCreate('read subscription', $guardName);
        $permissions['update subscription'] = Permission::findOrCreate('update subscription', $guardName);
        $permissions['delete subscription'] = Permission::findOrCreate('delete subscription', $guardName);

        // Invoices
        $permissions['access invoice settings'] = Permission::findOrCreate('access invoice settings', $guardName);
        $permissions['read invoice settings'] = Permission::findOrCreate('read invoice settings', $guardName);
        $permissions['update invoice settings'] = Permission::findOrCreate('update invoice settings', $guardName);
        $permissions['access invoices'] = Permission::findOrCreate('access invoices', $guardName);
        $permissions['read invoices'] = Permission::findOrCreate('read invoices', $guardName);

        return $permissions;
    }

    /**
     * Sync roles and permissions
     *
     * @param  \Illuminate\Database\Eloquent\Collection|Role[]  $roles
     * @param  \Illuminate\Database\Eloquent\Collection|Permission[]  $permissions
     */
    public static function syncRolesAndPermissions(array|Collection $roles, array|Collection $permissions): void
    {
        if ($roles instanceof Collection) {
            $roles = $roles->all();
        }

        if ($permissions instanceof Collection) {
            $permissions = $permissions->all();
        }

        $roles[static::ROLE_SUPER_ADMIN]->syncPermissions(array_values($permissions));

        $roles[static::ROLE_ADMIN]->syncPermissions(array_values($permissions));

        $roles[static::ROLE_CASHIER]->syncPermissions([
            $permissions['read product'],
            $permissions['update stock'],
            $permissions['access sales'],
            $permissions['access customer'],
            $permissions['create customer'],
            $permissions['read customer'],
            $permissions['update customer'],
            $permissions['delete customer'],
            $permissions['access order'],
            $permissions['create order'],
            $permissions['read order'],
            $permissions['update order'],
            $permissions['delete order'],
            $permissions['access tax'],
            $permissions['read tax'],
        ]);

        $roles[static::ROLE_WAREHOUSE_WORKER]->syncPermissions([
            $permissions['access catalog'],
            $permissions['access category'],
            $permissions['create category'],
            $permissions['read category'],
            $permissions['update category'],
            $permissions['delete category'],
            $permissions['access product'],
            $permissions['create product'],
            $permissions['read product'],
            $permissions['update product'],
            $permissions['delete product'],
            $permissions['access product type'],
            $permissions['create product type'],
            $permissions['read product type'],
            $permissions['update product type'],
            $permissions['delete product type'],
            $permissions['access stock'],
            $permissions['update stock'],
            $permissions['read stock'],
            $permissions['access tax'],
            $permissions['read tax'],
        ]);
    }

    public static function getRolesByShop(Shop $shop, array $relations = [])
    {
        $query = $shop->roles()->orderBy('name')->get();

        if (count($relations)) {
            $query->load($relations);
        }

        return $query->keyBy('name');
    }

    public static function getAllPermissions()
    {
        return Permission::all();
    }

    public static function deleteAllPermissions()
    {
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            $permission->delete();
        }
    }

    public static function createRole(string $roleName): Role
    {
        $key = PermissionRegistrar::$teamsKey;

        return Role::create(
            [
                'name' => $roleName,
                'editable' => true,
                'deletable' => true,
                $key => ShopSession::getId(),
            ]
        );
    }

    public static function updateRoleName(int $roleId, string $roleName)
    {
        $role = Role::findById($roleId);

        if (! $role->editable) {
            throw RoleNotEditableException::named($role->name);
        }

        $role->update(['name' => $roleName]);

        return $role;
    }

    public static function delete(int $id): bool
    {
        $role = Role::find($id);

        if (! $role->deletable) {
            throw RoleNotDeletableException::named($role->name);
        }

        $deleted = $role->delete();

        return $deleted;
    }
}
