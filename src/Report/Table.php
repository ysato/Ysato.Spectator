<?php

declare(strict_types=1);

namespace Ysato\Spectator\Report;

use Override;
use Symfony\Component\Console\Helper\Table as SymfonyTable;
use Symfony\Component\Console\Output\OutputInterface;
use Ysato\Spectator\Coverage\Coverage;

use function strtoupper;

class Table implements RendererInterface
{
    public function __construct()
    {
    }

    #[Override]
    public function render(OutputInterface $output): void
    {
        $results = Coverage::getInstance()->report();

        $table = new SymfonyTable($output);

        $table->setHeaders(['IMPLEMENTED', 'METHOD', 'ENDPOINT', 'STATUS CODE']);

        foreach ($results as $result) {
            $icon = $result->isImplemented() ? '✅' : '❌';

            $table->addRow([
                $icon,
                strtoupper($result->scene->method),
                $result->scene->path,
                $result->scene->statusCode,
            ]);
        }

        $table->render();
    }
}
