<?php

declare(strict_types = 1);

namespace GameOfLife\Commands;

use GameOfLife\Factories\GameFactory;
use GameOfLife\Game;
use GameOfLife\Model\Space\Position;
use GameOfLife\Services\Configuration\Loaders\JsonLoader;
use Nette\Utils\DateTime;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('game:play')]
class PlayCommand extends Command
{

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'File of configuration')
            ->addOption('save', 's')
            ->addOption('stepByStep', 'steps');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $input->getArgument('file');
        $save = $input->getOption('save');
        $stepByStep = $input->getOption('stepByStep');

        $loader = new JsonLoader();

        try {
            $configuration = $loader->getConfiguration($file);
            $game = GameFactory::create($configuration);
        } catch (RuntimeException $exception) {
            return Command::INVALID;
        }

        if ($stepByStep !== false && $stepByStep !== null) {
            if (!$output instanceof ConsoleOutputInterface) {
                throw new \LogicException('This command accepts only an instance of "ConsoleOutputInterface');
            }

            $this->renderStepByStep($game, $output);
        } else {
            $game->play();
        }

        if ($save !== false && $save !== null) {
            $loader->save($game, 'temp/' . date_format(new DateTime(), 'Y-m-d') . '.json');
        }

        return Command::SUCCESS;
    }

    private function renderStepByStep(Game $game, ConsoleOutputInterface $output): void
    {
        $tableStyle = new TableStyle();
        $tableStyle
            ->setDefaultCrossingChar('-')
            ->setVerticalBorderChars('|', '|')
            ->setHorizontalBorderChars('-', '-');

        $table = new Table($output);
        $table->setStyle($tableStyle);

        $i = 0;
        while ($i < $game->getIterations()) {
            $this->renderTable($i, $table, $game->getWorld()->getSpace()->getPositions());
            $i++;
            $game->next();
        }

        $this->renderTable($game->getIterations(), $table, $game->getWorld()->getSpace()->getPositions());
    }

    /**
     * @param Position[][] $positions
     */
    private function renderTable(int $iteration, Table $table, array $positions): void
    {
        $table->setRows([]);
        $table->setHeaderTitle(sprintf('Iteration %d', $iteration));
        foreach ($positions as $position) {
            $table->addRow($position);
            $table->addRow(new TableSeparator());
        }

        $table->render();
    }

}
