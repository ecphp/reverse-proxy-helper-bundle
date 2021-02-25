<?php

declare(strict_types=1);

namespace EcPhp\ReverseProxyHelperBundle\EventListener;

use EcPhp\ReverseProxyHelperBundle\Service\RequestAlterInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class SetReverseProxyHeaders
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