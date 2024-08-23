<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire()->autoconfigure();

    $services
        ->load('Untek\Utility\CodeGeneratorCli\\', __DIR__ . '/../../..')
        ->exclude([
            __DIR__ . '/../../../{resources,Domain,Application/Commands,Application/Queries}',
            __DIR__ . '/../../../**/*{Event.php,Helper.php,Message.php,Task.php,Relation.php,Normalizer.php}',
            __DIR__ . '/../../../**/{Dto,Enums}',
            __DIR__ . '/../../../**/{Generators}',
        ]);

    /*$services->set(\Untek\Utility\CodeGeneratorCli\Application\Handlers\GenerateCliCommandHandler::class, \Untek\Utility\CodeGeneratorCli\Application\Handlers\GenerateCliCommandHandler::class)
        ->args([
            service(GenerateResultCollection::class)
        ])
//        ->tag('cqrs.handler')
    ;*/
};
