<?php

declare(strict_types=1);

namespace Ysato\Spectator;

use Override;
use PHPUnit\Event\Application\Finished;
use PHPUnit\Event\Application\FinishedSubscriber;

use function env;

class RenderResult implements FinishedSubscriber
{
    use GetsSpecPath;

    #[Override]
    public function notify(Finished $event): void
    {
        unset($event);

        if (! env('RENDER_SPECTATION_RESULT', false)) {
            return;
        }

        $spectator = Spectator::fromSpecPath($this->getSpecPath());

        $renderer = new ResultRenderer($spectator->getResults());

        $renderer->render();
    }
}
