<?php

declare(strict_types = 1);

namespace GameOfLife;

use GameOfLife\Model\Organisms;
use GameOfLife\Model\Space\Position;
use GameOfLife\Model\Species;
use GameOfLife\Model\World;

class Game
{

    private World $world;

    public function __construct(int $dimension, Organisms $organisms, private int $iterations)
    {
        $this->world = new World($dimension, $organisms);
    }

    public function getWorld(): World
    {
        return $this->world;
    }

    public function play(): void
    {
        $i = 0;
        while ($i < $this->iterations) {
            $i++;
            $this->next();
        }
    }

    public function next(): void
    {
        $listToDie = [];
        $listToBorn = $this->listToBorn();

        foreach ($this->world->getSpace()->getPositions() as $x => $xPositions) {
            foreach ($xPositions as $y => $position) {
                if ($position->getSpecies() !== null) {
                    $listToDie = array_merge($this->getListToDie($position->getSpecies()), $listToDie);
                }
            }
        }

        foreach ($listToDie as $species) {
            $this->world = $this->world->removeSpecies($species);
        }

        foreach ($listToBorn as $species) {
            $this->world = $this->world->updatePosition($species->getPositionX(), $species->getPositionY(), $species);
            $position = $this->world->getPosition($species->getPositionX(), $species->getPositionY());
            $position->updateSpecies($species);
        }
    }

    /**
     * @return Species[]
     */
    public function listToBorn(): array
    {
        $positionsToBorn = $this->getPositionsToBorn();
        $toBorn = [];

        foreach ($positionsToBorn as $type => $xPositions) {
            foreach ($xPositions as $x => $positions) {
                foreach ($positions as $y => $position) {
                    $toBorn[] = new Species($type, $x, $y);
                }
            }
        }

        return $toBorn;
    }

    /**
     * @return array<string|int, array<int, array<int, Position>>>
     */
    public function getPositionsToBorn(): array
    {
        /** @var array<string|int, array<int, array<int, Position>>> $positionsToBorn */
        $positionsToBorn = [];
        foreach ($this->world->getOrganisms()->getArrayCopy() as $organism) {
            foreach ($organism->getNeighborsAvailableToBorn($this->world) as $position) {
                $freeNeighbors = $position->getNeighborsToBorn($this->world);

                if (isset($freeNeighbors[$organism->getType()]) && count($freeNeighbors[$organism->getType()]) === 3) {
                    $positionsToBorn[$organism->getType()][$position->getX()][$position->getY()] = $position;
                }
            }
        }

        return $positionsToBorn;
    }

    /**
     * @return Species[]
     */
    public function getListToDie(Species $species): array
    {
        $neighborsCount = $this->getSameSpeciesNeighborsAmount($species);
        $toDie = [];

        if ($neighborsCount < 2 || $neighborsCount >= 4) {
            $toDie[] = $species;
        }

        return $toDie;
    }

    public function getSameSpeciesNeighborsAmount(Species $species): int
    {
        $amount = 0;
        $neighbors = $this->getSameSpeciesNeighbors($species);
        foreach ($neighbors as $position) {
            if ($position->getSpecies() !== null) {
                $amount++;
            }
        }

        return $amount;
    }

    /**
     * @return Position[]
     */
    public function getSameSpeciesNeighbors(Species $species): array
    {
        $neighbors = $species->getSameTypeNeighbors($this->world);
        return array_values($neighbors);
    }

    /** @return array<int, Position> */
    public function getNeighbors(Species $species): array
    {
        $neighbors = $species->getNeighbors($this->world);
        return array_values($neighbors);
    }

}
