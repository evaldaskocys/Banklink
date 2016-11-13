<?php

namespace Banklink\Protocol\iPizza;

/**
 * List of all services available via iPizza for Nordea Bank
 *
 * @author Evaldas Kocys <evaldas.kocys@gmail.com>
 * @since  11.11.2016
 */
class ServicesNordea extends Services
{
    // Requests
    public $paymentRequest      = '1011';
    public $authenticateRequest = '4011';

    // Responses
    public $paymentSuccess      = '1111';
    public $paymentAccept       = '1012';
    public $paymentCancel       = '1911';
    public $paymentError        = '1911';
    public $authenticateSuccess = '3012';

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
            Fields::SELLER_NAME,
            Fields::ORDER_REFERENCE,
            Fields::DESCRIPTION,
            Fields::SUCCESS_URL,
            Fields::CANCEL_URL,
            Fields::TRANSACTION_DATETIME,
        );
    }
}