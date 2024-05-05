<?php

namespace App\Discogs\Http\Controllers;

use App\Discogs\Requests\AddToWantlistRequest;
use App\Discogs\Services\Wantlist;
use App\Http\Controllers\Controller;
use App\Models\DiscogsToken;
use App\Services\Shop\ShopSession;

class WantlistController extends Controller
{
    protected Wantlist $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = Wantlist::make(DiscogsToken::forShop(ShopSession::getId()));
    }

    public function add(AddToWantlistRequest $request)
    {
        $model = $this->service->fill($request->validated())->addRelease();

        return $model;
    }
}
