<?php

namespace App\Http\Helpers;

abstract class HttpResponseCode
{
    /**
     * Används när ingen data returneras från controllern men man vill ändå skicka en ok respons.
     */
    const OK = 200;

    const TEMP_REDIRECT = 302;

    /**
     * Används när den efterfrågade resursen kräver att klienten prenumererar på den.
     */
    const PAYMENT_REQUIRED = 402;

    /**
     * Används när den efterfrågade resursen inte är giltig
     */
    const FORBIDDEN = 403;

    /**
     * Används när den efterfrågade resursen inte hittas.
     */
    const NOT_FOUND = 404;

    /**
     * Används när åtgärd på den efterfrågade resursen inte är tillåten.
     * Skiljer sig från FORBIDDEN på så sätt att klienten har behörighet till resursen, men åtgärden är av någon anledning inte tillåten.
     */
    const METHOD_NOT_ALLOWED = 405;
}
