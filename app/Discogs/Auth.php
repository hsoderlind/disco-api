<?php

namespace App\Discogs;

use Hsoderlind\Discogs\Auth\Auth as BaseAuth;
use Illuminate\Http\RedirectResponse;

abstract class Auth
{
    public static function requestToken(string $callbackUrl): array
    {
        $consumerKey = config('discogs.consumerKey');
        $consumerSecret = config('discogs.consumerSecret');
        $signature = $consumerSecret.'&';

        return BaseAuth::requestToken($consumerKey, $signature, $callbackUrl);
    }

    public static function authorize(string $oauthToken): RedirectResponse
    {
        return redirect()->away(BaseAuth::AUTHORIZE_URL.'?oauth_token='.$oauthToken);
    }

    public static function accessToken(string $oauthToken, string $oauthTokenSecret, string $oauthVerifier): array
    {
        $consumerKey = config('discogs.consumerKey');
        $consumerSecret = config('discogs.consumerSecret');
        $signature = $consumerSecret.'&'.$oauthTokenSecret;

        return BaseAuth::accessToken($consumerKey, $signature, $oauthToken, $oauthVerifier);
    }
}
