<?php

namespace Raziul\Shurjopay\Facades;

use Illuminate\Support\Facades\Facade;
use Raziul\Shurjopay\Gateway;

/**
 * @method static \Raziul\Shurjopay\Gateway setCallbackUrl(string $success_url, string $cancel_url)
 * @method static \Raziul\Shurjopay\Gateway getToken(): array
 * @method static \Raziul\Shurjopay\Gateway makePayment()
 * @method static \Raziul\Shurjopay\Gateway verify(string $order_id): \Raziul\Shurjopay\Data\Payment
 */
class Shurjopay extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Gateway::class;
    }
}
