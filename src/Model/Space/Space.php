<?php

declare(strict_types=1);

namespace GameOfLife\Model\Space;

use GameOfLife\Model\Species;
use ReturnTypeWillChange;

class Space implements \Iterator
{
    /** @var array<int, array<int, Position>>  */
    protected array $items = [];

    protected array $types = [];

    private int $indexX = 0;

    private int $indexY = 0;

    public function __construct(int $dimension)
    {
        for ($x = 0; $x < $dimension; $x++) {
            for ($y = 0; $y < $dimension; $y++) {
                $this->items[$x][$y] = new Position($x, $y, null);
            }
        }
    }

    public function offsetExists(int $x, int $y): bool
    {
        return isset($this->items[$x][$y]);
    }

    public function offsetGet(int $x, int $y): Position
    {
        return $this->items[$x][$y];
    }

    public function offsetSet(int $x, int $y, ?Species $species): self
    {
        $this->items[$x][$y] = new Position($x, $y, $species);
        return $this;
    }

    public function offsetUnset(int $x, int $y): self
    {
        $position = $this->items[$x][$y];
        $this->items[$x][$y] = $position->updateSpecies(null);
        //$this->items[$x][$y] = new Position($x, $y, null);
        return $this;
    }

    public function current(): mixed
    {
        return $this->items[$this->indexX][$this->indexY];
    }

    public function next(): void
    {
        if (isset($this->items[$this->indexX + 1])) {
            $this->indexX++;
            return;
        }

        $this->indexY++;
    }

    #[ReturnTypeWillChange] public function key(): void
    {

        if (isset($this->items[$this->indexX + 1])) {
            $this->indexX++;
            return;
        }

        $this->indexY++;
    }

    public function valid(): bool
    {
        return $this->offsetExists($this->indexX, $this->indexY);
    }

    public function rewind(): void
    {
        $this->indexX = 0;
        $this->indexY = 0;
    }

    /**
     * @return array<int, array<int, Position>>
     */
    public function getPositions(): array
    {
        return $this->items;
    }
}