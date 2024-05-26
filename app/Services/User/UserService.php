<?php

namespace App\Services\User;

use App\Exceptions\InvalidUserException;
use App\Exceptions\UserNotVerifiedException;
use App\Models\Shop;
use App\Models\User;
use App\Services\AbstractService;
use App\Services\Shop\ShopService;

class UserService extends AbstractService
{
    protected Shop $shop;

    public function inShop(int $id)
    {
        $this->shop = ShopService::get($id);

        return $this;
    }

    public function read(int $id)
    {
        $this->data = User::findOrFail($id);

        return $this;
    }

    public function verifyUser(int $id)
    {
        $model = $this->read($id)->get();

        return ShopService::verifyUser($model, $this->shop);
    }

    public function masquerade(int $id)
    {
        $verified = $this->verifyUser($id);

        if (! $verified) {
            throw UserNotVerifiedException::withId($id);
        }

        $user = $this->data;

        if ($user->state === 'INVITED') {
            throw new InvalidUserException('Can not masquerade a user of state INVITED');
        }

        /** @var \App\Models\User $oldUser */
        $oldUser = auth()->user();

        auth()->logout();

        auth()->login($user);

        if (! session()->regenerate()) {
            return false;
        }

        session()->put('oldUserId', $oldUser->getKey());

        return true;
    }

    public function unmasquerade()
    {
        $id = session('oldUserId');

        $verified = $this->verifyUser($id);

        if (! $verified) {
            throw UserNotVerifiedException::withId($id);
        }

        $user = $this->data;

        auth()->logout();

        auth()->login($user);

        return session()->regenerate();
    }
}
