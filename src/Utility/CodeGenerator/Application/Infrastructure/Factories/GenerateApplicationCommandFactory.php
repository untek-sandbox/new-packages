<?php

namespace Untek\Utility\CodeGenerator\Application\Infrastructure\Factories;

use Symfony\Contracts\Translation\TranslatorInterface;
use Untek\Utility\CodeGenerator\Application\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGenerator\Application\Infrastructure\Generators\CommandHandlerGenerator;

class GenerateApplicationCommandFactory
{

    public static function create($namespace, $type, $entityName, $properties, array $parameters = [], string $modelName = null): GenerateApplicationCommand {
        $parameters[CommandHandlerGenerator::class]['constructArguments'] = array_merge([TranslatorInterface::class], $parameters[CommandHandlerGenerator::class]['constructArguments'] ?? []);


        $command = new GenerateApplicationCommand();
        $command->setNamespace($namespace);
        $command->setCommandType($type);
        $command->setCommandName($entityName);
        $command->setProperties($properties);
        $command->setParameters($parameters);
        $command->setModelName($modelName);
        return $command;
    }
}