<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Utility\CodeGenerator\Database\Application\Handlers\GenerateDatabaseCommandHandler;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire()->autoconfigure();

    $services
        ->load('Untek\Utility\CodeGenerator\Database\\', __DIR__ . '/../../..')
        ->exclude([
            __DIR__ . '/../../../Application/**/*{Command.php,Query.php}',
            __DIR__ . '/../../../{resources,Domain}',
            __DIR__ . '/../../../**/*{Event.php,Helper.php,Message.php,Task.php,Relation.php,Normalizer.php}',
            __DIR__ . '/../../../**/{Dto,Enums}',
            __DIR__ . '/../../../**/{Generators}',
        ]);
};