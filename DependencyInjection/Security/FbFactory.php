<?php
namespace Voltash\FbApplicationBundle\DependencyInjection\Security;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

class FbFactory extends  AbstractFactory
{

    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId) {
        $providerId = 'fb.security.authentication.provider';
        $container
            ->setDefinition($providerId, new DefinitionDecorator('fb.security.authentication.provider'))
            ->replaceArgument(0, new Reference($userProviderId))
        ;
        return 'fb.security.authentication.provider';
    }

    protected function getListenerId() {
        return 'fb.security.authentication.listener';
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'fb';
    }

    public function isRememberMeAware($config)
    {
        return false;
    }
}