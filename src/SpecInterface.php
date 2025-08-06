<?php

declare(strict_types=1);

namespace Ysato\Spectator;

interface SpecInterface
{
    public function resolvePath(string $method, string $actualPath): string;

    /** @return Scene[] */
    public function getScenes(): array;
}
