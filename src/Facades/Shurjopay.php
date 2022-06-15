<?php

namespace Raziul\Shurjopay\Facades;

use Illuminate\Support\Facades\Facade;
use Raziul\Shurjopay\Gateway;

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
