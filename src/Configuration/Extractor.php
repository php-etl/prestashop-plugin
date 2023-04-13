<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Prestashop\Configuration;

use Kiboko\Contract\Configurator\PluginConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

final class Extractor implements PluginConfigurationInterface
{
    private static array $endpoints = [
        'categories' => [
            'all',
            'get',
        ],
        'combinations' => [
            'all',
            'get',
        ],
        'manufacturers' => [
            'all',
            'get',
        ],
        'product_features' => [
            'all',
            'get',
        ],
        'product_feature_values' => [
            'all',
            'get',
        ],
        'product_options' => [
            'all',
            'get',
        ],
        'product_option_values' => [
            'all',
            'get',
        ],
        'products' => [
            'all',
            'get',
        ],
        'shops' => [
            'all',
            'get',
        ],
        'stock_availables' => [
            'all',
            'get',
        ],
        'suppliers' => [
            'all',
            'get',
        ],
        'tax_rule_groups' => [
            'all',
            'get',
        ],
        'tax_rules' => [
            'all',
            'get',
        ],
    ];

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('extractor');

        /* @phpstan-ignore-next-line */
        $builder->getRootNode()
            ->children()
                ->scalarNode('type')
                ->isRequired()
                    ->validate()
                        ->ifNotInArray(array_keys(self::$endpoints))
                        ->thenInvalid(
                            sprintf(
                                'the value should be one of [%s], got %%s',
                                implode(', ', array_keys(self::$endpoints))
                            )
                        )
                    ->end()
                ->end()
                ->scalarNode('method')->isRequired()->end()
                ->arrayNode('options')
                    ->children()
                        ->scalarNode('display')->end()
                        ->arrayNode('filter')
                            ->useAttributeAsKey('key')
                            ->scalarPrototype()->end()
                        ->end()
                        ->scalarNode('sort')->end()
                        ->scalarNode('limit')->end()
                        ->scalarNode('id_shop')->end()
                        ->scalarNode('id_group_shop')->end()
                        ->scalarNode('language')->end()
                        ->scalarNode('date')->end()
                        ->arrayNode('price')
                            ->useAttributeAsKey('key')
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('country')->end()
                                        ->scalarNode('state')->end()
                                        ->scalarNode('postcode')->end()
                                        ->scalarNode('currency')->end()
                                        ->scalarNode('group')->end()
                                        ->scalarNode('quantity')->end()
                                        ->scalarNode('product_attribute')->end()
                                        ->scalarNode('decimals')->end()
                                        ->scalarNode('use_tax')->end()
                                        ->scalarNode('use_reduction')->end()
                                        ->scalarNode('only_reduction')->end()
                                        ->scalarNode('use_ecotax')->end()
                                    ->end()
                            ->end()
                        ->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}
