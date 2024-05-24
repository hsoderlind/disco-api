<?php

namespace App\Helpers;

use Illuminate\Support\Str;

abstract class Frontend
{
    public static function getUrl()
    {
        return config('app.frontend_url');
    }

    public static function urlTo(string $uri)
    {
        $uri = Str::startsWith($uri, '/') ? $uri : '/'.$uri;

        return self::getUrl().$uri;
    }
}
