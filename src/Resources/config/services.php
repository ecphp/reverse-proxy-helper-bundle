<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use EcPhp\EcReverseProxyBundle\EventListener\SetEcReverseProxyHeaders;
use EcPhp\EcReverseProxyBundle\Service\RequestAlterInterface;
use EcPhp\EcReverseProxyBundle\Service\RequestEcReverseProxyHeadersAlter;

return static function (ContainerConfigurator $container) {
    $container
        ->services()
        ->set(RequestEcReverseProxyHeadersAlter::class)
        ->autowire()
        ->autoconfigure()
        ->arg('$parameters', '%ec_reverse_proxy%');

    $container
        ->services()
        ->alias(RequestAlterInterface::class, RequestEcReverseProxyHeadersAlter::class);

    $container
        ->services()
        ->set(SetEcReverseProxyHeaders::class)
        ->autowire()
        ->autoconfigure()
        ->tag(
            'kernel.event_listener',
            [
                'event' => 'kernel.request',
                'priority' => 4096,
            ]
        );
};
