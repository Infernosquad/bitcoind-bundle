<?php

namespace Infernosquad\BitcoindBundle\DependencyInjection;

use Nbobtc\Http\Driver\DriverInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('is_bitcoind');

        $rootNode
            ->children()
                ->arrayNode('drivers')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('class')
                                ->defaultValue('Nbobtc\Http\Driver\CurlDriver')
                                ->validate()
                                ->ifTrue(function($class){ return !class_exists($class); })
                                    ->thenInvalid('Class doesn\'t exist "%s"')
                                ->end()
                                ->validate()
                                ->ifTrue(function($class){ return ! new $class instanceof DriverInterface; })
                                    ->thenInvalid('Class "%s" should implement Nbobtc\Http\Driver\DriverInterface')
                                ->end()
                            ->end()
                            ->arrayNode('options')
                            ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('clients')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('dsn')->end()
                            ->scalarNode('driver')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
