<?php

declare(strict_types=1);

namespace EcPhp\EcReverseProxyBundle;

use Symfony\Component\HttpFoundation\Request;

interface RequestAlter
{
    public function __invoke(Request $request): Request;
}
