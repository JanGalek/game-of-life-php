<?php

declare(strict_types = 1);

namespace GameOfLife\Factories;

use GameOfLife\Model\Organisms;
use GameOfLife\Model\Species;

class OrganismFactory
{

    protected function __construct()
    {
        // Factory
    }

    /**
     * @param array<int, array<string, mixed>> $species
     */
    public static function create(array $species): Organisms
    {
        $organism = [];

        foreach ($species as $entity) {
            $organism[] = new Species($entity['species'], $entity['pos_x'], $entity['pos_y']);
        }

        return new Organisms($organism);
    }

}
