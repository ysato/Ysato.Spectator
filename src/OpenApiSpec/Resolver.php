<?php

declare(strict_types=1);

namespace Ysato\Spectator\OpenApiSpec;

use cebe\openapi\spec\OpenApi;
use League\OpenAPIValidation\PSR7\PathFinder;

class Resolver
{
    public function __construct(private OpenApi $openApi)
    {
    }

    public function resolve(string $method, string $actualPath): string
    {
        $finder = new PathFinder($this->openApi, $actualPath, $method);

        $addresses = $finder->search();

        return ! empty($addresses) ? $addresses[0]->path() : $actualPath;
    }
}
