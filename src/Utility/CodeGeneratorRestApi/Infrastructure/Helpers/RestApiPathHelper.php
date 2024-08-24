<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Helpers;

use Untek\Core\Instance\Helpers\ClassHelper;
use Yiisoft\Strings\Inflector;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Application\Helpers\CommandHelper;

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