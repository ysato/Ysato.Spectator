<?php

declare(strict_types=1);

namespace Ysato\Spectator;

use function assert;
use function base_path;
use function env;
use function is_string;

trait GetsOpenApiSpecPath
{
    protected function getOpenApiSpecPath(): string
    {
        $path = env('OPENAPI_SPEC_PATH', 'openapi.yaml');
        assert(is_string($path));

        return base_path($path);
    }
}
