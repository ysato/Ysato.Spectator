<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Ysato\Spectator\Scene;

class SceneTest extends TestCase
{
    #[Test]
    #[DataProvider('matchProvider')]
    public function match(string $method, string $path, string $statusCode, bool $result): void
    {
        $SUT = new Scene('GET', '/users', '200');

        $this->assertThat($SUT->match($method, $path, $statusCode), $this->equalTo($result));
    }

    #[DataProvider('toStringProvider')]
    public function testToString(string $method, string $path, string $statusCode, string $expected): void
    {
        $SUT = new Scene($method, $path, $statusCode);

        $this->assertEquals($expected, (string) $SUT);
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

    /** @return array<array{string, string, string, string}> */
    public static function toStringProvider(): array
    {
        return [
            ['GET', '/users', '200', 'GET:/users:200'],
            ['get', '/users', '200', 'GET:/users:200'],
            ['POST', '/users', '200', 'POST:/users:200'],
            ['GET', '/users', '404', 'GET:/users:404'],
            ['POST', '/products', '200', 'POST:/products:200'],
        ];
    }
}
