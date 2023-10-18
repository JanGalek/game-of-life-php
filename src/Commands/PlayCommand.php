<?php

declare(strict_types=1);

namespace GameOfLife\Commands;

use GameOfLife\Factories\GameFactory;
use GameOfLife\Game;
use GameOfLife\Services\Configuration\Loaders\JsonLoader;
use Nette\Utils\DateTime;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PlayCommand extends Command
{
    protected static $defaultName = 'game:play';

    protected function configure()
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'File of configuration')
            ->addOption('save', 's');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        $save = $input->getOption('save');
        $loader = new JsonLoader();

        try {
            $configuration = $loader->getConfiguration($file);
            $game = GameFactory::create($configuration);
        } catch (RuntimeException $exception) {
            return Command::INVALID;
        }

        $game->play();

        if ($save !== false && $save !== null) {
            $loader->save($game, 'temp/' . date_format(new DateTime(), 'Y-m-d') . '.json');
        }

        return Command::SUCCESS;
    }
}