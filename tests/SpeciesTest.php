<?php

declare(strict_types = 1);

namespace Tests;

use GameOfLife\Factories\OrganismFactory;
use GameOfLife\Model\Space\Position;
use GameOfLife\Model\Species;
use GameOfLife\Model\World;
use PHPUnit\Framework\TestCase;

class SpeciesTest extends TestCase
{

    public function testPositions(): void
    {
        $testSpecies = new Species('Human', 2, 2);
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
            new Position(1, 1, new Species('Human', 1, 1)),
            new Position(2, 1, new Species('Human', 2, 1)),
            new Position(3, 1, null),
            new Position(1, 2, null),
            new Position(3, 2, new Species('Human', 3, 2)),
            new Position(1, 3, null),
            new Position(2, 3, new Species('Human', 2, 3)),
            new Position(3, 3, null),
        ];
        $expectedToBorn = [
            new Position(3, 1, null),
            new Position(1, 2, null),
            new Position(1, 3, null),
            new Position(3, 3, null),
        ];

        self::assertEquals($expected, $testSpecies->getNeighbors($world));
        self::assertEquals($expectedToBorn, $testSpecies->getNeighborsAvailableToBorn($world));
    }

    public function testPositionsCross(): void
    {
        $testSpecies = new Species('Human', 2, 2);
        $species = [
            ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 1],
            ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 1],
            ['species' => 'Human', 'pos_x' => 2, 'pos_y' => 2],
            ['species' => 'Human', 'pos_x' => 1, 'pos_y' => 3],
            ['species' => 'Human', 'pos_x' => 3, 'pos_y' => 3],
        ];
        $organisms = OrganismFactory::create($species);
        $world = new World(5, $organisms);
        $expected = [
            new Position(1, 1, new Species('Human', 1, 1)),
            new Position(2, 1, null),
            new Position(3, 1, new Species('Human', 3, 1)),
            new Position(1, 2, null),
            new Position(3, 2, null),
            new Position(1, 3, new Species('Human', 1, 3)),
            new Position(2, 3, null),
            new Position(3, 3, new Species('Human', 3, 3)),
        ];

        self::assertEquals($expected, $testSpecies->getNeighbors($world));
    }

}
