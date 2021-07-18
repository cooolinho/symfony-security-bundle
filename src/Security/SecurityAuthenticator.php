<?php

namespace Cooolinho\Bundle\SecurityBundle\Security;

class SecurityAuthenticator extends AbstractAuthenticator
{
    protected function getLoginRoute(): string
    {
        return $this->parameterBag->get('cooolinho_security.route_login');
    }

    protected function getAfterLoginRoute(): string
    {
        return $this->parameterBag->get('cooolinho_security.route_after_login');
    }
}
