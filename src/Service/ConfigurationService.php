<?php

namespace Cooolinho\Bundle\SecurityBundle\Service;

use Cooolinho\Bundle\SecurityBundle\DependencyInjection\Configuration;
use Cooolinho\Bundle\SecurityBundle\DependencyInjection\CooolinhoSecurityExtension;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ConfigurationService
{
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function getRouteAfterLogin(): string
    {
        return $this->getConfigurationValue(Configuration::ROUTE_AFTER_LOGIN);
    }

    private function getConfigurationValue(string $key)
    {
        return $this->parameterBag->get(CooolinhoSecurityExtension::ALIAS . '.' . $key);
    }

    public function getUserClass(): string
    {
        return trim($this->getConfigurationValue(Configuration::USER_CLASS));
    }

    public function isRegistrationEnabled(): bool
    {
        return (bool)$this->getConfigurationValue(Configuration::REGISTRATION_ENABLED);
    }

    public function getRegistrationForm(): string
    {
        return trim($this->getConfigurationValue(Configuration::REGISTRATION_FORM));
    }

    public function getRouteLogin(): string
    {
        return trim($this->getConfigurationValue(Configuration::ROUTE_LOGIN));
    }

    public function getRouteLogout(): string
    {
        return trim($this->getConfigurationValue(Configuration::ROUTE_LOGOUT));
    }

    public function getMailerFrom(): string
    {
        return trim($this->getConfigurationValue(Configuration::MAILER_FROM));
    }

    public function getMailerName(): string
    {
        return trim($this->getConfigurationValue(Configuration::MAILER_NAME));
    }

    public function getLoginProviderProperty(): string
    {
        return trim($this->getConfigurationValue(Configuration::LOGIN_PROVIDER_PROPERTY));
    }

    public function getRouteAfterRegistration(): string
    {
        return trim($this->getConfigurationValue(Configuration::ROUTE_AFTER_REGISTRATION));
    }
}