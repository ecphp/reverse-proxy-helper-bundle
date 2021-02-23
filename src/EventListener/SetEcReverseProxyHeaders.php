<?php

declare(strict_types=1);

namespace EcPhp\EcReverseProxyBundle\EventListener;

use EcPhp\EcReverseProxyBundle\RequestAlterInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class SetEcReverseProxyHeaders
{
    private RequestAlterInterface $requestAlter;

    public function __construct(RequestAlterInterface $requestAlter)
    {
        $this->requestAlter = $requestAlter;
    }

    public function __invoke(RequestEvent $event): void
    {
        ($this->requestAlter)->alter($event->getRequest());
    }
}
