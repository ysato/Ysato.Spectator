<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Ysato\Spectator\Result\Implemented;
use Ysato\Spectator\Result\NotImplemented;
use Ysato\Spectator\Result\Result;
use Ysato\Spectator\Scene;

class ResultTest extends TestCase
{
    /** @dataProvider isImplementedProvider */
    public function testIsImplemented(Result $SUT, bool $result): void
    {
        $this->assertThat($SUT->isImplemented(), $this->equalTo($result));
    }

    /** @return array<array{Result, bool}> */
    public static function isImplementedProvider(): array
    {
        return [
            [new Implemented(new Scene('GET', '/users', '200')), true],
            [new NotImplemented(new Scene('GET', '/users', '200')), false],
        ];
    }
}
