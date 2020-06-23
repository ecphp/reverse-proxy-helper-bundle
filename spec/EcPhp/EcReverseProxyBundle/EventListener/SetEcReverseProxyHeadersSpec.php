<?php

declare(strict_types=1);

namespace spec\EcPhp\EcReverseProxyBundle\EventListener;

use EcPhp\EcReverseProxyBundle\EventListener\SetEcReverseProxyHeaders;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class SetEcReverseProxyHeadersSpec extends ObjectBehavior
{
    public function it_can_alter_headers(ParameterBagInterface $parameterBag, RequestEvent $requestEvent)
    {
        $parameterBag
            ->get('ec_reverse_proxy')
            ->willReturn(
                [
                    'url' => 'https://foobar.com:123/',
                ]
            );

        $this->beConstructedWith($parameterBag);
        $request = Request::create('http://local/user');

        $requestEvent
            ->getRequest()
            ->willReturn($request);

        $this
            ->__invoke($requestEvent);

        $requestEvent
            ->getRequest()
            ->shouldHaveBeenCalledOnce();

        $parameterBag
            ->get('ec_reverse_proxy')
            ->shouldHaveBeenCalledOnce();
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SetEcReverseProxyHeaders::class);
    }

    public function let(ParameterBagInterface $parameterBag)
    {
        $this->beConstructedWith($parameterBag);
    }
}
