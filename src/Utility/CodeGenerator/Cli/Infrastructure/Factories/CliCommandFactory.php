<?php

namespace Untek\Utility\CodeGenerator\Cli\Infrastructure\Factories;

use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Utility\CodeGenerator\Application\Application\Helpers\TypeHelper;
use Untek\Utility\CodeGenerator\Application\Infrastructure\Helpers\ApplicationPathHelper;
use Untek\Utility\CodeGenerator\Cli\Application\Commands\GenerateCliCommand;

class CliCommandFactory
{

    public static function create($namespace, $commandType, $commandName, $properties, $cliCommand): GenerateCliCommand
    {
        $moduleName = ClassHelper::getClassOfClassName($namespace);
        $commandClassName = ApplicationPathHelper::generateCommandClass($namespace, $commandType, $commandName);

        return new GenerateCliCommand(
            $namespace,
            $moduleName,
            $commandClassName,
            $cliCommand,
            $properties,
        );
    }
}