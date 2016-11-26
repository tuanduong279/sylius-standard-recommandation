<?php
namespace Payum\OmnipayBridge\Action;

use Omnipay\Common\GatewayInterface;

use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\UnsupportedApiException;

abstract class BaseApiAwareAction implements ActionInterface, ApiAwareInterface
{
    /**
     * @var GatewayInterface
     */
    protected $omnipayGateway;

    /**
     * {@inheritDoc}
     */
    public function setApi($api)
    {
        if (false == $api instanceof GatewayInterface) {
            throw new UnsupportedApiException('Not supported.');
        }
        
        $this->omnipayGateway = $api;
    }
}