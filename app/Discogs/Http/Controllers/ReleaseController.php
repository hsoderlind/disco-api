<?php

namespace App\Discogs\Http\Controllers;

use App\Discogs\Requests\ReleaseRequest;
use App\Discogs\Requests\ReleaseStatsRequest;
use App\Discogs\Services\Release;
use App\Http\Controllers\Controller;
use App\Models\DiscogsToken;
use App\Services\Shop\ShopSession;

class ReleaseController extends Controller
{
    protected Release $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = Release::make(DiscogsToken::forShop(ShopSession::getId()));
    }

    public function index(ReleaseRequest $request)
    {
        $model = $this->service->fill($request->validated())->getRelease();

        return $model;
    }

    public function stats(ReleaseStatsRequest $request)
    {
        $model = $this->service->fill($request->validated())->getRelease();

        return [
            'num_have' => $model->community->have,
            'num_want' => $model->community->want,
            'rating' => [
                'average' => $model->community->rating->average,
                'count' => $model->community->rating->count,
            ],
        ];
    }
}
