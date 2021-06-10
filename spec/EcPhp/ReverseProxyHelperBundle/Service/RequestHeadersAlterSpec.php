<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ecphp
 */

declare(strict_types=1);

namespace spec\EcPhp\ReverseProxyHelperBundle\Service;

use EcPhp\ReverseProxyHelperBundle\Service\RequestHeadersAlter;
use InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;

class RequestHeadersAlterSpec extends ObjectBehavior
{
    public function it_alter_a_request_with_a_port()
    {
        $request = Request::create('http://local:543/foo');

        $parameters = [
            'base_url' => 'https://foobar.com:789/',
        ];

        $this->beConstructedWith($parameters);

        $this
            ->alter($request)
            ->getUri()
            ->shouldReturn('https://foobar.com:789/foo');
    }

    public function it_alter_a_request_with_a_prefix()
    {
        $request = Request::create('http://local:543/foo/index.html');

        $parameters = [
            'base_url' => 'https://foobar.com/f/b/',
        ];

        $this->beConstructedWith($parameters);

        $this
            ->alter($request)
            ->getUri()
            ->shouldReturn('https://foobar.com/f/b/foo/index.html');
    }

    public function it_can_alter_a_request()
    {
        $request = Request::create('http://local/foo');

        $parameters = [
            'base_url' => 'https://foobar.com:321',
        ];

        $this->beConstructedWith($parameters);

        $this
            ->alter($request)
            ->getUri()
            ->shouldReturn('https://foobar.com:321/foo');
    }

    public function it_does_not_alter_a_request_if_no_uri_is_provided()
    {
        $request = Request::create('http://local/foo');

        $parameters = [
            'base_url' => '',
        ];

        $this->beConstructedWith($parameters);

        $this
            ->alter($request)
            ->getUri()
            ->shouldReturn('http://local/foo');
    }

    public function it_does_not_alter_a_request_if_the_provided_url_is_invalid()
    {
        $request = Request::create('http://local/foo');

        $parameters = [
            'base_url' => 'https://foo bar dot com',
        ];

        $this->beConstructedWith($parameters);

        $this
            ->alter($request)
            ->getUri()
            ->shouldReturn('http://local/foo');
    }

    public function it_does_not_alter_the_request_if_the_provided_url_is_invalid()
    {
        $request = Request::create('http://local:543/foo');

        $parameters = [
            'base_url' => 'this-is-not-an-url',
        ];

        $this->beConstructedWith($parameters);

        $this
            ->alter($request)
            ->getUri()
            ->shouldReturn('http://local:543/foo');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RequestHeadersAlter::class);
    }

    public function it_throws_an_issue_when_it_is_unable_to_parse_an_url()
    {
        $request = Request::create('http://local:543/foo');

        $parameters = [
            'base_url' => '//:\/ /s?a=12&b=12.3.3.4:1233',
        ];

        $this->beConstructedWith($parameters);

        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->during('alter', [$request]);
    }

    public function let()
    {
        $parameters = [
            'base_url' => 'https://foobar.com:321',
        ];

        // 60 = Request::HEADER_X_FORWARDED_HOST + Request::HEADER_X_FORWARDED_PORT + Request::HEADER_X_FORWARDED_PROTO + Request::HEADER_X_FORWARDED_PREFIX
        Request::setTrustedProxies(['127.0.0.1', 'REMOTE_ADDR', '0.0.0.0'], 60);

        $this->beConstructedWith($parameters);
    }
}
