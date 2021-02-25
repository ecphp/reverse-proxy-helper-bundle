<?php

declare(strict_types=1);

namespace EcPhp\ReverseProxyHelperBundle\Service;

use Symfony\Component\HttpFoundation\Request;

interface RequestAlterInterface
{
    public function alter(Request $request): Request;
}
