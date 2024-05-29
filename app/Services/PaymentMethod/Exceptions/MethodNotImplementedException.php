<?php

namespace App\Services\PaymentMethod\Exceptions;

use Exception;

class MethodNotImplementedException extends Exception
{
    public static function withMethodName(string $methodName)
    {
        return new static("Method `{$methodName}` not implemented");
    }
}
