<?php

namespace Untek\Utility\CodeGenerator\CodeGeneratorDatabase\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGenerator\CodeGeneratorDatabase\Infrastructure\Helpers\DatabasePathHelper;
use Illuminate\Database\Capsule\Manager;

class ContainerConfigGenerator
{

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    public function generate(GenerateDatabaseCommand $command): void
    {
        $repositoryClassName = DatabasePathHelper::getRepositoryClass($command, $command->getRepositoryDriver());
        $repositoryInterfaceClassName = DatabasePathHelper::getRepositoryInterface($command);
        $consoleConfigGenerator = new \Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\ContainerConfigGenerator($this->collection, $command->getNamespace());
        $consoleConfigGenerator->generate($repositoryInterfaceClassName, $repositoryClassName, [], [], 'alias');
    }
}