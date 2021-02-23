<?php

declare(strict_types=1);

namespace EcPhp\EcReverseProxyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class EcReverseProxyExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @phpstan-ignore-next-line
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Load EC Reverse Proxy configuration.
        $container->setParameter('ec_reverse_proxy', $config);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('services.yaml');
    }
}
