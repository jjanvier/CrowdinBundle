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
                        ->scalarNode('path')->defaultValue('/tmp/crowdin')->cannotBeEmpty()->end()
                        ->booleanNode('clean')->defaultFalse()->cannotBeEmpty()->end()
                        ->scalarNode('header')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('project')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('path')->defaultValue('/tmp/crowdin')->cannotBeEmpty()->end()
                        ->scalarNode('default_locale')->defaultValue('en')->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('git')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('branch_prefix')->defaultValue('crowdin')->cannotBeEmpty()->end()
                        ->scalarNode('commit_message')->defaultValue('Updating translations from Crowdin')->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('github')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('username')->cannotBeEmpty()->end()
                        ->scalarNode('email')->cannotBeEmpty()->end()
                        ->scalarNode('token')->cannotBeEmpty()->end()
                        ->scalarNode('organization')->cannotBeEmpty()->end()
                        ->scalarNode('project')->cannotBeEmpty()->end()
                        ->scalarNode('origin_branch')->defaultValue('master')->cannotBeEmpty()->end()
                        ->scalarNode('pr_title')->defaultValue('[AUTO] Updating translations from Crowdin')->cannotBeEmpty()->end()
                        ->scalarNode('pr_message')->defaultValue('')->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
