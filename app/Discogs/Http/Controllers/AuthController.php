<?php

namespace App\Discogs\Http\Controllers;

use App\Discogs\Auth;
use App\Discogs\Services\User;
use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpResponseCode;
use App\Models\DiscogsToken;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function initAuth(Request $request)
    {
        $callbackUrl = route('discogs.auth.complete', ['shopId' => $request->shop->getKey()]);

        $result = Auth::requestToken($callbackUrl);

        session()->put('discogs', [
            'oauthToken' => $result['oauth_token'],
            'oauthTokenSecret' => $result['oauth_token_secret'],
            'callbackConfirmed' => $result['oauth_callback_confirmed'] === 'true',
            'refererUrl' => $request->query('referer'),
        ]);

        return Auth::authorize($result['oauth_token']);
    }

    public function completeAuth(Request $request)
    {
        $authIsDenied = (bool) $request->query('denied');

        if ($authIsDenied) {
            return $this->denyAuth($request);
        }

        $oauthToken = $request->query('oauth_token');
        $oauthVerifier = $request->query('oauth_verifier');
        $discogsSession = session()->get('discogs');
        $oauthTokenSecret = $discogsSession['oauthTokenSecret'];
        $tokenMatches = $oauthToken === $discogsSession['oauthToken'];
        $refererUrl = urldecode($discogsSession['refererUrl']);

        abort_if(! $tokenMatches, HttpResponseCode::FORBIDDEN, 'Tecken matchar inte');

        $result = Auth::accessToken($oauthToken, $oauthTokenSecret, $oauthVerifier);

        $model = new DiscogsToken();
        $model->token = $result['oauth_token'];
        $model->token_secret = $result['oauth_token_secret'];

        $identity = User::make($model)->getIdentity();

        $model->username = $identity->username;
        $model->save();

        session()->remove('discogs');

        return redirect()->away(config('app.frontend_url').$refererUrl, HttpResponseCode::TEMP_REDIRECT, ['discogs_auth_complete' => 'success']);
    }

    public function denyAuth(Request $request)
    {
        $discogsSession = session()->get('discogs');
        $refererUrl = urldecode($discogsSession['refererUrl']);

        session()->remove('discogs');

        return redirect()->away(config('app.frontend_url').$refererUrl, HttpResponseCode::TEMP_REDIRECT, ['discogs_auth_complete' => 'failed']);
    }

    public function checkAuthed(Request $request)
    {
        $model = DiscogsToken::forShop($request->shop->getKey());

        return ['hasToken' => ! is_null($model)];
    }
}
