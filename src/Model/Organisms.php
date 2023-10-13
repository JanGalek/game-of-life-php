<?php

declare(strict_types = 1);

namespace GameOfLife\Model;

use ArrayAccess;
use Iterator;

class Organisms implements Iterator, ArrayAccess
{
    /** @var Species[] */
    protected array $items = [];

    protected array $types = [];

    private int $index = 0;

    /**
     * @param Species[] $items
     */
    public function __construct(array $items)
    {
        $this->items = array_values($items);
        foreach ($this->items as $item) {
            $this->types[$item->getType()][] = $item;
        }
    }

    public function current(): mixed
    {
        return $this->items[$this->index];
    }

    public function next(): void
    {
        $this->index++;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return array_key_exists($this->index, $this->items);
    }

    public function rewind(): void
    {
        $this->index = 0;
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->items[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    public function remove(Species $species): self
    {
        $this->offsetUnset(array_search($species, $this->items));

        return new Organisms($this->items);
    }

    public function update(?Species $species): self
    {
        $this->offsetSet(array_search($species, $this->items), $species);

        return new Organisms($this->items);
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @return Species[]
     */
    public function getArrayCopy(): array
    {
        return $this->items;
    }

}
