<?php

declare(strict_types=1);

namespace Ysato\Spectator;

use Override;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

/** @psalm-suppress UnusedClass */
class SpectatorExtension implements Extension
{
    #[Override]
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        unset($configuration, $parameters);

        $facade->registerSubscriber(new RenderResult());
    }
}
