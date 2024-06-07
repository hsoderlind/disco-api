<?php

namespace App\Exceptions;

use App\Http\Helpers\HttpResponseCode;
use App\Services\Orders\Exceptions\OrderPaymentException;
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
        $this->reportable(function (OrderPaymentException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'payment_name' => $e->getPaymentName(),
                'reason' => $e->getReason(),
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
