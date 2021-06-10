<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ecphp
 */

declare(strict_types=1);

namespace EcPhp\ReverseProxyHelperBundle\Service;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

use function array_key_exists;

use const FILTER_VALIDATE_URL;

final class RequestHeadersAlter implements RequestAlterInterface
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
        $parsed = parse_url($this->parameters['base_url']);

        if (false === $parsed) {
            throw new InvalidArgumentException('Unable to parse the provided URL.');
        }

        if (false === array_key_exists('host', $parsed)) {
            return $request;
        }

        if (false === filter_var($this->parameters['base_url'], FILTER_VALIDATE_URL)) {
            return $request;
        }

        $request
            ->headers
            ->add(
                array_map(
                    static function (string $key) use ($parsed): string {
                        return (string) $parsed[$key];
                    },
                    array_filter(
                        [
                            'X-Forwarded-Host' => 'host',
                            'X-Forwarded-Proto' => 'scheme',
                            'X-Forwarded-Port' => 'port',
                            'X-Forwarded-Prefix' => 'path',
                        ],
                        static function (string $key) use ($parsed): bool {
                            return array_key_exists($key, $parsed);
                        }
                    )
                )
            );

        return $request;
    }
}
