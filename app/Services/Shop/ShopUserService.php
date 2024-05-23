<?php

namespace App\Services\Shop;

use App\Http\Resources\UserResource;
use App\Models\Shop;
use App\Models\User;
use App\Services\AbstractService;
use InvalidArgumentException;

class ShopUserService extends AbstractService
{
    protected Shop $shop;

    protected $resource = UserResource::class;

    protected function boot()
    {
        $this->shop = ShopService::get($this->shopId);
    }

    public function list()
    {
        $this->data = $this->shop->users;

        return $this;
    }

    public function transferOwnership(User $oldOwner, User $newOwner)
    {
        $oldOwnerVerified = ShopService::verifyUser($oldOwner, $this->shop) && $this->shop->account_owner === $oldOwner->getKey();

        if (! $oldOwnerVerified) {
            throw new InvalidArgumentException('User is not current owner of the shop account.');
        }

        $newOwnerVerified = ShopService::verifyUser($newOwner, $this->shop);

        if (! $newOwnerVerified) {
            throw new InvalidArgumentException('Not a user of this shop.');
        }

        return $this->shop->update(['account_owner' => $newOwner->getKey()]);
    }
}
