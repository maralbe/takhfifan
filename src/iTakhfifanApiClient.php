<?php

namespace thirdparty\takhfifan;


/**
 * Interface iTakhfifanApiClient
 *
 * @package thirdparty\takhfifan
 */
interface iTakhfifanApiClient
{

    function trackPurchase(string $token, string $order_id, int $amount, int $status, bool $is_new_customer, float $coupon_discount, float $tax, float $shipping_amount);
}