<?php

namespace JHV\Payment\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class JHVPaymentCoreExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services/listeners.yml');
        $loader->load('services/services.yml');
        
        // Conexão
        $connection = $config['connection'];
        $container->setParameter('jhv_payment_core.parameter.connection_name', $connection);
        
        // Classes
        $classes = $config['classes'];
        
        // Definindo genereciador de pagamentos
        $container->setParameter('jhv_payment_core.parameter.payment_manager.class', $classes['payment_manager']);
        unset($classes['payment_manager']);
        
        // Definindo entity classes
        $container->setParameter('jhv_payment_core.parameter.entity_classes', $classes);
        
        // Listeners
        $container->setParameter('jhv_payment_core.parameter.listener.operation.class', $config['listeners']['operation']);
        $container->setParameter('jhv_payment_core.parameter.listener.transaction.class', $config['listeners']['transaction']);
    }
}
