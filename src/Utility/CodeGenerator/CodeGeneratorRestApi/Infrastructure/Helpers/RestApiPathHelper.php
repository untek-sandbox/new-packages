<?php

namespace Untek\Utility\CodeGenerator\CodeGeneratorRestApi\Infrastructure\Helpers;

use Untek\Core\Instance\Helpers\ClassHelper;
use Yiisoft\Strings\Inflector;
use Untek\Utility\CodeGenerator\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;
use Untek\Utility\CodeGenerator\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGenerator\CodeGeneratorRestApi\Application\Helpers\CommandHelper;

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