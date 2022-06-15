<?php

namespace Raziul\Shurjopay\Data;

/**
 * Payment
 * 
 * @author Raziul Islam <raziul.cse@gmail.com>
 * @package Raziul\Shurjopay
 */
class Payment
{
    /**
     * ShurjoPay response
     * 
     * @var array
     */
    private $data;


    /**
     * The constructor
     * 
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = end($data);
    }

    /**
     * Get success status
     *
     * @return bool
     */
    public function success(): bool
    {
        return $this->data['sp_code'] == 1000;
    }

    /**
     * Get failed status
     *
     * @return bool
     */
    public function failed(): bool
    {
        return $this->data['sp_code'] != 1000;
    }

    /**
     * Get success/error message
     *
     * @return string
     */
    public function message(): string
    {
        return $this->data['sp_massage'];
    }

    /**
     * Get order id
     *
     * @return string
     */
    public function orderId(): string
    {
        return $this->data['order_id'];
    }

    /**
     * Get currency code
     *
     * @return string
     */
    public function currency(): string
    {
        return $this->data['currency'];
    }

    /**
     * Get the amount
     *
     * @return float|int
     */
    public function amount()
    {
        return $this->data['amount'];
    }

    /**
     * Get customer order id
     *
     * @return string|int
     */
    public function customerOrderId()
    {
        return $this->data['customer_order_id'];
    }

    /**
     * Get the payment method name
     *
     * @return string
     */
    public function paymentMethod(): string
    {
        return $this->data['method'];
    }

    /**
     * Get the transaction date time
     * 
     * @return string
     */
    public function dateTime(): string
    {
        return $this->data['date_time'];
    }

    /**
     * Get whole data as array
     *
     * @return string
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
