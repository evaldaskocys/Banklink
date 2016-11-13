<?php

namespace Banklink;

use Banklink\Protocol\iPizza;

/**
 * Banklink implementation for Nordea using iPizza protocol for communication
 * For specs see http://www.nordea.ee/sitemod/upload/root/content/nordea_ee_uk/eeen_corporate/eeen_co_igapaevapangandus_pr/maksete_kogumine/e-makse_teh_kirj_eng.pdf
 *
 * @author Evaldas Kocys <evaldas.kocys@gmail.com>
 * @since  13.11.2016
 */
class NordeaIPizza extends Banklink
{
    protected $requestUrl = 'https://netbank.nordea.com/pnbepay/epayn.jsp';
    protected $testRequestUrl = 'https://netbank.nordea.com/pnbepaytest/epayp.jsp';

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