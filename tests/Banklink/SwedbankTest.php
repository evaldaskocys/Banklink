<?php

namespace Banklink;

use Banklink\Swedbank;
use Banklink\Protocol\iPizza;
use Banklink\Protocol\iPizza\ServicesSwedbank;

use Banklink\Response\PaymentResponse;

/**
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  31.10.2012
 */
class SwedbankTest extends \PHPUnit_Framework_TestCase
{
    private $swedbank;

    public function setUp()
    {
        $protocol = new iPizza(
            new ServicesSwedbank(),
            'uid258629',
            'Test Testov',
            '119933113300',
            __DIR__.'/data/iPizza/private_key.pem',
            __DIR__.'/data/iPizza/public_key.pem',
            'http://www.google.com',
            true
        );

        $this->swedbank = new Swedbank($protocol);
    }

    public function testPreparePaymentRequest()
    {
        $expectedRequestData = array(
          'VK_SERVICE'  => '1002',
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
          'VK_LANG'     => 'ENG',
          'VK_ENCODING' => 'UTF-8',
          'VK_MAC'      => 'PnO6qoTfgkb0iHZ/Qfb3Zs+csx+YIwV6Vv8q0ZoDqDh18Y2DGFXVVMgGggpM5fkPViGQZdSRP8rXA5SyLvIiQ0uALeIELafyEzPKnVuP1qMaJ24pMiH4Tc5zirJOLSolUhKAEKKipoR6MprlaN8JQIuKiUwIFAfBxGusEtZ8u/pwULRH4eOSmefa8xZPA8DapK0QM84jg3klxhNdsLkDctbgi5vNtlV7rj6HwA1XnBTWTd9a0CvU9y/Y9sx0g2XyKdK6nmURgwl7+VBkCu20SsiS3tBv11pI+OKtNNTYsvSGqlvl8z1PEJzWlUYGu4u5kEk0SPAvJPAQvIGbsE9jMw=='
        );

        $request = $this->swedbank->preparePaymentRequest(1, 100, 'Test payment', 'ENG', 'EUR');

        $this->assertEquals($expectedRequestData, $request->getRequestData());
        $this->assertEquals('https://ib.swedbank.lt/banklink', $request->getRequestUrl());
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

        $response = $this->swedbank->handleResponse($responseData);

        $this->assertInstanceOf('Banklink\Response\Response', $response);
        $this->assertEquals(PaymentResponse::STATUS_SUCCESS, $response->getStatus());
    }
}