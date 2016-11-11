<?php

namespace Banklink\Protocol\iPizza;

/**
 * List of all services available via iPizza for Swedbank
 *
 * @author Evaldas Kocys <evaldas.kocys@gmail.com>
 * @since  11.11.2016
 */
class ServicesDNB extends ServicesWithPANK
{
    public $paymentRequest      = '2001';
}