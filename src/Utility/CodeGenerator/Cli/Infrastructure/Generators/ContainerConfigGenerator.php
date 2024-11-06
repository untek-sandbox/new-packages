<?php

namespace Untek\Utility\CodeGenerator\Cli\Infrastructure\Generators;

use Untek\Component\Cqs\Application\Interfaces\CommandBusInterface;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Cli\Application\Commands\GenerateCliCommand;
use Untek\Utility\CodeGenerator\Cli\Infrastructure\Helpers\CliPathHelper;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\GeneratorInterface;

class ContainerConfigGenerator implements GeneratorInterface
{

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    public function generate(GenerateCliCommand $command): void
    {
        $cliCommandClassName = CliPathHelper::getCliCommandClass($command);
        $args = [
            CommandBusInterface::class,
        ];
        $consoleConfigGenerator = new \Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\ContainerConfigGenerator(
            $this->collection,
            $command->getNamespace(),
            null,
            '/resources/config/services/console.php'
        );
        $consoleConfigGenerator->generate($cliCommandClassName, $cliCommandClassName, $args, [
            'console.command'
        ]);
    }
}