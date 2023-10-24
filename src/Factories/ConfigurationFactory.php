<?php

declare(strict_types = 1);

namespace GameOfLife\Factories;

use GameOfLife\Model\Configuration;

class ConfigurationFactory
{

    public static function create(array $data): Configuration
    {
        $world = $data['world'];
        $organisms = OrganismFactory::create($data['organisms']);

        return new Configuration($world['dimension'], $world['speciesCount'], $world['iterationsCount'], $organisms);
    }

}
