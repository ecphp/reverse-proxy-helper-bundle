<?php

declare(strict_types=1);

namespace EcPhp\EcReverseProxyBundle;

use Symfony\Component\HttpFoundation\Request;

use function array_key_exists;

use const FILTER_VALIDATE_URL;

final class RequestEcReverseProxyHeadersAlter
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var string
     */
    private $url;

    public function __construct(Request $request, string $url)
    {
        $this->request = $request;
        $this->url = $url;
    }

    /**
     * @return void
     */
    public function __invoke()
    {
        if ('' === $this->url) {
            return;
        }

        if (false === filter_var($this->url, FILTER_VALIDATE_URL)) {
            return;
        }

        $this->request::setTrustedProxies(
            ['127.0.0.1', 'REMOTE_ADDR'],
            Request::HEADER_X_FORWARDED_ALL
        );

        $this->request->headers->set('X-Forwarded-Port', '');

        $headertoKeyMapping = [
            'X-Forwarded-Host' => 'host',
            'X-Forwarded-Proto' => 'scheme',
            'X-Forwarded-Port' => 'port',
        ];

        $parsed = parse_url($this->url);

        if (false === $parsed) {
            return;
        }

        foreach ($headertoKeyMapping as $header => $key) {
            if (false === array_key_exists($key, $parsed)) {
                continue;
            }

            $this->request->headers->set($header, (string) $parsed[$key]);
        }
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
