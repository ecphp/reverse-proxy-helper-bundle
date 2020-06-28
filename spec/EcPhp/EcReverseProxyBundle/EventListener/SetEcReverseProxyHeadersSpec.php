<?php

declare(strict_types=1);

namespace spec\EcPhp\EcReverseProxyBundle\EventListener;

use EcPhp\EcReverseProxyBundle\EventListener\SetEcReverseProxyHeaders;
use EcPhp\EcReverseProxyBundle\RequestAlter;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class SetEcReverseProxyHeadersSpec extends ObjectBehavior
{
    public function it_can_alter_headers(RequestAlter $requestAlter, RequestEvent $requestEvent)
    {
        $requestAlter
            ->beConstructedWith([
                'url' => 'https://foo.bar.com:123/',
            ]);

        $request = Request::create('http://local/user');

        $requestAlter
            ->__invoke($request)
            ->willReturn($request);

        $this->beConstructedWith($requestAlter);

        $requestEvent
            ->getRequest()
            ->willReturn($request);

        $this
            ->__invoke($requestEvent);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SetEcReverseProxyHeaders::class);
    }

    public function let(RequestAlter $requestAlter)
    {
        $requestAlter
            ->beConstructedWith([
                'url' => 'https://foo.bar.com:123/',
            ]);

        $this->beConstructedWith($requestAlter);
    }
}
