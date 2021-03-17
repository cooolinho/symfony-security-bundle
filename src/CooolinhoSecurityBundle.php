<?php

namespace Cooolinho\SecurityBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Cooolinho\SecurityBundle\DependencyInjection\CooolinhoSecurityExtension;

class CooolinhoSecurityBundle extends Bundle
{
    public const TRANSLATION_DOMAIN = 'security';

    public function getContainerExtension(): CooolinhoSecurityExtension
    {
        return new CooolinhoSecurityExtension();
    }
}
