<?php

declare(strict_types=1);

namespace EcPhp\EcReverseProxyBundle\Service;

use Symfony\Component\HttpFoundation\Request;

interface RequestAlterInterface
{
    public function alter(Request $request): Request;
}
