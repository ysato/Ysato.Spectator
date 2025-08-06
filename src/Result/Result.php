<?php

declare(strict_types=1);

namespace Ysato\Spectator\Result;

use Ysato\Spectator\Scene;

abstract class Result
{
    public function __construct(public readonly Scene $scene)
    {
    }

    public function isImplemented(): bool
    {
        return $this instanceof Implemented;
    }
}
