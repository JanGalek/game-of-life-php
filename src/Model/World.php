<?php

declare(strict_types = 1);

namespace GameOfLife\Model;

use GameOfLife\Model\Space\Position;
use GameOfLife\Model\Space\Space;

class World
{

    private Space $space;

    public function __construct(private readonly int $dimension, private Organisms $organisms)
    {
        $this->space = new Space($dimension);
        $this->fillSpecies($this->organisms);
    }

    public function getSpace(): Space
    {
        return $this->space;
    }

    public function getSpeciesByPosition(int $x, int $y): ?Species
    {
        $position = $this->getPosition($x, $y);
        return $position->getSpecies();
    }

    public function removeSpecies(Species $species): self
    {
        $this->space = $this->space->offsetUnset($species->getPositionX(), $species->getPositionY());
        $this->organisms = $this->organisms->remove($species);
        return $this;
    }

    public function updatePosition(int $x, int $y, ?Species $species): self
    {
        $this->space = $this->space->offsetSet($x, $y, $species);
        $this->organisms = $this->organisms->update($species);
        return $this;
    }

    public function getPosition(int $x, int $y): Position
    {
        return $this->space->offsetGet($x, $y);
    }

    public function getDimension(): int
    {
        return $this->dimension;
    }

    public function getOrganisms(): Organisms
    {
        return $this->organisms;
    }

    public function fillSpecies(Organisms $organisms): void
    {
        foreach ($organisms as $entity) {
            $this->fillSpeciesToPosition($entity);
        }
    }

    private function fillSpeciesToPosition(Species $species): void
    {
        if (!$this->space->offsetExists($species->getPositionX(), $species->getPositionY())) {
            throw new \RuntimeException(
                sprintf(
                    'World space error. Trying set species to position x: %d, y: %d, space size: %d',
                    $species->getPositionX(),
                    $species->getPositionY(),
                    $this->dimension
                )
            );
        }

        $this->updatePosition($species->getPositionX(), $species->getPositionY(), $species);
    }

}
