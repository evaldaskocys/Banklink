<?php

namespace Banklink\Protocol\iPizza;

/**
 * List of all services available via iPizza for Swedbank
 *
 * @author Evaldas Kocys <evaldas.kocys@gmail.com>
 * @since  11.11.2016
 */
class ServicesWithPANK extends Services
{
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
            Fields::SELLER_BANK_ACC,
            Fields::BANK_CODE,
            Fields::SELLER_NAME,
            Fields::ORDER_REFERENCE,
            Fields::DESCRIPTION
        );
    }
}