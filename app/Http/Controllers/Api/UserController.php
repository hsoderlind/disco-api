<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpResponseCode;
use App\Http\Resources\UserResource;
use App\Services\Shop\ShopSession;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $service;

    protected function beforeCallingAction($method, $parameters)
    {
        $this->service = UserService::factory();
    }

    public function index(Request $request)
    {
        return new UserResource($request->user()->first());
    }

    public function masquerade(Request $request, int $id)
    {
        $masqueraded = $this->service->inShop(ShopSession::getId())->masquerade($id);

        abort_if(! $masqueraded, HttpResponseCode::FORBIDDEN, 'Misslyckades att maskera');

        return $this->service->toResource();
    }

    public function unmasquerade(Request $request)
    {
        $success = $this->service->inShop(ShopSession::getId())->unmasquerade();

        abort_if(! $success, HttpResponseCode::FORBIDDEN, 'Misslyckades att avmaskera');

        return $this->service->toResource();
    }
}
