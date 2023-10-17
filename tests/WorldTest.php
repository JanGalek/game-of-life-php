<?php

declare(strict_types = 1);

namespace Tests;

use GameOfLife\Factories\OrganismFactory;
use GameOfLife\Model\Space\Position;
use GameOfLife\Model\Species;
use GameOfLife\Model\World;
use PHPUnit\Framework\TestCase;

class WorldTest extends TestCase
{

    /**
     * @return array<string, array<int, array<int, Position>>>
     */
    public static function provideSpaceData(): array
    {
        return [
            'Init' => [
                [
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
                ],
                5,
            ],
        ];
    }

    /**
     * @dataProvider provideSpaceData
     * @param array<string, array<int, array<int, Position>>> $expected
     */
    public function testInitSpace(
        array $expected,
        int $dimension
    ): void
    {
        $organisms = OrganismFactory::create([]);
        $world = new World($dimension, $organisms);

        self::assertEquals($expected, $world->getSpace()->getPositions());
    }

    public function testInitSpaceWithSpecies(): void
    {
        $species = [
            ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 1],
            ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 2],
            ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 1],
            ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 3],
            ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 2],
        ];
        $organisms = OrganismFactory::create($species);
        $world = new World(5, $organisms);
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
                new Position(1, 1, new Species('Human', 1, 1)),
                new Position(1, 2, null),
                new Position(1, 3, null),
                new Position(1, 4, null),
            ],
            [
                new Position(2, 0, null),
                new Position(2, 1, new Species('Human', 2, 1)),
                new Position(2, 2, new Species('Human', 2, 2)),
                new Position(2, 3, new Species('Human', 2, 3)),
                new Position(2, 4, null),
            ],
            [
                new Position(3, 0, null),
                new Position(3, 1, null),
                new Position(3, 2, new Species('Human', 3, 2)),
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

        self::assertEquals($expected, $world->getSpace()->getPositions());
    }

    public function testRemoveSpecies(): void
    {
        $species = [
            ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 1],
            ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 2],
            ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 1],
            ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 3],
            ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 2],
        ];
        $organisms = OrganismFactory::create($species);
        $world = new World(5, $organisms);
        $world->removeSpecies(new Species('Human', 2, 2));
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
                new Position(1, 1, new Species('Human', 1, 1)),
                new Position(1, 2, null),
                new Position(1, 3, null),
                new Position(1, 4, null),
            ],
            [
                new Position(2, 0, null),
                new Position(2, 1, new Species('Human', 2, 1)),
                new Position(2, 2, null),
                new Position(2, 3, new Species('Human', 2, 3)),
                new Position(2, 4, null),
            ],
            [
                new Position(3, 0, null),
                new Position(3, 1, null),
                new Position(3, 2, new Species('Human', 3, 2)),
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

        self::assertEquals($expected, $world->getSpace()->getPositions());
    }

    public function testInitFillSpeciesToPositionException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('World space error. Trying set species to position x: 5, y: 2, space size: 5');
        $species = [
            ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 2],
            ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 3],
            ['species' => 'Human', 'pos_x' => 5, 'pos_y' => 2],
        ];
        $organisms = OrganismFactory::create($species);
        new World(5, $organisms);
    }

}
