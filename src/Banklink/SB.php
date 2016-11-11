<?php

namespace Banklink;

use Banklink\Protocol\iPizza;

/**
 * Banklink implementation for "Šiaulių Bankas" bank using iPizza protocol for communication
 *
 * @author Evaldas Kocys <evaldas.kocys@gmail.com>
 * @since  11.11.2016
 */
class SB extends Banklink
{
    protected $requestUrl = 'https://online.sb.lt/ib/site/ibpay/login';
    protected $testRequestUrl = 'https://pangalink.net/banklink/seb';

    /**
     * Force iPizza protocol
     *
     * @param \Banklink\Protocol\iPizza $protocol
     * @param boolean                   $testMode
     * @param string | null             $requestUrl
     */
    public function __construct(iPizza $protocol, $testMode = false, $requestUrl = null)
    {
        parent::__construct($protocol, $testMode, $requestUrl);
    }

    /**
     * @inheritDoc
     */
    protected function getEncodingField()
    {
        return 'VK_ENCODING';
    }

    /**
     * Force UTF-8 encoding
     *
     * @see Banklink::getAdditionalFields()
     *
     * @return array
     */
    protected function getAdditionalFields()
    {
        return array(
            'VK_ENCODING' => $this->requestEncoding
        );
    }
}