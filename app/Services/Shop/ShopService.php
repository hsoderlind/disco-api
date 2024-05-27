<?php

namespace App\Services\Shop;

use App\Models\Shop;
use App\Models\User;
use App\Services\Logotype\LogotypeService;
use App\Services\Permissions\PermissionsService;
use Illuminate\Support\Facades\DB;

abstract class ShopService
{
    public static function create(array $shopData, User $user): Shop
    {
        try {
            DB::beginTransaction();
            $shop = new Shop($shopData);
            $shop->account_owner = $user->id;
            $shop->save();

            ShopSession::setId($shop->id);

            $roles = PermissionsService::createDefaultRoles($shop->id);
            $permissions = PermissionsService::findOrCreatePermissions();
            PermissionsService::syncRDefaultRolesAndPermissions($roles, $permissions);

            self::addUser($user->id, $shop);
            $user->assignRole(PermissionsService::ROLE_SUPER_ADMIN);

            DB::commit();

            return $shop;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function get(int $id): Shop
    {
        return Shop::findOrFail($id);
    }

    public static function update(int $id, array $shopData): Shop
    {
        $shop = self::get($id);
        $shop->updateOrFail($shopData);

        return $shop;
    }

    public static function delete(int $id): void
    {
        $shop = self::get($id);
        $shop->deleteOrFail();
    }

    public static function getByUrlAlias(string $urlAlias): Shop
    {
        return Shop::with(['defaultLogotype', 'miniLogotype'])->where('url_alias', $urlAlias)->firstOrFail();
    }

    public static function addUser(int $userId, ?Shop $shop = null): void
    {
        if (! $shop) {
            $shop = Shop::find(ShopSession::getId());
        }

        if (! $shop) {
            throw new \Exception('Butiken hittades inte');
        }

        $shop->users()->attach($userId);
    }

    public static function removeUser(int $userId, ?Shop $shop = null): void
    {
        if (! $shop) {
            $shop = Shop::find(ShopSession::getId());
        }

        if (! $shop) {
            throw new \Exception('Butiken hittades inte');
        }

        $shop->users()->detach($userId);
    }

    public static function listByUser(User $user)
    {
        return Shop::with(['defaultLogotype', 'miniLogotype'])->whereHas('users', fn ($query) => $query->where('user_id', $user->getKey()))->get();
    }

    public static function verifyUser(User $user, ?Shop $shop = null): bool
    {
        if (! $shop) {
            $shop = self::get(ShopSession::getId());
        }

        return $shop->users()->where('user_id', $user->getKey())->exists();
    }

    public static function setLogotype(int $id, string $context, array $data): Shop
    {
        $shop = self::get($id);

        $logotypeService = LogotypeService::factory($id);
        $logotype = $logotypeService->create($data)->get();

        if ($context == 'default') {
            $shop->defaultLogotype()->associate($logotype);
        } elseif ($context == 'mini') {
            $shop->miniLogotype()->associate($logotype);
        }

        $shop->save();

        return $shop;
    }
}
