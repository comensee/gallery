<?php

namespace NetworksLabs\CsvReaderBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('networks_labs_csv_reader')->children()
                                ->variableNode('default_upload_dir')->isRequired()->end()
                                ->arrayNode('uploads')
                                ->useAttributeAsKey('id')
                                ->prototype('array')->children() 
                                    ->variableNode('inputs')->isRequired()->end();

        


        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
