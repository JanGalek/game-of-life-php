<?php

declare(strict_types=1);

namespace GameOfLife\Services\Configuration\Loaders;

use GameOfLife\Game;
use Nette\IOException;
use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class JsonLoader implements Loader
{

    public function getConfiguration(string $filePath): array
    {
        try {
            $content = FileSystem::read($filePath);
            return Json::decode($content, true);
        } catch (IOException $exception) {
            throw new \RuntimeException('Read file failed.', 500, $exception);
        } catch (JsonException $exception) {
            throw new \RuntimeException('Parse json failed.', 500, $exception);
        }
    }

    public function getResult(Game $game): string
    {
        return Json::encode($game, true);
    }

    public function save(Game $game, string $filePath): void
    {
        FileSystem::write($filePath, $this->getResult($game));
    }
}