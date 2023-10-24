<?php

declare(strict_types = 1);

namespace GameOfLife\Services\Configuration\Loaders;

use GameOfLife\Factories\ConfigurationFactory;
use GameOfLife\Game;
use GameOfLife\Model\Configuration;
use Nette\IOException;
use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class JsonLoader implements Loader
{

    public function getConfiguration(string $filePath): Configuration
    {
        try {
            $content = FileSystem::read($filePath);

            $result = Json::decode($content, true);
        } catch (IOException $exception) {
            throw new \RuntimeException('Read file failed.', 500, $exception);
        } catch (JsonException $exception) {
            throw new \RuntimeException('Parse json failed.', 500, $exception);
        }

        return ConfigurationFactory::create($result);
    }

    /**
     * @throws JsonException
     */
    public function getResult(Game $game): string
    {
        return Json::encode($game, true);
    }

    /**
     * @throws JsonException
     */
    public function save(Game $game, string $filePath): void
    {
        FileSystem::write($filePath, $this->getResult($game));
    }

}
