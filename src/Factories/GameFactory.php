<?php

declare(strict_types = 1);

namespace GameOfLife\Factories;

use GameOfLife\Game;
use GameOfLife\Model\Configuration;
use GameOfLife\Services\RandomBorn;

class GameFactory
{

    protected function __construct()
    {
        // Factory
    }

    public static function create(Configuration $configuration): Game
    {
        return new Game(
            $configuration->getDimension(),
            $configuration->getOrganisms(),
            $configuration->getIterationsCount(),
            new RandomBorn()
        );
    }

}
