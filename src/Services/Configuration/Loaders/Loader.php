<?php
declare(strict_types=1);

namespace GameOfLife\Services\Configuration\Loaders;

use GameOfLife\Game;
use RuntimeException;

interface Loader
{
    /**
     * @throws RuntimeException
     */
    public function getConfiguration(string $filePath): array;

    public function getResult(Game $game): string;
}