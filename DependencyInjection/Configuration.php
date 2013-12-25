<?php

/*
 * This file is part of the XabbuhCrApiBundle package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\CrApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration tree definition for XabbuhCrApiBundle.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('xabbuh_cr_api');

        $rootNode
            ->fixXmlConfig('repository', 'repositories')
            ->children()
                ->arrayNode('repositories')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->fixXmlConfig('parameter')
                        ->children()
                            ->scalarNode('factory')
                                ->isRequired()
                            ->end()
                            ->arrayNode('parameters')
                                ->useAttributeAsKey('name')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
 