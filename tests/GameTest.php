<?php

declare(strict_types = 1);

namespace Tests;

use GameOfLife\Factories\OrganismFactory;
use GameOfLife\Game;
use GameOfLife\Model\Space\Position;
use GameOfLife\Model\Species;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{

    /**
     * @dataProvider provideBorning
     */
    public function testBorning(array $species, int $dimension, array $expected): void
    {
        $organisms = OrganismFactory::create($species);
        $game = new Game($dimension, $organisms, 2);
        //$game->listToBorn();

        self::assertEquals($expected, $game->listToBorn());
        //self::assertEquals($expected, $game->getWorld()->getSpace()->getPositions());
    }

    /**
     * @dataProvider provideListToDie
     */
    public function testListToDie(array $species, Species $speciesToTest, int $dimension, array $expected): void
    {
        $organisms = OrganismFactory::create($species);
        $game = new Game($dimension, $organisms, 2);

        self::assertEquals($expected, $game->getListToDie($speciesToTest));
    }

    /**
     * @dataProvider provideBorningPositions
     */
    public function testPositionsToBorn(array $species, int $dimension, array $expected): void
    {
        $organisms = OrganismFactory::create($species);
        $game = new Game($dimension, $organisms, 2);

        self::assertEquals($expected, $game->getPositionsToBorn());
    }

    /**
     * @dataProvider provideSameSpeciesNeighbors
     */
    public function testGetSameSpeciesNeighbors($dimension, Species $testSpecies, array $species, $expected): void
    {
        $organisms = OrganismFactory::create($species);
        $game = new Game($dimension, $organisms, 2);
        self::assertEquals($expected, $game->getSameSpeciesNeighbors($testSpecies));
    }

    /**
     * @dataProvider provideHumanNeighbors
     */
    public function testGetNeighbors($dimension, Species $testSpecies, array $species, $expected): void
    {
        $organisms = OrganismFactory::create($species);
        $game = new Game($dimension, $organisms, 2);
        self::assertEquals($expected, $game->getNeighbors($testSpecies));
    }

    /**
     * @dataProvider provideNext
     */
    public function testPlay($dimension, int $iterations, array $species, $expected): void
    {
        $organisms = OrganismFactory::create($species);
        $game = new Game($dimension, $organisms, $iterations);
        $game->play();

        self::assertEquals($expected, $game->getWorld()->getSpace()->getPositions());
    }

    public static function provideListToDie(): array
    {
        return [
            'Kill Empty' => [
                [
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 3],
                ],
                new Species('Human', 0, 2),
                5,
                []
            ],
            'Kill 1' => [
                [
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 3],
                ],
                new Species('Human', 2, 1),
                5,
                [
                    new Species('Human', 2, 1),
                ]
            ],
            'Kill test' => [
                [
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 0],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 4, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 3],
                ],
                new Species('Human', 1, 1),
                5,
                [
                    new Species('Human', 1, 1),
                ]
            ],
        ];
    }

    public static function provideBorning(): array
    {
        return [
            'Born 1 Only Human' => [
                [
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 3],
                ],
                5,
                [
                    new Species('Human', 0, 2),
                    new Species('Human', 2, 4),
                    new Species('Human', 4, 2),
                ]
            ],
            'Borning 1 Cat and 1 Human' => [
                [
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 3],
                    ['species' => 'Cat', 'pos_x' => 0, 'pos_y' => 3],
                    ['species' => 'Cat', 'pos_x' => 0, 'pos_y' => 4],
                    ['species' => 'Cat', 'pos_x' => 1, 'pos_y' => 3],
                ],
                5,
                [
                    new Species('Human', 4, 2),
                    new Species('Cat', 1, 4),
                ]
            ],
            'Borning 1 colision Cat and Human' => [
                [
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
                    ['species' => 'Cat', 'pos_x' => 2, 'pos_y' => 3],
                    ['species' => 'Cat', 'pos_x' => 3, 'pos_y' => 2],
                    ['species' => 'Cat', 'pos_x' => 3, 'pos_y' => 3],
                ],
                5,
                [
                    new Species('Human', 2, 2),
                    new Species('Cat', 2, 2),
                ]
            ],
        ];
    }

    public static function provideBorningPositions(): array
    {
        return [
            'Born 1 Only Human' => [
                [
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 3],
                ],
                5,
                [
                    'Human' => [
                        4 => [
                            2 => new Position(4, 2, null),
                        ],
                    ],
                ]
            ],
            'Borning 1 Cat and 1 Human' => [
                [
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 3],
                    ['species' => 'Cat', 'pos_x' => 0, 'pos_y' => 3],
                    ['species' => 'Cat', 'pos_x' => 0, 'pos_y' => 4],
                    ['species' => 'Cat', 'pos_x' => 1, 'pos_y' => 3],
                ],
                5,
                [
                    'Human' => [
                        4 => [
                            2 => new Position(4, 2, null),
                        ],
                    ],
                    'Cat' => [
                        1 => [
                            4 => new Position(1, 4, null),
                        ],
                    ],
                ]
            ],
            'Borning 1 colision Cat and Human' => [
                [
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
                    ['species' => 'Cat', 'pos_x' => 2, 'pos_y' => 3],
                    ['species' => 'Cat', 'pos_x' => 3, 'pos_y' => 2],
                    ['species' => 'Cat', 'pos_x' => 3, 'pos_y' => 3],
                ],
                5,
                [
                    'Human' => [
                        2 => [
                            2 => new Position(2, 2, null),
                        ],
                    ],
                    'Cat' => [
                        2 => [
                            2 => new Position(2, 2, null),
                        ],
                    ],
                ]
            ],
        ];
    }

    public static function provideNext(): array
    {
        return [
            'Next Neighbors 8 Humans Only 3' => [
                5,
                1,
                [
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 3],
                ],
                [
                    [
                        new Position(0, 0, null),
                        new Position(0, 1, null),
                        new Position(0, 2, new Species('Human', 0, 2)),
                        new Position(0, 3, null),
                        new Position(0, 4, null),
                    ],
                    [
                        new Position(1, 0, null),
                        new Position(1, 1, new Species('Human', 1, 1)),
                        new Position(1, 2, null),
                        new Position(1, 3, new Species('Human', 1, 3)),
                        new Position(1, 4, null),
                    ],
                    [
                        new Position(2, 0, new Species('Human', 2, 0)),
                        new Position(2, 1, null),
                        new Position(2, 2, null),
                        new Position(2, 3, null),
                        new Position(2, 4, new Species('Human', 2, 4)),
                    ],
                    [
                        new Position(3, 0, null),
                        new Position(3, 1, new Species('Human', 3, 1)),
                        new Position(3, 2, null),
                        new Position(3, 3, new Species('Human', 3, 3)),
                        new Position(3, 4, null),
                    ],
                    [
                        new Position(4, 0, null),
                        new Position(4, 1, null),
                        new Position(4, 2, new Species('Human', 4, 2)),
                        new Position(4, 3, null),
                        new Position(4, 4, null),
                    ],
                ],
            ],
            'NextNeighbors8MultiSpecies' => [
                5,
                1,
                [
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 2],
                    ['species' => 'Cat', 'pos_x' => 0, 'pos_y' => 3],
                    ['species' => 'Cat', 'pos_x' => 1, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 3],
                    ['species' => 'Cat', 'pos_x' => 0, 'pos_y' => 4],
                ],
                [

                    [
                        new Position(0, 0, null),
                        new Position(0, 1, null),
                        new Position(0, 2, null),
                        new Position(0, 3, new Species('Cat', 0, 3)),
                        new Position(0, 4, new Species('Cat', 0, 4)),
                    ],
                    [
                        new Position(1, 0, null),
                        new Position(1, 1, new Species('Human', 1, 1)),
                        new Position(1, 2, null),
                        new Position(1, 3, new Species('Cat', 1, 3)),
                        new Position(1, 4, new Species('Cat', 1, 4)),
                    ],
                    [
                        new Position(2, 0, new Species('Human', 2, 0)),
                        new Position(2, 1, null),
                        new Position(2, 2, null),
                        new Position(2, 3, null),
                        new Position(2, 4, null),
                    ],
                    [
                        new Position(3, 0, null),
                        new Position(3, 1, new Species('Human', 3, 1)),
                        new Position(3, 2, null),
                        new Position(3, 3, new Species('Human', 3, 3)),
                        new Position(3, 4, null),
                    ],
                    [
                        new Position(4, 0, null),
                        new Position(4, 1, null),
                        new Position(4, 2, new Species('Human', 4, 2)),
                        new Position(4, 3, null),
                        new Position(4, 4, null),
                    ],
                ],
            ],
            'testNextNeighbors8MultiSpecies2step' => [
                5,
                2,
                [
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 2],
                    ['species' => 'Cat', 'pos_x' => 0, 'pos_y' => 3],
                    ['species' => 'Cat', 'pos_x' => 1, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 3],
                    ['species' => 'Cat', 'pos_x' => 0, 'pos_y' => 4],
                ],
                [
                    [
                        new Position(0, 0, null),
                        new Position(0, 1, null),
                        new Position(0, 2, null),
                        new Position(0, 3, new Species('Cat', 0, 3)),
                        new Position(0, 4, new Species('Cat', 0, 4)),
                    ],
                    [
                        new Position(1, 0, null),
                        new Position(1, 1, null),
                        new Position(1, 2, null),
                        new Position(1, 3, new Species('Cat', 1, 3)),
                        new Position(1, 4, new Species('Cat', 1, 4)),
                    ],
                    [
                        new Position(2, 0, new Species('Human', 2, 0)),
                        new Position(2, 1, new Species('Human', 2, 1)),
                        new Position(2, 2, new Species('Human', 2, 2)),
                        new Position(2, 3, null),
                        new Position(2, 4, null),
                    ],
                    [
                        new Position(3, 0, null),
                        new Position(3, 1, new Species('Human', 3, 1)),
                        new Position(3, 2, new Species('Human', 3, 2)),
                        new Position(3, 3, null),
                        new Position(3, 4, null),
                    ],
                    [
                        new Position(4, 0, null),
                        new Position(4, 1, null),
                        new Position(4, 2, new Species('Human', 4, 2)),
                        new Position(4, 3, null),
                        new Position(4, 4, null),
                    ],
                ]
            ],
        ];
    }

    public static function provideSameSpeciesNeighbors(): array
    {
        return [
            '3 Same Species Neighbors' => [
                5,
                new Species('Human', 2, 2),
                [
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 3],
                    ['species' => 'Cat', 'pos_x' => 3, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 1],
                ],
                [
                    new Position(1, 1, null),
                    new Position(2, 1, new Species('Human', 2, 1)),
                    new Position(3, 1, null),
                    new Position(1, 2, new Species('Human', 1, 2)),
                    new Position(1, 3, null),
                    new Position(2, 3, new Species('Human', 2, 3)),
                    new Position(3, 3, null),
                ],
            ],
            '6 Same Species of 8 Neighbors' => [
                5,
                new Species('Human', 2, 2),
                [
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
                    ['species' => 'Dog', 'pos_x' => 1, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 1],
                    ['species' => 'Cat', 'pos_x' => 3, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 3],
                ],
                [
                    new Position(1, 1, new Species('Human', 1, 1)),
                    new Position(2, 1, new Species('Human', 2, 1)),
                    new Position(1, 2, new Species('Human', 1, 2)),
                    new Position(3, 2, new Species('Human', 3, 2)),
                    new Position(2, 3, new Species('Human', 2, 3)),
                    new Position(3, 3, new Species('Human', 3, 3)),
                ],
            ],
        ];
    }

    public static function provideHumanNeighbors(): array
    {
        return [
            '1 Neighbors' => [
                5,
                new Species('Human', 2, 2),
                [
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
                ],
                [
                    new Position(1, 1, null),
                    new Position(2, 1, null),
                    new Position(3, 1, null),
                    new Position(1, 2, new Species('Human', 1, 2)),
                    new Position(3, 2, null),
                    new Position(1, 3, null),
                    new Position(2, 3, null),
                    new Position(3, 3, null),
                ],
            ],
            '2 Neighbors' => [
                5,
                new Species('Human', 2, 2),
                [
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
                ],
                [
                    new Position(1, 1, null),
                    new Position(2, 1, null),
                    new Position(3, 1, null),
                    new Position(1, 2, new Species('Human', 1, 2)),
                    new Position(3, 2, new Species('Human', 3, 2)),
                    new Position(1, 3, null),
                    new Position(2, 3, null),
                    new Position(3, 3, null),
                ],
            ],
            '3 Neighbors' => [
                5,
                new Species('Human', 2, 2),
                [
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 1],
                ],
                [
                    new Position(1, 1, null),
                    new Position(2, 1, new Species('Human', 2, 1)),
                    new Position(3, 1, null),
                    new Position(1, 2, new Species('Human', 1, 2)),
                    new Position(3, 2, new Species('Human', 3, 2)),
                    new Position(1, 3, null),
                    new Position(2, 3, null),
                    new Position(3, 3, null),
                ],
            ],
            '4 Neighbors' => [
                5,
                new Species('Human', 2, 2),
                [
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 1],
                ],
                [
                    new Position(1, 1, null),
                    new Position(2, 1, new Species('Human', 2, 1)),
                    new Position(3, 1, null),
                    new Position(1, 2, new Species('Human', 1, 2)),
                    new Position(3, 2, new Species('Human', 3, 2)),
                    new Position(1, 3, null),
                    new Position(2, 3, new Species('Human', 2, 3)),
                    new Position(3, 3, null),
                ],
            ],
            '8 Neighbors' => [
                5,
                new Species('Human', 2, 2),
                [
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 2],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 3],
                ],
                [
                    new Position(1, 1, new Species('Human', 1, 1)),
                    new Position(2, 1, new Species('Human', 2, 1)),
                    new Position(3, 1, new Species('Human', 3, 1)),
                    new Position(1, 2, new Species('Human', 1, 2)),
                    new Position(3, 2, new Species('Human', 3, 2)),
                    new Position(1, 3, new Species('Human', 1, 3)),
                    new Position(2, 3, new Species('Human', 2, 3)),
                    new Position(3, 3, new Species('Human', 3, 3)),
                ],
            ],
            '2 Neighbors on end of dimension' => [
                5,
                new Species('Human', 4, 4),
                [
                    ['species' => 'Human', 'pos_x' => 4, 'pos_y' => 4],
                    ['species' => 'Human', 'pos_x' => 4, 'pos_y' => 3],
                    ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 4],
                ],
                [
                    new Position(3, 3, null),
                    new Position(4, 3, new Species('Human', 4, 3)),
                    new Position(3, 4, new Species('Human', 3, 4)),
                ],
            ],
            '2 Neighbors on start of dimension' => [
                5,
                new Species('Human', 0, 0),
                [
                    ['species' => 'Human', 'pos_x' => 0, 'pos_y' => 0],
                    ['species' => 'Human', 'pos_x' => 0, 'pos_y' => 1],
                    ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 0],
                ],
                [
                    new Position(1, 0, new Species('Human', 1, 0)),
                    new Position(0, 1, new Species('Human', 0, 1)),
                    new Position(1, 1, null),
                ],
            ],
        ];
    }

}
