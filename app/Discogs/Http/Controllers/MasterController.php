<?php

namespace App\Discogs\Http\Controllers;

use App\Discogs\Requests\MasterReleaseVersionsRequest;
use App\Discogs\Requests\MasterRequest;
use App\Discogs\Services\Master;
use App\Http\Controllers\Controller;
use App\Models\DiscogsToken;
use App\Services\Shop\ShopSession;

class MasterController extends Controller
{
    protected Master $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = Master::make(DiscogsToken::forShop(ShopSession::getId()));
    }

    public function index(MasterRequest $request)
    {
        $model = $this->service->fill($request->validated())->getMaster();

        return $model;
    }

    public function releaseVersions(MasterReleaseVersionsRequest $request)
    {
        $response = $this->service->fill($request->validated())->listReleaseVersions();

        return [
            'pagination' => $response->getPagination()->toArray(),
            'versions' => $response->all(),
        ];
    }
}
