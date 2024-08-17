<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Utility\CodeGeneratorDatabase\Application\Handlers\GenerateDatabaseCommandHandler;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autoconfigure();

    $services->set(GenerateDatabaseCommandHandler::class, GenerateDatabaseCommandHandler::class)
        ->args([
            service(GenerateResultCollection::class)
        ])
//        ->tag('cqrs.handler')
    ;
};