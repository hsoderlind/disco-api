<?php

namespace App\Exceptions;

use Exception;

class RoleNotDeletableException extends Exception
{
    public static function named(string $roleName)
    {
        return new static("Rollen `{$roleName}` är inte raderbar.");
    }

    public static function withId(int $id)
    {
        return new static("Rollen med ID `{$id}` är inte raderbar.");
    }
}
