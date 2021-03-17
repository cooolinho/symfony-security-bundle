<?php

namespace Cooolinho\Bundle\SecurityBundle;

use Cooolinho\Bundle\SecurityBundle\DependencyInjection\CooolinhoSecurityExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CooolinhoSecurityBundle extends Bundle
{
    public const TRANSLATION_DOMAIN = 'security';

    public function getContainerExtension(): CooolinhoSecurityExtension
    {
        return new CooolinhoSecurityExtension();
    }
}
