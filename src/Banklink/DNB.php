<?php

namespace Banklink;

use Banklink\Protocol\iPizza;

/**
 * Banklink implementation for DNB using iPizza protocol for communication
 * For specs see https://www.dnb.lt/sites/default/files/dokumentai/bendri/technines_salygos.pdf
 *
 * @author Evaldas Kocys <evaldas.kocys@gmail.com>
 * @since  11.11.2016
 */
class DNB extends Banklink
{
    protected $requestUrl = 'https://ib.dnb.lt/loginB2B.aspx';
    protected $testRequestUrl = 'https://www.dnb.lt/B7-DEMO/dnb-ilinija-demo/';

    /**
     * Force iPizza protocol
     *
     * @param \Banklink\Protocol\iPizza $protocol
     * @param boolean          $testMode
     * @param string | null    $requestUrl
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
     * Add Bank Code
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