<?php

namespace Banklink\Protocol\iPizza;

/**
 * List of all services available via iPizza
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  10.01.2012
 */
abstract class Services
{
    // Requests
    public $paymentRequest      = '1001';
    public $authenticateRequest = '3001';

    // Responses
    public $paymentSuccess      = '1101';
    public $paymentAccept       = '1201';
    public $paymentCancel       = '1901';
    public $paymentError        = '1902';
    public $authenticateSuccess = '3002';

    /**
     * Fetch mandatory fields for a given service
     *
     * @param string $serviceId
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getFieldsForService($serviceId)
    {
        switch ($serviceId) {
            case $this->paymentRequest:
                return $this->getPaymentRequestFields();
            case $this->paymentSuccess:
                return $this->getPaymentSuccessFields();
            case $this->paymentCancel:
                return $this->getPaymentCancelFields();
            default:
                throw new \InvalidArgumentException('Unsupported service id: '.$serviceId);
        }
    }

    /**
     * Fetch supported payment services
     *
     * @return array
     */
    public function getPaymentServices()
    {
        return array(
            $this->paymentRequest,
            $this->paymentSuccess,
            $this->paymentCancel,
            $this->paymentError,
        );
    }

    /**
     * Fetch supported authentication services
     *
     * @return array
     */
    public function getAuthenticationServices()
    {
        return array(
            $this->authenticateRequest,
            $this->authenticateSuccess,
        );
    }

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
            Fields::DESCRIPTION
        );
    }

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
            Fields::TRANSACTION_ID,
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

    /**
     * Fetch mandatory fields for a payment cancellation
     *
     * @return array
     */
    protected function getPaymentCancelFields()
    {
        return array(
            Fields::SERVICE_ID,
            Fields::PROTOCOL_VERSION,
            Fields::SELLER_ID,
            Fields::SELLER_ID_RESPONSE,
            Fields::ORDER_ID,
            Fields::ORDER_REFERENCE,
            Fields::DESCRIPTION,
        );
    }
}