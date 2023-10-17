<?php

declare(strict_types = 1);

namespace GameOfLife;

use GameOfLife\Model\Organisms;
use GameOfLife\Model\Space\Position;
use GameOfLife\Model\Species;
use GameOfLife\Model\World;
use GameOfLife\Services\RandomBorn;

class Game
{

    private World $world;

    public function __construct(
        int $dimension,
        Organisms $organisms,
        private int $iterations,
        private RandomBorn $randomBorn
    )
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
        $listToBorn = $this->getPositionsToBorn();

        foreach ($this->world->getSpace()->getPositions() as $xPositions) {
            foreach ($xPositions as $position) {
                if ($position->getSpecies() !== null) {
                    $listToDie = array_merge($this->getListToDie($position->getSpecies()), $listToDie);
                }
            }
        }

        foreach ($listToDie as $species) {
            $this->world = $this->world->removeSpecies($species);
        }

        foreach ($listToBorn as $position) {
            $species = $position->getSpecies();
            $this->world = $this->world->updatePosition($species->getPositionX(), $species->getPositionY(), $species);
            $position = $this->world->getPosition($species->getPositionX(), $species->getPositionY());
            $position->updateSpecies($species);
        }
    }

    /**
     * @return Position[]
     */
    public function getPositionsToBorn(): array
    {
        /** @var array<string|int, array<int, array<int, Position>>> $positionsToBorn */
        $positionsToBorn = [];

        $positions = [];

        foreach ($this->world->getOrganisms()->getArrayCopy() as $organism) {
            foreach ($organism->getNeighborsAvailableToBorn($this->world) as $position) {
                $freeNeighbors = $position->getNeighborsToBorn($this->world);

                if (
                    isset($freeNeighbors[$organism->getType()])
                    && count($freeNeighbors[$organism->getType()]) === 3
                ) {

                    if (!isset($positions[$position->getX()][$position->getY()])) {
                        $newPosition = new Position(
                            $position->getX(),
                            $position->getY(),
                            new Species($organism->getType(), $position->getX(), $position->getY())
                        );
                        $positions[$position->getX()][$position->getY()] = $newPosition;
                        $positionsToBorn[] = $newPosition;
                    } elseif ($this->randomBorn->hasReplace()) {
                        // @phpstan-ignore-next-line
                        $key = array_search($positions[$position->getX()][$position->getY()], $positionsToBorn, false); // @phpstan-ignore function.strict
                        $positionsToBorn[$key] = new Position(
                            $position->getX(),
                            $position->getY(),
                            new Species($organism->getType(), $position->getX(), $position->getY())
                        );
                    }
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
