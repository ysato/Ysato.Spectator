<?php

declare(strict_types=1);

namespace Ysato\Spectator;

use function sprintf;
use function strtolower;
use function strtoupper;

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

    public function __toString(): string
    {
        return sprintf(
            '%s:%s:%s',
            strtoupper($this->method),
            strtolower($this->path),
            $this->statusCode,
        );
    }
}
