<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\EcPhp\ReverseProxyHelperBundle\DependencyInjection;

use EcPhp\ReverseProxyHelperBundle\DependencyInjection\ReverseProxyHelperExtension;
use EcPhp\ReverseProxyHelperBundle\EventListener\SetReverseProxyHeaders;
use EcPhp\ReverseProxyHelperBundle\Service\RequestAlterInterface;
use EcPhp\ReverseProxyHelperBundle\Service\RequestHeadersAlter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ReverseProxyHelperExtensionSpec extends ObjectBehavior
{
    public function it_is_initializable(ContainerBuilder $containerBuilder)
    {
        $this->shouldHaveType(ReverseProxyHelperExtension::class);
    }

    public function it_load_the_extension(ContainerBuilder $containerBuilder)
    {
        $containerBuilder
            ->setDefinition(
                SetReverseProxyHeaders::class,
                Argument::type(Definition::class)
            )
            ->shouldBeCalled();

        $containerBuilder
            ->removeBindings(
                SetReverseProxyHeaders::class
            )
            ->shouldBeCalled();

        $containerBuilder
            ->setAlias(
                RequestAlterInterface::class,
                Argument::type(Alias::class)
            )
            ->shouldBeCalled();

        $containerBuilder
            ->setDefinition(
                RequestHeadersAlter::class,
                Argument::type(Definition::class)
            )
            ->shouldBeCalled();

        $containerBuilder
            ->removeBindings(RequestHeadersAlter::class)
            ->shouldBeCalled();

        $containerBuilder
            ->setParameter(
                'reverse_proxy_helper',
                Argument::any()
            )
            ->shouldBeCalled();

        $containerBuilder
            ->fileExists(Argument::any())
            ->willReturn(true);

        $this
            ->load([], $containerBuilder);
    }
}
