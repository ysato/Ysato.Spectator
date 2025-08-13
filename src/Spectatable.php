<?php

declare(strict_types=1);

namespace Ysato\Spectator;

use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;

use function assert;
use function base_path;
use function env;
use function is_string;

/** @phpstan-ignore trait.unused */
trait Spectatable
{
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
        $spectator = Spectator::fromSpecPath($this->getOpenApiSpecPath());

        $spectator->spectate($method, $uri, (string) $statusCode);
    }

    protected function getOpenApiSpecPath(): string
    {
        $path = env('OPENAPI_SPEC_PATH', 'openapi.yaml');
        assert(is_string($path));

        return base_path($path);
    }
}
