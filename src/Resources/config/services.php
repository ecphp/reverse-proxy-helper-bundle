<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use EcPhp\ReverseProxyHelperBundle\EventListener\SetReverseProxyHeaders;
use EcPhp\ReverseProxyHelperBundle\Service\RequestAlterInterface;
use EcPhp\ReverseProxyHelperBundle\Service\RequestHeadersAlter;

return static function (ContainerConfigurator $container) {
    $container
        ->services()
        ->set(RequestHeadersAlter::class)
        ->autowire()
        ->autoconfigure()
        ->arg('$parameters', '%reverse_proxy_helper%');

    $container
        ->services()
        ->alias(RequestAlterInterface::class, RequestHeadersAlter::class);

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
