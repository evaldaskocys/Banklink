<?php

namespace Banklink;

use Banklink\DNB;
use Banklink\Protocol\iPizza;
use Banklink\Protocol\iPizza\ServicesDNB;

use Banklink\Response\PaymentResponse;

/**
 * @author Evaldas Kocys <evaldas.kocys@gmail.com>
 * @since  11.11.2016
 */
class DNBTest extends \PHPUnit_Framework_TestCase
{
    private $dnb;

    public function setUp()
    {
        $protocol = new iPizza(
            new ServicesDNB(),
            'uid258629',
            'Test Testov',
            '119933113300',
            __DIR__.'/data/iPizza/private_key.pem',
            __DIR__.'/data/iPizza/public_key.pem',
            'http://www.google.com',
            true,
            '40100'
        );

        $this->dnb = new DNB($protocol);
    }

    public function testPreparePaymentRequest()
    {
        $expectedRequestData = array(
          'VK_SERVICE'  => '2001',
          'VK_VERSION'  => '008',
          'VK_SND_ID'   => 'uid258629',
          'VK_STAMP'    => '1',
          'VK_AMOUNT'   => '100',
          'VK_CURR'     => 'EUR',
          'VK_ACC'      => '119933113300',
          'VK_PANK'     => '40100',
          'VK_NAME'     => 'Test Testov',
          'VK_REF'      => '13',
          'VK_MSG'      => 'Test payment',
          'VK_RETURN'   => 'http://www.google.com',
          'VK_CANCEL'   => 'http://www.google.com',
          'VK_LANG'     => 'ENG',
          'VK_ENCODING' => 'UTF-8',
          'VK_MAC'      => 'pi9opY+urApD2tNL5u2tWFl6JSLACbL1RvT/FjYbtLEpwLBg1HbnO7M82jrqO0PCg+FrRe6jPNLkMWZqCuv922iQg3HqZaNHiqpfNXOTiz9ct77AWoRN7cqtG+lMIYX9EalDTaB8HezK1p6XjVRB55nW0rUPhFfv0oeiA4vZ5UTl+IkZn1PpCMfvFEGW0XEMg9aeDB3rJMqIXU/AWURIMxxVehpwo/Q5Lt9eANPP+LnxqtJINh83QlWP2tRoAgOdtY9QfzYM6GMFGnbxLrQ009IL6aymOHdJmdoc8WnrAi+YXz4hOdiMxoYIuweSK11raG4FsJol0PU23zTgZfxfJA=='
        );

        $request = $this->dnb->preparePaymentRequest(1, 100, 'Test payment', 'ENG', 'EUR');

        $this->assertEquals($expectedRequestData, $request->getRequestData());
        $this->assertEquals('https://ib.dnb.lt/loginB2B.aspx', $request->getRequestUrl());
    }

    public function testHandlePaymentResponseSuccessWithSpecialCharacters()
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

        $response = $this->dnb->handleResponse($responseData);

        $this->assertInstanceOf('Banklink\Response\Response', $response);
        $this->assertEquals(PaymentResponse::STATUS_SUCCESS, $response->getStatus());
    }
}