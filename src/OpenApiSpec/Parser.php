<?php

declare(strict_types=1);

namespace Ysato\Spectator\OpenApiSpec;

use cebe\openapi\spec\OpenApi;
use Ysato\Spectator\Scene;

use function assert;
use function is_string;

class Parser
{
    public function __construct(private OpenApi $openApi)
    {
    }

    /** @return Scene[] */
    public function getAllScenes(): array
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
