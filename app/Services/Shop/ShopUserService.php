<?php

namespace App\Services\Shop;

use App\Http\Resources\UserResource;
use App\Mail\ShopUserInvited;
use App\Mail\ShopUserRemoved;
use App\Models\Shop;
use App\Models\User;
use App\Services\AbstractService;
use App\Services\Permissions\PermissionsService;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;
use RuntimeException;

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
        $this->data = $this->shop->users()->with('roles')->orderBy('name')->get();

        return $this;
    }

    public function create(array $data)
    {
        $this->data = User::firstOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'state' => 'INVITED',
            ]
        );

        $this->data->syncRoles($data['roles']);

        ShopService::addUser($this->data->getKey(), $this->shop);

        return $this;
    }

    public function read(int $id)
    {
        $this->data = $this->shop->users()->wherePivot('user_id', $id)->firstOrFail();

        return $this;
    }

    public function update(int $id, array $data)
    {
        /** @var \App\Models\User */
        $user = $this->read($id)->get();

        if ($user->state === 'INVITED') {
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);
        }

        $user->syncRoles($data['roles']);

        $this->data = $user;

        return $this;
    }

    public function delete(?int $id = null)
    {
        if (is_null($id) && ! ($this->data instanceof User)) {
            throw new RuntimeException('No user provided.');
        }

        if (! is_null($id)) {
            $this->read($id);
        }

        if ($this->data->state === 'INVITED') {
            $deleted = $this->data->delete();

            if (! $deleted) {
                return false;
            }
        }

        return true;
    }

    public function removeUserFromShop(?int $id = null)
    {
        if (is_null($id) && ! ($this->data instanceof User)) {
            throw new RuntimeException('No user provided.');
        }

        if (! is_null($id)) {
            $this->read($id);
        }

        ShopService::removeUser($this->data->getKey());

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

        if ($newOwner->state !== 'REGISTERED') {
            throw new RuntimeException('The new owner must be registered');
        }

        $updated = $this->shop->update(['account_owner' => $newOwner->getKey()]);

        if ($updated) {
            $roles = PermissionsService::getRolesByShop($this->shop);
            $adminRole = $roles[PermissionsService::ROLE_ADMIN];
            $superAdminRole = $roles[PermissionsService::ROLE_SUPER_ADMIN];
            $oldOwner->syncRoles([$adminRole]);
            $newOwner->syncRoles([$superAdminRole]);
        }

        return $this;
    }

    public function inviteUser(User $inviter, ?User $invited = null)
    {

        if (is_null($invited) && $this->data instanceof User) {
            $invited = $this->data;
        }

        if (! ($invited instanceof User)) {
            throw new RuntimeException('Missing invited user');
        }

        Mail::to($invited)->queue(new ShopUserInvited($inviter, $invited));

        return $this;
    }

    public function sendRemovedFromShopMail(?User $removedUser = null)
    {
        if (is_null($removedUser) && $this->data instanceof User) {
            $removedUser = $this->data;
        }

        if (! ($removedUser instanceof User)) {
            throw new RuntimeException('Missing removed user');
        }

        Mail::to($removedUser->email)->queue(new ShopUserRemoved($this->shop->name, $removedUser->name));

        return $this;
    }
}
