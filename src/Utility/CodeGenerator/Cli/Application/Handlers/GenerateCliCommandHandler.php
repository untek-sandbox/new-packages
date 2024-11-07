<?php

namespace Untek\Utility\CodeGenerator\Cli\Application\Handlers;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\InfoResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers\GeneratorHelper;
use Untek\Utility\CodeGenerator\Application\Infrastructure\Generators\ContainerConfigImportGenerator;
use Untek\Utility\CodeGenerator\Cli\Application\Commands\GenerateCliCommand;
use Untek\Utility\CodeGenerator\Cli\Application\Validators\GenerateCliCommandValidator;
use Untek\Utility\CodeGenerator\Cli\Infrastructure\Generators\CliCommandGenerator;
use Untek\Utility\CodeGenerator\Cli\Infrastructure\Generators\CliCommandShortcutGenerator;
use Untek\Utility\CodeGenerator\Cli\Infrastructure\Generators\ConsoleCommandConfigGenerator;
use Untek\Utility\CodeGenerator\Cli\Infrastructure\Generators\ContainerConfigGenerator;

#[AsMessageHandler]
class GenerateCliCommandHandler
{

    public function __construct(
        protected GenerateResultCollection $collection,
        private GenerateCliCommandValidator $commandValidator,
    )
    {
    }

    /**
     * @param GenerateCliCommand $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(GenerateCliCommand $command)
    {
//        $validator = new GenerateCliCommandValidator();
        $this->commandValidator->validate($command);

        $generators = [
            new CliCommandGenerator($this->collection),
//            new ConsoleCommandConfigGenerator($this->collection),
//            new ContainerConfigGenerator($this->collection),
//            new ContainerConfigImportGenerator($this->collection, '/resources/config/services/console.php', __DIR__ . '/../../../../../../../../config/console/container.php'),
            new CliCommandShortcutGenerator($this->collection),
        ];

        GeneratorHelper::generate($generators, $command);
//        GeneratorHelper::dump($this->collection);

        $cliCommand = $command->getCliCommand();
        $this->collection->addInfo(new InfoResult('CLI command', 'php bin/console ' . $cliCommand));
    }
}