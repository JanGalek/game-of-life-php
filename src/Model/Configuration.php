<?php

declare(strict_types = 1);

namespace GameOfLife\Model;

readonly class Configuration
{

    private int $dimension;

    private int $speciesCount;

    private int $iterationsCount;

    private Organisms $organisms;

    public function __construct(int $dimension, int $speciesCount, int $iterationsCount, Organisms $organisms)
    {
        $this->dimension = $dimension;
        $this->speciesCount = $speciesCount;
        $this->iterationsCount = $iterationsCount;
        $this->organisms = $organisms;
    }

    public function getDimension(): int
    {
        return $this->dimension;
    }

    public function getSpeciesCount(): int
    {
        return $this->speciesCount;
    }

    public function getIterationsCount(): int
    {
        return $this->iterationsCount;
    }

    public function getOrganisms(): Organisms
    {
        return $this->organisms;
    }

}
