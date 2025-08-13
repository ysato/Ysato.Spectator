<?php

declare(strict_types=1);

namespace Ysato\Spectator\Coverage;

use Ysato\Spectator\OpenApiSpec\Parser;
use Ysato\Spectator\OpenApiSpec\Resolver;
use Ysato\Spectator\Scene;

class Collector
{
    public function __construct(
        private readonly Parser $parser,
        private readonly Resolver $resolver,
        private Coverage $coverage,
    ) {
        $this->initialize();
    }

    public function append(string $method, string $actualPath, string $statusCode): void
    {
        $path = $this->resolver->resolve($method, $actualPath);

        $scene = new Scene($method, $path, $statusCode);

        $this->coverage->markSceneAsCalledByTestCase((string) $scene, $scene);
    }

    private function initialize(): void
    {
        $this->coverage->initializeUncovered($this->parser->getAllScenes());
    }
}
