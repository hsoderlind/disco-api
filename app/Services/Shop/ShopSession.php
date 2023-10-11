<?php

namespace App\Services\Shop;

abstract class ShopSession
{
    public static function setId(int $id): void
    {
        setPermissionsTeamId($id);
    }

    public static function getId(): ?int
    {
        return getPermissionsTeamId();
    }
}
