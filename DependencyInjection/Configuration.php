<?php

namespace Voltash\FbApplicationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('fb_app');

        $rootNode
            ->children()
                ->scalarNode('scope')->end()
                ->arrayNode('app')
                    ->children()
                        ->scalarNode('appId')->end()
                        ->scalarNode('secret')->end()
                    ->end()
                ->end()
                ->scalarNode('user_class')->end()
                ->arrayNode('fan')
                    ->children()
                        ->scalarNode('fanOnly')->end()
                        ->scalarNode('nonFanRoute')->end()
                    ->end()
                ->end()
                ->arrayNode('page')
                    ->children()
                        ->scalarNode('fan_page')->end()
                        ->scalarNode('fan_page_url')->end()
                        ->scalarNode('canvas_page_url')->end()
                        ->booleanNode('canvas')->end()
                    ->end()
                ->end()
            ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
