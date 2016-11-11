<?php

namespace Banklink\Protocol\iPizza;

/**
 * List of all services available via iPizza for Swedbank
 *
 * @author Evaldas Kocys <evaldas.kocys@gmail.com>
 * @since  11.11.2016
 */
class ServicesSwedbank extends Services
{
    public $paymentRequest      = '1002';

    /**
     * Fetch mandatory fields for a payment request
     *
     * @return array
     */
    protected function getPaymentRequestFields()
    {
        return array(
            Fields::SERVICE_ID,
            Fields::PROTOCOL_VERSION,
            Fields::SELLER_ID,
            Fields::ORDER_ID,
            Fields::SUM,
            Fields::CURRENCY,
            Fields::ORDER_REFERENCE,
            Fields::DESCRIPTION
        );
    }
}