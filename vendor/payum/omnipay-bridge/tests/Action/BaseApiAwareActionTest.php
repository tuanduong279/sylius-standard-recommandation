<?php
namespace Payum\OmnipayBridge\Tests\Action;

class BaseApiAwareActionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldImplementActionInterface()
    {
        $rc = new \ReflectionClass('Payum\OmnipayBridge\Action\BaseApiAwareAction');

        $this->assertTrue($rc->isSubclassOf('Payum\Core\Action\ActionInterface'));
    }

    /**
     * @test
     */
    public function shouldImplementApiAwareInterface()
    {
        $rc = new \ReflectionClass('Payum\OmnipayBridge\Action\BaseApiAwareAction');

        $this->assertTrue($rc->isSubclassOf('Payum\Core\ApiAwareInterface'));
    }

    /**
     * @test
     */
    public function shouldBeAbstract()
    {
        $rc = new \ReflectionClass('Payum\OmnipayBridge\Action\BaseApiAwareAction');

        $this->assertTrue($rc->isAbstract());
    }

    /**
     * @test
     */
    public function shouldAllowSetApi()
    {
        $expectedApi = $this->createGatewayMock();

        $action = $this->getMockForAbstractClass('Payum\OmnipayBridge\Action\BaseApiAwareAction');

        $action->setApi($expectedApi);

        $this->assertAttributeSame($expectedApi, 'omnipayGateway', $action);
    }

    /**
     * @test
     *
     * @expectedException \Payum\Core\Exception\UnsupportedApiException
     */
    public function throwIfUnsupportedApiGiven()
    {
        $action = $this->getMockForAbstractClass('Payum\OmnipayBridge\Action\BaseApiAwareAction');

        $action->setApi(new \stdClass);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Omnipay\Common\GatewayInterface
     */
    protected function createGatewayMock()
    {
        return $this->getMock('Omnipay\Common\GatewayInterface');
    }
}