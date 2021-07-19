<?php

namespace Cooolinho\Bundle\SecurityBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class CooolinhoSecurityExtension extends Extension
{
    public const ALIAS = 'cooolinho_security';

    /**
     * @param array $configs
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $locator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new YamlFileLoader(
            $container,
            $locator
        );

        $loader->load('services.yaml');

        foreach (Configuration::$all as $configKey) {
            $container->setParameter($this->getAlias() . '.' . $configKey, $config[$configKey]);
        }
    }

    public function getAlias(): string
    {
        return self::ALIAS;
    }
}
