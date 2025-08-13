<?php

declare(strict_types=1);

namespace Ysato\Spectator\Report;

use Symfony\Component\Console\Output\OutputInterface;

interface RendererInterface
{
    public function render(OutputInterface $output): void;
}
