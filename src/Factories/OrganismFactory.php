<?php

declare(strict_types=1);

namespace GameOfLife\Factories;

use GameOfLife\Species;

class OrgamismFactory
{
    /**
     * @param array $species
     * @return Species[]
     */
    public static function create(array $species): array
    {
        $organism = [];

        foreach ($species as $entity) {
            $organism[] = new Species($entity['species'], $entity['pos_x'], $entity['pos_y']);
        }

        return $organism;
    }
}