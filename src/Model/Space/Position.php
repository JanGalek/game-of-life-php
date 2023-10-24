<?php

declare(strict_types = 1);

namespace GameOfLife\Model\Space;

use GameOfLife\Model\Species;
use GameOfLife\Model\World;
use JsonSerializable;
use Stringable;

class Position implements JsonSerializable, Stringable
{

    public function __construct(
        private readonly int $x,
        private readonly int $y,
        private ?Species $species = null
    )
    {
    }

    /**
     * @return Position[]
     */
    public static function getNeighborsPositions(World $world, int $x, int $y): array
    {
        $posX = $x;
        $posY = $y;
        $neighbors = [];

        if ($posY > 0) {
            if ($posX > 0) {
                $neighbors[] = $world->getSpace()->offsetGet($posX - 1, $posY - 1);
            }

            $neighbors[] = $world->getSpace()->offsetGet($posX, $posY - 1);
            if ($posX + 1 < $world->getDimension()) {
                $neighbors[] = $world->getSpace()->offsetGet($posX + 1, $posY - 1);
            }
        }

        if ($posX > 0) {
            $neighbors[] = $world->getSpace()->offsetGet($posX - 1, $posY);
        }

        if ($posX + 1 < $world->getDimension()) {
            $neighbors[] = $world->getSpace()->offsetGet($posX + 1, $posY);
        }

        if ($posY + 1 < $world->getDimension()) {
            if ($posX > 0) {
                $neighbors[] = $world->getSpace()->offsetGet($posX - 1, $posY + 1);
            }

            $neighbors[] = $world->getSpace()->offsetGet($posX, $posY + 1);

            if ($posX + 1 < $world->getDimension()) {
                $neighbors[] = $world->getSpace()->offsetGet($posX + 1, $posY + 1);
            }
        }

        return $neighbors;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getSpecies(): ?Species
    {
        return $this->species;
    }

    public function updateSpecies(?Species $species): self
    {
        $this->species = $species;

        return $this;
    }

    /**
     * @return Position[]
     */
    public function getNeighbors(World $world): array
    {
        return self::getNeighborsPositions($world, $this->x, $this->y);
    }

    /**
     * @return array<int|string, array<int, Position>>
     */
    public function getNeighborsToBorn(World $world): array
    {
        $neighbors = $this->getNeighbors($world);
        $neighbors2 = [];

        foreach ($neighbors as $position) {
            if ($position->getSpecies() !== null) {
                $neighbors2[$position->getSpecies()->getType()][] = $position;
            }
        }

        return $neighbors2;
    }

    public function jsonSerialize(): array
    {
        return [
            'pos_x' => $this->x,
            'pos_y' => $this->y,
            'organism' => $this->species,
        ];
    }

    public function __toString(): string
    {
        return $this->species === null ? ' ' : $this->species->getType();
    }

}
