<?php
namespace Payum\Bundle\PayumBundle\DependencyInjection\Factory\Gateway;

/**
 * @deprecated  since 1.2 and will be removed in 2.0
 */
class OfflineGatewayFactory extends AbstractGatewayFactory
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'offline';
    }

    /**
     * {@inheritDoc}
     */
    protected function getPayumGatewayFactoryClass()
    {
        return 'Payum\Offline\OfflineGatewayFactory';
    }

    /**
     * {@inheritDoc}
     */
    protected function getComposerPackage()
    {
        return 'payum/offline';
    }
}