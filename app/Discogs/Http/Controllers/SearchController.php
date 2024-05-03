<?php

namespace App\Discogs\Http\Controllers;

use App\Discogs\Requests\SearchRequest;
use App\Discogs\Services\Search;
use App\Http\Controllers\Controller;
use App\Models\DiscogsToken;
use App\Services\Shop\ShopSession;

class SearchController extends Controller
{
    protected Search $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = Search::make(DiscogsToken::forShop(ShopSession::getId()));
    }

    public function index(SearchRequest $request)
    {
        $result = $this->service->fill($request->validated())->search();

        return [
            'pagination' => $result->getPagination()->toArray(),
            'results' => $result->all(),
        ];
    }
}
