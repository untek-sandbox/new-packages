<?php

namespace Untek\Utility\CodeGenerator\CodeGenerator\Presentation\Cli\Commands;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Component\FileSystem\Helpers\FilePathHelper;
use Untek\Component\FormatAdapter\StoreFile;
use Untek\Component\Arr\Helpers\ExtArrayHelper;
use Untek\Core\Container\Helpers\ContainerHelper;
use Untek\Component\FileSystem\Helpers\FileHelper;
use Untek\Component\Text\Helpers\TemplateHelper;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Component\Measure\Byte\Helpers\ByteSizeFormatHelper;
use Untek\Component\Cqrs\Application\Services\CommandBusInterface;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\InfoResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\ResultList;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\InteractInterface;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers\GeneratorHelper;

class GenerateCodeCommand extends Command
{

    public function __construct(
        private CommandBusInterface $bus,
        private ContainerInterface $container,
        private array $interacts = [],
    )
    {
        parent::__construct();
    }

    public static function getDefaultName(): ?string
    {
        return 'code-generator:generate';
    }

    protected function configure()
    {
        $this->addOption(
            'inputFile',
            null,
            InputOption::VALUE_OPTIONAL,
            'File with input data'
        );
    }

    protected function input(SymfonyStyle $io): array
    {
        $commands = [];
        foreach ($this->interacts as $interact) {
            /** @var InteractInterface $interact */
            $interactCommands = $interact->input($io);
            if ($interactCommands) {
                $commands = ExtArrayHelper::merge($commands, $interactCommands);
            }
        }
        return $commands;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $inputFile = $input->getOption('inputFile');
        if ($inputFile) {
            $renderParams = ['directory' => $_SERVER['OLDPWD'] ?? null];
            $inputFile = TemplateHelper::render($inputFile, $renderParams, '{{', '}}');
            $store = new StoreFile($inputFile);
            $commands = $store->load();
        } else {
            $commands = $this->input($io);
        }
        if ($commands) {
            try {
                $collection = $this->handleCommands($commands);
            } catch (UnprocessableEntityException $exception) {
                $errors = [];
                foreach ($exception->getViolations() as $violation) {
                    $fieldName = $violation->getPropertyPath();
                    $error = "$fieldName: {$violation->getMessage()}";
                    $errors[] = $error;
                }
//                dd($exception->getViolations());
                throw new \Exception('Unprocessable entity.' . PHP_EOL . PHP_EOL . implode(PHP_EOL, $errors));
            }
            $this->outputGeneratedFiles($collection, $io);
            $this->outputInfoTable($collection, $io);
            $io->success('Code generated successfully');
        }
        return Command::SUCCESS;
    }

    protected function handleCommands(array $commands): GenerateResultCollection
    {
        foreach ($commands as $command) {
            $this->bus->handle($command);
        }
        $collection = $this->container->get(GenerateResultCollection::class);
        GeneratorHelper::dump($collection);
        return $this->sortCollection($collection);
    }

    protected function sortCollection(GenerateResultCollection $collection): GenerateResultCollection {
        $items = [];
        foreach ($collection->getAll() as $result) {
            $items[$result->getName()] = $result;
        }
        ksort($items);
        return new GenerateResultCollection($items);
    }

    private function outputGeneratedFiles(GenerateResultCollection $collection, SymfonyStyle $io): void
    {
        $io->title('Generated files');
        $generatedFiles = $this->getFilesList($collection);
        $io->listing($generatedFiles);
    }

    private function outputInfoTable(GenerateResultCollection $collection, SymfonyStyle $io): void
    {
        $io->title('Info');
        $table = $this->generateInfoTableRows($collection);
        $size = $this->calculateSize($collection);
        $generatedFiles = $this->getFilesList($collection);
        $table[] = ['Total files', count($generatedFiles)];
        $table[] = ['New files', $this->newFilesCount($collection)];
        $table[] = ['Updated files', $this->updateFilesCount($collection)];
        $table[] = ['Total size', ByteSizeFormatHelper::sizeFormat($size)];
        $io->table([], $table);
    }

    private function generateInfoTableRows(GenerateResultCollection $collection): array
    {
        $table = [];
        foreach ($collection->getAll() as $result) {
            if ($result instanceof InfoResult || $result instanceof ResultList) {
                $table[] = [$result->getName(), $result->getContent()];
            }
        }
        return $table;
    }

    private function newFilesCount(GenerateResultCollection $collection): int
    {
        $count = 0;
        foreach ($collection->getAll() as $result) {
            if ($result instanceof FileResult && $result->isNew()) {
                $count++;
            }
        }
        return $count;
    }

    private function updateFilesCount(GenerateResultCollection $collection): int
    {
        $count = 0;
        foreach ($collection->getAll() as $result) {
            if ($result instanceof FileResult && !$result->isNew()) {
                $count++;
            }
        }
        return $count;
    }

    private function calculateSize(GenerateResultCollection $collection): int
    {
        $size = 0;
        foreach ($collection->getAll() as $result) {
            if ($result instanceof FileResult && $result->isNew()) {
                $size = $size + mb_strlen($result->getContent());
            }
        }
        return $size;
    }

    private function getFilesList(GenerateResultCollection $collection): array
    {
        $list = [];
        foreach ($collection->getAll() as $result) {
            if ($result instanceof FileResult) {
                $file = GeneratorFileHelper::fileNameTotoRelative($result->getName());
                $prefix = $result->isNew() ? '<bg=green>ADD</>' : '<bg=blue>UPD</>';
                $list[] = $prefix . ' ' . $file;
            }
        }
        return $list;
    }
}
