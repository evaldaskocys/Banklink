<?php

namespace Banklink;

use Banklink\DanskeBank;
use Banklink\Protocol\iPizza;
use Banklink\Protocol\iPizza\ServicesDanske;

use Banklink\Response\PaymentResponse;

/**
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  31.10.2012
 */
class DanskeBankTest extends \PHPUnit_Framework_TestCase
{
    private $danske;

    public function setUp()
    {
        $protocol = new iPizza(
            new ServicesDanske(),
            'uid258629',
            'Test Testov',
            '119933113300',
            __DIR__.'/data/iPizza/private_key.pem',
            __DIR__.'/data/iPizza/public_key.pem',
            'http://www.google.com',
            false,
            '74000'
        );

        $this->danske = new DanskeBank($protocol);
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
            'VK_PANK'     => '74000',
            'VK_NAME'     => 'Test Testov',
            'VK_REF'      => '13',
            'VK_MSG'      => 'Test payment',
            'VK_RETURN'   => 'http://www.google.com',
            'VK_CANCEL'   => 'http://www.google.com',
            'VK_LANG'     => 'ENG',
            'VK_MAC'      => 'Q8RbtD+kp0Om2IM3fAM5M3N6Z5jPpavSQmkjwohGOxoYn4Vp10al4IEhpPJieNMB2LDcn4qDdHqnYWZPPOBExuPAvMQ7aVrznsCGh/9W/D0KzBjfc74+VOxgeEcPhNv+XreKGWrq+3+iCDmly9spK7hYhKklyxpaSj/kfZ8raMjZFk3UibmY5m+0tkJgQT2WO8TMR0/rqz/ARcSZWX4L7Cb9EPDv+5XeEAupdSeOa5hOfJRqokbNosejTqG0zBFfwe/3B5UwzMIDnj91D6Hhyv7d0W8geOVH7fiwprkh5djiOSnlRx8f2NKEF5KXFsQDthg5bv0PLi3SrpHVXwGsCQ=='
        );

        $request = $this->danske->preparePaymentRequest(1, 100, 'Test payment', 'ENG', 'EUR');

        $this->assertEquals($expectedRequestData, $request->getRequestData());
        $this->assertEquals('https://ebankas.danskebank.lt/ib/site/ibpay/login', $request->getRequestUrl());
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

        $response = $this->danske->handleResponse($responseData);

        $this->assertInstanceOf('Banklink\Response\Response', $response);
        $this->assertEquals(PaymentResponse::STATUS_ERROR, $response->getStatus());
    }
}