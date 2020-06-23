<?php

declare(strict_types=1);

namespace spec\EcPhp\EcReverseProxyBundle;

use EcPhp\EcReverseProxyBundle\RequestEcReverseProxyHeadersAlter;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;

class RequestEcReverseProxyHeadersAlterSpec extends ObjectBehavior
{
    public function it_alter_a_request_with_a_port_properly()
    {
        $request = Request::create('http://local:543/foo');
        $url = 'https://foobar.com';

        $this->beConstructedWith($request, $url);

        $this
            ->__invoke();

        $this
            ->getRequest()
            ->getUri()
            ->shouldReturn('https://foobar.com/foo');
    }

    public function it_can_alter_a_request()
    {
        $this
            ->__invoke();

        $this
            ->getRequest()
            ->getUri()
            ->shouldReturn('https://foobar.com:321/foo');
    }

    public function it_does_not_alter_a_request_if_no_uri_is_provided()
    {
        $request = Request::create('http://local/foo');
        $url = '';

        $this->beConstructedWith($request, $url);

        $this
            ->__invoke();

        $this
            ->getRequest()
            ->getUri()
            ->shouldReturn('http://local/foo');
    }

    public function it_does_not_alter_a_request_if_the_provided_url_is_invalid()
    {
        $request = Request::create('http://local/foo');
        $url = 'https://foo bar dot com';

        $this->beConstructedWith($request, $url);

        $this
            ->__invoke();

        $this
            ->getRequest()
            ->getUri()
            ->shouldReturn('http://local/foo');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RequestEcReverseProxyHeadersAlter::class);
    }

    public function let()
    {
        $request = Request::create('http://local/foo');
        $url = 'https://foobar.com:321';

        $this->beConstructedWith($request, $url);
    }
}
