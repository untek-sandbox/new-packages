<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Utility\CodeGenerator\RestApi\Application\Handlers\GenerateRestApiCommandHandler;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire()->autoconfigure();

    $services
        ->load('Untek\Utility\CodeGenerator\RestApi\\', __DIR__ . '/../../..')
        ->exclude([
            __DIR__ . '/../../../{resources,Domain,Application/Commands,Application/Queries}',
            __DIR__ . '/../../../**/*{Event.php,Helper.php,Message.php,Task.php,Relation.php,Normalizer.php}',
            __DIR__ . '/../../../**/{Dto,Enums}',
            __DIR__ . '/../../../**/{Generators}',
        ]);

    /*$services->set(GenerateRestApiCommandHandler::class, GenerateRestApiCommandHandler::class)
        ->args([
            service(GenerateResultCollection::class)
        ])
//        ->tag('cqrs.handler')
    ;*/
};