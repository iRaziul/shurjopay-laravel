<p align="center">
    <a href="https://github.com/iraziul" target="_blank">
        <img src="https://www.shurjopay.com.bd/dev/images/shurjoPay.png" alt="ShurjoPay payment gateway integration for Laravel" height="100px">
    </a>
    <h1 align="center">ShurjoPay payment gateway integration for Laravel</h1>
</p>

This is a [Laravel](https://laravel.com) package for integrating **ShurjoPay** payment gateway in your Laravel application.

---

## Features

-   Easy to use.
-   Friendly simple interface.
-   Laravel Facade.

## Installation

Add the dependency to your Laravel project using Composer:

```sh
composer require raziul/shurjopay-laravel
```

> **Note**: This package supports Laravel auto-discovery. You don't need to add the service provider manually.

## Configuration

You can add ShurjoPay mercant credentials in the `.env` file like below:

```sh
# ShurjoPay merchant credentials
SHURJOPAY_SANDBOX_MODE=false
SHURJOPAY_MERCHANT_USERNAME="<your-merchant-username>"
SHURJOPAY_MERCHANT_PASSWORD="<your-merchant-password>"
SHURJOPAY_MERCHANT_PREFIX="<your-merchant-prefix>"
```

or you can publish the config file:

```sh
php artisan vendor:publish --provider="Raziul\Shurjopay\ShurjopayServiceProvider"
```

> It should copy the config file to `config/shurjopay.php` of your project.

## Usage Guide

Whenever you need to use Shurjopay payment gateway, just use the `Shurjopay` Facade.

```php
use Raziul\Shurjopay\Facades\Shurjopay;
```

### To make a payment

In your controller

```php

// The payload will be passed to Shurjopay.
$payload = [
    // order info
    // customer info
    // custom values
];

// set the callback url
Shurjopay::setCallbackUrl($success_url, $cancel_url);

// Make a payment
Shurjopay::makePayment($payload);

// OR use methond chaining like below:
Shurjopay::setCallbackUrl($success_url, $cancel_url)->makePayment($payload);

```

### Verify Payment

> **Note**: You need to call this method in the callback url. The `order_id` will be available in the query string.

```php
$payment = Shurjopay::verifyPayment($order_id);

if ($payment->success()) {
    // payment success
} else {
    // payment failed
}
```

> `Shurjopay::verifyPayment` return an instance of `Raziul\Shurjopay\Data\Payment`.

### Available methods in the `Payment` class.

| Method                      | Description                   |
| --------------------------- | ----------------------------- |
| $payment->success()         | Return payment success status |
| $payment->failed()          | Return payment failed status  |
| $payment->message()         | Get the success/error message |
| $payment->orderId()         | Get the order ID              |
| $payment->currency()        | Get currency code             |
| $payment->amount()          | Get the amount                |
| $payment->customerOrderId() | Get customer order ID         |
| $payment->paymentMethod()   | Get the payment method name   |
| $payment->dateTime()        | Get the transaction date time |
| $payment->toArray()         | Get all the data as array     |

### Error Handling

> For better error handling you can catch the `Raziul\Shurjopay\Exceptions\ShurjopayException`.

```php
try {
	// making payment
	Shurjopay::setCallbackUrl($success_url, $cancel_url)
		->makePayment($payload);

	// or verfication
	Shurjopay::verifyPayment($order_id);

} catch (Raziul\Shurjopay\Exceptions\ShurjopayException $e) {
	return $e->getMessage();
}
```

## Suggestion/Issues

If you found any issues or have any suggestion then please create an [issue](https://github.com/iRaziul/shurjopay-laravel/issues).

You can also submit PR regarding any issues.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Thank You

Thanks for using this package and If you foound this package useful then consider giving it a star.
