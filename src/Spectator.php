<?php

declare(strict_types=1);

namespace Ysato\Spectator;

use Ysato\Spectator\Result\Implemented;
use Ysato\Spectator\Result\NotImplemented;
use Ysato\Spectator\Result\Result;

class Spectator
{
    private static self|null $instance = null;

    /** @param Result[] $results */
    private function __construct(private SpecInterface $spec, private array $results = [])
    {
        $this->initialize();
    }

    public static function fromSpecPath(string $specPath): self
    {
        if (self::$instance === null) {
            $spec = OpenApiSpec::fromSpecPath($specPath);

            self::$instance = new self($spec);
        }

        return self::$instance;
    }

    /** @psalm-api */
    public function spectate(string $method, string $actualPath, string $statusCode): void
    {
        $path = $this->spec->resolvePath($method, $actualPath);

        $results = [];
        foreach ($this->results as $result) {
            if ($result->scene->match($method, $path, $statusCode)) {
                $result = new Implemented($result->scene);
            }

            $results[] = $result;
        }

        $this->results = $results;
    }

    /** @return Result[] */
    public function getResults(): array
    {
        return $this->results;
    }

    protected function initialize(): void
    {
        $this->results = [];
        foreach ($this->spec->getScenes() as $scene) {
            $this->results[] = new NotImplemented($scene);
        }
    }
}
