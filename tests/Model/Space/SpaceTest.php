<?php

declare(strict_types = 1);

namespace Tests\Model\Space;

use GameOfLife\Model\Space\Position;
use GameOfLife\Model\Space\Space;
use PHPUnit\Framework\TestCase;

class SpaceTest extends TestCase
{

    public function testConstruct(): void
    {
        $space = new Space(1);
        $expected = [
            0 => [
                0 => new Position(0, 0, null),
            ],
        ];

        $this->assertEquals($expected, $space->getPositions());
    }

    public function testConstruct5(): void
    {
        $space = new Space(5);
        $expected = [
            [
                new Position(0, 0, null),
                new Position(0, 1, null),
                new Position(0, 2, null),
                new Position(0, 3, null),
                new Position(0, 4, null),
            ],
            [
                new Position(1, 0, null),
                new Position(1, 1, null),
                new Position(1, 2, null),
                new Position(1, 3, null),
                new Position(1, 4, null),
            ],
            [
                new Position(2, 0, null),
                new Position(2, 1, null),
                new Position(2, 2, null),
                new Position(2, 3, null),
                new Position(2, 4, null),
            ],
            [
                new Position(3, 0, null),
                new Position(3, 1, null),
                new Position(3, 2, null),
                new Position(3, 3, null),
                new Position(3, 4, null),
            ],
            [
                new Position(4, 0, null),
                new Position(4, 1, null),
                new Position(4, 2, null),
                new Position(4, 3, null),
                new Position(4, 4, null),
            ],
        ];

        $this->assertEquals($expected, $space->getPositions());
    }

}
