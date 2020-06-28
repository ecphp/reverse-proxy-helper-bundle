<?php

declare(strict_types=1);

namespace EcPhp\EcReverseProxyBundle\EventListener;

use EcPhp\EcReverseProxyBundle\RequestAlter;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class SetEcReverseProxyHeaders
{
    /**
     * @var RequestAlter
     */
    private $requestAlter;

    public function __construct(RequestAlter $requestAlter)
    {
        $this->requestAlter = $requestAlter;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @return void
     */
    public function __invoke(RequestEvent $event)
    {
        ($this->requestAlter)($event->getRequest());
    }
}
