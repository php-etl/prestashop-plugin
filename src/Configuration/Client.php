<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Prestashop\Configuration;

use Kiboko\Contract\Configurator\PluginConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

final class Client implements PluginConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('client');

        /* @phpstan-ignore-next-line */
        $builder->getRootNode()
            ->children()
                ->scalarNode('url')->isRequired()->end()
                ->scalarNode('api_key')->isRequired()->end()
            ->end()
        ;

        return $builder;
    }
}
