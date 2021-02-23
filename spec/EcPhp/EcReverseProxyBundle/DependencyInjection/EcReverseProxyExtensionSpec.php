<?php

declare(strict_types=1);

namespace spec\EcPhp\EcReverseProxyBundle\DependencyInjection;

use EcPhp\EcReverseProxyBundle\DependencyInjection\EcReverseProxyExtension;
use EcPhp\EcReverseProxyBundle\EventListener\SetEcReverseProxyHeaders;
use EcPhp\EcReverseProxyBundle\Service\RequestAlterInterface;
use EcPhp\EcReverseProxyBundle\Service\RequestEcReverseProxyHeadersAlter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class EcReverseProxyExtensionSpec extends ObjectBehavior
{
    public function it_is_initializable(ContainerBuilder $containerBuilder)
    {
        $this->shouldHaveType(EcReverseProxyExtension::class);
    }

    public function it_load_the_extension(ContainerBuilder $containerBuilder)
    {
        $containerBuilder
            ->setDefinition(
                SetEcReverseProxyHeaders::class,
                Argument::type(Definition::class)
            )
            ->shouldBeCalled();

        $containerBuilder
            ->removeBindings(
                SetEcReverseProxyHeaders::class
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
                RequestEcReverseProxyHeadersAlter::class,
                Argument::type(Definition::class)
            )
            ->shouldBeCalled();

        $containerBuilder
            ->removeBindings(RequestEcReverseProxyHeadersAlter::class)
            ->shouldBeCalled();

        $containerBuilder
            ->setParameter(
                'ec_reverse_proxy',
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
