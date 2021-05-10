<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace EcPhp\ReverseProxyHelperBundle\Service;

use Symfony\Component\HttpFoundation\Request;

interface RequestAlterInterface
{
    public function alter(Request $request): Request;
}
