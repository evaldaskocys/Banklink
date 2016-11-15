<?php

namespace Banklink;

use Banklink\SB;
use Banklink\Protocol\iPizza;
use Banklink\Protocol\iPizza\ServicesNoTransactionId;

use Banklink\Response\PaymentResponse;

/**
 * @author Evaldas Kocys <evaldas.kocys@gmail.com>
 * @since  11.11.2016
 */
class SBTest extends \PHPUnit_Framework_TestCase
{
    private $sb;

    public function setUp()
    {
        $protocol = new iPizza(
            new ServicesNoTransactionId(),
            'uid258629',
            'Test Testov',
            '119933113300',
            __DIR__.'/data/iPizza/private_key.pem',
            __DIR__.'/data/iPizza/public_key.pem',
            'http://www.google.com',
            false,
            '71800'
        );

        $this->sb = new SB($protocol);
    }

    public function testPreparePaymentRequest()
    {
        $expectedRequestData = array(
            'VK_SERVICE'  => '1001',
            'VK_VERSION'  => '008',
            'VK_SND_ID'   => 'uid258629',
            'VK_STAMP'    => '1',
            'VK_AMOUNT'   => '100',
            'VK_CURR'     => 'EUR',
            'VK_ACC'      => '119933113300',
            'VK_PANK'     => '71800',
            'VK_NAME'     => 'Test Testov',
            'VK_REF'      => '13',
            'VK_MSG'      => 'Test payment',
            'VK_RETURN'   => 'http://www.google.com',
            'VK_CANCEL'   => 'http://www.google.com',
            'VK_LANG'     => 'ENG',
            'VK_ENCODING' => 'UTF-8',
            'VK_MAC'      => 'NfsajWaoC11qmp54aZF1U3jpOy5utmLcpKmVscFhHLuF1uOIsO3/tdGmDgy6jZeaEmF4UWD+ed+KRaXUpoFMcLADdztWOW5N7ro1P53Wixj1HqDeJGVi98vUkBvHxRnx98xL7golZ8JXD9YueJBDytetooVo8AYinFL48c4fM1i6qM1jKCh02tfCQkKRKbSpQoeym8Ikj+45b/G9ZfvtoyLCO/R9WholedaaIyr7srN9L6Ym88DFbEsDqPTGFkTdYsdhPewlAWZm3BEZ6RIJUa6d8Zljka2Gnb0jmqZ8WT9gVtvI6O3NNRmmXjlUah7d9ZmGPVbLwCg7Z4IXMVsVqw=='
        );

        $request = $this->sb->preparePaymentRequest(1, 100, 'Test payment', 'ENG', 'EUR');

        $this->assertEquals($expectedRequestData, $request->getRequestData());
        $this->assertEquals('https://online.sb.lt/ib/site/ibpay/login', $request->getRequestUrl());
    }

    public function testHandlePaymentResponseError()
    {
        $responseData = array(
            'VK_SERVICE'  => '1101',
            'VK_VERSION'  => '008',
            'VK_SND_ID'   => 'GENIPIZZA',
            'VK_REC_ID'   => 'uid258629',
            'VK_STAMP'    => '1',
            'VK_T_NO'     => '18193',
            'VK_AMOUNT'   => '100',
            'VK_CURR'     => 'EUR',
            'VK_REC_ACC'  => '119933113300',
            'VK_REC_NAME' => 'Test Testov',
            'VK_REF'      => '13',
            'VK_MSG'      => 'Test payment',
            'VK_T_DATE'   => '06.11.2012',
            'VK_AUTO'     => 'N',
            'VK_ENCODING' => 'ISO-8859-1',
            'VK_SND_NAME' => mb_convert_encoding('Tõõger Leõpäöld', 'ISO-8859-1', 'UTF-8'),
            'VK_SND_ACC'  => '221234567897',
            'VK_MAC'      => 'eK4mEiRhpZ/gz1/4GEaNwvX+AhfpaTJOQRGdWky4Cb6Gqubn3pgSDeApdcccu+WMrAX1ozzx3H/kEzIHn2NT3mFDUHNkEnOlx7OFgNZY+Wvypz18GCYyW/QIsNi/dk3HTzAymU6rVhGSi9v9OkogASRrSn6OMnFofa+WIwvnHJzHCZ8uY37NSERHv+FcT7CGoHHgU5+3hjEAWsXkX4TRDfrWvzsb/tkDaJbNv0KHo+WjcPHL/rBVIoexZpahaf4z4f1g6DfH6LOOgvwbjJZ3JEHNvE+DM5bY58Asn8MxOayYJ3hZ39J0hdepO+2+YUdkqPPxyJIvufXeoaGtsu0AYQ=='
        );

        $response = $this->sb->handleResponse($responseData);

        $this->assertInstanceOf('Banklink\Response\Response', $response);
        $this->assertEquals(PaymentResponse::STATUS_ERROR, $response->getStatus());
    }
}