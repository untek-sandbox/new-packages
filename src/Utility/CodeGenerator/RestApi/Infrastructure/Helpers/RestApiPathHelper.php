<?php

namespace Untek\Utility\CodeGenerator\RestApi\Infrastructure\Helpers;

use Untek\Core\Instance\Helpers\ClassHelper;
use Yiisoft\Strings\Inflector;
use Untek\Utility\CodeGenerator\Application\Infrastructure\Helpers\ApplicationPathHelper;
use Untek\Utility\CodeGenerator\RestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGenerator\RestApi\Application\Helpers\CommandHelper;

class RestApiPathHelper
{

    public static function getRestApiSchemaClass(GenerateRestApiCommand $command): string
    {
        $commandFullClassName = ApplicationPathHelper::getCommandClass($command);
        $commandClassName = ClassHelper::getClassOfClassName($commandFullClassName);
        $commandClassName = (new Inflector())->toPascalCase($commandClassName);
//        $endCommandClassName = CommandHelper::getType($command->getCommandClass());
        $pureCommandClassName = substr($commandClassName, 0, 0 - strlen($command->getCommandType()));
        return $command->getNamespace() . '\\Presentation\\Http\\RestApi\\Schema\\' . $pureCommandClassName . 'Schema';
    }

    public static function getControllerRouteName(GenerateRestApiCommand $command): string
    {
        return $command->getHttpMethod() . '_' . $command->getUri();
    }

    public static function getControllerClass(GenerateRestApiCommand $command): string
    {
        $commandFullClassName = ApplicationPathHelper::getCommandClass($command);
        $commandClassName = ClassHelper::getClassOfClassName($commandFullClassName);
        $commandClassName = (new Inflector())->toPascalCase($commandClassName);
//        $endCommandClassName = CommandHelper::getType($command->getCommandClass());
        $pureCommandClassName = substr($commandClassName, 0, 0 - strlen($command->getCommandType()));
        return $command->getNamespace() . '\\Presentation\\Http\\RestApi\\Controllers\\' . $pureCommandClassName . 'Controller';
    }

    public static function getControllerTestClass(GenerateRestApiCommand $command): string
    {
        $commandFullClassName = ApplicationPathHelper::getCommandClass($command);
        $commandClassName = ClassHelper::getClassOfClassName($commandFullClassName);
        $commandClassName = (new Inflector())->toPascalCase($commandClassName);
//        $endCommandClassName = CommandHelper::getType($command->getCommandClass());
        $pureCommandClassName = substr($commandClassName, 0, 0 - strlen($command->getCommandType()));
        return 'Tests\\RestApi\\'.$command->getModuleName().'\\' . $pureCommandClassName . 'Test';
    }
}