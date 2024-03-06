<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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

    protected function afterCallingAction($response, $method, $parameters)
    {
        //
    }
}
