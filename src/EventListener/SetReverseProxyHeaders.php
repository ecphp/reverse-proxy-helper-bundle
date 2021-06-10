<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ecphp
 */

declare(strict_types=1);

namespace EcPhp\ReverseProxyHelperBundle\EventListener;

use EcPhp\ReverseProxyHelperBundle\Service\RequestAlterInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class SetReverseProxyHeaders
{
    /**
     * @var RequestAlterInterface
     */
    private $requestAlter;

    public function __construct(RequestAlterInterface $requestAlter)
    {
        $this->requestAlter = $requestAlter;
    }

    public function __invoke(RequestEvent $event): void
    {
        ($this->requestAlter)->alter($event->getRequest());
    }
}
