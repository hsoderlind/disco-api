<?php

namespace App\Exceptions;

use App\Http\Helpers\HttpResponseCode;
use App\Services\Orders\Exceptions\OrderException;
use App\Services\Shop\ShopSession;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (OrderException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                ...$e->toArray(),
            ], HttpResponseCode::BAD_REQUEST);
        });
    }

    public function context(): array
    {
        return array_merge(parent::context(), [
            'shop_id' => ShopSession::getId(),
        ]);
    }
}
