<?php

declare(strict_types = 1);

namespace Tests\Model\Space;

use GameOfLife\Factories\OrganismFactory;
use GameOfLife\Model\Space\Position;
use GameOfLife\Model\Species;
use GameOfLife\Model\World;
use PHPUnit\Framework\TestCase;

class PositionTest extends TestCase
{

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function provideNeighborsPositions(): array
    {
        return [
            '0x0' => [
                0,
                0,
                [
                    new Position(1, 0, null),
                    new Position(0, 1, null),
                    new Position(1, 1, new Species('Human', 1, 1)),
                ]],
            '0x1' => [
            0,
            1,
            [
                new Position(0, 0, null),
                new Position(1, 0, null),
                new Position(1, 1, new Species('Human', 1, 1)),
                new Position(0, 2, null),
                new Position(1, 2, new Species('Human', 1, 2)),
            ]],
            '0x2' => [
            0,
            2,
            [
                new Position(0, 1, null),
                new Position(1, 1, new Species('Human', 1, 1)),
                new Position(1, 2, new Species('Human', 1, 2)),
                new Position(0, 3, null),
                new Position(1, 3, new Species('Human', 1, 3)),
            ]],
            '0x3' => [
            0,
            3,
            [
                new Position(0, 2, null),
                new Position(1, 2, new Species('Human', 1, 2)),
                new Position(1, 3, new Species('Human', 1, 3)),
                new Position(0, 4, null),
                new Position(1, 4, null),
            ]],
            '0x4' => [
            0,
            4,
            [
                new Position(0, 3, null),
                new Position(1, 3, new Species('Human', 1, 3)),
                new Position(1, 4, null),
            ]],
            '1x0' => [
            1,
            0,
            [
                new Position(0, 0, null),
                new Position(2, 0, null),
                new Position(0, 1, null),
                new Position(1, 1, new Species('Human', 1, 1)),
                new Position(2, 1, null),
            ]],
            '1x1' => [
            1,
            1,
            [
                new Position(0, 0, null),
                new Position(1, 0, null),
                new Position(2, 0, null),
                new Position(0, 1, null),
                new Position(2, 1, null),
                new Position(0, 2, null),
                new Position(1, 2, new Species('Human', 1, 2)),
                new Position(2, 2, null),
            ]],
            '1x2' => [
            1,
            2,
            [
                new Position(0, 1, null),
                new Position(1, 1, new Species('Human', 1, 1)),
                new Position(2, 1, null),
                new Position(0, 2, null),
                new Position(2, 2, null),
                new Position(0, 3, null),
                new Position(1, 3, new Species('Human', 1, 3)),
                new Position(2, 3, null),
            ]],
            '1x3' => [
            1,
            3,
            [
                new Position(0, 2, null),
                new Position(1, 2, new Species('Human', 1, 2)),
                new Position(2, 2, null),
                new Position(0, 3, null),
                new Position(2, 3, null),
                new Position(0, 4, null),
                new Position(1, 4, null),
                new Position(2, 4, null),
            ]],
            '1x4' => [
            1,
            4,
            [
                new Position(0, 3, null),
                new Position(1, 3, new Species('Human', 1, 3)),
                new Position(2, 3, null),
                new Position(0, 4, null),
                new Position(2, 4, null),
            ]],
            '2x0' => [
            2,
            0,
            [
                new Position(1, 0, null),
                new Position(3, 0, null),
                new Position(1, 1, new Species('Human', 1, 1)),
                new Position(2, 1, null),
                new Position(3, 1, new Species('Human', 3, 1)),
            ]],
            '2x1' => [
            2,
            1,
            [
                new Position(1, 0, null),
                new Position(2, 0, null),
                new Position(3, 0, null),
                new Position(1, 1, new Species('Human', 1, 1)),
                new Position(3, 1, new Species('Human', 3, 1)),
                new Position(1, 2, new Species('Human', 1, 2)),
                new Position(2, 2, null),
                new Position(3, 2, new Species('Human', 3, 2)),
            ]],
            '2x2' => [
            2,
            2,
            [
                new Position(1, 1, new Species('Human', 1, 1)),
                new Position(2, 1, null),
                new Position(3, 1, new Species('Human', 3, 1)),
                new Position(1, 2, new Species('Human', 1, 2)),
                new Position(3, 2, new Species('Human', 3, 2)),
                new Position(1, 3, new Species('Human', 1, 3)),
                new Position(2, 3, null),
                new Position(3, 3, new Species('Human', 3, 3)),
            ]],
            '2x3' => [
            2,
            3,
            [
                new Position(1, 2, new Species('Human', 1, 2)),
                new Position(2, 2, null),
                new Position(3, 2, new Species('Human', 3, 2)),
                new Position(1, 3, new Species('Human', 1, 3)),
                new Position(3, 3, new Species('Human', 3, 3)),
                new Position(1, 4, null),
                new Position(2, 4, null),
                new Position(3, 4, null),
            ]],
            '2x4' => [
            2,
            4,
            [
                new Position(1, 3, new Species('Human', 1, 3)),
                new Position(2, 3, null),
                new Position(3, 3, new Species('Human', 3, 3)),
                new Position(1, 4, null),
                new Position(3, 4, null),
            ]],
            '3x0' => [
            3,
            0,
            [
                new Position(2, 0, null),
                new Position(4, 0, null),
                new Position(2, 1, null),
                new Position(3, 1, new Species('Human', 3, 1)),
                new Position(4, 1, null),
            ]],
            '3x1' => [
            3,
            1,
            [
                new Position(2, 0, null),
                new Position(3, 0, null),
                new Position(4, 0, null),
                new Position(2, 1, null),
                new Position(4, 1, null),
                new Position(2, 2, null),
                new Position(3, 2, new Species('Human', 3, 2)),
                new Position(4, 2, null),
            ]],
            '3x2' => [
            3,
            2,
            [
                new Position(2, 1, null),
                new Position(3, 1, new Species('Human', 3, 1)),
                new Position(4, 1, null),
                new Position(2, 2, null),
                new Position(4, 2, null),
                new Position(2, 3, null),
                new Position(3, 3, new Species('Human', 3, 3)),
                new Position(4, 3, null),
            ]],
            '3x3' => [
            3,
            3,
            [
                new Position(2, 2, null),
                new Position(3, 2, new Species('Human', 3, 2)),
                new Position(4, 2, null),
                new Position(2, 3, null),
                new Position(4, 3, null),
                new Position(2, 4, null),
                new Position(3, 4, null),
                new Position(4, 4, null),
            ]],
            '3x4' => [
            3,
            4,
            [
                new Position(2, 3, null),
                new Position(3, 3, new Species('Human', 3, 3)),
                new Position(4, 3, null),
                new Position(2, 4, null),
                new Position(4, 4, null),
            ]],
            '4x0' => [
            4,
            0,
            [
                new Position(3, 0, null),
                new Position(3, 1, new Species('Human', 3, 1)),
                new Position(4, 1, null),
            ]],
            '4x1' => [
            4,
            1,
            [
                new Position(3, 0, null),
                new Position(4, 0, null),
                new Position(3, 1, new Species('Human', 3, 1)),
                new Position(3, 2, new Species('Human', 3, 2)),
                new Position(4, 2, null),
            ]],
            '4x2' => [
            4,
            2,
            [
                new Position(3, 1, new Species('Human', 3, 1)),
                new Position(4, 1, null),
                new Position(3, 2, new Species('Human', 3, 2)),
                new Position(3, 3, new Species('Human', 3, 3)),
                new Position(4, 3, null),
            ]],
            '4x3' => [
            4,
            3,
            [
                new Position(3, 2, new Species('Human', 3, 2)),
                new Position(4, 2, null),
                new Position(3, 3, new Species('Human', 3, 3)),
                new Position(3, 4, null),
                new Position(4, 4, null),
            ]],
            '4x4' => [
            4,
            4,
            [
                new Position(3, 3, new Species('Human', 3, 3)),
                new Position(4, 3, null),
                new Position(3, 4, null),
            ]],
        ];
    }

    /**
     * @dataProvider provideNeighborsPositions
     * @param array<string, array<int, mixed>> $expected
     */
    public function testNeighborsPositions(int $x, int $y, array $expected): void
    {
        $organisms = [
            ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 1],
            ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 1],
            ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
            ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 2],
            ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 3],
            ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 3],
        ];

        $organism = OrganismFactory::create($organisms);
        $world = new World(5, $organism);

        self::assertEquals($expected, Position::getNeighborsPositions($world, $x, $y));
    }

}
