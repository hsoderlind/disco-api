<?php

namespace App\Discogs\Http\Controllers;

use App\Discogs\Requests\MarketplaceRequest;
use App\Discogs\Services\Marketplace;
use App\Http\Controllers\Controller;
use App\Models\DiscogsToken;
use App\Services\Shop\ShopSession;

class MarketplaceController extends Controller
{
    protected Marketplace $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = Marketplace::make(DiscogsToken::forShop(ShopSession::getId()));
    }

    public function priceSuggestions(MarketplaceRequest $request)
    {
        $model = $this->service->fill($request->validated())->getPriceSuggestions();

        return $model;
    }
}
