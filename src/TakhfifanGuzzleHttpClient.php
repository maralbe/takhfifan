<?php

namespace thirdparty\takhfifan;


use Exception;
use GuzzleHttp\Client;
use thirdparty\takhfifan\Exception\ApiResponseException;

/**
 * Class TakhfifanGuzzleHttpClient
 *
 * @package thirdparty\takhfifan
 */
class TakhfifanGuzzleHttpClient implements iTakhfifanApiClient
{

    /**
     * @var array
     */
    protected $default_config = [
        'track_purchase_url' => 'https://analytics.takhfifan.com/track/purchase',
        'website_url'        => 'https://www.azki.com/',
    ];
    /**
     * @var array
     */
    private $config;
    /**
     * @var Client
     */
    private $http_client;

    /**
     * TakhfifanGuzzleHttpClient constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->http_client = new Client();
    }

    /**
     * @param string $token
     * @param string $order_id
     * @param int    $amount
     * @param int    $status
     * @param bool   $is_new_customer
     * @param float  $coupon_discount
     * @param float  $tax
     * @param float  $shipping_amount
     * @return mixed
     * @throws ApiResponseException
     */
    public function trackPurchase(string $token, string $order_id, int $amount, int $status, bool $is_new_customer, float $coupon_discount, float $tax, float $shipping_amount)
    {
        return $this->postRequest($this->getConfig('track_purchase_url'), [
                'transaction_id' => $order_id,
                'revenue'        => $amount,
                'token'          => $token,
                'shipping'       => $shipping_amount,
                'tax'            => $tax,
                'discount'       => $coupon_discount,
                'new_customer'   => $is_new_customer,
                'status'         => $status,
                'affiliation'    => 'takhfifan'
            ]
        );
    }

    /**
     * @param string $key
     * @return string
     */
    public function getConfig(string $key)
    {
        return $this->config[$key] ?? $this->default_config[$key];
    }

    /**
     * @param string $url
     * @param array  $request_body
     * @return mixed
     * @throws ApiResponseException
     */
    private function postRequest(string $url, array $request_body)
    {
        $headers = [
            'Content-Type' => 'application/json'
        ];

        try {
            $response = $this->http_client->request(
                'POST',
                $url,
                [
                    'headers' => $headers,
                    'json'    => $request_body
                ]
            );

            return json_encode([
                'response' => $response->getBody()->getContents(),
                'status'   => $response->getStatusCode()
            ]);
        } catch (Exception $e) {

            throw new ApiResponseException(sprintf('Exception class : %s , Exception Message is : %s', get_class($e), $e->getMessage()));
        }
    }
}