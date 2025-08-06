<?php

declare(strict_types=1);

namespace Ysato\Spectator;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;
use Ysato\Spectator\Result\Result;

use function strtolower;
use function strtoupper;

class ResultRenderer
{
    /** @param Result[] $results */
    public function __construct(private readonly array $results)
    {
    }

    public function render(): void
    {
        $output = new ConsoleOutput();
        $table = new Table($output);

        $table->setHeaders(['IMPLEMENTED', 'METHOD', 'ENDPOINT', 'STATUS CODE']);

        foreach ($this->results as $result) {
            $icon = $result->isImplemented() ? '✅' : '❌';

            $table->addRow([
                $icon,
                strtoupper($result->scene->method),
                strtolower($result->scene->path),
                $result->scene->statusCode,
            ]);
        }

        $table->render();
    }
}
