<?php

namespace Cooolinho\Bundle\SecurityBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const ROUTE_LOGIN = 'route_login';
    public const ROUTE_AFTER_LOGIN = 'route_after_login';
    public const ROUTE_LOGOUT = 'route_logout';
    public const MAILER_FROM = 'mailer_from';
    public const MAILER_NAME = 'mailer_name';

    public static $all = [
        self::ROUTE_LOGIN,
        self::ROUTE_AFTER_LOGIN,
        self::ROUTE_LOGOUT,
        self::MAILER_FROM,
        self::MAILER_NAME,
    ];

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('cooolinho_security');

        $treeBuilder->getRootNode()
            ->children()
            ->scalarNode(self::ROUTE_LOGIN)
            ->defaultValue('app_login')
            ->end()
            ->scalarNode(self::ROUTE_AFTER_LOGIN)
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode(self::ROUTE_LOGOUT)
            ->defaultValue('app_logout')
            ->end()
            ->scalarNode(self::MAILER_FROM)
            ->defaultValue('test@localhost')
            ->end()
            ->scalarNode(self::MAILER_NAME)
            ->defaultValue('Localhost Mailbot')
            ->end()
            ->end();

        return $treeBuilder;
    }
}
