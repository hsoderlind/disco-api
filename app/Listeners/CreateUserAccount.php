<?php

namespace App\Listeners;

use App\Models\Account;
use Illuminate\Auth\Events\Registered;

class CreateUserAccount
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        Account::create([
            'user_id' => $event->user->id,
            'name' => $event->user->name,
        ]);
    }
}
