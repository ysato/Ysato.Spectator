<?php

declare(strict_types=1);

namespace Ysato\Spectator;

use Override;
use PHPUnit\Event\Application\Finished;
use PHPUnit\Event\Application\FinishedSubscriber;

use function env;

class SpectationReporter implements FinishedSubscriber
{
    #[Override]
    public function notify(Finished $event): void
    {
        unset($event);

        if (! env('ENABLE_SPECTATION_REPORT', false)) {
            return;
        }

        Spectator::report();
    }
}
