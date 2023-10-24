<?php

declare(strict_types = 1);

namespace GameOfLife\Services\Configuration\Loaders;

use GameOfLife\Game;
use GameOfLife\Model\Configuration;
use RuntimeException;

interface Loader
{

    /**
     * @throws RuntimeException
     */
    public function getConfiguration(string $filePath): Configuration;

    public function getResult(Game $game): string;

}
