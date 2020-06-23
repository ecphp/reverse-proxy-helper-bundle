<?php

declare(strict_types=1);

namespace EcPhp\EcReverseProxyBundle\EventListener;

use EcPhp\EcReverseProxyBundle\RequestEcReverseProxyHeadersAlter;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class SetEcReverseProxyHeaders
{
    /**
     * @var \Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface
     */
    private $parameters;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameters = $parameterBag;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @return void
     */
    public function __invoke(RequestEvent $event)
    {
        $configuration = $this->parameters->get('ec_reverse_proxy');

        (new RequestEcReverseProxyHeadersAlter(
            $event->getRequest(),
            (string) $configuration['url']
        ))();
    }
}
