<?php

namespace JHV\Payment\CoreBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('jhv_payment_core');

        $rootNode
            ->children()
                
                // Conexão
                ->scalarNode('connection')->defaultValue('default')->end()
                
                // Classes
                ->arrayNode('classes')
                    ->children()
                        ->scalarNode('credit')->cannotBeEmpty()->isRequired()->end()
                        ->scalarNode('debit')->cannotBeEmpty()->isRequired()->end()
                        ->scalarNode('instruction')->cannotBeEmpty()->isRequired()->end()
                        ->scalarNode('transaction')->cannotBeEmpty()->isRequired()->end()
                        ->scalarNode('payment_manager')->defaultValue('JHV\\Payment\\CoreBundle\\Manager\\PaymentManager')->end()
                        ->scalarNode('result')->defaultValue('JHV\\Payment\\CoreBundle\\Operator\\Connection\\Result')->end()
                    ->end()
                ->end()
                
                // Listeners
                ->arrayNode('listeners')
                    ->children()
                        ->scalarNode('operation')->defaultValue('JHV\\Payment\\CoreBundle\\Listener\\OperationListener')->end()
                        ->scalarNode('transaction')->defaultValue('JHV\\Payment\\CoreBundle\\Listener\\TransactionListener')->end()
                    ->end()
                ->end()
                
            ->end()
        ;

        return $treeBuilder;
    }
}
