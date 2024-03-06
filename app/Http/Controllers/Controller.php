<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function callAction($method, $parameters)
    {
        $this->beforeCallingAction($method, $parameters);

        $response = parent::callAction($method, $parameters);

        $this->afterCallingAction($response, $method, $parameters);

        return $response;
    }

    // Events
    protected function beforeCallingAction($method, $parameters)
    {
        //
    }

    protected function afterCallingAction(Response $response, $method, $parameters)
    {
        //
    }
}
