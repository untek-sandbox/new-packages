<?php

namespace Untek\Utility\CodeGeneratorCli\Infrastructure\Helpers;

use Untek\Utility\CodeGeneratorCli\Application\Commands\GenerateCliCommand;
use Untek\Core\Instance\Helpers\ClassHelper;
use Yiisoft\Strings\Inflector;
use Untek\Utility\CodeGeneratorRestApi\Application\Helpers\CommandHelper;

class CliPathHelper
{

    public static function getCliCommandClass(GenerateCliCommand $command): string
    {
        $commandClassName = ClassHelper::getClassOfClassName($command->getCommandClass());
        $commandClassName = (new Inflector())->toCamelCase($commandClassName);
        $endCommandClassName = CommandHelper::getType($command->getCommandClass());
        $pureCommandClassName = substr($commandClassName, 0, 0 - strlen($endCommandClassName));
        return $command->getNamespace() . '\\Presentation\\Cli\\Commands\\' . $pureCommandClassName . 'CliCommand';
    }

    /*public static function getCommandTestClass(GenerateCliCommand $command): string
    {
        $commandClassName = ClassHelper::getClassOfClassName($command->getCommandClass());
        $commandClassName = Inflector::camelize($commandClassName);
        $endCommandClassName = CommandHelper::getType($command->getCommandClass());
        $pureCommandClassName = substr($commandClassName, 0, 0 - strlen($endCommandClassName));
        return 'Tests\\Console\\' . $command->getModuleName() . '\\' . $pureCommandClassName . 'Test';
    }*/
}