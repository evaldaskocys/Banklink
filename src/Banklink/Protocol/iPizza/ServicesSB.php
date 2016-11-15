<?php

namespace Banklink\Protocol\iPizza;

/**
 * List of all services available via iPizza for Siauliu Bankas
 *
 * @author Evaldas Kocys <evaldas.kocys@gmail.com>
 * @since  11.11.2016
 */
class ServicesSB extends ServicesWithPANK
{
    /**
     * Fetch mandatory fields for a successful payment
     *
     * @return array
     */
    protected function getPaymentSuccessFields()
    {
        return array(
            Fields::SERVICE_ID,
            Fields::PROTOCOL_VERSION,
            Fields::SELLER_ID,
            Fields::SELLER_ID_RESPONSE,
            Fields::ORDER_ID,
            Fields::SUM,
            Fields::CURRENCY,
            Fields::SELLER_BANK_ACC_RESPONSE,
            Fields::SELLER_NAME_RESPONSE,
            Fields::SENDER_BANK_ACC,
            Fields::SENDER_NAME,
            Fields::ORDER_REFERENCE,
            Fields::DESCRIPTION,
            Fields::TRANSACTION_DATE,
        );
    }
}