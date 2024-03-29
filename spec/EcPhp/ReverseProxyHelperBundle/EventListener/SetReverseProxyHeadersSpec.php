<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ecphp
 */

declare(strict_types=1);

namespace spec\EcPhp\ReverseProxyHelperBundle\EventListener;

use EcPhp\ReverseProxyHelperBundle\EventListener\SetReverseProxyHeaders;
use EcPhp\ReverseProxyHelperBundle\Service\RequestAlterInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class SetReverseProxyHeadersSpec extends ObjectBehavior
{
    public function it_can_alter_headers(RequestAlterInterface $requestAlter, RequestEvent $requestEvent)
    {
        $requestAlter
            ->beConstructedWith([
                'https://foo.bar.com:123/',
            ]);

        $request = Request::create('http://local/user');

        $requestAlter
            ->alter($request)
            ->willReturn($request);

        $this->beConstructedWith($requestAlter);

        $requestEvent
            ->getRequest()
            ->willReturn($request);

        $requestAlter
            ->alter($request)
            ->shouldBeCalled();

        $this
            ->__invoke($requestEvent);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SetReverseProxyHeaders::class);
    }

    public function let(RequestAlterInterface $requestAlter)
    {
        $requestAlter
            ->beConstructedWith([
                'https://foo.bar.com:123/',
            ]);

        $this->beConstructedWith($requestAlter);
    }
}
