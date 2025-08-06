<?php

declare(strict_types=1);

namespace Ysato\Spectator;

use cebe\openapi\Reader;
use cebe\openapi\spec\OpenApi;
use League\OpenAPIValidation\PSR7\PathFinder;
use Ysato\Spectator\Exception\RuntimeException;

use function assert;
use function file_exists;
use function is_string;

class OpenApiSpec
{
    public function __construct(private readonly OpenApi $openApi)
    {
    }

    public static function fromSpecPath(string $specPath): self
    {
        if (! file_exists($specPath)) {
            throw new RuntimeException("OpenAPI specification file not found: {$specPath}");
        }

        $openapi = Reader::readFromYamlFile($specPath);

        return new self($openapi);
    }

    public function resolvePath(string $method, string $actualPath): string
    {
        $finder = new PathFinder($this->openApi, $actualPath, $method);

        $addresses = $finder->search();

        return ! empty($addresses) ? $addresses[0]->path() : $actualPath;
    }

    /** @return Scene[] */
    public function getScenes(): array
    {
        $scenes = [];
        foreach ($this->openApi->paths as $path => $pathItem) {
            assert(is_string($path));

            foreach ($pathItem->getOperations() as $method => $operation) {
                assert(is_string($method));
                if ($operation->responses === null) {
                    continue;
                }

                foreach ($operation->responses as $statusCode => $response) {
                    unset($response);
                    // @phpstan-ignore cast.string
                    $scenes[] = new Scene($method, $path, (string) $statusCode);
                }
            }
        }

        return $scenes;
    }
}
