<?php

declare(strict_types=1);

namespace EcPhp\EcReverseProxyBundle;

use Symfony\Component\HttpFoundation\Request;

use function array_key_exists;

use const FILTER_VALIDATE_URL;

final class RequestEcReverseProxyHeadersAlter implements RequestAlterInterface
{
    /**
     * @var array<mixed>
     */
    private array $parameters;

    /**
     * @param array<mixed> $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function alter(Request $request): Request
    {
        if (false === filter_var($this->parameters['url'], FILTER_VALIDATE_URL)) {
            return $request;
        }

        if (false === $parsed = parse_url($this->parameters['url'])) {
            return $request;
        }

        $request
            ->headers
            ->add(
                array_map(
                    static fn ($key): string => (string) $parsed[$key],
                    array_filter(
                        [
                            'X-Forwarded-Host' => 'host',
                            'X-Forwarded-Proto' => 'scheme',
                            'X-Forwarded-Port' => 'port',
                            'X-Forwarded-Prefix' => 'path',
                        ],
                        static fn ($key): bool => array_key_exists($key, $parsed)
                    )
                )
            );

        return $request;
    }
}
