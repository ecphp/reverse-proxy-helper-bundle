<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use EcPhp\ReverseProxyHelperBundle\EventListener\SetReverseProxyHeaders;
use EcPhp\ReverseProxyHelperBundle\Service\RequestAlterInterface;
use EcPhp\ReverseProxyHelperBundle\Service\ReverseProxyHelperHeadersAlter;

return static function (ContainerConfigurator $container) {
    $container
        ->services()
        ->set(ReverseProxyHelperHeadersAlter::class)
        ->autowire()
        ->autoconfigure()
        ->arg('$parameters', '%reverse_proxy_helper%');

    $container
        ->services()
        ->alias(RequestAlterInterface::class, ReverseProxyHelperHeadersAlter::class);

    $container
        ->services()
        ->set(SetReverseProxyHeaders::class)
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
