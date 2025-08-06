<?php

declare(strict_types=1);

namespace Ysato\Spectator;

use function strtolower;

readonly class Scene
{
    public function __construct(public string $method, public string $path, public string $statusCode)
    {
    }

    public function match(string $method, string $path, string $statusCode): bool
    {
        return strtolower($this->method) === strtolower($method)
            && $this->path === $path
            && $this->statusCode === $statusCode;
    }
}
