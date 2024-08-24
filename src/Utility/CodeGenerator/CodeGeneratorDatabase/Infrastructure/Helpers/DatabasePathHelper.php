<?php

namespace Untek\Utility\CodeGenerator\CodeGeneratorDatabase\Infrastructure\Helpers;

use Yiisoft\Strings\Inflector;
use Untek\Utility\CodeGenerator\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;

class DatabasePathHelper
{

    public static function getModelClass(object $command): string
    {
        return $command->getNamespace() . '\\Domain\\Model\\' . $command->getModelName();
    }

    public static function getSeedClass(object $command): string
    {
        return $command->getNamespace() . '\\Infrastructure\\Persistence\\Seeds\\' . $command->getModelName() . 'Seed';
    }

    public static function getNormalizerClass(object $command): string
    {
        return $command->getNamespace() . '\\Infrastructure\\Persistence\\Normalizer\\' . $command->getModelName() . 'Normalizer';
    }

    public static function getRelationClass(object $command): string
    {
        return $command->getNamespace() . '\\Infrastructure\\Persistence\\Relation\\' . $command->getModelName() . 'Relation';
    }

    public static function getRepositoryInterface(object $command): string
    {
        return $command->getNamespace() . '\\Application\\Services\\' . $command->getModelName() . 'RepositoryInterface';
    }

    public static function getRepositoryClass(object $command, string $driver): string
    {
        $driverName = (new Inflector())->toPascalCase($driver);
        return $command->getNamespace() . '\\Infrastructure\\Persistence\\' . $driverName . '\\Repository\\' . $command->getModelName() . 'Repository';
    }
}