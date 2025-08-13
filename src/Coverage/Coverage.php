<?php

declare(strict_types=1);

namespace Ysato\Spectator\Coverage;

use Ysato\Spectator\Result\Implemented;
use Ysato\Spectator\Result\NotImplemented;
use Ysato\Spectator\Result\Result;
use Ysato\Spectator\Scene;

class Coverage
{
    private static self|null $instance = null;

    /**
     * @param array<string, Scene> $expectedScenes
     * @param array<string, Scene> $coveredScenes
     */
    private function __construct(private array $expectedScenes = [], private array $coveredScenes = [])
    {
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /** @return Result[] */
    public function report(): array
    {
        $results = [];
        foreach ($this->expectedScenes as $id => $scene) {
            $results[] = isset($this->coveredScenes[$id])
                ? new Implemented($scene)
                : new NotImplemented($scene);
        }

        return $results;
    }

    /** @param Scene[] $scenes */
    public function initializeUncovered(array $scenes): void
    {
        $expectedScenes = [];
        foreach ($scenes as $scene) {
            $expectedScenes[(string) $scene] = $scene;
        }

        $this->expectedScenes = $expectedScenes;
    }

    public function markSceneAsCalledByTestCase(string $id, Scene $scene): void
    {
        $this->coveredScenes[$id] = $scene;
    }
}
