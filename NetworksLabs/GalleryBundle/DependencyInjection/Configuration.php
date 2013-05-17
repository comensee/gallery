<?php

namespace NetworksLabs\GalleryBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('networks_labs_gallery')->children()
                                ->variableNode('default_web_dir')->defaultValue("%kernel.root_dir%/../web")->end()
                                ->variableNode('default_upload_dir')->isRequired()->end()
                                ->arrayNode('upload_types')
                                ->useAttributeAsKey('id')
                                ->prototype('array')->children() 
                                    ->variableNode('upload_dir')->defaultValue("%kernel.root_dir%")->end()
                                    ->variableNode('upload_width')->end()
                                    ->variableNode('upload_height')->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
