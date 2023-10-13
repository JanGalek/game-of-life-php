<?php

declare(strict_types = 1);

namespace GameOfLife\Model;

use GameOfLife\Model\Space\Position;

class Species
{

    public function __construct(private string $type, private int $positionX, private int $positionY)
    {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPositionX(): int
    {
        return $this->positionX;
    }

    public function getPositionY(): int
    {
        return $this->positionY;
    }

    /**
     * @return Position[]
     */
    public function getNeighbors(World $world): array
    {
        return Position::getNeighborsPositions($world, $this->positionX, $this->positionY);
    }

    /**
     * @return Position[]
     */
    public function getSameTypeNeighbors(World $world): array
    {
       $neighbors = $this->getNeighbors($world);

       foreach ($neighbors as $index => $position) {
           if ($position->getSpecies() !== null && $position->getSpecies()->getType() !== $this->type) {
               unset($neighbors[$index]);
           }
       }

       return $neighbors;
    }

    /**
     * @return Position[]
     */
    public function getNeighborsAvailableToBorn(World $world): array
    {
        $neighbors = $this->getNeighbors($world);

        foreach ($neighbors as $index => $position) {
            if ($position->getSpecies() !== null) {
                unset($neighbors[$index]);
            }
        }

        return array_values($neighbors);
    }

    public function isOnSamePosition(Species $species): bool
    {
        return $this->positionY === $species->getPositionY() && $this->positionX === $species->getPositionX();
    }

}