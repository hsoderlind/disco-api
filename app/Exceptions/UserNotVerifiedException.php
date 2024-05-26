<?php

namespace App\Exceptions;

use Exception;

class UserNotVerifiedException extends Exception
{
    public static function named(string $userName)
    {
        return new static("User `{$userName}` is not a user of the shop.");
    }

    public static function withId(int $id)
    {
        return new static("User with ID `{$id}` is not a user of the shop.");
    }
}
