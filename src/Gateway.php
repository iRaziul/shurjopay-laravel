<?php

namespace Raziul\Shurjopay;

use Raziul\Shurjopay\Data\Payment;
use Raziul\Shurjopay\Exceptions\ShurjopayException;

/**
 * Gateway
 * 
 * @author Raziul Islam <raziul.cse@gmail.com>
 * @package Raziul\Shurjopay
 */
class Gateway
{
    const SANDBOX_URL = 'https://sandbox.shurjopayment.com';
    const PRODUCTION_URL = 'https://engine.shurjopayment.com';

    /**
     * The configuration array
     *
     * @var array
     */
    private $config;


    /**
     * The constructor
     *
     * @param array $config
     * 
     * @throws ShurjoPayException
     */
    public function __construct(array $config)
    {
        if (empty($config['username']) || empty($config['password'])) {
            throw new ShurjopayException('ShurjoPay: Please provide `username` and `password`');
        }

        if (!isset($config['sandbox_mode'])) {
            throw new ShurjoPayException('ShurjoPay: The value of `sandbox_mode` must be true or false');
        }

        $this->config = $config;
    }

    /**
     * Set callback url for payment
     * 
     * @param string $success_url
     * @param string $cancel_url
     * 
     * @return self
     */
    public function setCallbackUrl(string $success_url, string $cancel_url)
    {
        $this->config['success_url'] = $success_url;
        $this->config['cancel_url'] = $cancel_url;

        return $this;
    }


    /**
     * Get token for the merchant
     *
     * @return array
     * 
     * @throws ShurjoPayException
     */
    public function getToken(): array
    {
        $response = $this->httpRequest($this->engineUrl('/api/get_token'), [
            'username' => $this->config['username'],
            'password' => $this->config['password'],
        ]);

        if ($response['sp_code'] != 200) {
            throw new ShurjoPayException('ShurjoPay: ' . $response['message']);
        }

        if (empty($response['token'])) {
            throw new ShurjoPayException('ShurjoPay: Token not found');
        }

        return $response;
    }

    /**
     * Make a payment
     *
     * @param array $payload
     * 
     * @return void
     * 
     * @throws ShurjoPayException
     */
    public function makePayment(array $payload)
    {
        $token = $this->getToken();

        $payload = json_encode([
            'token' => $token['token'],
            'store_id' => $token['store_id'],
            'prefix' => $this->config['prefix'],
            'return_url' => $this->config['success_url'],
            'cancel_url' => $this->config['cancel_url'],
        ] + $payload);

        // send payment request to server
        $response = $this->httpRequest($token['execute_url'], $payload, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token['token']
        ]);

        // Goto payment page
        if (!empty($response['checkout_url'])) {
            return redirect()->away($response['checkout_url']);
        }

        // OH God! Errors
        throw new ShurjoPayException('ShurjoPay: ' . json_encode($response));
    }

    /**
     * Verify payment
     *
     * @param string $order_id
     * 
     * @return Payment
     * 
     * @throws ShurjoPayException
     */
    public function verifyPayment(string $order_id): Payment
    {
        $token = $this->getToken();
        $payload = json_encode(['order_id' => $order_id]);

        $response = $this->httpRequest($this->engineUrl('/api/verification'), $payload, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token['token']
        ]);

        // We got error
        if (!empty($response['message'])) {
            throw new ShurjoPayException('ShurjoPay: ' . $response['message']);
        }

        return new Payment($response);
    }


    /**
     * Make HTTP request
     *
     * @param string $url
     * @param array|string $payload
     * @param array $headers
     * 
     * @return array
     * 
     * @throws ShurjoPayException
     */
    private function httpRequest(string $url, $payload = null, array $headers = [])
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => $headers,
            // disable SSL verification
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($ch);

        if ($response === false && curl_errno($ch)) {
            throw new ShurjoPayException('Curl Error: ' . curl_error($ch));
        }

        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Get engine url
     *
     * @param string $path
     * 
     * @return string
     */
    private function engineUrl(string $path = ''): string
    {
        return ($this->config['sandbox_mode'] ? self::SANDBOX_URL : self::PRODUCTION_URL) . $path;
    }
}
