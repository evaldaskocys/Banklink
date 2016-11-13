<?php

namespace Banklink;

use Banklink\NordeaIPizza;
use Banklink\Protocol\iPizza;
use Banklink\Protocol\iPizza\ServicesNordea;

use Banklink\Response\PaymentResponse;

/**
 * @author Evaldas Kocys <evaldas.kocys@gmail.com>
 * @since  13.11.2016
 */
class NordeaIPizzaTest extends \PHPUnit_Framework_TestCase
{
    private $nordea;

    public function setUp()
    {
        $protocol = new iPizza(
            new ServicesNordea(),
            'uid258629',
            'Test Testov',
            '119933113300',
            __DIR__.'/data/iPizza/private_key.pem',
            __DIR__.'/data/iPizza/public_key.pem',
            'http://www.google.com',
            true,
            null,
            new \DateTime('2016-11-13 16:16:29')
        );

        $this->nordea = new NordeaIPizza($protocol);
    }

    public function testPreparePaymentRequest()
    {
        $expectedRequestData = array(
          'VK_SERVICE'  => '1011',
          'VK_VERSION'  => '008',
          'VK_SND_ID'   => 'uid258629',
          'VK_STAMP'    => '1',
          'VK_AMOUNT'   => '100',
          'VK_CURR'     => 'EUR',
          'VK_ACC'      => '119933113300',
          'VK_NAME'     => 'Test Testov',
          'VK_REF'      => '13',
          'VK_MSG'      => 'Test payment',
          'VK_RETURN'   => 'http://www.google.com',
          'VK_CANCEL'   => 'http://www.google.com',
          'VK_DATETIME' => '2016-11-13T16:16:29+0200',
          'VK_LANG'     => 'ENG',
          'VK_ENCODING' => 'UTF-8',
          'VK_MAC'      => 'CuGFwPrJDAJyIFBjcJem0PXhDSqrrcvJ/D9B1+r9w2+iKz3GYdhA8/HkRJzqHxmLh+p4kNzOioO5Xl6WPNwukKa3BCos5OW8abUIRZ5/CTI7OH3sdKqt31mikbSd/oPkkMx41fW65qrEnD56G7MtyLxwCmJNDrdbbMjojsFozmsxVXSw1JAua6m4hhDxei3yv7lsovcd3i/xoG3/KHuEzcncwAJYxX5MslCouZM394KU95JYnAWrcRsKr6iV0XUu1cijdGRpyXZP9PqKlz/SRna0BdzG+zDZz/DhXmXTRHFppFTECU/yjRUxfUwujIym67Bc2YdhLtMnAuAlbcu2Mg=='
        );

        $request = $this->nordea->preparePaymentRequest(1, 100, 'Test payment', 'ENG', 'EUR');

        $this->assertEquals($expectedRequestData, $request->getRequestData());
        $this->assertEquals('https://netbank.nordea.com/pnbepay/epayn.jsp', $request->getRequestUrl());
    }

    /*public function testHandlePaymentResponseSuccessWithSpecialCharacters()
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

        $response = $this->swedbank->handleResponse($responseData);

        $this->assertInstanceOf('Banklink\Response\Response', $response);
        $this->assertEquals(PaymentResponse::STATUS_SUCCESS, $response->getStatus());
    }*/
}