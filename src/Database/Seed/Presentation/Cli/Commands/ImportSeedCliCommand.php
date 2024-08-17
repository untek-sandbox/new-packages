<?php

namespace Untek\Database\Seed\Presentation\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Database\Seed\Application\Commands\ImportSeedCommand;
use Untek\Database\Seed\Application\Queries\GetTablesQuery;
use Untek\Framework\Console\Symfony4\Question\ChoiceQuestion;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Framework\Console\Symfony4\Widgets\LogWidgetIo;
use Untek\Model\Cqrs\Application\Services\CommandBusInterface;

class ImportSeedCliCommand extends Command
{

    public function __construct(private CommandBusInterface $bus)
    {
        parent::__construct(null);
    }

    protected function configure()
    {
        $this
            ->addOption(
                'withConfirm',
                null,
                InputOption::VALUE_REQUIRED,
                'Your selection migrations',
                true
            );
    }
    
    public static function getDefaultName(): string
    {
        return 'seed:import';
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $query = new GetTablesQuery();
        $tables = $this->bus->handle($query);

        $withConfirm = $input->getOption('withConfirm');
        if ($withConfirm) {
            $question = new ChoiceQuestion(
                'Select tables for import',
                $tables,
                'a'
            );
            $question->setMultiselect(true);
            $selectedTables = $io->askQuestion($question);
        } else {
            $selectedTables = $tables;
        }
        
        $logWidget = new LogWidgetIo($io);
        $logWidget->setPretty(true);
        $logWidget->setLineLength(64);

        $cb = function (string $tableName) use ($logWidget) {
            $logWidget->start($tableName);
            $logWidget->finishSuccess();
        };

        $command = new ImportSeedCommand();
        $command->setTables($selectedTables);
        $command->setProgressCallback($cb);
        $this->bus->handle($command);
        
        $io->success('Import completed successfully.');
        
        return Command::SUCCESS;
    }
}
