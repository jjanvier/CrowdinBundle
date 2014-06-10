<?php

/*
 * This file is part of the Crowdin package.
 *
 * (c) Julien Janvier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jjanvier\Bundle\CrowdinBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('jjanvier_crowdin');

        $rootNode
            ->children()
                ->arrayNode('crowdin')
                    ->children()
                        ->scalarNode('api_key')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('project_identifier')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('archive')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('path')->isRequired()->defaultValue('/tmp/crowdin')->cannotBeEmpty()->end()
                        ->booleanNode('clean')->isRequired()->defaultFalse()->cannotBeEmpty()->end()
                        ->scalarNode('header')->isRequired()->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('project')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('path')->defaultNull()->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
