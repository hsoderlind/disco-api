<?php

namespace App\Services\Permissions;

use App\Exceptions\RoleNotDeletableException;
use App\Exceptions\RoleNotEditableException;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Shop;
use App\Services\Shop\ShopSession;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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
        $permissions['access catalog'] = Permission::findOrCreateWithGroup('access catalog', 'Tillgång till katalogen', 'catalog', $guardName);

        // Categories
        $permissions['access category'] = Permission::findOrCreateWithGroup('access categories', 'Tillgång till kategorier', 'category', $guardName);
        $permissions['create category'] = Permission::findOrCreateWithGroup('create category', 'Skapa kategorier', 'category', $guardName);
        $permissions['read category'] = Permission::findOrCreateWithGroup('read category', 'Läsa kategorier', 'category', $guardName);
        $permissions['update category'] = Permission::findOrCreateWithGroup('update category', 'Uppdatera kategorier', 'category', $guardName);
        $permissions['delete category'] = Permission::findOrCreateWithGroup('delete category', 'Radera kategorier', 'category', $guardName);

        // Products
        $permissions['access product'] = Permission::findOrCreateWithGroup('access product', 'Tillgång till produkter', 'products', $guardName);
        $permissions['create product'] = Permission::findOrCreateWithGroup('create product', 'Skapa produkter', 'products', $guardName);
        $permissions['read product'] = Permission::findOrCreateWithGroup('read product', 'Läsa produkter', 'products', $guardName);
        $permissions['update product'] = Permission::findOrCreateWithGroup('update product', 'Uppdatera produkter', 'products', $guardName);
        $permissions['delete product'] = Permission::findOrCreateWithGroup('delete product', 'Radera produkter', 'products', $guardName);

        // Product type
        $permissions['access product type'] = Permission::findOrCreateWithGroup('access product type', 'Tillgång till produkttyper', 'product type', $guardName);
        $permissions['create product type'] = Permission::findOrCreateWithGroup('create product type', 'Skapa produkttyper', 'product type', $guardName);
        $permissions['read product type'] = Permission::findOrCreateWithGroup('read product type', 'Läsa produkttyper', 'product type', $guardName);
        $permissions['update product type'] = Permission::findOrCreateWithGroup('update product type', 'Uppdatera produkttyper', 'product type', $guardName);
        $permissions['delete product type'] = Permission::findOrCreateWithGroup('delete product type', 'Radera produkttyper', 'product type', $guardName);

        // Stock
        $permissions['access stock'] = Permission::findOrCreateWithGroup('access stock', 'Tillgång till lager', 'stock', $guardName);
        $permissions['update stock'] = Permission::findOrCreateWithGroup('update stock', 'Uppdatera lager', 'stock', $guardName);
        $permissions['read stock'] = Permission::findOrCreateWithGroup('read stock', 'Läsa lager', 'stock', $guardName);

        // Sales
        $permissions['access sales'] = Permission::findOrCreateWithGroup('access sales', 'Tillgång till försäljning', 'sales', $guardName);

        // Customers
        $permissions['access customer'] = Permission::findOrCreateWithGroup('access customer', 'Tillgång till kunder', 'customers', $guardName);
        $permissions['create customer'] = Permission::findOrCreateWithGroup('create customer', 'Skapa kunder', 'customers', $guardName);
        $permissions['read customer'] = Permission::findOrCreateWithGroup('read customer', 'Läsa kunder', 'customers', $guardName);
        $permissions['update customer'] = Permission::findOrCreateWithGroup('update customer', 'Uppdatera kunder', 'customers', $guardName);
        $permissions['delete customer'] = Permission::findOrCreateWithGroup('delete customer', 'Radera kunder', 'customers', $guardName);

        // Orders
        $permissions['access order'] = Permission::findOrCreateWithGroup('access order', 'Tillgång till beställningar', 'orders', $guardName);
        $permissions['create order'] = Permission::findOrCreateWithGroup('create order', 'Skapa beställningar', 'orders', $guardName);
        $permissions['read order'] = Permission::findOrCreateWithGroup('read order', 'Läsa beställningar', 'orders', $guardName);
        $permissions['update order'] = Permission::findOrCreateWithGroup('update order', 'Uppdatera beställningar', 'orders', $guardName);
        $permissions['delete order'] = Permission::findOrCreateWithGroup('delete order', 'Radera beställningar', 'orders', $guardName);

        // Shop profile
        $permissions['access shop profile'] = Permission::findOrCreateWithGroup('access shop profile', 'Tillgång till butiksprofilen', 'shop profile', $guardName);
        $permissions['read shop profile'] = Permission::findOrCreateWithGroup('read shop profile', 'Läsa butiksprofilen', 'shop profile', $guardName);
        $permissions['update shop profile'] = Permission::findOrCreateWithGroup('update shop profile', 'Uppdatera butiksprofilen', 'shop profile', $guardName);

        // Company profile
        $permissions['access company profile'] = Permission::findOrCreateWithGroup('access company profile', 'Tillgång till bolagsprofilen', 'company profile', $guardName);
        $permissions['read company profile'] = Permission::findOrCreateWithGroup('read company profile', 'Läsa bolagsprofilen', 'company profile', $guardName);
        $permissions['update company profile'] = Permission::findOrCreateWithGroup('update company profile', 'Uppdatera bolagsprofilen', 'company profile', $guardName);

        // Settings
        $permissions['access shop settings'] = Permission::findOrCreateWithGroup('access shop settings', 'Tillgång till butiksinställningar', 'shop settings', $guardName);

        // Taxes
        $permissions['access tax'] = Permission::findOrCreateWithGroup('access tax', 'Tillgång till moms', 'tax', $guardName);
        $permissions['create tax'] = Permission::findOrCreateWithGroup('create tax', 'Skapa moms', 'tax', $guardName);
        $permissions['read tax'] = Permission::findOrCreateWithGroup('read tax', 'Läsa moms', 'tax', $guardName);
        $permissions['update tax'] = Permission::findOrCreateWithGroup('update tax', 'Uppdatera tax', 'tax', $guardName);
        $permissions['delete tax'] = Permission::findOrCreateWithGroup('delete tax', 'Radera moms', 'tax', $guardName);

        // Sales Channels
        $permissions['access sales channels'] = Permission::findOrCreateWithGroup('access sales channels', 'Tillgång till försäljningskanaler', 'sales channels', $guardName);
        $permissions['update sales channels'] = Permission::findOrCreateWithGroup('update sales channels', 'Uppdatera försäljningskanaler', 'sales channels', $guardName);

        // Tradera settings
        $permissions['access tradera settings'] = Permission::findOrCreateWithGroup('access tradera settings', 'Tillgång till Tradera-inställningar', 'tradera', $guardName);

        // Discogs settings
        $permissions['access discogs settings'] = Permission::findOrCreateWithGroup('access discogs settings', 'Tillgång till Discogs-inställningar', 'discogs', $guardName);

        // PoS settings
        $permissions['access pos settings'] = Permission::findOrCreateWithGroup('access pos settings', 'Tillgång till kassan', 'checkout', $guardName);

        // Shop staff
        $permissions['access shop staff'] = Permission::findOrCreateWithGroup('access shop staff', 'Tillgång till butiksanvändare', 'user management', $guardName);
        $permissions['create shop staff'] = Permission::findOrCreateWithGroup('create shop staff', 'Skapa butiksanvändare', 'user management', $guardName);
        $permissions['read shop staff'] = Permission::findOrCreateWithGroup('read shop staff', 'Läsa butiksanvändare', 'user management', $guardName);
        $permissions['update shop staff'] = Permission::findOrCreateWithGroup('update shop staff', 'Uppdatera butiksanvändare', 'user management', $guardName);
        $permissions['delete shop staff'] = Permission::findOrCreateWithGroup('delete shop staff', 'Radera butiksanvändare', 'user management', $guardName);

        // Shop team
        $permissions['access shop team'] = Permission::findOrCreateWithGroup('access shop team', 'Tillgång till roller', 'user management', $guardName);
        $permissions['create shop team'] = Permission::findOrCreateWithGroup('create shop team', 'Skapa roller', 'user management', $guardName);
        $permissions['read shop team'] = Permission::findOrCreateWithGroup('read shop team', 'Läsa roller', 'user management', $guardName);
        $permissions['update shop team'] = Permission::findOrCreateWithGroup('update shop team', 'Uppdatera roller', 'user management', $guardName);
        $permissions['delete shop team'] = Permission::findOrCreateWithGroup('delete shop team', 'Radera roller', 'user management', $guardName);

        // Shop permission
        $permissions['access shop permission'] = Permission::findOrCreateWithGroup('access shop permission', 'Tillgång till behörigheter', 'user management', $guardName);
        $permissions['read shop permission'] = Permission::findOrCreateWithGroup('read shop permission', 'Läsa behörigheter', 'user management', $guardName);
        $permissions['update shopp ermission'] = Permission::findOrCreateWithGroup('update shop permission', 'Uppdatera behörigheter', 'user management', $guardName);

        // Subscription
        $permissions['access subscription'] = Permission::findOrCreateWithGroup('access subscription', 'Tillgång till prenumerationen', 'subscription', $guardName);
        $permissions['create subscription'] = Permission::findOrCreateWithGroup('create subscription', 'Skapa prenumerationen', 'subscription', $guardName);
        $permissions['read subscription'] = Permission::findOrCreateWithGroup('read subscription', 'Läsa prenumerationen', 'subscription', $guardName);
        $permissions['update subscription'] = Permission::findOrCreateWithGroup('update subscription', 'Uppdatera prenumerationen', 'subscription', $guardName);
        $permissions['delete subscription'] = Permission::findOrCreateWithGroup('delete subscription', 'Avsluta prenumerationen', 'subscription', $guardName);

        // Invoices
        $permissions['access invoice settings'] = Permission::findOrCreateWithGroup('access invoice settings', 'Tillgång till fakturainställningar', 'invoicing', $guardName);
        $permissions['read invoice settings'] = Permission::findOrCreateWithGroup('read invoice settings', 'Läsa fakturainställningar', 'invoicing', $guardName);
        $permissions['update invoice settings'] = Permission::findOrCreateWithGroup('update invoice settings', 'Uppdatera fakturainställningar', 'invoicing', $guardName);
        $permissions['access invoices'] = Permission::findOrCreateWithGroup('access invoices', 'Tillgång till fakturor', 'invoicing', $guardName);
        $permissions['read invoices'] = Permission::findOrCreateWithGroup('read invoices', 'Läsa fakturor', 'invoicing', $guardName);

        // Payment methods
        $permissions['access payment methods'] = Permission::findOrCreateWithGroup('access payment methods', 'Tillgång till betalningsmetoder', 'payment methods', $guardName);
        $permissions['create payment methods'] = Permission::findOrCreateWithGroup('create payment methods', 'Installera betalningsmetoder', 'payment methods', $guardName);
        $permissions['read payment methods'] = Permission::findOrCreateWithGroup('read payment methods', 'Använda betalningsmetoder', 'payment methods', $guardName);
        $permissions['update payment methods'] = Permission::findOrCreateWithGroup('update payment methods', 'Konfigurera betalningsmetoder', 'payment methods', $guardName);
        $permissions['delete payment methods'] = Permission::findOrCreateWithGroup('delete payment methods', 'Avinstallera betalningsmetoder', 'payment methods', $guardName);

        // Order total modules
        $permissions['access order total modules'] = Permission::findOrCreateWithGroup('access order total modules', 'Tillgång till order total-moduler', 'order total modules', $guardName);
        $permissions['create order total modules'] = Permission::findOrCreateWithGroup('create order total modules', 'Installera order total-moduler', 'order total modules', $guardName);
        $permissions['read order total modules'] = Permission::findOrCreateWithGroup('read order total modules', 'Använda order total-moduler', 'order total modules', $guardName);
        $permissions['update order total modules'] = Permission::findOrCreateWithGroup('update order total modules', 'Konfigurera order total-moduler', 'order total modules', $guardName);
        $permissions['delete order total modules'] = Permission::findOrCreateWithGroup('delete order total modules', 'Avinstallera order total-moduler', 'order total modules', $guardName);

        // Order status
        $permissions['access order status'] = Permission::findOrCreateWithGroup('access order status', 'Tillgång till order status', 'order statuses', $guardName);
        $permissions['create order status'] = Permission::findOrCreateWithGroup('create order status', 'Skapa order status', 'order statuses', $guardName);
        $permissions['read order status'] = Permission::findOrCreateWithGroup('read order status', 'Använda order status', 'order statuses', $guardName);
        $permissions['update order status'] = Permission::findOrCreateWithGroup('update order status', 'Redigera order status', 'order statuses', $guardName);
        $permissions['delete order status'] = Permission::findOrCreateWithGroup('delete order status', 'Radera order status', 'order statuses', $guardName);

        return $permissions;
    }

    /**
     * Sync roles and permissions
     *
     * @param  \Illuminate\Database\Eloquent\Collection|Role[]  $roles
     * @param  \Illuminate\Database\Eloquent\Collection|Permission[]  $permissions
     */
    public static function syncRDefaultRolesAndPermissions(array|Collection $roles, array|Collection $permissions): void
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
            $permissions['access payment methods'],
            $permissions['read payment methods'],
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

    /**
     * Sync roles with permissions
     *
     * @param  int[]  $permissionIds
     * @return Role
     */
    public static function syncRolesAndPermissions(int|Role $role, array $permissionIds)
    {
        return DB::transaction(function () use ($role, $permissionIds) {
            if (is_int($role)) {
                $role = Role::findById($role);
            }

            /** @var \App\Models\Role $role */
            $role->syncPermissions($permissionIds);

            return $role;
        });
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
