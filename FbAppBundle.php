<?php

namespace Voltash\FbApplicationBundle;

use Voltash\FbApplicationBundle\DependencyInjection\Security\FbFactory;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FbAppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new FbFactory());
    }
}
