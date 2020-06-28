<?php

declare(strict_types=1);

namespace EcPhp\EcReverseProxyBundle;

use Symfony\Component\HttpFoundation\Request;

use function array_key_exists;

use const FILTER_VALIDATE_URL;

final class RequestEcReverseProxyHeadersAlter implements RequestAlter
{
    /**
     * @var array<mixed>
     */
    private $parameters;

    /**
     * RequestEcReverseProxyHeadersAlter constructor.
     *
     * @param array<mixed> $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return Request
     */
    public function __invoke(Request $request): Request
    {
        if ('' === $this->parameters['url']) {
            return $request;
        }

        if (false === filter_var($this->parameters['url'], FILTER_VALIDATE_URL)) {
            return $request;
        }

        $request::setTrustedProxies(
            ['127.0.0.1', 'REMOTE_ADDR'],
            Request::HEADER_X_FORWARDED_ALL
        );

        $request->headers->set('X-Forwarded-Port', '');

        $headertoKeyMapping = [
            'X-Forwarded-Host' => 'host',
            'X-Forwarded-Proto' => 'scheme',
            'X-Forwarded-Port' => 'port',
        ];

        $parsed = parse_url($this->parameters['url']);

        if (false === $parsed) {
            return $request;
        }

        foreach ($headertoKeyMapping as $header => $key) {
            if (false === array_key_exists($key, $parsed)) {
                continue;
            }

            $request->headers->set($header, (string) $parsed[$key]);
        }

        return $request;
    }
}
