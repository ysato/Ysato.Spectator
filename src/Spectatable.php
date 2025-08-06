<?php

declare(strict_types=1);

namespace Ysato\Spectator;

use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;

/** @phpstan-ignore trait.unused */
trait Spectatable
{
    use GetsSpecPath;

    /**
     * @param string                  $method
     * @param string                  $uri
     * @param array<array-key, mixed> $parameters
     * @param array<array-key, mixed> $cookies
     * @param array<array-key, mixed> $files
     * @param array<array-key, mixed> $server
     * @param string|null             $content
     *
     * @return TestResponse<Response>
     */
    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $response = parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);

        $this->spectate($method, $uri, $response->getStatusCode());

        return $response;
    }

    public function spectate(string $method, string $uri, int $statusCode): void
    {
        $spectator = Spectator::fromSpecPath($this->getSpecPath());

        $spectator->spectate($method, $uri, (string) $statusCode);
    }
}
