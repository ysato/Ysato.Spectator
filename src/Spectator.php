<?php

declare(strict_types=1);

namespace Ysato\Spectator;

use cebe\openapi\Reader;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Ysato\Spectator\Coverage\Collector;
use Ysato\Spectator\Coverage\Coverage;
use Ysato\Spectator\Exception\ReadOpenApiSpecFailedException;
use Ysato\Spectator\OpenApiSpec\Parser;
use Ysato\Spectator\OpenApiSpec\Resolver;
use Ysato\Spectator\Report\RendererInterface;
use Ysato\Spectator\Report\Table;

use function file_exists;

class Spectator
{
    public function __construct(private Collector $collector)
    {
    }

    /** @psalm-api  */
    public static function fromSpecPath(string $specPath): self
    {
        if (! file_exists($specPath)) {
            throw new ReadOpenApiSpecFailedException($specPath);
        }

        $openapi = Reader::readFromYamlFile($specPath);

        $parser = new Parser($openapi);
        $resolver = new Resolver($openapi);
        $collector = new Collector($parser, $resolver, Coverage::getInstance());

        return new self($collector);
    }

    public static function report(OutputInterface|null $output = null, RendererInterface|null $renderer = null): void
    {
        $output ??= new ConsoleOutput();
        $renderer ??= new Table();

        $renderer->render($output);
    }

    /** @psalm-api */
    public function spectate(string $method, string $actualPath, string $statusCode): void
    {
        $this->collector->append($method, $actualPath, $statusCode);
    }
}
