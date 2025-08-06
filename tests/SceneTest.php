<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Ysato\Spectator\Scene;

class SceneTest extends TestCase
{
    #[DataProvider('matchProvider')]
    public function testMatch(string $method, string $path, string $statusCode, bool $result): void
    {
        $SUT = new Scene('GET', '/users', '200');

        $this->assertThat($SUT->match($method, $path, $statusCode), $this->equalTo($result));
    }

    /** @return array<array{string, string, string, bool}> */
    public static function matchProvider(): array
    {
        return [
            ['GET', '/users', '200', true],
            ['get', '/users', '200', true],
            ['POST', '/users', '200', false],
            ['GET', '/users', '404', false],
            ['POST', '/products', '200', false],
        ];
    }
}
