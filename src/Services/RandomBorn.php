<?php

declare(strict_types = 1);

namespace GameOfLife\Services;

class RandomBorn
{

    public function hasReplace(): bool
    {
        return rand(0, 1) === 0;
    }

}
