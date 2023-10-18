<?php

declare(strict_types = 1);

namespace GameOfLife\Factories;

use GameOfLife\Game;
use GameOfLife\Services\RandomBorn;

class GameFactory
{

    protected function __construct()
    {
        // Factory
    }

    public static function create(array $configuration): Game
    {
        $world = $configuration['world'];
        $organisms = OrganismFactory::create($configuration['organisms']);
        return new Game($world['dimension'], $organisms, $world['iterationsCount'], new RandomBorn());
    }

}
